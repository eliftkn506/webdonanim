<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UyumlulukKurali;
use App\Models\AltKategori;
use App\Models\Kriter;
use App\Models\Urun;
use App\Models\UyumluUrun;
use Illuminate\Support\Facades\DB;

class UyumlulukKuraliController extends Controller
{
    /**
     * Uyumluluk kuralları listesi
     */
    public function index()
    {
        $kurallar = UyumlulukKurali::with([
            'anaKategori.kategori',   // Zincirleme eager loading
            'hedefKategori.kategori', // Zincirleme eager loading
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

            // Yeni kurala göre uyumlulukları hesapla
            $this->yenidenHesaplaUyumluluk($kural);

            DB::commit();
            return redirect()->route('admin.uyumluluk.index')
                ->with('success', 'Kural başarıyla oluşturuldu ve ürünler eşleştirildi.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Hata: ' . $e->getMessage());
        }
    }

    /**
     * Kural düzenleme formu
     */
    public function edit(UyumlulukKurali $uyumluluk)
    {
        $altKategoriler = AltKategori::with('kategori')
            ->orderBy('alt_kategori_ad')
            ->get()
            ->groupBy('kategori.kategori_ad');

        // Mevcut seçili kategorilere ait kriterleri getir
        $anaKriterler = Kriter::where('alt_kategori_id', $uyumluluk->ana_kategori_id)->orderBy('kriter_ad')->get();
        $hedefKriterler = Kriter::where('alt_kategori_id', $uyumluluk->hedef_kategori_id)->orderBy('kriter_ad')->get();

        return view('admin.uyumluluk.create', compact('uyumluluk', 'altKategoriler', 'anaKriterler', 'hedefKriterler'));
    }

    /**
     * Kural güncelleme
     */
    public function update(Request $request, UyumlulukKurali $uyumluluk)
    {
        $request->validate([
            'ana_kategori_id' => 'required|exists:alt_kategoriler,id',
            'hedef_kategori_id' => 'required|exists:alt_kategoriler,id|different:ana_kategori_id',
            'ana_kriter_id' => 'required|exists:kriterler,id',
            'hedef_kriter_id' => 'required|exists:kriterler,id',
        ]);

        DB::beginTransaction();
        try {
            // 1. Adım: Eski kuralla oluşturulmuş eşleşmeleri temizle
            $this->kuralUyumluluklariSil($uyumluluk);

            // 2. Adım: Kuralı güncelle
            $uyumluluk->update($request->all());

            // 3. Adım: Yeni kuralla tekrar hesapla
            $this->yenidenHesaplaUyumluluk($uyumluluk);

            DB::commit();
            return redirect()->route('admin.uyumluluk.index')->with('success', 'Kural güncellendi ve eşleşmeler yenilendi.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Hata: ' . $e->getMessage());
        }
    }

    /**
     * Kural silme
     */
    public function destroy(UyumlulukKurali $uyumluluk)
    {
        DB::beginTransaction();
        try {
            // Kurala ait eşleşmeleri sil
            $this->kuralUyumluluklariSil($uyumluluk);
            
            $uyumluluk->delete();

            DB::commit();
            return redirect()->route('admin.uyumluluk.index')->with('success', 'Kural ve ilişkili tüm eşleşmeler silindi.');

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
     * Tüm sistemi sıfırdan hesapla (Manuel Tetikleme)
     */
    public function yenidenHesaplaTumunu()
    {
        // Bu işlem sunucuyu yorabilir, time limitini artıralım
        set_time_limit(300); 

        DB::beginTransaction();
        try {
            // Tabloyu tamamen boşalt
            UyumluUrun::truncate();

            $kurallar = UyumlulukKurali::all();
            foreach ($kurallar as $kural) {
                $this->yenidenHesaplaUyumluluk($kural);
            }

            DB::commit();
            return redirect()->route('admin.uyumluluk.index')->with('success', 'Tüm sistem tarandı ve uyumluluklar yeniden oluşturuldu.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Hesaplama hatası: ' . $e->getMessage());
        }
    }

    /**
     * YARDIMCI: Belirli bir kural için ürünleri eşleştir
     */
    private function yenidenHesaplaUyumluluk(UyumlulukKurali $kural)
    {
        // Ana kategorideki ürünleri, kriter değerleriyle beraber çek
        $anaUrunler = Urun::where('alt_kategori_id', $kural->ana_kategori_id)
            ->with(['kriterDegerleri' => function($q) use ($kural) {
                $q->where('kriter_id', $kural->ana_kriter_id);
            }, 'varyasyonlar']) // Varyasyon desteği eklenebilir
            ->get();

        // Hedef kategorideki ürünleri çek
        $hedefUrunler = Urun::where('alt_kategori_id', $kural->hedef_kategori_id)
            ->with(['kriterDegerleri' => function($q) use ($kural) {
                $q->where('kriter_id', $kural->hedef_kriter_id);
            }])
            ->get();

        foreach ($anaUrunler as $anaUrun) {
            $anaDeger = $anaUrun->kriterDegerleri->first();
            
            if (!$anaDeger) continue; // Bu ürünün ilgili kriter değeri yoksa geç

            foreach ($hedefUrunler as $hedefUrun) {
                $hedefDeger = $hedefUrun->kriterDegerleri->first();

                if (!$hedefDeger) continue;

                // Değerler Eşleşiyorsa (Örn: "LGA1700" == "LGA1700")
                if (strtoupper(trim($anaDeger->deger)) === strtoupper(trim($hedefDeger->deger))) {
                    $this->createUyumluluk($anaUrun->id, $hedefUrun->id);
                }
            }
        }
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

        // 2 -> 1 (Ters ilişki - PC Toplama sihirbazında hangi parçayı önce seçerse seçsin çalışması için)
        UyumluUrun::firstOrCreate([
            'urun_id' => $urunId2,
            'uyumlu_urun_id' => $urunId1
        ]);
    }

    /**
     * YARDIMCI: Kural silindiğinde veya değiştiğinde eski eşleşmeleri temizle
     */
    private function kuralUyumluluklariSil(UyumlulukKurali $kural)
    {
        // Bu iki kategori arasındaki TÜM eşleşmeleri siler.
        // DİKKAT: Eğer aynı iki kategori arasında birden fazla kural varsa (örn: hem Soket hem Chipset uymalı),
        // bu yöntem o kuralların eşleşmelerini de silebilir. 
        // Ancak genelde parça uyumluluğu tekil kural setleriyle yönetilir.
        
        $anaUrunIds = Urun::where('alt_kategori_id', $kural->ana_kategori_id)->pluck('id');
        $hedefUrunIds = Urun::where('alt_kategori_id', $kural->hedef_kategori_id)->pluck('id');

        // Ana -> Hedef yönündeki silme
        UyumluUrun::whereIn('urun_id', $anaUrunIds)
            ->whereIn('uyumlu_urun_id', $hedefUrunIds)
            ->delete();

        // Hedef -> Ana yönündeki silme (Ters ilişki)
        UyumluUrun::whereIn('urun_id', $hedefUrunIds)
            ->whereIn('uyumlu_urun_id', $anaUrunIds)
            ->delete();
    }
}