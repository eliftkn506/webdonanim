<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UrunFiyat;
use App\Models\Urun;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UrunFiyatController extends Controller
{
    public function index()
{
    $fiyatlar = UrunFiyat::with('urun')->paginate(15); // fiyat ve ilişkili ürün
    return view('admin.fiyatlar.index', compact('fiyatlar'));
}




    public function create()
{
    $urunler = Urun::all(); // ürün listesi
    return view('admin.fiyatlar.create', compact('urunler'));
}


    // Fiyat kaydetme
    public function store(Request $request)
    {
        $request->validate([
            'urun_id' => 'required|exists:urunler,id',
            'fiyat_turu' => 'required|in:standart,bayi,kampanya',
            'maliyet' => 'required|numeric|min:0',
            'kar_orani' => 'required|numeric|min:0|max:1000',
            'bayi_indirimi' => 'nullable|numeric|min:0|max:100',
            'vergi_orani' => 'required|numeric|min:0|max:100',
        ]);

        try {
            UrunFiyat::create([
                'urun_id' => $request->urun_id,
                'fiyat_turu' => $request->fiyat_turu,
                'maliyet' => $request->maliyet,
                'kar_orani' => $request->kar_orani,
                'bayi_indirimi' => $request->bayi_indirimi ?? 0,
                'vergi_orani' => $request->vergi_orani,
            ]);

            return redirect()->route('admin.fiyatlar.index')
                ->with('success', 'Fiyat başarıyla oluşturuldu.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Fiyat oluşturulurken hata: ' . $e->getMessage());
        }
    }

    // Fiyat düzenleme formu
    public function edit(UrunFiyat $fiyat)
    {
        return view('admin.fiyatlar.edit', compact('fiyat'));
    }

    public function update(Request $request, UrunFiyat $fiyat)
{
    $request->validate([
        'fiyat_turu' => 'required|in:standart,bayi,kampanya',
        'maliyet' => 'required|numeric|min:0',
        'kar_orani' => 'required|numeric|min:0|max:1000',
        'bayi_indirimi' => 'nullable|numeric|min:0|max:100',
        'vergi_orani' => 'required|numeric|min:0|max:100',
        'urunler' => 'nullable|array',
        'urunler.*' => 'exists:urunler,id',
        'baslangic_tarihi' => 'nullable|date',
        'bitis_tarihi' => 'nullable|date|after_or_equal:baslangic_tarihi',
    ]);

    try {
        // Fiyat bilgilerini güncelle
        $fiyat->update([
            'fiyat_turu' => $request->fiyat_turu,
            'maliyet' => $request->maliyet,
            'kar_orani' => $request->kar_orani,
            'bayi_indirimi' => $request->bayi_indirimi ?? 0,
            'vergi_orani' => $request->vergi_orani,
        ]);

        // Pivot tablo güncelleme (ürün ataması)
        if ($request->has('urunler')) {
            $urunlerPivot = [];
            foreach ($request->urunler as $urunId) {
                $urunlerPivot[$urunId] = [
                    'baslangic_tarihi' => $request->baslangic_tarihi ?? now(),
                    'bitis_tarihi' => $request->bitis_tarihi
                ];
            }

            $fiyat->urunler()->sync($urunlerPivot); // eskilerini sil ve yenileri ata
        }

        return redirect()->route('admin.fiyatlar.index')
            ->with('success', 'Fiyat başarıyla güncellendi ve ürün atamaları kaydedildi.');
    } catch (\Exception $e) {
        return redirect()->back()
            ->withInput()
            ->with('error', 'Güncelleme hatası: ' . $e->getMessage());
    }
}


    // Fiyat silme
    public function destroy(UrunFiyat $fiyat)
    {
        try {
            $fiyat->delete();
            return redirect()->route('admin.fiyatlar.index')
                ->with('success', 'Fiyat başarıyla silindi.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Silme hatası: ' . $e->getMessage());
        }
    }

    

    // Fiyat hesaplama metodu
    public function hesaplaFiyat(UrunFiyat $fiyat)
    {
        $maliyet = $fiyat->maliyet;
        $karOrani = $fiyat->kar_orani;
        $bayiIndirimi = $fiyat->bayi_indirimi;
        $vergiOrani = $fiyat->vergi_orani;

        $temelFiyat = $maliyet + ($maliyet * $karOrani / 100);
        $vergiDahilFiyat = $temelFiyat + ($temelFiyat * $vergiOrani / 100);

        $bayiFiyat = $bayiIndirimi > 0 ? $vergiDahilFiyat - ($vergiDahilFiyat * $bayiIndirimi / 100) : null;

        return [
            'maliyet' => $maliyet,
            'temel_fiyat' => round($temelFiyat, 2),
            'vergi_dahil_fiyat' => round($vergiDahilFiyat, 2),
            'bayi_fiyat' => $bayiFiyat ? round($bayiFiyat, 2) : null,
            'vergi_tutari' => round($temelFiyat * $vergiOrani / 100, 2),
        ];
    }

    // API: Fiyat hesaplama önizlemesi
    public function preview(Request $request)
    {
        $hesaplama = $this->hesaplaFiyat((object)[
            'maliyet' => $request->maliyet,
            'kar_orani' => $request->kar_orani,
            'bayi_indirimi' => $request->bayi_indirimi ?? 0,
            'vergi_orani' => $request->vergi_orani,
        ]);

        return response()->json($hesaplama);
    }

    public function assignToUrun(Urun $urun)
{
    // Bu ürün için zaten atanmış fiyatların IDs
    $atanmisFiyatIds = $urun->urunler()->pluck('urun_fiyatlar.fiyat_id')->toArray();

    // Sadece atanmış olmayan fiyatlar listelenecek
    $fiyatlar = UrunFiyat::whereNotIn('fiyat_id', $atanmisFiyatIds)
                ->get();

    return view('admin.urunler.fiyat-ata', compact('urun', 'fiyatlar'));
}



    public function storeAssignment(Request $request, Urun $urun)
{
    // Validation
    $request->validate([
        'fiyat_id' => 'required|exists:urun_fiyatlar,fiyat_id',
        'baslangic_tarihi' => 'nullable|date',
        'bitis_tarihi' => 'nullable|date|after_or_equal:baslangic_tarihi',
    ]);

    try {
        // Debug log
        Log::info('Fiyat atama başladı', [
            'urun_id' => $urun->id,
            'fiyat_id' => $request->fiyat_id,
            'baslangic' => $request->baslangic_tarihi ?? now(),
            'bitis' => $request->bitis_tarihi
        ]);

        // Direct SQL insert (Laravel ilişkisi yerine)
        DB::table('urun_fiyat_urun')->insert([
            'urun_id' => $urun->id,
            'fiyat_id' => $request->fiyat_id,
            'baslangic_tarihi' => $request->baslangic_tarihi ?? now(),
            'bitis_tarihi' => $request->bitis_tarihi,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Log::info('Fiyat başarıyla eklendi');

        return redirect()->route('admin.urunler.index')
            ->with('success', 'Fiyat başarıyla ürüne atandı.');
            
    } catch (\Exception $e) {
        Log::error('Fiyat atama hatası', [
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ]);
        
        return redirect()->back()
            ->withInput()
            ->with('error', 'Fiyat atanırken hata: ' . $e->getMessage());
    }
}
}
