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
    // Fiyat Listesi
    public function index()
    {
        $fiyatlar = UrunFiyat::with('urun')->orderByDesc('created_at')->paginate(15);
        return view('admin.fiyatlar.index', compact('fiyatlar'));
    }

    // Yeni Fiyat Ekleme Formu
    public function create()
    {
        $urunler = Urun::all();
        return view('admin.fiyatlar.create', compact('urunler'));
    }

    // Fiyat Kaydetme
    public function store(Request $request)
    {
        $request->validate([
            'urun_id'       => 'required|exists:urunler,id',
            'fiyat_turu'    => 'required|in:standart,bayi,kampanya',
            'maliyet'       => 'required|numeric|min:0',
            'kar_orani'     => 'required|numeric|min:0|max:1000',
            'bayi_indirimi' => 'nullable|numeric|min:0|max:100',
            'vergi_orani'   => 'required|numeric|min:0|max:100',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $fiyat = UrunFiyat::create([
                    'urun_id'       => $request->urun_id,
                    'fiyat_turu'    => $request->fiyat_turu,
                    'maliyet'       => $request->maliyet,
                    'kar_orani'     => $request->kar_orani,
                    'bayi_indirimi' => $request->bayi_indirimi ?? 0,
                    'vergi_orani'   => $request->vergi_orani,
                ]);

                // İsteğe bağlı: Fiyat oluşturulurken otomatik olarak pivot tabloya da eklenebilir.
                // Eğer sisteminizde oluşturulan fiyat hemen aktif olacaksa aşağıdaki satırı açabilirsiniz:
                // $fiyat->urunler()->attach($request->urun_id, ['baslangic_tarihi' => now()]);
            });

            return redirect()->route('admin.fiyatlar.index')
                ->with('success', 'Fiyat başarıyla oluşturuldu.');

        } catch (\Exception $e) {
            Log::error('Fiyat ekleme hatası: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Fiyat oluşturulurken hata: ' . $e->getMessage());
        }
    }

    // Fiyat Düzenleme Formu
    public function edit(UrunFiyat $fiyat)
    {
        return view('admin.fiyatlar.edit', compact('fiyat'));
    }

    // Fiyat Güncelleme
    public function update(Request $request, UrunFiyat $fiyat)
    {
        $request->validate([
            'fiyat_turu'       => 'required|in:standart,bayi,kampanya',
            'maliyet'          => 'required|numeric|min:0',
            'kar_orani'        => 'required|numeric|min:0|max:1000',
            'bayi_indirimi'    => 'nullable|numeric|min:0|max:100',
            'vergi_orani'      => 'required|numeric|min:0|max:100',
            'urunler'          => 'nullable|array',
            'urunler.*'        => 'exists:urunler,id',
            'baslangic_tarihi' => 'nullable|date',
            'bitis_tarihi'     => 'nullable|date|after_or_equal:baslangic_tarihi',
        ]);

        try {
            DB::transaction(function () use ($request, $fiyat) {
                // 1. Ana Fiyat Bilgilerini Güncelle
                $fiyat->update([
                    'fiyat_turu'    => $request->fiyat_turu,
                    'maliyet'       => $request->maliyet,
                    'kar_orani'     => $request->kar_orani,
                    'bayi_indirimi' => $request->bayi_indirimi ?? 0,
                    'vergi_orani'   => $request->vergi_orani,
                ]);

                // 2. Pivot Tablo (Ürün Ataması) Güncelleme
                if ($request->has('urunler')) {
                    $pivotData = [];
                    foreach ($request->urunler as $urunId) {
                        $pivotData[$urunId] = [
                            'baslangic_tarihi' => $request->baslangic_tarihi ?? now(),
                            'bitis_tarihi'     => $request->bitis_tarihi
                        ];
                    }
                    // sync: Mevcutları siler, yenileri ekler.
                    $fiyat->urunler()->sync($pivotData);
                }
            });

            return redirect()->route('admin.fiyatlar.index')
                ->with('success', 'Fiyat ve atamalar başarıyla güncellendi.');

        } catch (\Exception $e) {
            Log::error('Fiyat güncelleme hatası: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Güncelleme hatası: ' . $e->getMessage());
        }
    }

    // Fiyat Silme
    public function destroy(UrunFiyat $fiyat)
    {
        try {
            // İlişkili kayıtlar varsa (pivot), foreign key constraint hatası almamak için önce detach yapılabilir
            // veya modelde cascade tanımlı olmalıdır.
            $fiyat->urunler()->detach(); 
            $fiyat->delete();

            return redirect()->route('admin.fiyatlar.index')
                ->with('success', 'Fiyat başarıyla silindi.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Silme hatası: ' . $e->getMessage());
        }
    }

    // Hesaplama Yardımcısı
    public function hesaplaFiyat(UrunFiyat $fiyat)
    {
        $maliyet      = $fiyat->maliyet;
        $karOrani     = $fiyat->kar_orani;
        $bayiIndirimi = $fiyat->bayi_indirimi;
        $vergiOrani   = $fiyat->vergi_orani;

        $temelFiyat      = $maliyet + ($maliyet * $karOrani / 100);
        $vergiDahilFiyat = $temelFiyat + ($temelFiyat * $vergiOrani / 100);
        $bayiFiyat       = $bayiIndirimi > 0 ? $vergiDahilFiyat - ($vergiDahilFiyat * $bayiIndirimi / 100) : null;

        return [
            'maliyet'           => $maliyet,
            'temel_fiyat'       => round($temelFiyat, 2),
            'vergi_dahil_fiyat' => round($vergiDahilFiyat, 2),
            'bayi_fiyat'        => $bayiFiyat ? round($bayiFiyat, 2) : null,
            'vergi_tutari'      => round($temelFiyat * $vergiOrani / 100, 2),
        ];
    }

    // API: Hesaplama Önizleme
    public function preview(Request $request)
    {
        // Geçici bir obje oluşturup hesaplaFiyat metoduna gönderiyoruz
        $tempFiyat = new UrunFiyat([
            'maliyet'       => $request->maliyet,
            'kar_orani'     => $request->kar_orani,
            'bayi_indirimi' => $request->bayi_indirimi ?? 0,
            'vergi_orani'   => $request->vergi_orani,
        ]);

        return response()->json($this->hesaplaFiyat($tempFiyat));
    }

    // Ürüne Fiyat Atama Sayfası
    public function assignToUrun(Urun $urun)
    {
        // Bu ürün için zaten atanmış fiyatların ID'lerini al
        $atanmisFiyatIds = $urun->fiyatlar()->pluck('urun_fiyatlar.fiyat_id')->toArray();

        // Henüz atanmamış fiyatları getir
        $fiyatlar = UrunFiyat::whereNotIn('fiyat_id', $atanmisFiyatIds)->get();

        return view('admin.urunler.fiyat-ata', compact('urun', 'fiyatlar'));
    }

    // Ürüne Fiyat Atama İşlemi (Düzeltilen Kısım)
    public function storeAssignment(Request $request, Urun $urun)
    {
        $request->validate([
            'fiyat_id'         => 'required|exists:urun_fiyatlar,fiyat_id',
            'baslangic_tarihi' => 'required|date',
            'bitis_tarihi'     => 'nullable|date|after_or_equal:baslangic_tarihi',
        ]);

        try {
            // Loglama
            Log::info('Fiyat atama işlemi', [
                'urun_id'  => $urun->id,
                'fiyat_id' => $request->fiyat_id
            ]);

            // DB::table yerine Eloquent 'attach' kullanımı (Daha güvenli ve standart)
            $urun->fiyatlar()->attach($request->fiyat_id, [
                'baslangic_tarihi' => $request->baslangic_tarihi,
                'bitis_tarihi'     => $request->bitis_tarihi,
                // created_at ve updated_at pivot tablosunda otomatik dolması için 
                // Modelinizde ->withTimestamps() tanımlı olmalıdır.
            ]);

            return redirect()->route('admin.urunler.index')
                ->with('success', 'Fiyat başarıyla ürüne atandı.');
            
        } catch (\Exception $e) {
            Log::error('Fiyat atama hatası: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Fiyat atanırken hata oluştu: ' . $e->getMessage());
        }
    }
}