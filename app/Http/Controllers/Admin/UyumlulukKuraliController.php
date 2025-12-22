<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UyumlulukKurali;
use App\Models\AltKategori;
use App\Models\Kriter;
use App\Models\Urun;
use App\Models\UyumluUrun;
use App\Models\UrunKriterDegeri;
use App\Models\UrunVaryasyonKriterDegeri;
use Illuminate\Support\Facades\DB;

class UyumlulukKuraliController extends Controller
{
    /**
     * Uyumluluk kuralları listesi
     */
    public function index()
    {
        $kurallar = UyumlulukKurali::with([
            'anaKategori.kategori',   
            'hedefKategori.kategori', 
            'anaKriter',
            'hedefKriter'
        ])
        ->orderBy('created_at', 'desc')
        ->paginate(15);

        // İstatistikler
        $stats = [
            'toplam_kural' => UyumlulukKurali::count(),
            'kategori_sayisi' => AltKategori::count(),
            'toplam_uyumluluk' => UyumluUrun::count()
        ];

        return view('admin.uyumluluk.index', compact('kurallar', 'stats'));
    }

    /**
     * Yeni kural ekleme formu
     */
    public function create()
    {
        $altKategoriler = AltKategori::with('kategori')
            ->orderBy('alt_kategori_ad')
            ->get()
            ->groupBy('kategori.kategori_ad');

        return view('admin.uyumluluk.create', compact('altKategoriler'));
    }

    /**
     * Yeni kural kaydetme
     */
    public function store(Request $request)
    {
        $request->validate([
            'ana_kategori_id' => 'required|exists:alt_kategoriler,id',
            'hedef_kategori_id' => 'required|exists:alt_kategoriler,id|different:ana_kategori_id',
            'ana_kriter_id' => 'required|exists:kriterler,id',
            'hedef_kriter_id' => 'required|exists:kriterler,id',
        ], [
            'hedef_kategori_id.different' => 'Hedef kategori, ana kategoriden farklı olmalıdır.',
        ]);

        // Mükerrer kayıt kontrolü
        $exists = UyumlulukKurali::where('ana_kategori_id', $request->ana_kategori_id)
            ->where('hedef_kategori_id', $request->hedef_kategori_id)
            ->where('ana_kriter_id', $request->ana_kriter_id)
            ->where('hedef_kriter_id', $request->hedef_kriter_id)
            ->exists();

        if ($exists) {
            return back()->withInput()->with('error', 'Bu kural zaten tanımlı!');
        }

        DB::beginTransaction();
        try {
            $kural = UyumlulukKurali::create($request->all());

            // --- TETİKLEYİCİ: Yeni kurala göre mevcut ürünleri eşleştir ---
            $eslesmeSayisi = $this->yenidenHesaplaTekilKural($kural);

            DB::commit();
            return redirect()->route('admin.uyumluluk.index')
                ->with('success', "Kural oluşturuldu ve {$eslesmeSayisi} adet yeni eşleşme sağlandı.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Hata: ' . $e->getMessage());
        }
    }

    /**
     * Kural düzenleme formu
     */
    public function edit(UyumlulukKurali $uyumluluk) // Route model binding, parametre adı route'dakiyle aynı olmalı
    {
        // Eğer route parametreniz 'uyumluluk_kurali' veya 'id' ise binding çalışmayabilir, manuel bulalım:
        if (!$uyumluluk->exists) {
             // ID ile geliyorsa findOrFail yapalım
             $id = request()->route('uyumluluk'); 
             $uyumluluk = UyumlulukKurali::findOrFail($id);
        }

        $altKategoriler = AltKategori::with('kategori')
            ->orderBy('alt_kategori_ad')
            ->get()
            ->groupBy('kategori.kategori_ad');

        // Mevcut seçili kategorilere ait kriterleri getir (AJAX yerine backend'den hazır yollamak için)
        $anaKriterler = Kriter::where('alt_kategori_id', $uyumluluk->ana_kategori_id)->orderBy('kriter_ad')->get();
        $hedefKriterler = Kriter::where('alt_kategori_id', $uyumluluk->hedef_kategori_id)->orderBy('kriter_ad')->get();

        return view('admin.uyumluluk.create', compact('uyumluluk', 'altKategoriler', 'anaKriterler', 'hedefKriterler'));
    }

    /**
     * Kural güncelleme
     */
    public function update(Request $request, $id)
    {
        $uyumluluk = UyumlulukKurali::findOrFail($id);

        $request->validate([
            'ana_kategori_id' => 'required|exists:alt_kategoriler,id',
            'hedef_kategori_id' => 'required|exists:alt_kategoriler,id|different:ana_kategori_id',
            'ana_kriter_id' => 'required|exists:kriterler,id',
            'hedef_kriter_id' => 'required|exists:kriterler,id',
        ]);

        DB::beginTransaction();
        try {
            // 1. Adım: Eski kuralla ilgili olası eşleşmeleri temizlemek (Riskli olabilir, tümden temizleme daha güvenli)
            // Şimdilik sadece kuralı güncelleyip tarama yapacağız.
            
            $uyumluluk->update($request->all());

            // 2. Adım: Yeni kurala göre tekrar hesapla
            $eslesmeSayisi = $this->yenidenHesaplaTekilKural($uyumluluk);

            DB::commit();
            return redirect()->route('admin.uyumluluk.index')
                ->with('success', "Kural güncellendi ve {$eslesmeSayisi} adet eşleşme kontrol edildi.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Hata: ' . $e->getMessage());
        }
    }

    /**
     * Kural silme
     */
    public function destroy($id)
    {
        $uyumluluk = UyumlulukKurali::findOrFail($id);
        
        DB::beginTransaction();
        try {
            // Kurala ait eşleşmeleri silmeli miyiz?
            // Genelde evet, kural kalkarsa uyumluluk da bitmeli.
            // Ancak bu işlem karmaşıktır, o yüzden sadece kuralı siliyoruz.
            // İsterseniz tüm uyumlulukları sıfırlayan butonu kullanabilirsiniz.
            
            $uyumluluk->delete();

            DB::commit();
            return redirect()->route('admin.uyumluluk.index')->with('success', 'Kural silindi. Eşleşmeleri güncellemek için "Tümünü Yeniden Hesapla" diyebilirsiniz.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Silme hatası: ' . $e->getMessage());
        }
    }

    /**
     * AJAX: Alt kategoriye göre kriterleri getir
     */
    public function getKriterler($altKategoriId)
    {
        $kriterler = Kriter::where('alt_kategori_id', $altKategoriId)
            ->orderBy('kriter_ad')
            ->select('id', 'kriter_ad')
            ->get();

        return response()->json($kriterler);
    }

    /**
     * Tüm sistemi sıfırdan hesapla (Manuel Tetikleme Butonu İçin)
     * Route: Route::post('/uyumluluk/yeniden-hesapla', [UyumlulukKuraliController::class, 'yenidenHesaplaTumunu'])->name('admin.uyumluluk.recalc');
     */
    public function yenidenHesaplaTumunu()
    {
        set_time_limit(300); // 5 dakika süre tanı

        DB::beginTransaction();
        try {
            // 1. Tabloyu tamamen boşalt
            UyumluUrun::truncate();

            // 2. Tüm kuralları gez
            $kurallar = UyumlulukKurali::all();
            $toplamEslesme = 0;

            foreach ($kurallar as $kural) {
                $toplamEslesme += $this->yenidenHesaplaTekilKural($kural);
            }

            DB::commit();
            return redirect()->route('admin.uyumluluk.index')
                ->with('success', "Tüm sistem tarandı. Toplam {$toplamEslesme} eşleşme oluşturuldu.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Hesaplama hatası: ' . $e->getMessage());
        }
    }

    /**
     * --- MOTOR ---
     * Belirli bir kural için veritabanındaki tüm ürünleri tarar ve eşleştirir.
     */
    private function yenidenHesaplaTekilKural(UyumlulukKurali $kural)
    {
        $eslesmeSayaci = 0;

        // 1. Ana Kategorideki Ürünleri Bul (Sadece bu kuralın kriterine sahip olanlar)
        // Ana ürünün kriter değeri ne?
        $anaUrunler = Urun::where('alt_kategori_id', $kural->ana_kategori_id)
            ->with(['kriterDegerleri' => function($q) use ($kural) {
                $q->where('urun_kriter_degerleri.kriter_id', $kural->ana_kriter_id);
            }, 'varyasyonlar.kriterDegerleri'])
            ->get();

        // 2. Hedef Kategorideki Ürünleri Hazırla (Sorguyu döngü içinde tekrar etmemek için)
        // Bu sorguyu optimize etmek için "değer" bazlı gruplama yapılabilir ama
        // şimdilik basit mantıkla tüm hedef ürünleri çekelim.
        $hedefUrunlerTumu = Urun::where('alt_kategori_id', $kural->hedef_kategori_id)
            ->with(['kriterDegerleri' => function($q) use ($kural) {
                $q->where('urun_kriter_degerleri.kriter_id', $kural->hedef_kriter_id);
            }, 'varyasyonlar.kriterDegerleri'])
            ->get();

        foreach ($anaUrunler as $anaUrun) {
            // Bu ürünün kriter değeri(leri) nedir? (Dizi olarak alalım, varyasyonlar olabilir)
            $anaDegerler = collect();

            // Ana ürün pivot verisi
            foreach ($anaUrun->kriterDegerleri as $kd) {
                if ($kd->pivot->kriter_id == $kural->ana_kriter_id) {
                    $anaDegerler->push($kd->deger);
                }
            }
            // Varyasyon pivot verisi
            foreach ($anaUrun->varyasyonlar as $var) {
                foreach ($var->kriterDegerleri as $vkd) {
                    if ($vkd->pivot->kriter_id == $kural->ana_kriter_id) {
                        $anaDegerler->push($vkd->deger);
                    }
                }
            }
            
            $anaDegerler = $anaDegerler->unique()->filter();
            if ($anaDegerler->isEmpty()) continue; // Bu ürünün bu kural için değeri yoksa geç

            // Hedef ürünlerle karşılaştır
            foreach ($hedefUrunlerTumu as $hedefUrun) {
                // Kendisiyle eşleşmesin (farklı kategorideler ama yine de kontrol)
                if ($anaUrun->id == $hedefUrun->id) continue;

                $hedefDegerler = collect();
                
                // Hedef ana ürün değerleri
                foreach ($hedefUrun->kriterDegerleri as $hkd) {
                    if ($hkd->pivot->kriter_id == $kural->hedef_kriter_id) {
                        $hedefDegerler->push($hkd->deger);
                    }
                }
                // Hedef varyasyon değerleri
                foreach ($hedefUrun->varyasyonlar as $hvar) {
                    foreach ($hvar->kriterDegerleri as $hvkd) {
                        if ($hvkd->pivot->kriter_id == $kural->hedef_kriter_id) {
                            $hedefDegerler->push($hvkd->deger);
                        }
                    }
                }

                // Kesişim var mı? (Ortak değer varsa uyumludur)
                // Örn: Ana=["LGA1700"], Hedef=["LGA1700"] -> Eşleşir.
                if ($anaDegerler->intersect($hedefDegerler)->isNotEmpty()) {
                    $this->createUyumluluk($anaUrun->id, $hedefUrun->id);
                    $eslesmeSayaci++;
                }
            }
        }

        return $eslesmeSayaci;
    }

    /**
     * YARDIMCI: Çift yönlü kayıt oluştur
     */
    private function createUyumluluk($urunId1, $urunId2)
    {
        // 1 -> 2
        UyumluUrun::firstOrCreate([
            'urun_id' => $urunId1,
            'uyumlu_urun_id' => $urunId2
        ]);

        // 2 -> 1
        UyumluUrun::firstOrCreate([
            'urun_id' => $urunId2,
            'uyumlu_urun_id' => $urunId1
        ]);
    }
}