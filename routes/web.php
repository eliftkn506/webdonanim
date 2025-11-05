<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// Controller importları
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\AltKategoriController;
use App\Http\Controllers\Admin\KriterController;
use App\Http\Controllers\Admin\KriterDegerController;
use App\Http\Controllers\Admin\UrunController;
use App\Http\Controllers\Admin\KampanyaIndirimController;
use App\Http\Controllers\Admin\AdminSiparisController;
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

// ===================== ANASAYFA =====================
Route::get('/', [HomeController::class, 'index'])->name('home');

// Auth routes
Auth::routes();


// Başvuru formunu gösteren GET route
Route::get('/bayi-basvuru', [BayiBasvuruController::class, 'showForm'])->name('bayi.basvuru.form');

// Başvuru kaydını yapan POST route
Route::post('/bayi-basvuru', [BayiBasvuruController::class, 'submit'])->name('bayi.basvuru.submit');


  
// ===================== ADMIN PANEL =====================
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');

    // Ürünler
    Route::get('urunler/uyumlu', [UrunController::class, 'uyumluUrunler'])->name('urunler.uyumlu');
    Route::resource('urunler', UrunController::class);
    Route::get('urunler/kriterler/{altKategoriId}', [UrunController::class, 'getKriterlerByAltKategori'])
        ->name('urunler.getKriterlerByAltKategori');

    // Kategoriler
    Route::resource('kategoriler', KategoriController::class);
    Route::resource('altkategoriler', AltKategoriController::class);

    // Kriterler
    Route::resource('kriterler', KriterController::class);
    Route::resource('kriterdegerleri', KriterDegerController::class);
    Route::get('kriterdegerleri/kriterler/{altKategoriId}', [KriterDegerController::class, 'getKriterlerByAltKategori'])
        ->name('kriterdegerleri.getKriterlerByAltKategori');

    // Kampanyalar
    Route::resource('kampanyalar', KampanyaIndirimController::class);

    // Siparişler
    Route::resource('siparisler', AdminSiparisController::class);
    Route::get('siparisler/bekleyen', [AdminSiparisController::class, 'bekleyen'])->name('siparisler.bekleyen');
    Route::post('siparisler/{id}/durum-guncelle', [AdminSiparisController::class, 'durumGuncelle'])
        ->name('siparisler.durum-guncelle');

   Route::resource('kuponlar', \App\Http\Controllers\Admin\KuponController::class);
    Route::get('kuponlar/kullanici-ara', [\App\Http\Controllers\Admin\KuponController::class, 'kullaniciAra'])->name('kuponlar.kullanici-ara');
    Route::post('kuponlar/otomatik-ata', [\App\Http\Controllers\Admin\KuponController::class, 'kuralBazliKuponlariAta'])->name('kuponlar.otomatik-ata');

    // Fiyat CRUD İşlemleri
    Route::resource('fiyatlar', UrunFiyatController::class)->except(['show']);
    Route::post('fiyatlar/preview', [UrunFiyatController::class, 'preview'])->name('fiyatlar.preview');
    Route::get('urunler/{urun}/fiyat-ata', [UrunFiyatController::class, 'assignToUrun'])->name('urunler.fiyat.assign');
    Route::post('urunler/{urun}/fiyat-ata', [UrunFiyatController::class, 'storeAssignment'])->name('urunler.fiyat.store');
    Route::delete('urunler/{urun}/fiyat/{fiyat}', [UrunFiyatController::class, 'removeAssignment'])->name('urunler.fiyat.remove');
    Route::get('fiyatlar/{fiyat}/hesapla', [UrunFiyatController::class, 'hesaplaFiyat'])->name('fiyatlar.hesapla');

    // BAYİLER - Düzeltilmiş route grubu
    Route::prefix('bayiler')->name('bayiler.')->group(function () {
        Route::get('/basvurular', [BayiController::class, 'basvurular'])->name('basvurular');
        Route::get('/', [BayiController::class, 'index'])->name('index');
        Route::get('/{basvuru}', [BayiController::class, 'show'])->name('show');
        Route::post('/{basvuru}/onayla', [BayiController::class, 'approve'])->name('approve');
        Route::post('/{basvuru}/reddet', [BayiController::class, 'reject'])->name('reject');
    });
});

// ===================== WIZARD (PC TOPLAMA) =====================
Route::middleware(['auth'])->group(function () {
    Route::get('/wizard', [WizardController::class, 'index'])->name('wizard.index');
    Route::get('/wizard/urunler/{altKategoriId}', [WizardController::class, 'getUrunler'])->name('wizard.getUrunler');
    Route::post('/wizard/konfigurasyon-kaydet', [WizardController::class, 'konfigurasyonKaydet'])->name('wizard.kaydet');
});

// ===================== SİPARİŞ VE ÖDEME =====================
Route::middleware(['auth'])->group(function () {
    // Sipariş
    Route::get('/siparis/olustur', [SiparisController::class, 'olustur'])->name('siparis.olustur');
    Route::post('/siparis/tamamla', [SiparisController::class, 'tamamla'])->name('siparis.tamamla');
    Route::get('/siparis/basarili/{id}', [SiparisController::class, 'basarili'])->name('siparis.basarili');
    Route::get('/profil', [KullaniciController::class, 'profil'])->name('profil');
    Route::get('/siparis/detay/{id}', [SiparisController::class, 'detay'])->name('siparis.detay');
    Route::get('/siparislerim', [SiparisController::class, 'siparislerim'])->name('siparislerim');
    Route::post('siparis/kupon-kontrol', [SiparisController::class, 'kuponKontrol'])->name('siparis.kupon.kontrol');
    Route::get('kuponlarim', [KullaniciController::class, 'kuponlarim'])->name('kuponlarim');

    // Ödeme
    Route::prefix('odeme')->name('odeme.')->group(function () {
        Route::get('/basla/{siparis_id}', [OdemeController::class, 'basla'])->name('basla');
        Route::post('/kredi-karti', [OdemeController::class, 'krediKarti'])->name('kredi.karti');
        Route::get('/basarili/{siparis_id}', [OdemeController::class, 'basarili'])->name('basarili');
        Route::get('/basarisiz/{siparis_id}', [OdemeController::class, 'basarisiz'])->name('basarisiz');
        Route::post('/callback', [OdemeController::class, 'callback'])->name('callback');
        Route::get('/test-kart', [OdemeController::class, 'testKart'])->name('test.kart');
    });

    // Fatura
    Route::prefix('fatura')->name('fatura.')->group(function () {
        Route::get('/{siparis_id}', [FaturaController::class, 'goster'])->name('goster');
        Route::get('/{siparis_id}/pdf', [FaturaController::class, 'pdfIndir'])->name('pdf');
        Route::get('/{siparis_id}/preview', [FaturaController::class, 'pdfOnizle'])->name('preview');
        Route::get('/{siparis_id}/html', [FaturaController::class, 'htmlOnizle'])->name('html');
        Route::post('/{siparis_id}/email', [FaturaController::class, 'emailGonder'])->name('email');
        Route::post('/{siparis_id}/efatura', [FaturaController::class, 'eFaturaGonder'])->name('efatura');
        Route::post('/toplu-indir', [FaturaController::class, 'topluPdfIndir'])->name('toplu.pdf');
        Route::get('/arsiv', [FaturaController::class, 'arsiv'])->name('arsiv');
    });
});

// ===================== ÜRÜNLER (Kullanıcı) =====================
Route::get('/urunler', [KullaniciUrunController::class, 'index'])->name('urun.index');
Route::get('/urunler/ara', [KullaniciUrunController::class, 'ara'])->name('urun.ara');
Route::get('/urunler/kategori/{id}', [KullaniciUrunController::class, 'kategori'])->name('urun.kategori');
Route::get('/urunler/altkategori/{id}', [KullaniciUrunController::class, 'altkategori'])->name('urun.altkategori');
Route::get('/urunler/incele/{id}', [KullaniciUrunController::class, 'incele'])->name('urun.incele');

// AJAX ve filtre route'ları
Route::post('/urunler/quick-filter', [KullaniciUrunController::class, 'quickFilter'])->name('urun.quickFilter');
Route::post('/urunler/reset-filters', [KullaniciUrunController::class, 'resetFilters'])->name('urun.resetFilters');
Route::get('/urun/ajax/altkategoriler', [KullaniciUrunController::class, 'getAltKategoriler'])->name('urun.ajax.altkategoriler');

// ===================== KULLANICI =====================
Route::middleware(['auth'])->group(function () {
    Route::get('/profil', [KullaniciController::class, 'profil'])->name('profil');

    // Konfigürasyon işlemleri
    Route::delete('/kullanici/konfig/{id}', [KullaniciController::class, 'sil'])->name('konfig.sil');
    Route::post('/kullanici/konfig/{id}/sepete-ekle', [KullaniciController::class, 'konfigSepeteEkle'])->name('konfig.sepeteEkle');

    // Favoriler
    Route::prefix('kullanici/favori')->name('favori.')->group(function () {
        Route::post('/toggle', [FavoriController::class, 'toggle'])->name('toggle');
        Route::get('/', [FavoriController::class, 'listele'])->name('listele');
        Route::post('/ekle/{urunId}', [FavoriController::class, 'ekle'])->name('ekle');
        Route::delete('/{id}', [FavoriController::class, 'sil'])->name('sil');
        Route::delete('/urun/{urunId}', [FavoriController::class, 'urunSil'])->name('urun.sil');
        Route::get('/durum/{urunId}', [FavoriController::class, 'durumKontrol'])->name('durum');
        Route::get('/api', [FavoriController::class, 'apiFavoriler'])->name('api');
    });
});



// ===================== SEPET =====================
Route::prefix('sepet')->group(function () {
    Route::get('/', [SepetController::class, 'index'])->name('sepet.index');
    Route::post('/ekle', [SepetController::class, 'ekle'])->name('sepet.ekle');
    Route::post('/ekle-konfig', [SepetController::class, 'konfigEkle'])->name('sepet.konfigEkle');
    Route::delete('/sil/{id}', [SepetController::class, 'sil'])->name('sepet.sil');
    Route::delete('/temizle', [SepetController::class, 'temizle'])->name('sepet.temizle');
    Route::post('/guncelle/{id}', [SepetController::class, 'guncelle'])->name('sepet.guncelle');
});

// ===================== SABİT SAYFALAR =====================
Route::get('/hakkimizda', [SayfaController::class, 'hakkimizda'])->name('hakkimizda');
Route::get('/iletisim', [SayfaController::class, 'iletisim'])->name('iletisim');
Route::post('/iletisim', [SayfaController::class, 'iletisimGonder'])->name('iletisim.gonder');

// ===================== API / AJAX =====================
Route::middleware(['auth'])->prefix('api')->name('api.')->group(function () {
    Route::get('/favoriler', [FavoriController::class, 'apiFavoriler'])->name('favoriler');
    Route::get('/favori-durum/{urunId}', [FavoriController::class, 'durumKontrol'])->name('favori.durum');
    Route::post('/favori/toplu-ekle', [FavoriController::class, 'topluEkle'])->name('favori.toplu.ekle');
    Route::delete('/favori/toplu-sil', [FavoriController::class, 'topluSil'])->name('favori.toplu.sil');
});

// ===================== ÖDEME WEBHOOK (CSRF KAPALI) =====================
Route::prefix('webhook')->name('webhook.')->withoutMiddleware(['web'])->group(function () {
    Route::post('/odeme-callback', [OdemeController::class, 'paymentCallback'])->name('odeme.callback');
    Route::post('/iyzico', [OdemeController::class, 'iyzicoWebhook'])->name('iyzico');
    Route::post('/payu', [OdemeController::class, 'payuWebhook'])->name('payu');
});

// ===================== SEPET / FAVORİ SAYISI =====================
Route::get('/sepet/sayisi', function() {
    $sepet = session('sepet', []);
    return response()->json([
        'count' => array_sum(array_column($sepet, 'adet'))
    ]);
})->name('sepet.sayisi');

Route::middleware(['auth'])->get('/favori-sayisi', function() {
    return response()->json([
        'count' => FavoriUrun::where('user_id', Auth::id())->count()
    ]);
})->name('favori.sayisi');

Route::middleware(['auth'])->post('/favori-kontrol', function(Request $request) {
    $urunIds = $request->input('urun_ids', []);

    $favoriler = FavoriUrun::where('user_id', Auth::id())
                            ->whereIn('urun_id', $urunIds)
                            ->pluck('urun_id')
                            ->toArray();
    
    return response()->json([
        'favorites' => $favoriler
    ]);
})->name('favori.kontrol');

Route::middleware(['auth'])->group(function () {
    Route::get('/profil', [KullaniciController::class, 'profil'])->name('profil');
    Route::get('/siparis/{id}/detay', [SiparisController::class, 'detay'])->name('siparis.detay');
    Route::get('/siparis/{id}/fatura', [SiparisController::class, 'fatura'])->name('fatura.goster');
});






Route::middleware(['auth'])->group(function () {
    // Konfigürasyonu sepete ekleme
    Route::get('/konfigurasyon/{id}/sepete-ekle', [KullaniciController::class, 'konfigSepeteEkle'])
         ->name('konfigurasyon.sepet');

    // Konfigürasyonu silme
    Route::delete('/konfigurasyon/{id}/sil', [KullaniciController::class, 'sil'])
         ->name('konfigurasyon.sil');
});
