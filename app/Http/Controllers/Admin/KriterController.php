<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kriter;
use App\Models\AltKategori;

class KriterController extends Controller
{
    // Alt Kategoriye göre listele
    public function index(AltKategori $altKategori)
    {
        $kriterler = $altKategori->kriterler; // hasMany ilişkisi
        return view('admin.kriterler.index', compact('altKategori', 'kriterler'));
    }

    public function create(AltKategori $altKategori)
    {
        return view('admin.kriterler.create', compact('altKategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'alt_kategori_id' => 'required',
            'kriter_ad' => 'required|string|max:255',
        ]);

        Kriter::create($request->all());

        return redirect()->route('admin.altkategoriler.kriterler', $request->alt_kategori_id)
            ->with('success', 'Kriter eklendi.');
    }

    // DÜZELTİLEN FONKSİYON BURASI
    public function edit(Kriter $kriter)
    {
        // Select kutusunu doldurmak için tüm alt kategorileri çekiyoruz
        $altkategoriler = AltKategori::all();
        
        return view('admin.kriterler.edit', compact('kriter', 'altkategoriler'));
    }

    public function update(Request $request, Kriter $kriter)
    {
        $request->validate([
            'alt_kategori_id' => 'required|exists:alt_kategoriler,id', // Validasyon eklendi
            'kriter_ad' => 'required|string|max:255',
        ]);

        $kriter->update([
            'alt_kategori_id' => $request->alt_kategori_id,
            'kriter_ad' => $request->kriter_ad
        ]);

        // Güncelleme bitince kriter listesine geri dön
        return redirect()->route('admin.altkategoriler.kriterler', $kriter->alt_kategori_id)
            ->with('success', 'Kriter güncellendi.');
    }

    public function destroy(Kriter $kriter)
    {
        $parentId = $kriter->alt_kategori_id;
        $kriter->delete();
        return redirect()->route('admin.altkategoriler.kriterler', $parentId)->with('success', 'Silindi.');
    }
}