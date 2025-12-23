<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Urun;
use App\Models\Kategori;
use App\Models\Slider;

class HomeController extends Controller
{
    public function index()
    {
        // Aktif sliderları getir
        $sliders = Slider::where('status', 1)->orderBy('order', 'asc')->get();

        // Kategorileri getir
        $kategoriler = Kategori::take(6)->get();

        // Ürünleri fiyat ilişkileriyle birlikte getir (₺0,00 hatasını önler)
        $urunler = Urun::with(['altKategori', 'fiyatlar' => function($query) {
            $query->wherePivot('baslangic_tarihi', '<=', now())
                  ->where(function($sq) {
                      $sq->whereNull('urun_fiyat_urun.bitis_tarihi')
                         ->orWhere('urun_fiyat_urun.bitis_tarihi', '>=', now());
                  });
        }])->latest()->take(8)->get();

        return view('home', compact('urunler', 'kategoriler', 'sliders'));
    }

    public function ara(Request $request)
    {
        $aranan = $request->input('q');
        if (empty($aranan)) {
            return redirect()->route('home');
        }

        $urunler = Urun::where('urun_ad', 'LIKE', "%{$aranan}%")
            ->orWhere('marka', 'LIKE', "%{$aranan}%")
            ->get();

        return view('kullanici.urunler.index', compact('urunler', 'aranan'));
    }
}