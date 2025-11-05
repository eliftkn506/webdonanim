<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AltKategori;
use App\Models\Kategori;

class AltKategoriController extends Controller
{
    // Listeleme
    public function index()
    {
        $altKategoriler = AltKategori::with('kategori')->get();
        return view('admin.altkategoriler.index', compact('altKategoriler'));
    }

    // Ekleme formu
    public function create()
    {
        $kategoriler = Kategori::all();
        return view('admin.altkategoriler.create', compact('kategoriler'));
    }

    // Yeni alt kategori kaydet
    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategoriler,id',
            'alt_kategori_ad' => 'required|string|max:255',
        ]);

        AltKategori::create($request->all());

        return redirect()->route('admin.altkategoriler.index')->with('success', 'Alt kategori başarıyla eklendi.');
    }

    // Düzenleme formu
    public function edit(AltKategori $altkategoriler)
    {
        $kategoriler = Kategori::all();
        return view('admin.altkategoriler.edit', compact('altkategoriler', 'kategoriler'));
    }

    // Güncelle
    public function update(Request $request, AltKategori $altkategoriler)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategoriler,id',
            'alt_kategori_ad' => 'required|string|max:255',
        ]);

        $altkategoriler->update($request->all());

        return redirect()->route('admin.altkategoriler.index')->with('success', 'Alt kategori başarıyla güncellendi.');
    }

    // Sil
    public function destroy(AltKategori $altkategoriler)
    {
        $altkategoriler->delete();
        return redirect()->route('admin.altkategoriler.index')->with('success', 'Alt kategori başarıyla silindi.');
    }
}
