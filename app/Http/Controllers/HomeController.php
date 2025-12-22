<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Urun;

class HomeController extends Controller
{
    public function index()
    {
        // Öne çıkan ürünler (son eklenen 8 ürün)
        $urunler = Urun::with('altKategori')
            ->latest()
            ->take(8)
            ->get();
        
        return view('home', compact('urunler'));
    }

    // ARAMA FONKSİYONU (Bunu ekleyin)
    public function ara(Request $request)
    {
        // Formdan gelen 'q' parametresini al (name="q" olduğu için)
        $aranan = $request->input('q');

        // Eğer arama boşsa anasayfaya yönlendir
        if (empty($aranan)) {
            return redirect()->route('home');
        }

        // Veritabanında arama yap
        // HATA BURADAYDI: ->get() veya ->paginate() kullanılmazsa "Builder" hatası verir.
        $urunler = Urun::where('urun_ad', 'LIKE', "%{$aranan}%")
            ->orWhere('aciklama', 'LIKE', "%{$aranan}%") // İsterseniz açıklamada da aratabilirsiniz
            ->orWhere('marka', 'LIKE', "%{$aranan}%")   // İsterseniz markada da aratabilirsiniz
            ->get(); // <-- BU KISIM ÇOK ÖNEMLİ (Sorguyu çalıştırır)

        // Sonuçları göstermek için bir view'a gönder (örneğin urunler.index veya ozel bir arama sayfası)
        // Eğer ayrı bir arama sayfası yoksa, ürün listeleme sayfasını kullanabiliriz.
        return view('urunler.index', compact('urunler', 'aranan'));
    }
}