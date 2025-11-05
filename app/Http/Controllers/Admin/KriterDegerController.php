<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kriter;
use App\Models\KriterDeger;
use App\Models\AltKategori;

class KriterDegerController extends Controller
{
    // Listeleme
    public function index()
    {
        $degerler = KriterDeger::with('kriter', 'altKategori')->get();
        return view('admin.kriterdegerleri.index', compact('degerler'));
    }

    // Yeni ekleme formu
    public function create()
    {
        $altkategoriler = AltKategori::all();
        $kriterler = Kriter::all();
        return view('admin.kriterdegerleri.create', compact('altkategoriler', 'kriterler'));
    }

    // Kaydetme
    public function store(Request $request)
    {
        $request->validate([
            'alt_kategori_id' => 'required|exists:alt_kategoriler,id',
            'kriter_id' => 'required|exists:kriterler,id',
            'deger' => 'required|string|max:255',
        ]);

        KriterDeger::create($request->only('alt_kategori_id', 'kriter_id', 'deger'));

        return redirect()->route('admin.kriterdegerleri.index')->with('success', 'Kriter değeri başarıyla eklendi.');
    }

    // Düzenleme formu
    public function edit(KriterDeger $kriterDeger)
    {
        $altkategoriler = AltKategori::all();
        $kriterler = Kriter::where('alt_kategori_id', $kriterDeger->alt_kategori_id)->get();
        return view('admin.kriterdegerleri.edit', compact('kriterDeger', 'altkategoriler', 'kriterler'));
    }

    // Güncelleme
    public function update(Request $request, KriterDeger $kriterDeger)
    {
        $request->validate([
            'alt_kategori_id' => 'required|exists:alt_kategoriler,id',
            'kriter_id' => 'required|exists:kriterler,id',
            'deger' => 'required|string|max:255',
        ]);

        $kriterDeger->update($request->only('alt_kategori_id', 'kriter_id', 'deger'));

        return redirect()->route('admin.kriterdegerleri.index')->with('success', 'Kriter değeri başarıyla güncellendi.');
    }

    // Silme
    public function destroy(KriterDeger $kriterDeger)
    {
        $kriterDeger->delete();
        return redirect()->route('admin.kriterdegerleri.index')->with('success', 'Kriter değeri başarıyla silindi.');
    }

    // Ajax: Alt kategoriye göre kriter listesi
    public function getKriterlerByAltKategori($altKategoriId)
    {
        $kriterler = Kriter::where('alt_kategori_id', $altKategoriId)->get();
        return response()->json($kriterler);
    }
}
