<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KampanyaIndirim;
use App\Models\Urun;
use App\Models\AltKategori; // Kategorileri çekmek için
use Illuminate\Http\Request;

class KampanyaIndirimController extends Controller
{
    public function index()
    {
        // HATA ÇÖZÜMÜ: 'kategori' ilişkisi modelde yoksa hata verir.
        // Eğer modelde tanımlı değilse sadece 'urun' ile çekin.
        // Güvenli yöntem:
        $query = KampanyaIndirim::with('urun');
        
        // Modelde 'kategori' metodu varsa ekle
        if (method_exists(new KampanyaIndirim, 'kategori')) {
            $query->with('kategori');
        }

        $kampanyalar = $query->latest()->paginate(10);
        return view('admin.kampanyalar.index', compact('kampanyalar'));
    }

    public function create()
    {
        $urunler = Urun::select('id', 'urun_ad')->get();
        $kategoriler = AltKategori::select('id', 'alt_kategori_ad')->get(); 
        
        return view('admin.kampanyalar.create', compact('urunler', 'kategoriler'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kampanya_adi' => 'required|string|max:255',
            'kapsam' => 'required|in:urun,kategori,tum',
            'urun_id' => 'required_if:kapsam,urun', 
            'kategori_id' => 'required_if:kapsam,kategori',
            'indirim_orani' => 'nullable|numeric|min:0|max:100',
            'yeni_fiyat' => 'nullable|numeric|min:0',
            'baslangic_tarihi' => 'required|date',
            'bitis_tarihi' => 'required|date|after_or_equal:baslangic_tarihi',
        ]);

        $data = $request->all();
        $data['aktif'] = $request->has('aktif');

        if ($request->kapsam == 'tum') {
            $data['urun_id'] = null;
            $data['kategori_id'] = null;
        } elseif ($request->kapsam == 'kategori') {
            $data['urun_id'] = null;
        } elseif ($request->kapsam == 'urun') {
            $data['kategori_id'] = null;
        }

        KampanyaIndirim::create($data);

        return redirect()->route('admin.kampanyalar.index')->with('success', 'Kampanya başarıyla eklendi.');
    }

    public function edit($id)
    {
        $kampanya = KampanyaIndirim::findOrFail($id);
        $urunler = Urun::select('id', 'urun_ad')->get();
        $kategoriler = AltKategori::select('id', 'alt_kategori_ad')->get();

        return view('admin.kampanyalar.edit', compact('kampanya', 'urunler', 'kategoriler'));
    }

    public function update(Request $request, $id)
    {
        $kampanya = KampanyaIndirim::findOrFail($id);

        $request->validate([
            'kampanya_adi' => 'required|string|max:255',
            'kapsam' => 'required|in:urun,kategori,tum',
            'urun_id' => 'required_if:kapsam,urun',
            'kategori_id' => 'required_if:kapsam,kategori',
            'indirim_orani' => 'nullable|numeric|min:0|max:100',
            'yeni_fiyat' => 'nullable|numeric|min:0',
            'baslangic_tarihi' => 'required|date',
            'bitis_tarihi' => 'required|date|after_or_equal:baslangic_tarihi',
        ]);

        $data = $request->all();
        $data['aktif'] = $request->has('aktif');

        if ($request->kapsam == 'tum') {
            $data['urun_id'] = null;
            $data['kategori_id'] = null;
        } elseif ($request->kapsam == 'kategori') {
            $data['urun_id'] = null;
        } elseif ($request->kapsam == 'urun') {
            $data['kategori_id'] = null;
        }

        $kampanya->update($data);

        return redirect()->route('admin.kampanyalar.index')->with('success', 'Kampanya güncellendi.');
    }

    public function destroy($id)
    {
        $kampanya = KampanyaIndirim::findOrFail($id);
        $kampanya->delete();
        return redirect()->route('admin.kampanyalar.index')->with('success', 'Kampanya silindi.');
    }
}