<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kriter;
use App\Models\KriterDeger;

class KriterDegerController extends Controller
{
    // Listeleme
    public function index(Kriter $kriter)
    {
        $degerler = KriterDeger::where('kriter_id', $kriter->id)->get();
        return view('admin.kriterdegerleri.index', compact('kriter', 'degerler'));
    }

    // Ekleme Formu
    public function create(Kriter $kriter)
    {
        return view('admin.kriterdegerleri.create', compact('kriter'));
    }

  public function store(Request $request)
    {
        $request->validate([
            'kriter_id' => 'required|exists:kriterler,id',
            'deger'     => 'required|string|max:255',
        ]);

        $kriter = Kriter::findOrFail($request->kriter_id);

        KriterDeger::create([
            'kriter_id'       => $request->kriter_id,
            'alt_kategori_id' => $kriter->alt_kategori_id,
            'deger'           => $request->deger
        ]);

        // İSTEĞİN ÜZERİNE DEĞİŞTİRİLEN KISIM:
        // Kaydettikten sonra KRİTERLER listesine (üst sayfaya) döner.
        return redirect()->route('admin.altkategoriler.kriterler', $kriter->alt_kategori_id)
            ->with('success', 'Değer eklendi ve kriter listesine dönüldü.');
    }

    // Düzenleme Formu
    public function edit(KriterDeger $kriterDeger)
    {
        return view('admin.kriterdegerleri.edit', compact('kriterDeger'));
    }

    // Güncelleme
    public function update(Request $request, KriterDeger $kriterDeger)
    {
        $request->validate(['deger' => 'required|string|max:255']);

        $kriterDeger->update(['deger' => $request->deger]);

        // Yönlendirme rotası güncellendi
        return redirect()->route('admin.kriterdegerleri.edit', $kriterDeger->kriter_id)
            ->with('success', 'Değer güncellendi.');
    }

    // Silme
    public function destroy(KriterDeger $kriterDeger)
    {
        $parentId = $kriterDeger->kriter_id;
        $kriterDeger->delete();

        // Yönlendirme rotası güncellendi
        return redirect()->route('admin.kriterdegerleri.index', $parentId)
            ->with('success', 'Silindi.');
    }
}