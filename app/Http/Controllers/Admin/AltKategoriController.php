<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AltKategori;
use App\Models\Kategori;

class AltKategoriController extends Controller
{
    // Belirli bir kategoriye ait alt kategorileri listele
    public function index(Kategori $kategori)
    {
        $altKategoriler = $kategori->altKategoriler; // İlişki tanımlı olmalı hasMany
        return view('admin.altkategoriler.index', compact('kategori', 'altKategoriler'));
    }

    // Belirli bir kategori için ekleme formu
    public function create(Kategori $kategori)
    {
        return view('admin.altkategoriler.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategoriler,id',
            'alt_kategori_ad' => 'required|string|max:255',
        ]);

        AltKategori::create($request->all());

        return redirect()->route('admin.kategoriler.altkategoriler', $request->kategori_id)
            ->with('success', 'Alt kategori eklendi.');
    }

    public function edit(AltKategori $altkategoriler)
    {
        // Edit işleminde tüm kategorileri seçtirebiliriz veya kısıtlayabiliriz.
        // Basitlik adına sadece isim değiştiriyoruz, kategori değişimi karmaşık olabilir.
        return view('admin.altkategoriler.edit', compact('altkategoriler'));
    }

    public function update(Request $request, AltKategori $altkategoriler)
    {
        $request->validate(['alt_kategori_ad' => 'required|string|max:255']);
        $altkategoriler->update(['alt_kategori_ad' => $request->alt_kategori_ad]);

        return redirect()->route('admin.kategoriler.altkategoriler', $altkategoriler->kategori_id)
            ->with('success', 'Güncellendi.');
    }

    public function destroy(AltKategori $altkategoriler)
    {
        $parentId = $altkategoriler->kategori_id;
        $altkategoriler->delete();
        return redirect()->route('admin.kategoriler.altkategoriler', $parentId)->with('success', 'Silindi.');
    }
}