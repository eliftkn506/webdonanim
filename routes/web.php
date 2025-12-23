<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Artisan; // Link oluşturmak için eklendi

// ===================== CONTROLLER IMPORTLARI =====================
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\AltKategoriController;
use App\Http\Controllers\Admin\KriterController;
use App\Http\Controllers\Admin\KriterDegerController;
use App\Http\Controllers\Admin\UyumlulukKuraliController;
use App\Http\Controllers\Admin\UrunController;
use App\Http\Controllers\Admin\KampanyaIndirimController;
use App\Http\Controllers\Admin\AdminSiparisController;
use App\Http\Controllers\Admin\DegerlendirmeController;
use App\Http\Controllers\WizardController;
use App\Http\Controllers\KullaniciUrunController;
use App\Http\Controllers\KullaniciController;
use App\Http\Controllers\SepetController;
use App\Http\Controllers\SayfaController;
use App\Http\Controllers\FavoriController;
use App\Http\Controllers\SiparisController;
use App\Http\Controllers\FaturaController;
use App\Http\Controllers\OdemeController;
use App\Models\FavoriUrun;
use App\Http\Controllers\Admin\KuponController;
use App\Http\Controllers\Admin\UrunFiyatController;
use App\Http\Controllers\Admin\BayiController;
use App\Http\Controllers\BayiBasvuruController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\PageController;

// ===================== SUNUCU YARDIMCI ROTASI (ÖNEMLİ) =====================
// Resimler açılmazsa tarayıcıdan sitenize.com/storage-link-yap adresine gidin
Route::get('/storage-link-yap', function () {
    try {
        Artisan::call('storage:link');
        return "Storage linki başarıyla oluşturuldu!";
    } catch (\Exception $e) {
        return "Hata: " . $e->getMessage();
    }
});

// ===================== ANASAYFA =====================
Route::get('/', [HomeController::class, 'index'])->name('home');

// ===================== AUTH ROUTES =====================
Auth::routes();

// ===================== BAYİ BAŞVURU =====================
Route::get('/bayi-basvuru', [BayiBasvuruController::class, 'showForm'])->name('bayi.basvuru.form');
Route::post('/bayi-basvuru', [BayiBasvuruController::class, 'submit'])->name('bayi.basvuru.submit');

// ===================== ÜRÜNLER (Kullanıcı Arayüzü) =====================
Route::get('/urunler', [KullaniciUrunController::class, 'index'])->name('urun.index');
Route::get('/urunler/ara', [KullaniciUrunController::class, 'ara'])->name('urun.ara');
Route::get('/urunler/kategori/{id}', [KullaniciUrunController::class, 'kategori'])->name('urun.kategori');
Route::get('/urunler/altkategori/{id}', [KullaniciUrunController::class, 'altkategori'])->name('urun.altkategori');
Route::get('/urunler/incele/{id}', [KullaniciUrunController::class, 'incele'])->name('urun.incele');

// ===================== ÜRÜN FİLTRELEME - AJAX ENDPOINTS =====================
Route::get('/urun/get-alt-kategoriler', [KullaniciUrunController::class, 'getAltKategoriler'])->name('urun.getAltKategoriler');
Route::get('/urun/get-kriterler', [KullaniciUrunController::class, 'getKriterler'])->name('urun.getKriterler');
Route::get('/urun/get-marka-model', [KullaniciUrunController::class, 'getMarkaModel'])->name('urun.getMarkaModel');

// ===================== SEPET =====================
Route::prefix('sepet')->group(function () {
    Route::get('/', [SepetController::class, 'index'])->name('sepet.index');
    Route::post('/ekle', [SepetController::class, 'ekle'])->name('sepet.ekle');
    Route::post('/ekle-konfig', [SepetController::class, 'konfigEkle'])->name('sepet.konfigEkle');
    Route::delete('/sil/{id}', [SepetController::class, 'sil'])->name('sepet.sil');
    Route::delete('/temizle', [SepetController::class, 'temizle'])->name('sepet.temizle');
    Route::post('/guncelle/{id}', [SepetController::class, 'guncelle'])->name('sepet.guncelle');
});

Route::get('/sepet/sayisi', function() {
    $sepet = session('sepet', []);
    return response()->json([
        'count' => array_sum(array_column($sepet, 'adet'))
    ]);
})->name('sepet.sayisi');

// ===================== KULLANICI İŞLEMLERİ (AUTH GEREKLİ) =====================
Route::middleware(['auth'])->group(function () {
    Route::get('/profil', [KullaniciController::class, 'profil'])->name('profil');
    Route::get('/kuponlarim', [KullaniciController::class, 'kuponlarim'])->name('kuponlarim');
    Route::post('/urun/{id}/degerlendirme', [KullaniciUrunController::class, 'degerlendirmeYap'])->name('urun.degerlendirme');

    Route::delete('/kullanici/konfig/{id}', [KullaniciController::class, 'sil'])->name('konfig.sil');
    Route::post('/kullanici/konfig/{id}/sepete-ekle', [KullaniciController::class, 'konfigSepeteEkle'])->name('konfig.sepeteEkle');
    Route::get('/konfigurasyon/{id}/sepete-ekle', [KullaniciController::class, 'konfigSepeteEkle'])->name('konfigurasyon.sepet');
    Route::delete('/konfigurasyon/{id}/sil', [KullaniciController::class, 'sil'])->name('konfigurasyon.sil');

    // FAVORİLER
    Route::prefix('kullanici/favori')->name('favori.')->group(function () {
        Route::post('/toggle', [FavoriController::class, 'toggle'])->name('toggle');
        Route::get('/', [FavoriController::class, 'listele'])->name('listele');
        Route::post('/ekle/{urunId}', [FavoriController::class, 'ekle'])->name('ekle');
        Route::delete('/{id}', [FavoriController::class, 'sil'])->name('sil');
        Route::delete('/urun/{urunId}', [FavoriController::class, 'urunSil'])->name('urun.sil');
        Route::get('/durum/{urunId}', [FavoriController::class, 'durumKontrol'])->name('durum');
        Route::get('/api', [FavoriController::class, 'apiFavoriler'])->name('api');
    });

    Route::get('/favori-sayisi', function() {
        return response()->json(['count' => FavoriUrun::where('user_id', Auth::id())->count()]);
    })->name('favori.sayisi');

    Route::post('/favori-kontrol', function(Request $request) {
        $urunIds = $request->input('urun_ids', []);
        $favoriler = FavoriUrun::where('user_id', Auth::id())
                                ->whereIn('urun_id', $urunIds)
                                ->pluck('urun_id')
                                ->toArray();
        return response()->json(['favorites' => $favoriler]);
    })->name('favori.kontrol');

    // SİPARİŞLER
    Route::get('/siparislerim', [SiparisController::class, 'siparislerim'])->name('siparislerim');
    Route::get('/siparis/olustur', [SiparisController::class, 'olustur'])->name('siparis.olustur');
    Route::post('/siparis/tamamla', [SiparisController::class, 'tamamla'])->name('siparis.tamamla');
    Route::get('/siparis/basarili/{id}', [SiparisController::class, 'basarili'])->name('siparis.basarili');
    Route::get('/siparis/detay/{id}', [SiparisController::class, 'detay'])->name('siparis.detay');
    Route::get('/siparis/{id}/detay', [SiparisController::class, 'detay'])->name('siparis.detay.alt');
    Route::get('/siparis/{id}/fatura', [SiparisController::class, 'fatura'])->name('fatura.goster');
    Route::post('siparis/kupon-kontrol', [SiparisController::class, 'kuponKontrol'])->name('siparis.kupon.kontrol');

    // ÖDEME
    Route::prefix('odeme')->name('odeme.')->group(function () {
        Route::get('/basla/{siparis_id}', [OdemeController::class, 'basla'])->name('basla');
        Route::post('/kredi-karti', [OdemeController::class, 'krediKarti'])->name('kredi.karti');
        Route::get('/basarili/{siparis_id}', [OdemeController::class, 'basarili'])->name('basarili');
        Route::get('/basarisiz/{siparis_id}', [OdemeController::class, 'basarisiz'])->name('basarisiz');
        Route::post('/callback', [OdemeController::class, 'callback'])->name('callback');
    });

    // FATURA
    Route::prefix('fatura')->name('fatura.')->group(function () {
        Route::get('/{siparis_id}/pdf', [FaturaController::class, 'pdfIndir'])->name('pdf');
        Route::get('/arsiv', [FaturaController::class, 'arsiv'])->name('arsiv');
    });

    // WIZARD
    Route::get('/wizard', [WizardController::class, 'index'])->name('wizard.index');
    Route::get('/wizard/urunler/{altKategoriId}', [WizardController::class, 'getUrunler'])->name('wizard.getUrunler');
    Route::post('/wizard/konfigurasyon-kaydet', [WizardController::class, 'konfigurasyonKaydet'])->name('wizard.kaydet');
});

// ===================== SABİT SAYFALAR =====================
Route::get('/hakkimizda', [SayfaController::class, 'hakkimizda'])->name('hakkimizda');
Route::get('/iletisim', [SayfaController::class, 'iletisim'])->name('iletisim');
Route::post('/iletisim', [SayfaController::class, 'iletisimGonder'])->name('iletisim.gonder');

// ===================== ADMIN PANEL =====================
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::resource('sliders', SliderController::class);
    Route::get('pages', [PageController::class, 'index'])->name('pages.index');
    Route::resource('kategoriler', KategoriController::class)->parameters(['kategoriler' => 'kategori']);
    Route::resource('urunler', UrunController::class);
    Route::resource('siparisler', AdminSiparisController::class);
    
    Route::prefix('bayiler')->name('bayiler.')->group(function () {
        Route::get('/basvurular', [BayiController::class, 'basvurular'])->name('basvurular'); 
        Route::get('/', [BayiController::class, 'index'])->name('index'); 
        Route::post('/{basvuru}/onayla', [BayiController::class, 'approve'])->name('approve');
    });
});

// ===================== ÖDEME WEBHOOK =====================
Route::prefix('webhook')->name('webhook.')->withoutMiddleware(['web'])->group(function () {
    Route::post('/odeme-callback', [OdemeController::class, 'paymentCallback'])->name('odeme.callback');
});

// ===================== RESİM PROXY (SUNUCUDA GÖRÜNMEME ÇÖZÜMÜ) =====================
// Sadece storage/public altındaki dosyalara erişim sağlar
Route::get('storage/{path}', function ($path) {
    $fullPath = storage_path('app/public/' . $path);

    if (!File::exists($fullPath)) {
        abort(404);
    }

    $file = File::get($fullPath);
    $type = File::mimeType($fullPath);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
})->where('path', '.*')->name('storage.proxy');