<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Urun;
use App\Models\AltKategori;
use App\Models\Kriter;
use App\Models\UyumluUrun;
use App\Models\UrunVaryasyon;
use App\Models\UrunVaryasyonKriterDegeri;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\UrunFiyat;

class UrunController extends Controller
{
    public function index()
{
    $urunler = Urun::with(['altKategori', 'fiyatlar'])->paginate(15);
    $fiyatlar = UrunFiyat::all(); // Tüm fiyatları ekle
    return view('admin.urunler.index', compact('urunler', 'fiyatlar'));
}

    // Yeni ürün ekleme formu
    public function create()
    {
        $altkategoriler = AltKategori::with('kategori')->orderBy('alt_kategori_ad')->get();
        return view('admin.urunler.create', compact('altkategoriler'));
    }

     public function store(Request $request)
    {
        $request->validate([
            'alt_kategori_id' => 'required|exists:alt_kategoriler,id',
            'urun_ad'         => 'required|string|max:255',
            'marka'           => 'required|string|max:255',
            'model'           => 'required|string|max:255',
            'resim_url'       => 'nullable|string',
            'barkod_no'       => 'nullable|string|max:100',
            'aciklama'        => 'nullable|string',
           
            'stok'            => 'required|integer|min:0',
            'kriter_degerleri'=> 'nullable|array',
            'varyasyonlar'    => 'nullable|array',
        ]);

        DB::beginTransaction();
        try {
            // Ana ürün oluştur
            $urun = Urun::create([
                'alt_kategori_id' => $request->alt_kategori_id,
                'urun_ad'         => $request->urun_ad,
                'marka'           => $request->marka,
                'model'           => $request->model,
                'resim_url'       => $request->resim_url,
                'barkod_no'       => $request->barkod_no,
                'aciklama'        => $request->aciklama,
                
               
                'stok'            => $request->stok,
            ]);

            // Ana ürün kriter değerlerini kaydet
            if ($request->has('kriter_degerleri') && is_array($request->kriter_degerleri)) {
                foreach ($request->kriter_degerleri as $kriterId => $degerId) {
                    if ($degerId) {
                        $urun->kriterDegerleri()->attach($degerId, ['kriter_id' => $kriterId]);
                    }
                }
            }

            // Varyasyonları kaydet - ANA ÜRÜN BİLGİLERİYLE BİRLİKTE
            if ($request->has('varyasyonlar') && is_array($request->varyasyonlar)) {
                $varyasyonIndex = 1;
                foreach ($request->varyasyonlar as $varyasyonData) {
                    // Varyasyon için benzersiz barkod oluştur
                    $varyasyonBarkod = $request->barkod_no 
                        ? $request->barkod_no . '-V' . $varyasyonIndex
                        : 'VAR-' . $urun->id . '-' . $varyasyonIndex;

                    $varyasyon = $urun->varyasyonlar()->create([
                        // Ana ürün bilgilerini kopyala
                        'urun_ad'   => $request->urun_ad,
                        'marka'     => $request->marka,
                        'model'     => $request->model,
                        'aciklama'  => $request->aciklama,
                        'resim_url' => $request->resim_url,
                        'barkod_no' => $varyasyonBarkod,
                        // Varyasyona özgü bilgiler
                      
                        'stok'      => $varyasyonData['stok'],
                      
                    ]);
                    
                    $varyasyonIndex++;

                    // Varyasyon kriter değerlerini kaydet
                    if (isset($varyasyonData['kriter_degerleri']) && is_array($varyasyonData['kriter_degerleri'])) {
                        foreach ($varyasyonData['kriter_degerleri'] as $kriterId => $degerId) {
                            if ($degerId) {
                                UrunVaryasyonKriterDegeri::create([
                                    'urun_varyasyon_id' => $varyasyon->id,
                                    'kriter_id'         => $kriterId,
                                    'kriter_deger_id'   => $degerId,
                                ]);
                            }
                        }
                    }
                }
            }

            // Uyumluluk kontrolü
            $this->syncUyumluluk($urun);

            DB::commit();
            return redirect()->route('admin.urunler.index')
                ->with('success', 'Ürün ve ' . count($request->varyasyonlar ?? []) . ' varyasyon başarıyla eklendi.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ürün eklenirken hata oluştu: ' . $e->getMessage());
        }
    }

    

    // Düzenleme formu
    public function edit(Urun $urun)
    {
        $altkategoriler = AltKategori::with('kategori')->orderBy('alt_kategori_ad')->get();
        $urun->load([
            'kriterDegerleri.kriter', 
            'varyasyonlar.kriterDegerleri.kriter',
            'altKategori.kriterler.degerler'
        ]);
        
        return view('admin.urunler.edit', compact('urun', 'altkategoriler'));
    }

    public function update(Request $request, Urun $urun)
    {
        $request->validate([
            'alt_kategori_id' => 'required|exists:alt_kategoriler,id',
            'urun_ad'         => 'required|string|max:255',
            'marka'           => 'required|string|max:255',
            'model'           => 'required|string|max:255',
            'aciklama'        => 'nullable|string',
            'resim_url'       => 'nullable|string',
            'barkod_no'       => 'nullable|string|max:100',
        
            'stok'            => 'required|integer|min:0',
            'kriter_degerleri'=> 'nullable|array',
            'varyasyonlar'    => 'nullable|array',
        ]);

        DB::beginTransaction();
        try {
            // Ana ürünü güncelle
            $urun->update([
                'alt_kategori_id' => $request->alt_kategori_id,
                'urun_ad'         => $request->urun_ad,
                'marka'           => $request->marka,
                'model'           => $request->model,
                'resim_url'       => $request->resim_url,
                'barkod_no'       => $request->barkod_no,
                'aciklama'        => $request->aciklama,
                
                'stok'            => $request->stok,
            ]);

            // Kriter değerlerini güncelle
            $urun->kriterDegerleri()->detach();
            if ($request->has('kriter_degerleri') && is_array($request->kriter_degerleri)) {
                foreach ($request->kriter_degerleri as $kriterId => $degerId) {
                    if ($degerId) {
                        $urun->kriterDegerleri()->attach($degerId, ['kriter_id' => $kriterId]);
                    }
                }
            }

            // Eski varyasyonları sil
            foreach ($urun->varyasyonlar as $eskiVaryasyon) {
                UrunVaryasyonKriterDegeri::where('urun_varyasyon_id', $eskiVaryasyon->id)->delete();
            }
            $urun->varyasyonlar()->delete();

            // Yeni varyasyonları ekle - ANA ÜRÜN BİLGİLERİYLE
            if ($request->has('varyasyonlar') && is_array($request->varyasyonlar)) {
                foreach ($request->varyasyonlar as $index => $varyasyonData) {
                    $varyasyonBarkod = $request->barkod_no 
                        ? $request->barkod_no . '-V' . ($index + 1)
                        : null;

                    $varyasyon = $urun->varyasyonlar()->create([
                        // Ana ürün bilgilerini kopyala
                        'urun_ad'   => $request->urun_ad,
                        'marka'     => $request->marka,
                        'model'     => $request->model,
                        'aciklama'  => $request->aciklama,
                        'resim_url' => $request->resim_url,
                        'barkod_no' => $varyasyonBarkod,
                        // Varyasyona özgü bilgiler
                        
                        'stok'      => $varyasyonData['stok'],
                        
                    ]);

                    if (isset($varyasyonData['kriter_degerleri']) && is_array($varyasyonData['kriter_degerleri'])) {
                        foreach ($varyasyonData['kriter_degerleri'] as $kriterId => $degerId) {
                            if ($degerId) {
                                UrunVaryasyonKriterDegeri::create([
                                    'urun_varyasyon_id' => $varyasyon->id,
                                    'kriter_id'         => $kriterId,
                                    'kriter_deger_id'   => $degerId,
                                ]);
                            }
                        }
                    }
                }
            }

            // Uyumluluğu güncelle
            $this->syncUyumluluk($urun);

            DB::commit();
            return redirect()->route('admin.urunler.index')
                ->with('success', 'Ürün başarıyla güncellendi.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ürün güncellenirken hata oluştu: ' . $e->getMessage());
        }
    }

   public function destroy(Urun $urun)
    {
        DB::beginTransaction();
        try {
            // 1. Kriter değerlerini sil (belongsToMany)
            $urun->kriterDegerleri()->detach();
            
            // 2. FİYAT ilişkilerini sil (belongsToMany) - BU EKSİKTİ
            $urun->fiyatlar()->detach();

            // 3. Varyasyonları ve kriter değerlerini sil (hasMany)
            foreach ($urun->varyasyonlar as $varyasyon) {
                // Varyasyonun kendi kriterlerini sil
                UrunVaryasyonKriterDegeri::where('urun_varyasyon_id', $varyasyon->id)->delete();
            }
            $urun->varyasyonlar()->delete(); // Tüm varyasyonları sil
            
            // 4. Uyumluluk kayıtlarını sil (hasMany/custom)
            UyumluUrun::where('urun_id', $urun->id)
                ->orWhere('uyumlu_urun_id', $urun->id)
                ->delete();
            
            // 5. FAVORİLERİ sil (hasMany) - BU EKSİKTİ
            $urun->favoriler()->delete();

            // 6. KAMPANYALARI sil (hasMany) - BU EKSİKTİ
            $urun->kampanyalar()->delete();

            
            // 7. Ürünü sil
            
            // SEÇENEK 1: Eğer Soft Deletes kullanıyorsanız ve bu amaçlıysa, bu kalsın:
            // $urun->delete(); 
            // Bu, 'deleted_at' sütununu doldurur ve ürün listede görünmez.

            // SEÇENEK 2: Eğer veritabanından KALICI olarak silmek istiyorsanız:
            $urun->forceDelete(); 
            // Yukarıdaki $urun->delete(); satırını silip bunu kullanın.
            

            DB::commit();
            return redirect()->route('admin.urunler.index')
                ->with('success', 'Ürün başarıyla kalıcı olarak silindi.'); // Mesajı güncelleyebilirsiniz
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Ürün silinirken hata oluştu: ' . $e->getMessage());
        }
    }

    // Ajax: alt kategoriye göre kriter ve değerleri getir
    public function getKriterlerByAltKategori($altKategoriId)
    {
        $kriterler = Kriter::where('alt_kategori_id', $altKategoriId)
            ->with('degerler')
            ->orderBy('kriter_ad')
            ->get();

        return response()->json($kriterler);
    }

    private function syncUyumluluk(Urun $urun)
    {
        $urun->load('kriterDegerleri', 'varyasyonlar');
        
        // Önce bu ürünle ilgili eski uyumlulukları sil
        UyumluUrun::where('urun_id', $urun->id)
            ->orWhere('uyumlu_urun_id', $urun->id)
            ->delete();
        
        $kurallar = DB::table('uyumluluk_kurallari')->get();

        foreach ($kurallar as $kural) {
            // Ana ürün kriterleriyle normal yönde kontrol
            $this->checkUyumluluk(
                $urun,
                $kural->ana_kategori_id,
                $kural->ana_kriter_id,
                $kural->hedef_kategori_id,
                $kural->hedef_kriter_id
            );

            // Ana ürün kriterleriyle ters yönde kontrol
            $this->checkUyumluluk(
                $urun,
                $kural->hedef_kategori_id,
                $kural->hedef_kriter_id,
                $kural->ana_kategori_id,
                $kural->ana_kriter_id
            );

            // Varyasyonlar için de kontrol yap
            foreach ($urun->varyasyonlar as $varyasyon) {
                $this->checkVaryasyonUyumluluk(
                    $varyasyon,
                    $kural->ana_kategori_id,
                    $kural->ana_kriter_id,
                    $kural->hedef_kategori_id,
                    $kural->hedef_kriter_id
                );

                $this->checkVaryasyonUyumluluk(
                    $varyasyon,
                    $kural->hedef_kategori_id,
                    $kural->hedef_kriter_id,
                    $kural->ana_kategori_id,
                    $kural->ana_kriter_id
                );
            }
        }
    }

    /**
     * Ana ürün kriterleriyle uyumluluk kontrolü
     */
    private function checkUyumluluk(Urun $urun, $anaKategoriId, $anaKriterId, $hedefKategoriId, $hedefKriterId)
    {
        if ($urun->alt_kategori_id != $anaKategoriId) return;

        $anaDeger = $urun->kriterDegerleri->firstWhere('pivot.kriter_id', $anaKriterId);
        if (!$anaDeger) return;

        // Hedef ürünleri bul (hem ana ürün hem varyasyonları kontrol et)
        $hedefUrunler = Urun::where('alt_kategori_id', $hedefKategoriId)
            ->where('id', '!=', $urun->id)
            ->with(['kriterDegerleri', 'varyasyonlar'])
            ->get();

        foreach ($hedefUrunler as $hedef) {
            // Hedef ürünün ana kriterleriyle kontrol
            $hedefDeger = $hedef->kriterDegerleri->firstWhere('pivot.kriter_id', $hedefKriterId);

            if ($hedefDeger && $hedefDeger->deger == $anaDeger->deger) {
                $this->createUyumluluk($urun->id, $hedef->id);
            }

            // Hedef ürünün varyasyonlarıyla kontrol
            foreach ($hedef->varyasyonlar as $hedefVaryasyon) {
                $hedefVaryasyonKriter = UrunVaryasyonKriterDegeri::where('urun_varyasyon_id', $hedefVaryasyon->id)
                    ->where('kriter_id', $hedefKriterId)
                    ->with('kriterDeger')
                    ->first();

                if ($hedefVaryasyonKriter && $hedefVaryasyonKriter->kriterDeger->deger == $anaDeger->deger) {
                    $this->createUyumluluk($urun->id, $hedef->id);
                    break; // Bir varyasyon uyumluysa yeter
                }
            }
        }
    }

    /**
     * Varyasyon kriterleriyle uyumluluk kontrolü
     */
    private function checkVaryasyonUyumluluk(UrunVaryasyon $varyasyon, $anaKategoriId, $anaKriterId, $hedefKategoriId, $hedefKriterId)
    {
        $urun = $varyasyon->urun;
        
        if ($urun->alt_kategori_id != $anaKategoriId) return;

        // Varyasyonun kriter değerini bul
        $varyasyonKriter = UrunVaryasyonKriterDegeri::where('urun_varyasyon_id', $varyasyon->id)
            ->where('kriter_id', $anaKriterId)
            ->with('kriterDeger')
            ->first();

        if (!$varyasyonKriter) return;

        // Hedef ürünleri bul
        $hedefUrunler = Urun::where('alt_kategori_id', $hedefKategoriId)
            ->where('id', '!=', $urun->id)
            ->with(['kriterDegerleri', 'varyasyonlar'])
            ->get();

        foreach ($hedefUrunler as $hedef) {
            // Hedef ürünün ana kriterleriyle kontrol
            $hedefDeger = $hedef->kriterDegerleri->firstWhere('pivot.kriter_id', $hedefKriterId);

            if ($hedefDeger && $hedefDeger->deger == $varyasyonKriter->kriterDeger->deger) {
                $this->createUyumluluk($urun->id, $hedef->id);
            }

            // Hedef ürünün varyasyonlarıyla kontrol
            foreach ($hedef->varyasyonlar as $hedefVaryasyon) {
                $hedefVaryasyonKriter = UrunVaryasyonKriterDegeri::where('urun_varyasyon_id', $hedefVaryasyon->id)
                    ->where('kriter_id', $hedefKriterId)
                    ->with('kriterDeger')
                    ->first();

                if ($hedefVaryasyonKriter && 
                    $hedefVaryasyonKriter->kriterDeger->deger == $varyasyonKriter->kriterDeger->deger) {
                    $this->createUyumluluk($urun->id, $hedef->id);
                    break;
                }
            }
        }
    }

    /**
     * Uyumluluk kaydı oluştur (çift yönlü)
     */
    private function createUyumluluk($urunId, $uyumluUrunId)
    {
        // Birinci yön
        UyumluUrun::firstOrCreate([
            'urun_id' => $urunId,
            'uyumlu_urun_id' => $uyumluUrunId,
        ]);

        // İkinci yön (ters ilişki)
        UyumluUrun::firstOrCreate([
            'urun_id' => $uyumluUrunId,
            'uyumlu_urun_id' => $urunId,
        ]);
    }

    /**
     * Uyumluluk listeleme sayfası
     */
    public function uyumluUrunler()
    {
        $uyumluUrunler = UyumluUrun::with([
            'urun' => function($query) {
                $query->with([
                    'altKategori',
                    'urunKriterDegerleri.kriter',
                    'urunKriterDegerleri.kriterDeger',
                    'varyasyonlar' => function($q) {
                        $q->with(['kriterDegerleri']);
                    }
                ]);
            },
            'uyumluUrun' => function($query) {
                $query->with([
                    'altKategori',
                    'urunKriterDegerleri.kriter',
                    'urunKriterDegerleri.kriterDeger',
                    'varyasyonlar' => function($q) {
                        $q->with(['kriterDegerleri']);
                    }
                ]);
            }
        ])
        ->orderBy('urun_id')
        ->orderBy('uyumlu_urun_id')
        ->paginate(20);

        return view('admin.urunler.uyumlu', compact('uyumluUrunler'));
    }

    public function uruneFiyatEkle(Request $request, $urunId)
{
    $urun = Urun::findOrFail($urunId);

    $fiyat = UrunFiyat::create([
        'fiyat_turu'   => $request->fiyat_turu,
        'maliyet'      => $request->maliyet,
        'kar_orani'    => $request->kar_orani,
        'bayi_indirimi'=> $request->bayi_indirimi ?? 0,
        'vergi_orani'  => $request->vergi_orani ?? 0,
    ]);

    // Pivot tablosuna ekleme
    $urun->fiyatlar()->attach($fiyat->fiyat_id, [
        'baslangic_tarihi' => $request->baslangic_tarihi,
        'bitis_tarihi'     => $request->bitis_tarihi,
    ]);

    return response()->json([
        'message' => 'Fiyat başarıyla eklendi',
        'satis_fiyati' => $urun->satis_fiyati
    ]);
}
}