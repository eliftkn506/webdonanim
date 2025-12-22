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
use App\Models\UrunKriterDegeri;
use Illuminate\Support\Facades\DB;
use App\Models\UrunFiyat;
use Illuminate\Support\Str;

class UrunController extends Controller
{
    public function index()
    {
        $urunler = Urun::with(['altKategori', 'fiyatlar'])->paginate(15);
        $fiyatlar = UrunFiyat::all();
        return view('admin.urunler.index', compact('urunler', 'fiyatlar'));
    }

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
            'resim'           => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'barkod_no'       => 'nullable|string|max:100',
            'aciklama'        => 'nullable|string',
            'stok'            => 'required|integer|min:0',
            'kriter_degerleri'=> 'nullable|array',
            'varyasyonlar'    => 'nullable|array',
        ]);

        DB::beginTransaction();
        try {
            // --- RESİM YÜKLEME ---
            $resimUrl = null;
            if ($request->hasFile('resim')) {
                $file = $request->file('resim');
                $fileName = Str::slug($request->urun_ad) . '-' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/urunler'), $fileName);
                $resimUrl = 'uploads/urunler/' . $fileName;
            }

            // --- ANA ÜRÜN OLUŞTURMA ---
            $urun = Urun::create([
                'alt_kategori_id' => $request->alt_kategori_id,
                'urun_ad'         => $request->urun_ad,
                'marka'           => $request->marka,
                'model'           => $request->model,
                'resim_url'       => $resimUrl,
                'barkod_no'       => $request->barkod_no,
                'aciklama'        => $request->aciklama,
                'stok'            => $request->stok,
            ]);

            // --- ANA ÜRÜN KRİTERLERİNİ KAYDET (Pivot Tablo: urun_kriter_degerleri) ---
            if ($request->has('kriter_degerleri') && is_array($request->kriter_degerleri)) {
                foreach ($request->kriter_degerleri as $kriterId => $degerId) {
                    if ($degerId) {
                        // Manuel insert yerine model kullanımı (Daha güvenli)
                        UrunKriterDegeri::create([
                            'urun_id' => $urun->id,
                            'kriter_id' => $kriterId,
                            'kriter_deger_id' => $degerId
                        ]);
                    }
                }
            }

            // --- VARYASYONLARI KAYDET ---
            if ($request->has('varyasyonlar') && is_array($request->varyasyonlar)) {
                $varyasyonIndex = 1;
                foreach ($request->varyasyonlar as $varyasyonData) {
                    $varyasyonBarkod = $request->barkod_no 
                        ? $request->barkod_no . '-V' . $varyasyonIndex
                        : 'VAR-' . $urun->id . '-' . $varyasyonIndex;

                    $varyasyon = $urun->varyasyonlar()->create([
                        'urun_ad'   => $request->urun_ad,
                        'marka'     => $request->marka,
                        'model'     => $request->model,
                        'aciklama'  => $request->aciklama,
                        'resim_url' => $resimUrl,
                        'barkod_no' => $varyasyonBarkod,
                        'stok'      => $varyasyonData['stok'],
                    ]);
                    
                    $varyasyonIndex++;

                    // Varyasyon Kriterleri (Pivot Tablo: urun_varyasyon_kriter_degerleri)
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

            // --- OTOMATİK UYUMLULUK TARAMASI ---
            // Veritabanına kayıt işlemi bittiği an bunu çalıştırıyoruz.
            $this->syncUyumluluk($urun);

            DB::commit();
            return redirect()->route('admin.urunler.index')
                ->with('success', 'Ürün başarıyla eklendi ve uyumluluk eşleştirmesi yapıldı.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Hata: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $urun = Urun::with([
                'kriterDegerleri', 
                'varyasyonlar.kriterDegerleri',
                'altKategori.kategori',
                'altKategori.kriterler.degerler'
            ])->findOrFail($id);
            
            $altkategoriler = AltKategori::with('kategori')->orderBy('alt_kategori_ad')->get();
            return view('admin.urunler.edit', compact('urun', 'altkategoriler'));
        } catch (\Exception $e) {
            return redirect()->route('admin.urunler.index')->with('error', 'Ürün bulunamadı.');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'alt_kategori_id' => 'required|exists:alt_kategoriler,id',
            'urun_ad'         => 'required|string|max:255',
            'marka'           => 'required|string|max:255',
            'model'           => 'required|string|max:255',
            'resim'           => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'barkod_no'       => 'nullable|string|max:100',
            'aciklama'        => 'nullable|string',
            'stok'            => 'required|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            $urun = Urun::findOrFail($id);
            
            // --- RESİM GÜNCELLEME ---
            $resimUrl = $urun->resim_url;
            if ($request->hasFile('resim')) {
                if ($urun->resim_url && file_exists(public_path($urun->resim_url))) {
                    @unlink(public_path($urun->resim_url));
                }
                $file = $request->file('resim');
                $fileName = Str::slug($request->urun_ad) . '-' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/urunler'), $fileName);
                $resimUrl = 'uploads/urunler/' . $fileName;
            }

            $urun->update([
                'alt_kategori_id' => $request->alt_kategori_id,
                'urun_ad'         => $request->urun_ad,
                'marka'           => $request->marka,
                'model'           => $request->model,
                'resim_url'       => $resimUrl,
                'barkod_no'       => $request->barkod_no,
                'aciklama'        => $request->aciklama,
                'stok'            => $request->stok,
            ]);

            // Kriterleri Yenile
            UrunKriterDegeri::where('urun_id', $urun->id)->delete();
            if ($request->has('kriter_degerleri') && is_array($request->kriter_degerleri)) {
                foreach ($request->kriter_degerleri as $kriterId => $degerId) {
                    if ($degerId) {
                        UrunKriterDegeri::create([
                            'urun_id' => $urun->id,
                            'kriter_id' => $kriterId,
                            'kriter_deger_id' => $degerId
                        ]);
                    }
                }
            }

            // Varyasyonları Yenile
            foreach ($urun->varyasyonlar as $eskiVaryasyon) {
                UrunVaryasyonKriterDegeri::where('urun_varyasyon_id', $eskiVaryasyon->id)->delete();
            }
            $urun->varyasyonlar()->delete();

            if ($request->has('varyasyonlar') && is_array($request->varyasyonlar)) {
                foreach ($request->varyasyonlar as $index => $varyasyonData) {
                    $varyasyonBarkod = $request->barkod_no ? $request->barkod_no . '-V' . ($index + 1) : null;
                    $varyasyon = $urun->varyasyonlar()->create([
                        'urun_ad'   => $request->urun_ad,
                        'marka'     => $request->marka,
                        'model'     => $request->model,
                        'aciklama'  => $request->aciklama,
                        'resim_url' => $resimUrl,
                        'barkod_no' => $varyasyonBarkod,
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

            // Uyumluluk taramasını güncelle
            $this->syncUyumluluk($urun);

            DB::commit();
            return redirect()->route('admin.urunler.index')->with('success', 'Ürün güncellendi.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Hata: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $urun = Urun::find($id);
        if (!$urun) return redirect()->route('admin.urunler.index')->with('error', 'Bulunamadı.');

        DB::beginTransaction();
        try {
            // İlişkileri Temizle
            UrunKriterDegeri::where('urun_id', $urun->id)->delete();
            
            if ($urun->fiyatlar) $urun->fiyatlar()->detach();

            if ($urun->varyasyonlar) {
                foreach ($urun->varyasyonlar as $varyasyon) {
                    UrunVaryasyonKriterDegeri::where('urun_varyasyon_id', $varyasyon->id)->delete();
                    $varyasyon->delete();
                }
            }

            // Uyumlulukları Sil
            UyumluUrun::where('urun_id', $urun->id)->orWhere('uyumlu_urun_id', $urun->id)->delete();

            try {
                if (method_exists($urun, 'favoriler')) $urun->favoriler()->delete();
                if (method_exists($urun, 'kampanyalar')) $urun->kampanyalar()->delete();
            } catch (\Exception $e) {}

            if ($urun->resim_url && file_exists(public_path($urun->resim_url))) {
                @unlink(public_path($urun->resim_url));
            }

            $urun->forceDelete(); 

            DB::commit();
            return redirect()->route('admin.urunler.index')->with('success', 'Ürün silindi.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Hata: ' . $e->getMessage());
        }
    }

    public function getKriterlerByAltKategori($altKategoriId)
    {
        $kriterler = Kriter::where('alt_kategori_id', $altKategoriId)
            ->with('degerler')->orderBy('kriter_ad')->get();
        return response()->json($kriterler);
    }

    // =====================================================================
    // === GELİŞMİŞ OTOMATİK UYUMLULUK MOTORU (Düzeltildi) ===
    // =====================================================================

    private function syncUyumluluk(Urun $urun)
    {
        // 1. Ürünün en güncel halini ve ilişkilerini yükle
        $urun->refresh();
        // belongsToMany kullandığın için pivot verisiyle beraber yükler
        $urun->load(['kriterDegerleri', 'varyasyonlar.kriterDegerleri']);
        
        // 2. Bu ürüne ait eski uyumluluk kayıtlarını temizle
        UyumluUrun::where('urun_id', $urun->id)
            ->orWhere('uyumlu_urun_id', $urun->id)
            ->delete();
        
        // 3. Tüm kuralları getir
        $kurallar = DB::table('uyumluluk_kurallari')->get();

        foreach ($kurallar as $kural) {
            // A. Eğer eklenen ürün "Ana Kategori"deyse, "Hedef Kategori"deki ürünleri ara
            $this->findAndLinkMatches(
                $urun,
                $kural->ana_kategori_id, // Kaynak (Bizim ürün)
                $kural->ana_kriter_id,   // Kaynak Kriter ID
                $kural->hedef_kategori_id, // Hedef Kategori
                $kural->hedef_kriter_id    // Hedef Kriter ID
            );

            // B. Eğer eklenen ürün "Hedef Kategori"deyse, "Ana Kategori"deki ürünleri ara (Ters Eşleşme)
            $this->findAndLinkMatches(
                $urun,
                $kural->hedef_kategori_id,
                $kural->hedef_kriter_id,
                $kural->ana_kategori_id,
                $kural->ana_kriter_id
            );
        }
    }

    /**
     * Veritabanı sorgusu ile eşleşen ürünleri bulup bağlar.
     */
    private function findAndLinkMatches(Urun $urun, $sourceCatId, $sourceCritId, $targetCatId, $targetCritId)
    {
        // Kuralın kaynak kategorisinde değilsek çık
        if ($urun->alt_kategori_id != $sourceCatId) return;

        // --- 1. ADIM: Bizim ürünün kriter değerlerini topla (Değer isimleri, örn: "AM4", "DDR4") ---
        $degerler = collect();

        // A. Ana ürünün kriter değerlerine bak
        // belongsToMany olduğu için ->kriterDegerleri direkt Collection döner.
        // Pivot verisine (kriter_id) göre filtreliyoruz.
        foreach ($urun->kriterDegerleri as $kriterDeger) {
            if ($kriterDeger->pivot->kriter_id == $sourceCritId) {
                $degerler->push($kriterDeger->deger);
            }
        }

        // B. Varyasyonların kriter değerlerine bak
        foreach ($urun->varyasyonlar as $varyasyon) {
            foreach ($varyasyon->kriterDegerleri as $kriterDeger) {
                if ($kriterDeger->pivot->kriter_id == $sourceCritId) {
                    $degerler->push($kriterDeger->deger);
                }
            }
        }

        $degerler = $degerler->unique()->filter(); // Boşları temizle, tekrar edenleri sil
        
        // Eğer bu ürünün kurala uygun bir değeri yoksa eşleşme arama
        if ($degerler->isEmpty()) return;

        // --- 2. ADIM: Hedef kategoride, bu değerlere sahip ürünleri bul ---
        // Bu sorgu, veritabanında "değeri X olan" ürünleri getirir.
        $eslesecekUrunler = Urun::where('alt_kategori_id', $targetCatId)
            ->where('id', '!=', $urun->id) // Kendisi hariç
            ->where(function($query) use ($targetCritId, $degerler) {
                
                // A. Hedef ürünün ANA kriterlerinde ara
                $query->whereHas('kriterDegerleri', function($q) use ($targetCritId, $degerler) {
                    // urun_kriter_degerleri pivot tablosundaki kriter_id ve asıl tablodaki değer
                    $q->where('urun_kriter_degerleri.kriter_id', $targetCritId)
                      ->whereIn('deger', $degerler);
                })
                // B. VEYA Hedef ürünün VARYASYONLARINDA ara
                ->orWhereHas('varyasyonlar', function($qVar) use ($targetCritId, $degerler) {
                    $qVar->whereHas('kriterDegerleri', function($qKd) use ($targetCritId, $degerler) {
                        $qKd->where('urun_varyasyon_kriter_degerleri.kriter_id', $targetCritId)
                            ->whereIn('deger', $degerler);
                    });
                });
            })
            ->get();

        // --- 3. ADIM: Bulunan ürünlerle eşleşme kaydı oluştur ---
        foreach ($eslesecekUrunler as $hedef) {
            $this->createUyumluluk($urun->id, $hedef->id);
        }
    }

    private function createUyumluluk($urunId, $uyumluUrunId)
    {
        // Çift yönlü kayıt (Tekrarları önler)
        UyumluUrun::firstOrCreate([
            'urun_id' => $urunId,
            'uyumlu_urun_id' => $uyumluUrunId,
        ]);

        UyumluUrun::firstOrCreate([
            'urun_id' => $uyumluUrunId,
            'uyumlu_urun_id' => $urunId,
        ]);
    }

    public function uyumluUrunler()
    {
        $uyumluUrunler = UyumluUrun::with([
            'urun' => function($query) {
                $query->with(['altKategori', 'urunKriterDegerleri.kriter', 'urunKriterDegerleri.kriterDeger', 'varyasyonlar']);
            },
            'uyumluUrun' => function($query) {
                $query->with(['altKategori', 'urunKriterDegerleri.kriter', 'urunKriterDegerleri.kriterDeger', 'varyasyonlar']);
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
        $fiyat = UrunFiyat::create($request->all());
        $urun->fiyatlar()->attach($fiyat->fiyat_id, [
            'baslangic_tarihi' => $request->baslangic_tarihi,
            'bitis_tarihi' => $request->bitis_tarihi
        ]);
        return response()->json(['message' => 'Fiyat eklendi', 'satis_fiyati' => $urun->satis_fiyati]);
    }

    public function show($id)
    {
        $urun = Urun::with(['altKategori.kategori', 'fiyatlar', 'varyasyonlar'])->findOrFail($id);
        return view('admin.urunler.show', compact('urun'));
    }
}