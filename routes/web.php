<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

// ===================== CONTROLLER IMPORTLARI =====================
// Site Ön Yüzü
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SayfaController;
use App\Http\Controllers\KullaniciUrunController;
use App\Http\Controllers\SepetController;
use App\Http\Controllers\KullaniciController;
use App\Http\Controllers\HesapController;
use App\Http\Controllers\FavoriController;
use App\Http\Controllers\SiparisController;
use App\Http\Controllers\OdemeController;
use App\Http\Controllers\WizardController;
use App\Http\Controllers\BayiBasvuruController;

// Admin Paneli
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\DegerlendirmeController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\AltKategoriController;
use App\Http\Controllers\Admin\UrunController;
use App\Http\Controllers\Admin\UrunFiyatController;
use App\Http\Controllers\Admin\KriterController;
use App\Http\Controllers\Admin\KriterDegerController;
use App\Http\Controllers\Admin\AdminSiparisController;
use App\Http\Controllers\Admin\KuponController;
use App\Http\Controllers\Admin\KampanyaIndirimController;
use App\Http\Controllers\Admin\BayiController;
use App\Http\Controllers\Admin\BlogController;

// Modeller
use App\Models\FavoriUrun;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ===================== BAKIM & YARDIMCI ROTALAR (Sadece Auth) =====================
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/storage-link-yap', function () {
        try {
            Artisan::call('storage:link');
            return "Storage linki başarıyla oluşturuldu!";
        } catch (\Exception $e) {
            return "Hata: " . $e->getMessage();
        }
    });

    Route::get('/admin/onbellek-temizle', function () {
        Artisan::call('optimize:clear');
        return "Tüm önbellek başarıyla temizlendi!";
    });
});

// ===================== GENEL SAYFALAR (Herkese Açık) =====================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/hakkimizda', [SayfaController::class, 'hakkimizda'])->name('hakkimizda');
Route::get('/iletisim', [SayfaController::class, 'iletisim'])->name('iletisim');
Route::post('/iletisim', [SayfaController::class, 'iletisimGonder'])->name('iletisim.gonder');
Route::get('/blog/{slug}', [HomeController::class, 'blogDetay'])->name('blog.detay');

// ===================== AUTH & BAYİ BAŞVURU =====================
Auth::routes();
Route::get('/bayi-basvuru', [BayiBasvuruController::class, 'showForm'])->name('bayi.basvuru.form');
Route::post('/bayi-basvuru', [BayiBasvuruController::class, 'submit'])->name('bayi.basvuru.submit');

// ===================== ÜRÜNLER & KATALOG =====================
Route::get('/urunler', [KullaniciUrunController::class, 'index'])->name('urun.index');
Route::get('/urunler/ara', [KullaniciUrunController::class, 'ara'])->name('urun.ara');
Route::get('/urunler/kategori/{id}', [KullaniciUrunController::class, 'kategori'])->name('urun.kategori');
Route::get('/urunler/altkategori/{id}', [KullaniciUrunController::class, 'altkategori'])->name('urun.altkategori');
Route::get('/urunler/incele/{id}', [KullaniciUrunController::class, 'incele'])->name('urun.incele');

// AJAX Filtreleme
Route::get('/urun/get-alt-kategoriler', [KullaniciUrunController::class, 'getAltKategoriler'])->name('urun.getAltKategoriler');
Route::get('/urun/get-kriterler', [KullaniciUrunController::class, 'getKriterler'])->name('urun.getKriterler');
Route::get('/urun/get-marka-model', [KullaniciUrunController::class, 'getMarkaModel'])->name('urun.getMarkaModel');

// ===================== SEPET İŞLEMLERİ =====================
Route::prefix('sepet')->group(function () {
    Route::get('/', [SepetController::class, 'index'])->name('sepet.index');
    Route::post('/ekle', [SepetController::class, 'ekle'])->name('sepet.ekle');
    Route::post('/ekle-konfig', [SepetController::class, 'konfigEkle'])->name('sepet.konfigEkle');
    Route::delete('/sil/{id}', [SepetController::class, 'sil'])->name('sepet.sil');
    Route::delete('/temizle', [SepetController::class, 'temizle'])->name('sepet.temizle');
    Route::post('/guncelle/{id}', [SepetController::class, 'guncelle'])->name('sepet.guncelle');
});

// Sepet sayısı (AJAX)
Route::get('/sepet/sayisi', function() {
    $sepet = session('sepet', []);
    return response()->json(['count' => array_sum(array_column($sepet, 'adet'))]);
})->name('sepet.sayisi');

// ===================== KULLANICI ÖZEL SAYFALARI (Auth) =====================
Route::middleware(['auth'])->group(function () {
    
    // --- Profil & Genel ---
    Route::get('/profil', [KullaniciController::class, 'profil'])->name('profil');
    Route::post('/urun/{id}/degerlendirme', [KullaniciUrunController::class, 'degerlendirmeYap'])->name('urun.degerlendirme');

    // --- Kupon İşlemleri (Müşteri Tarafı) ---
    Route::get('/kuponlarim', [KullaniciController::class, 'kuponlarim'])->name('kuponlarim');
    Route::get('hesabim/kuponlarim', [HesapController::class, 'kuponlarim'])->name('hesap.kuponlarim');
    Route::post('sepet/kupon-uygula', [SepetController::class, 'kuponUygula'])->name('sepet.kupon-uygula');
    Route::post('sepet/kupon-kaldir', [SepetController::class, 'kuponKaldir'])->name('sepet.kupon-kaldir');

    // --- Konfigürasyon ---
    Route::delete('/kullanici/konfig/{id}', [KullaniciController::class, 'sil'])->name('konfig.sil');
    Route::post('/kullanici/konfig/{id}/sepete-ekle', [KullaniciController::class, 'konfigSepeteEkle'])->name('konfig.sepeteEkle');
    Route::get('/konfigurasyon/{id}/sepete-ekle', [KullaniciController::class, 'konfigSepeteEkle'])->name('konfigurasyon.sepet');
    Route::delete('/konfigurasyon/{id}/sil', [KullaniciController::class, 'sil'])->name('konfigurasyon.sil');

    // --- Favoriler ---
    Route::prefix('kullanici/favori')->name('favori.')->group(function () {
        Route::post('/toggle', [FavoriController::class, 'toggle'])->name('toggle');
        Route::get('/', [FavoriController::class, 'listele'])->name('listele');
        Route::post('/ekle/{urunId}', [FavoriController::class, 'ekle'])->name('ekle');
        Route::delete('/{id}', [FavoriController::class, 'sil'])->name('sil');
        Route::delete('/urun/{urunId}', [FavoriController::class, 'urunSil'])->name('urun.sil');
        Route::get('/durum/{urunId}', [FavoriController::class, 'durumKontrol'])->name('durum');
        Route::get('/api', [FavoriController::class, 'apiFavoriler'])->name('api');
    });
    
    // Favori Sayısı
    Route::get('/favori-sayisi', function() {
        return response()->json(['count' => FavoriUrun::where('user_id', Auth::id())->count()]);
    })->name('favori.sayisi');

    // --- Siparişler ---
    Route::get('/siparislerim', [SiparisController::class, 'siparislerim'])->name('siparislerim');
    Route::get('/siparis/olustur', [SiparisController::class, 'olustur'])->name('siparis.olustur');
    Route::post('/siparis/tamamla', [SiparisController::class, 'tamamla'])->name('siparis.tamamla');
    Route::get('/siparis/basarili/{id}', [SiparisController::class, 'basarili'])->name('siparis.basarili');
    Route::get('/siparis/detay/{id}', [SiparisController::class, 'detay'])->name('siparis.detay');
    Route::get('/siparis/{id}/fatura', [SiparisController::class, 'fatura'])->name('fatura.goster');
    Route::post('/siparis/kupon-kontrol', [SiparisController::class, 'kuponKontrol'])->name('siparis.kupon.kontrol');

    // --- Ödeme ---
    Route::prefix('odeme')->name('odeme.')->group(function () {
        Route::get('/basla/{siparis_id}', [OdemeController::class, 'basla'])->name('basla');
        Route::post('/kredi-karti', [OdemeController::class, 'krediKarti'])->name('kredi.karti');
        Route::get('/basarili/{siparis_id}', [OdemeController::class, 'basarili'])->name('basarili');
        Route::get('/basarisiz/{siparis_id}', [OdemeController::class, 'basarisiz'])->name('basarisiz');
        Route::post('/callback', [OdemeController::class, 'callback'])->name('callback');
    });

    // --- Wizard (Sihirbaz) ---
    Route::get('/wizard', [WizardController::class, 'index'])->name('wizard.index');
    Route::get('/wizard/urunler/{altKategoriId}', [WizardController::class, 'getUrunler'])->name('wizard.getUrunler');
    Route::post('/wizard/konfigurasyon-kaydet', [WizardController::class, 'konfigurasyonKaydet'])->name('wizard.kaydet');
});

// ===================== ADMIN PANELİ =====================
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    
    // İçerik Yönetimi
    Route::resource('sliders', SliderController::class);
    Route::resource('blog', BlogController::class);
    
    // Sayfa Yönetimi
    Route::get('pages', [PageController::class, 'index'])->name('pages.index');
    Route::get('pages/create', [PageController::class, 'create'])->name('pages.create');
    Route::post('pages/store', [PageController::class, 'store'])->name('pages.store');
    Route::get('pages/{slug}/edit', [PageController::class, 'edit'])->name('pages.edit');
    Route::put('pages/{slug}/update', [PageController::class, 'update'])->name('pages.update');

    // Değerlendirmeler
    Route::get('/degerlendirmeler', [DegerlendirmeController::class, 'index'])->name('degerlendirmeler.index');
    Route::post('/degerlendirmeler/{id}/onayla', [DegerlendirmeController::class, 'onayla'])->name('degerlendirmeler.onayla');
    Route::delete('/degerlendirmeler/{id}/sil', [DegerlendirmeController::class, 'sil'])->name('degerlendirmeler.sil');
    Route::post('/degerlendirmeler/{id}/cevapla', [DegerlendirmeController::class, 'cevapla'])->name('degerlendirmeler.cevapla');
    Route::delete('/degerlendirmeler/{id}/cevap-sil', [DegerlendirmeController::class, 'cevapSil'])->name('degerlendirmeler.cevapSil');

    // Kategoriler
    Route::resource('kategoriler', KategoriController::class)->parameters(['kategoriler' => 'kategori']);
    Route::resource('altkategoriler', AltKategoriController::class)->except(['index', 'create']);

    // Ürün & Fiyat
    Route::resource('urunler', UrunController::class);
    Route::get('urunler/kriterler/{altKategoriId}', [UrunController::class, 'getKriterlerByAltKategori'])->name('urunler.getKriterlerByAltKategori');
    Route::post('urunler/{id}/fiyat-ekle', [UrunController::class, 'storeFiyat'])->name('urunler.fiyat.store');
    Route::delete('urunler/fiyat-sil/{id}', [UrunController::class, 'deleteFiyat'])->name('urunler.fiyat.delete');

    // Kriterler
    Route::resource('kriterler', KriterController::class)->except(['create'])->parameters(['kriterler' => 'kriter']);
    Route::controller(KriterDegerController::class)->group(function () {
        Route::get('/kriter-degerleri', 'index')->name('kriterdegerleri.index'); 
        Route::post('/kriter-degerleri/store', 'store')->name('kriterdegerleri.store');
        Route::delete('/kriter-degerleri/{kriterDeger}', 'destroy')->name('kriterdegerleri.destroy');
    });

    // Siparişler
    Route::post('siparisler/{id}/durum-guncelle', [AdminSiparisController::class, 'durumGuncelle'])->name('siparisler.durumGuncelle');
    Route::resource('siparisler', AdminSiparisController::class);
    
    // --- KUPON YÖNETİMİ (DÜZELTİLDİ) ---
    // 1. Önce özel (spesifik) rotalar
    Route::get('kuponlar/ara/kullanici', [KuponController::class, 'kullaniciAra'])->name('kuponlar.kullanici-ara');
    Route::post('kuponlar/otomatik-ata', [KuponController::class, 'kuralBazliKuponlariAta'])->name('kuponlar.otomatik-ata');
    Route::post('kuponlar/{kupon}/kural-calistir', [KuponController::class, 'tekilKuralCalistir'])->name('kuponlar.kural-calistir');
    Route::get('kuponlar/{kupon}/istatistikler', [KuponController::class, 'istatistikler'])->name('kuponlar.istatistikler');
    
    // 2. Resource Tanımlaması (Hata burada düzeltildi)
    // parameters(['kuponlar' => 'kupon']) sayesinde URL'deki {kuponlar} parametresi {kupon} olarak değişir
    // ve Controller'daki (Kupon $kupon) ile eşleşir.
    Route::resource('kuponlar', KuponController::class)->parameters([
        'kuponlar' => 'kupon'
    ]);

    // Kampanyalar & Fiyatlar
    Route::resource('kampanyalar', KampanyaIndirimController::class);
    Route::resource('fiyatlar', UrunFiyatController::class)->except(['show']);

    // Bayiler
    Route::prefix('bayiler')->name('bayiler.')->group(function () {
        Route::get('/basvurular', [BayiController::class, 'basvurular'])->name('basvurular'); 
        Route::get('/', [BayiController::class, 'index'])->name('index'); 
        Route::post('/{basvuru}/onayla', [BayiController::class, 'approve'])->name('approve');
        Route::post('/{basvuru}/reddet', [BayiController::class, 'reject'])->name('reject');
    });
});

// ===================== WEBHOOK & DOSYA PROXY (Auth Gerektirmez) =====================
Route::prefix('webhook')->name('webhook.')->withoutMiddleware(['web', 'auth'])->group(function () {
    Route::post('/odeme-callback', [OdemeController::class, 'paymentCallback'])->name('odeme.callback');
});

// Storage Proxy
Route::get('storage/{path}', function ($path) {
    $fullPath = storage_path('app/public/' . $path);
    if (!File::exists($fullPath)) abort(404);
    
    $file = File::get($fullPath);
    $type = File::mimeType($fullPath);
    
    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);
    return $response;
})->where('path', '.*')->name('storage.proxy');