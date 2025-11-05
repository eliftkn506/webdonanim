<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Urun;
use App\Models\Kategori;
use App\Models\AltKategori;
use App\Models\Kriter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KullaniciUrunController extends Controller
{
    /**
     * Ana ürün listeleme sayfası
     */
    public function index(Request $request)
    {
        $urunler = Urun::with(['urunKriterDegerleri.kriter', 'urunKriterDegerleri.kriterDeger'])
                        ->orderBy('created_at', 'desc')
                        ->paginate(12);

        return view('kullanici.urunler.index', compact('urunler'));
    }

    public function kategori($id, Request $request)
    {
        try {
            $kategori = Kategori::with('altKategoriler')->findOrFail($id);

            $urunler = Urun::with(['urunKriterDegerleri.kriter', 'urunKriterDegerleri.kriterDeger'])
                ->whereHas('altKategori', function($q) use ($id) {
                    $q->where('kategori_id', $id);
                })
                ->orderBy('created_at', 'desc')
                ->paginate(12);

            $filterData = $this->getFilterData($request, $id, null);

            return view('kullanici.urunler.index', array_merge([
                'urunler' => $urunler,
                'kategori' => $kategori
            ], $filterData));

        } catch (\Exception $e) {
            Log::error('Kategori listeleme hatası: ' . $e->getMessage());
            return redirect()->route('urun.index')->with('error', 'Kategori bulunamadı.');
        }
    }

    public function altkategori($id, Request $request)
    {
        try {
            $altKategori = AltKategori::with('kategori')->findOrFail($id);

            $urunler = Urun::with(['urunKriterDegerleri.kriter', 'urunKriterDegerleri.kriterDeger'])
                ->where('alt_kategori_id', $id)
                ->orderBy('created_at', 'desc')
                ->paginate(12);

            $filterData = $this->getFilterData($request, null, $id);

            return view('kullanici.urunler.index', array_merge([
                'urunler' => $urunler,
                'altKategori' => $altKategori
            ], $filterData));

        } catch (\Exception $e) {
            Log::error('Alt kategori listeleme hatası: ' . $e->getMessage());
            return redirect()->route('urun.index')->with('error', 'Alt kategori bulunamadı.');
        }
    }

    /**
     * Arama
     */
    public function ara(Request $request)
    {
        try {
            $q = $request->input('q');

            if (empty($q)) {
                return redirect()->route('urun.index');
            }

            $urunler = Urun::where('urun_ad', 'LIKE', "%{$q}%")
                ->orWhere('marka', 'LIKE', "%{$q}%")
                ->orWhere('model', 'LIKE', "%{$q}%")
                ->orderBy('created_at', 'desc')
                ->paginate(12);

            $filterData = $this->getFilterData($request);

            return view('kullanici.urunler.index', array_merge([
                'urunler' => $urunler,
                'searchQuery' => $q
            ], $filterData));

        } catch (\Exception $e) {
            Log::error('Arama hatası: ' . $e->getMessage());
            return redirect()->route('urun.index')->with('error', 'Arama sırasında bir hata oluştu.');
        }
    }

    /**
     * Ürün detayı
     */
    public function incele($id, Request $request)
    {
        try {
            $urun = Urun::with([
                'altKategori.kategori', 
                'urunKriterDegerleri.kriter', 
                'urunKriterDegerleri.kriterDeger',
                'fiyatlar' => function($query) {
                    $query->wherePivot('baslangic_tarihi', '<=', now())
                          ->where(function($q) {
                              $q->whereNull('urun_fiyat_urun.bitis_tarihi')
                                ->orWhere('urun_fiyat_urun.bitis_tarihi', '>=', now());
                          })
                          ->latest('urun_fiyat_urun.baslangic_tarihi');
                }
            ])
            ->find($id);

            if (!$urun) {
                return redirect()->route('urun.index')->with('error', 'Ürün bulunamadı.');
            }

            $user = auth()->user();
            
            // Kullanıcıya göre fiyatları hesapla
            $satisFiyati = $urun->getFiyatForUser($user);
            $standartFiyat = $urun->getStandartFiyat();
            
            // Bayi fiyatı kontrolü
            $isBayi = $user && $user->isBayi();
            $bayiFiyat = $isBayi ? $urun->getBayiFiyat() : null;

            // Kampanya kontrolü
            $kampanya = DB::table('kampanya_indirim')
                ->where('urun_id', $urun->id)
                ->where('aktif', 1)
                ->where('baslangic_tarihi', '<=', now())
                ->where('bitis_tarihi', '>=', now())
                ->first();
            
            $indirimliFiyat = $satisFiyati;
            if($kampanya && $satisFiyati > 0) {
                $indirimliFiyat = $satisFiyati * (1 - $kampanya->indirim_orani / 100);
            }

            // Favori kontrolü
            $isFavorite = false;
            if (auth()->check()) {
                $isFavorite = \App\Models\FavoriUrun::where('user_id', auth()->id())
                    ->where('urun_id', $id)
                    ->exists();
            }

            // Benzer ürünler
            $benzerUrunler = collect();
            if ($urun->alt_kategori_id) {
                $benzerUrunler = Urun::with(['fiyatlar' => function($query) {
                    $query->wherePivot('baslangic_tarihi', '<=', now())
                          ->where(function($q) {
                              $q->whereNull('urun_fiyat_urun.bitis_tarihi')
                                ->orWhere('urun_fiyat_urun.bitis_tarihi', '>=', now());
                          })
                          ->latest('urun_fiyat_urun.baslangic_tarihi');
                }])
                ->where('alt_kategori_id', $urun->alt_kategori_id)
                ->where('id', '!=', $urun->id)
                ->limit(8)
                ->get();
            }

            $adet = $request->input('adet', 1);

            return view('kullanici.urunler.incele', compact(
                'urun', 
                'adet', 
                'benzerUrunler', 
                'isFavorite',
                'satisFiyati',
                'standartFiyat',
                'isBayi',
                'bayiFiyat',
                'kampanya',
                'indirimliFiyat'
            ));

        } catch (\Exception $e) {
            Log::error('Ürün detay hatası: ' . $e->getMessage());
            return redirect()->route('urun.index')->with('error', 'Ürün bilgileri yüklenirken bir hata oluştu.');
        }
    }

    /**
     * Filtre verilerini hazırla
     */
    private function getFilterData($request, $kategoriId = null, $altKategoriId = null)
    {
        try {
            $kategoriler = Kategori::all();

            $altKategoriler = $kategoriId 
                ? AltKategori::where('kategori_id', $kategoriId)->get()
                : ($request->filled('kategori_id') 
                    ? AltKategori::where('kategori_id', $request->kategori_id)->get()
                    : AltKategori::all());

            $baseQuery = Urun::query();

            if ($kategoriId) {
                $baseQuery->whereHas('altKategori', function($q) use ($kategoriId) {
                    $q->where('kategori_id', $kategoriId);
                });
            } elseif ($altKategoriId) {
                $baseQuery->where('alt_kategori_id', $altKategoriId);
            }

            $markalar = $baseQuery->select('marka', DB::raw('count(*) as count'))
                ->whereNotNull('marka')
                ->where('marka', '!=', '')
                ->groupBy('marka')
                ->orderBy('marka')
                ->pluck('count', 'marka');

            $modeller = $baseQuery->select('model', DB::raw('count(*) as count'))
                ->whereNotNull('model')
                ->where('model', '!=', '')
                ->groupBy('model')
                ->orderBy('model')
                ->pluck('count', 'model');

            $kriterler = collect();
            if ($altKategoriId) {
                $kriterler = Kriter::with('degerler')->where('alt_kategori_id', $altKategoriId)->get();
            } elseif ($request->filled('alt_kategori_id')) {
                $kriterler = Kriter::with('degerler')->where('alt_kategori_id', $request->alt_kategori_id)->get();
            }

            return [
                'kategoriler' => $kategoriler,
                'altKategoriler' => $altKategoriler,
                'markalar' => $markalar->keys(),
                'modeller' => $modeller->keys(),
                'markaCounts' => $markalar->toArray(),
                'modelCounts' => $modeller->toArray(),
                'kriterler' => $kriterler,
            ];

        } catch (\Exception $e) {
            Log::error('Filter data error: ' . $e->getMessage());
            return $this->getEmptyFilterData();
        }
    }

    /**
     * Boş filtre verisi
     */
    private function getEmptyFilterData()
    {
        return [
            'kategoriler' => collect(),
            'altKategoriler' => collect(),
            'markalar' => collect(),
            'modeller' => collect(),
            'markaCounts' => [],
            'modelCounts' => [],
            'kriterler' => collect(),
        ];
    }

    /**
     * AJAX endpoint: alt kategoriler
     */
    public function getAltKategoriler(Request $request)
    {
        try {
            $kategoriId = $request->get('kategori_id');
            if (!$kategoriId) return response()->json([]);

            $altKategoriler = AltKategori::where('kategori_id', $kategoriId)
                ->select('id', 'alt_kategori_ad')
                ->get();

            return response()->json($altKategoriler);
        } catch (\Exception $e) {
            Log::error('Alt kategoriler hatası: ' . $e->getMessage());
            return response()->json([], 500);
        }
    }
}