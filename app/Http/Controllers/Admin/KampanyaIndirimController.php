<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KampanyaIndirim;
use App\Models\Urun;
use Illuminate\Http\Request;

class KampanyaIndirimController extends Controller
{
    public function index()
    {
        $kampanyalar = KampanyaIndirim::with('urun')->latest()->paginate(10);
        return view('admin.kampanyalar.index', compact('kampanyalar'));
    }

    public function create()
    {
        $urunler = Urun::all();
        return view('admin.kampanyalar.create', compact('urunler'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'urun_id' => 'required|exists:urunler,id',
            'kampanya_adi' => 'required|string|max:255',
            'indirim_orani' => 'nullable|numeric|min:0|max:100',
            'yeni_fiyat' => 'nullable|numeric|min:0',
            'baslangic_tarihi' => 'required|date',
            'bitis_tarihi' => 'required|date|after_or_equal:baslangic_tarihi',
            'aktif' => 'required|boolean',
        ]);

        KampanyaIndirim::create($request->all());

        return redirect()->route('admin.kampanyalar.index')->with('success', 'Kampanya başarıyla eklendi.');
    }

    public function edit($id)
    {
        $kampanya = KampanyaIndirim::findOrFail($id);
        $urunler = Urun::all();
        return view('admin.kampanyalar.edit', compact('kampanya', 'urunler'));
    }

    public function update(Request $request, $id)
    {
        $kampanya = KampanyaIndirim::findOrFail($id);

        $request->validate([
            'urun_id' => 'required|exists:urunler,id',
            'kampanya_adi' => 'required|string|max:255',
            'indirim_orani' => 'nullable|numeric|min:0|max:100',
            'yeni_fiyat' => 'nullable|numeric|min:0',
            'baslangic_tarihi' => 'required|date',
            'bitis_tarihi' => 'required|date|after_or_equal:baslangic_tarihi',
            'aktif' => 'required|boolean',
        ]);

        $kampanya->update($request->all());

        return redirect()->route('admin.kampanyalar.index')->with('success', 'Kampanya başarıyla güncellendi.');
    }

    public function destroy($id)
    {
        $kampanya = KampanyaIndirim::findOrFail($id);
        $kampanya->delete();

        return redirect()->route('admin.kampanyalar.index')->with('success', 'Kampanya başarıyla silindi.');
    }
}
