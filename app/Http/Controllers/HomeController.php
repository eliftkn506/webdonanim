<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Urun;
use App\Models\Kategori;
use App\Models\Slider;
use App\Models\Degerlendirme; // Eklendi
use App\Models\Blog; // Eklendi

class HomeController extends Controller
{
    public function index()
    {
        // Aktif sliderları getir
        $sliders = Slider::where('status', 1)->orderBy('order', 'asc')->get();

        // Kategorileri getir
        $kategoriler = Kategori::take(6)->get();

        // Ürünleri fiyat ilişkileriyle birlikte getir
        $urunler = Urun::with(['altKategori', 'fiyatlar' => function($query) {
            $query->wherePivot('baslangic_tarihi', '<=', now())
                  ->where(function($sq) {
                      $sq->whereNull('urun_fiyat_urun.bitis_tarihi')
                          ->orWhere('urun_fiyat_urun.bitis_tarihi', '>=', now());
                  });
        }])->latest()->take(8)->get();

        // YENİ: Onaylı Müşteri Yorumlarını Getir (Son 3 adet)
        // User ilişkisiyle beraber çekiyoruz ki isimleri alabilelim.
        $degerlendirmeler = Degerlendirme::with('user')
            ->where('onay', 1)
            ->latest()
            ->take(3)
            ->get();

        // YENİ: Aktif Blog/Haberleri Getir (Son 3 adet)
        $bloglar = Blog::where('aktif', 1)
            ->latest()
            ->take(3)
            ->get();

        return view('home', compact('urunler', 'kategoriler', 'sliders', 'degerlendirmeler', 'bloglar'));
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

public function blogDetay($slug)
    {
        // 1. İstenen blog yazısını getir
        $blog = Blog::where('slug', $slug)->where('aktif', 1)->firstOrFail();

        // 2. Yan menü için son eklenen diğer 5 yazıyı getir
        $sonYazilar = Blog::where('aktif', 1)
            ->where('id', '!=', $blog->id)
            ->latest()
            ->take(5)
            ->get();

        // 3. Yan menüde rastgele 3 ürün önerelim (Fiyat ilişkisi eklendi)
        $onerilenUrunler = Urun::with(['fiyatlar' => function($query) {
            $query->wherePivot('baslangic_tarihi', '<=', now())
                  ->where(function($sq) {
                      $sq->whereNull('urun_fiyat_urun.bitis_tarihi')
                          ->orWhere('urun_fiyat_urun.bitis_tarihi', '>=', now());
                  });
        }])->inRandomOrder()->take(3)->get();
        
        return view('kullanici.blog_detay', compact('blog', 'sonYazilar', 'onerilenUrunler'));
    }
}