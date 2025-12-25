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
use App\Models\UrunFiyat; // Fiyat modeli eklendi
use Illuminate\Support\Str;

class UrunController extends Controller
{
    public function index()
    {
        // Fiyatlar ilişkisiyle beraber ürünleri getir
        $urunler = Urun::with(['altKategori', 'fiyatlar'])->orderByDesc('created_at')->paginate(15);
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

            // --- ANA ÜRÜN KRİTERLERİNİ KAYDET ---
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

                    // Varyasyon Kriterleri
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

    // =====================================================================
    // === EKLENEN YENİ METODLAR (FİYAT YÖNETİMİ İÇİN) ===
    // =====================================================================

    /**
     * Ürün Detay Sayfası (Fiyat Yönetimi ile Birlikte)
     */
    public function show($id)
    {
        // Ürün ve tüm ilişkilerini (fiyatlar dahil) çekiyoruz
        $urun = Urun::with([
            'altKategori.kategori', 
            // Fiyatları en yeni tarihe göre sırala
            'fiyatlar' => function($q) {
                $q->orderByDesc('created_at');
            },
            'varyasyonlar',
            'kriterDegerleri',
            'uyumluUrunler.uyumluUrun'
        ])->findOrFail($id);

        return view('admin.urunler.show', compact('urun'));
    }

    /**
     * Ürün Detay Sayfasından Fiyat Ekleme Metodu
     * Bu metod, yeni bir UrunFiyat kaydı oluşturur ve eski aynı türdeki fiyatı arşivler.
     */
    public function storeFiyat(Request $request, $id)
    {
        // 1. Validasyon
        $request->validate([
            'fiyat_turu'       => 'required|in:standart,bayi,kampanya',
            'maliyet'          => 'required|numeric|min:0',
            'kar_orani'        => 'required|numeric|min:0',
            'vergi_orani'      => 'required|numeric|min:0',
            'baslangic_tarihi' => 'required|date',
            'bayi_indirimi'    => 'nullable|numeric|min:0',
            'bitis_tarihi'     => 'nullable|date|after_or_equal:baslangic_tarihi',
        ]);

        $urun = Urun::findOrFail($id);

        DB::beginTransaction();
        try {
            // 2. Aynı türdeki eski aktif fiyatın bitiş tarihini güncelle (Arşivleme Mantığı)
            // Eğer yeni bir 'standart' fiyat ekleniyorsa, önceki 'standart' fiyatın bitiş tarihi 
            // yeni fiyatın başlangıç tarihi olarak ayarlanır.
            UrunFiyat::where('urun_id', $urun->id)
                ->where('fiyat_turu', $request->fiyat_turu)
                ->whereNull('bitis_tarihi')
                ->update(['bitis_tarihi' => $request->baslangic_tarihi]);

            // 3. Yeni Fiyatı Oluştur (UrunFiyat tablosuna ekle)
            $fiyat = UrunFiyat::create([
                'urun_id'       => $urun->id,
                'fiyat_turu'    => $request->fiyat_turu,
                'maliyet'       => $request->maliyet,
                'kar_orani'     => $request->kar_orani,
                'bayi_indirimi' => $request->bayi_indirimi ?? 0,
                'vergi_orani'   => $request->vergi_orani,
                'baslangic_tarihi' => $request->baslangic_tarihi,
                'bitis_tarihi'  => $request->bitis_tarihi ?? null,
            ]);

            // 4. İlişki tablosunu güncelle (Pivot: urun_fiyat_urun)
            // Eğer pivot tablonuzda 'fiyat_id' ve 'urun_id' varsa:
            if(!$urun->fiyatlar->contains($fiyat->fiyat_id)) {
                $urun->fiyatlar()->attach($fiyat->fiyat_id, [
                    'baslangic_tarihi' => $request->baslangic_tarihi,
                    'bitis_tarihi' => $request->bitis_tarihi
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Yeni fiyat tanımlandı ve aktif edildi.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Hata: ' . $e->getMessage());
        }
    }

    /**
     * Fiyat Silme Metodu
     */
    public function deleteFiyat($id)
    {
        try {
            $fiyat = UrunFiyat::findOrFail($id);
            // Önce ilişkiden çıkar (pivot), sonra asıl kaydı sil
            $fiyat->urunler()->detach(); 
            $fiyat->delete();
            
            return redirect()->back()->with('success', 'Fiyat kaydı silindi.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Silme işleminde hata: ' . $e->getMessage());
        }
    }

    // =====================================================================
    // === YARDIMCI METODLAR (Aynen korundu) ===
    // =====================================================================

    public function getKriterlerByAltKategori($altKategoriId)
    {
        $kriterler = Kriter::where('alt_kategori_id', $altKategoriId)
            ->with('degerler')->orderBy('kriter_ad')->get();
        return response()->json($kriterler);
    }

    private function syncUyumluluk(Urun $urun)
    {
        $urun->refresh();
        $urun->load(['kriterDegerleri', 'varyasyonlar.kriterDegerleri']);
        
        UyumluUrun::where('urun_id', $urun->id)
            ->orWhere('uyumlu_urun_id', $urun->id)
            ->delete();
        
        $kurallar = DB::table('uyumluluk_kurallari')->get();

        foreach ($kurallar as $kural) {
            $this->findAndLinkMatches(
                $urun,
                $kural->ana_kategori_id, $kural->ana_kriter_id, 
                $kural->hedef_kategori_id, $kural->hedef_kriter_id
            );

            $this->findAndLinkMatches(
                $urun,
                $kural->hedef_kategori_id, $kural->hedef_kriter_id,
                $kural->ana_kategori_id, $kural->ana_kriter_id
            );
        }
    }

    private function findAndLinkMatches(Urun $urun, $sourceCatId, $sourceCritId, $targetCatId, $targetCritId)
    {
        if ($urun->alt_kategori_id != $sourceCatId) return;

        $degerler = collect();

        foreach ($urun->kriterDegerleri as $kriterDeger) {
            if ($kriterDeger->pivot->kriter_id == $sourceCritId) {
                $degerler->push($kriterDeger->deger);
            }
        }

        foreach ($urun->varyasyonlar as $varyasyon) {
            foreach ($varyasyon->kriterDegerleri as $kriterDeger) {
                if ($kriterDeger->pivot->kriter_id == $sourceCritId) {
                    $degerler->push($kriterDeger->deger);
                }
            }
        }

        $degerler = $degerler->unique()->filter();
        
        if ($degerler->isEmpty()) return;

        $eslesecekUrunler = Urun::where('alt_kategori_id', $targetCatId)
            ->where('id', '!=', $urun->id) 
            ->where(function($query) use ($targetCritId, $degerler) {
                
                $query->whereHas('kriterDegerleri', function($q) use ($targetCritId, $degerler) {
                    $q->where('urun_kriter_degerleri.kriter_id', $targetCritId)
                      ->whereIn('deger', $degerler);
                })
                ->orWhereHas('varyasyonlar', function($qVar) use ($targetCritId, $degerler) {
                    $qVar->whereHas('kriterDegerleri', function($qKd) use ($targetCritId, $degerler) {
                        $qKd->where('urun_varyasyon_kriter_degerleri.kriter_id', $targetCritId)
                            ->whereIn('deger', $degerler);
                    });
                });
            })
            ->get();

        foreach ($eslesecekUrunler as $hedef) {
            $this->createUyumluluk($urun->id, $hedef->id);
        }
    }

    private function createUyumluluk($urunId, $uyumluUrunId)
    {
        UyumluUrun::firstOrCreate([
            'urun_id' => $urunId,
            'uyumlu_urun_id' => $uyumluUrunId,
        ]);

        UyumluUrun::firstOrCreate([
            'urun_id' => $uyumluUrunId,
            'uyumlu_urun_id' => $urunId,
        ]);
    }
}