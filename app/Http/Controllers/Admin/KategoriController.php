<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kategori;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoriler = Kategori::all();
        return view('admin.kategori.index', compact('kategoriler'));
    }

    public function create()
    {
        return view('admin.kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_ad' => 'required|unique:kategoriler,kategori_ad',
        ]);

        Kategori::create([
            'kategori_ad' => $request->kategori_ad,
        ]);

        return redirect()->route('admin.kategoriler.index')->with('success', 'Kategori başarıyla eklendi.');
    }

    public function edit(Kategori $kategori)
    {
        return view('admin.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, Kategori $kategori)
    {
        $request->validate([
            'kategori_ad' => 'required|unique:kategoriler,kategori_ad,' . $kategori->id,
        ]);

        $kategori->update([
            'kategori_ad' => $request->kategori_ad,
        ]);

        return redirect()->route('admin.kategoriler.index')->with('success', 'Kategori başarıyla güncellendi.');
    }

    public function destroy(Kategori $kategori)
    {
        $kategori->delete();
        return redirect()->route('admin.kategoriler.index')->with('success', 'Kategori silindi.');
    }
}
