<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kriter;
use App\Models\AltKategori;

class KriterController extends Controller
{
    // Listeleme
    public function index()
    {
        $kriterler = Kriter::with('altKategori')->get();
        return view('admin.kriterler.index', compact('kriterler'));
    }

    // Yeni ekleme formu
    public function create()
    {
        $altkategoriler = AltKategori::all();
        return view('admin.kriterler.create', compact('altkategoriler'));
    }

    // Kaydetme
    public function store(Request $request)
    {
        $request->validate([
            'alt_kategori_id' => 'required|exists:alt_kategoriler,id',
            'kriter_ad' => 'required|string|max:255',
        ]);

        Kriter::create([
            'alt_kategori_id' => $request->alt_kategori_id,
            'kriter_ad' => $request->kriter_ad,
        ]);

        return redirect()->route('admin.kriterler.index')->with('success', 'Kriter başarıyla eklendi.');
    }

    // Düzenleme formu
    public function edit(Kriter $kriter)
    {
        $altkategoriler = AltKategori::all();
        return view('admin.kriterler.edit', compact('kriter', 'altkategoriler'));
    }

    // Güncelleme
    public function update(Request $request, Kriter $kriter)
    {
        $request->validate([
            'alt_kategori_id' => 'required|exists:alt_kategoriler,id',
            'kriter_ad' => 'required|string|max:255',
        ]);

        $kriter->update([
            'alt_kategori_id' => $request->alt_kategori_id,
            'kriter_ad' => $request->kriter_ad,
        ]);

        return redirect()->route('admin.kriterler.index')->with('success', 'Kriter başarıyla güncellendi.');
    }

    // Silme
    public function destroy(Kriter $kriter)
    {
        $kriter->delete();
        return redirect()->route('admin.kriterler.index')->with('success', 'Kriter başarıyla silindi.');
    }

    // Ajax: Alt kategoriye göre kriter listesi
    public function getByAltKategori($altKategoriId)
    {
        $kriterler = Kriter::where('alt_kategori_id', $altKategoriId)->get();
        return response()->json($kriterler);
    }
}
