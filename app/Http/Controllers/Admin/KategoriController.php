<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kategori;
use Illuminate\Support\Facades\Storage; // Resim işlemleri için gerekli

class KategoriController extends Controller
{
    public function index()
    {
        // Sayfalama ile kategorileri çekiyoruz
        $kategoriler = Kategori::with('altKategoriler')->paginate(10);
        return view('admin.kategori.index', compact('kategoriler'));
    }

    public function create()
    {
        return view('admin.kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_ad' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Resim doğrulama
        ]);

        $data = $request->only('kategori_ad');

        // Resim yükleme işlemi
        if ($request->hasFile('image')) {
            // 'public/kategoriler' klasörüne kaydeder
            $path = $request->file('image')->store('kategoriler', 'public');
            $data['image'] = $path;
        }

        Kategori::create($data);

        return redirect()->route('admin.kategoriler.index')
            ->with('success', 'Kategori başarıyla eklendi.');
    }

    public function edit($id)
    {
        $kategori = Kategori::findOrFail($id);
        return view('admin.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, $id)
    {
        $kategori = Kategori::findOrFail($id);

        $request->validate([
            'kategori_ad' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->only('kategori_ad');

        // Yeni resim yüklendiyse
        if ($request->hasFile('image')) {
            // Eski resmi sil
            if ($kategori->image && Storage::disk('public')->exists($kategori->image)) {
                Storage::disk('public')->delete($kategori->image);
            }

            // Yeni resmi kaydet
            $path = $request->file('image')->store('kategoriler', 'public');
            $data['image'] = $path;
        }

        $kategori->update($data);

        return redirect()->route('admin.kategoriler.index')
            ->with('success', 'Kategori başarıyla güncellendi.');
    }

    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);

        // Kategori silinirken resmini de diskten siliyoruz
        if ($kategori->image && Storage::disk('public')->exists($kategori->image)) {
            Storage::disk('public')->delete($kategori->image);
        }

        $kategori->delete();

        return redirect()->route('admin.kategoriler.index')
            ->with('success', 'Kategori ve görseli silindi.');
    }
}