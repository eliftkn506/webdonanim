<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Urun;
use App\Models\Kategori;
use App\Models\AltKategori;
use App\Models\Kriter;
use App\Models\KriterDeger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Validator;


class KullaniciUrunController extends Controller
{
    /**
     * Ürün sorgusunu Request filtrelere göre oluşturur.
     * @param Request $request
     * @param array $excludeFilters keys to ignore (e.g. ['marka','kriterler'])
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function buildUrunQuery(Request $request, array $excludeFilters = [])
    {
        $query = Urun::query();
        $requestData = $request->all();

        // Eager load sadece ana listeleme için
        if (empty($excludeFilters)) {
            $query->with([
                'altKategori.kategori',
                'urunKriterDegerleri.kriter',
                'urunKriterDegerleri.kriterDeger',
                'fiyatlar' => fn($q) => $q->wherePivot('baslangic_tarihi', '<=', now())
                      ->where(fn($sq) => $sq->whereNull('urun_fiyat_urun.bitis_tarihi')
                                           ->orWhere('urun_fiyat_urun.bitis_tarihi', '>=', now()))
            ]);
        }

        // Kısayollar
        $has = fn($key) => isset($requestData[$key]) && !in_array($key, $excludeFilters);

        // Kategori Filtresi
        if ($has('kategori_id')) {
            $query->whereHas('altKategori', fn($q) => $q->where('kategori_id', $requestData['kategori_id']));
        }

        // Alt Kategori Filtresi
        if ($has('alt_kategori_id')) {
            $query->where('alt_kategori_id', $requestData['alt_kategori_id']);
        }

        // Marka Filtresi
        if ($has('marka')) {
            $markalar = (array) $requestData['marka'];
            $query->whereIn('marka', $markalar);
        }

        // Model Filtresi
        if ($has('model')) {
            $modeller = (array) $requestData['model'];
            $query->whereIn('model', $modeller);
        }

        // KRİTİK ARAMA DÜZELTMESİ (Q): Tüm arama koşullarını tek where(function) içinde toplayıp OR'luyoruz.
        if ($has('q')) {
            $q = $requestData['q'];
            $query->where(function($sq) use ($q) {
                // Temel Ürün alanlarında arama (İlk koşul WHERE'dir, sonra OR)
                $sq->where('urun_ad', 'like', "%{$q}%")
                   ->orWhere('marka', 'like', "%{$q}%")
                   ->orWhere('model', 'like', "%{$q}%");
                   
                // İlişkili alanlarda arama (OR WHERE HAS)
                // Bu çağrılar, Query Builder'ın orWhereExists yapısı ile uyumludur.
                $sq->orWhereHas('altKategori.kategori', fn($q) => $q->where('kategori_ad', 'like', "%{$q}%"));
                $sq->orWhereHas('altKategori', fn($q) => $q->where('alt_kategori_ad', 'like', "%{$q}%"));
            });
        }

        // Kriterler: AND ilişkisi (Tablo adları açıkça belirtilmeli)
        if ($has('kriterler')) {
            $kriterler = $requestData['kriterler'];
            if (is_array($kriterler)) {
                foreach ($kriterler as $kriterId => $degerIds) {
                    $degerIds = is_array($degerIds) ? $degerIds : [$degerIds]; 
                    
                    if (!empty($degerIds)) {
                        $query->whereHas('kriterDegerleri', fn($q) => $q
                            ->where('urun_kriter_degerleri.kriter_id', $kriterId)
                            ->whereIn('urun_kriter_degerleri.kriter_deger_id', $degerIds)
                        );
                    }
                }
            }
        }

        // Fiyat aralığı
        $hasMin = isset($requestData['min_fiyat']) && is_numeric($requestData['min_fiyat']);
        $hasMax = isset($requestData['max_fiyat']) && is_numeric($requestData['max_fiyat']);

        if (($hasMin || $hasMax) && !in_array('fiyat_araligi', $excludeFilters)) {
            $min = $hasMin ? $requestData['min_fiyat'] : null;
            $max = $hasMax ? $requestData['max_fiyat'] : null;

            $query->whereHas('fiyatlar', function($q) use ($min, $max) {
                $q->where('fiyat_turu', 'standart')
                  ->wherePivot('baslangic_tarihi', '<=', now())
                  ->where(fn($sq) => $sq->whereNull('urun_fiyat_urun.bitis_tarihi')
                                        ->orWhere('urun_fiyat_urun.bitis_tarihi', '>=', now()));

                if (!is_null($min)) $q->where('maliyet', '>=', $min);
                if (!is_null($max)) $q->where('maliyet', '<=', $max);
            });
        }

        // Stok durumu
        if ($has('stok_durumu') && $requestData['stok_durumu'] !== 'hepsi') {
            if ($requestData['stok_durumu'] === 'var') {
                $query->where('stok', '>', 0);
            } else {
                $query->where('stok', '<=', 0);
            }
        }

        return $query;
    }

    /**
     * Apply sorting to the query 
     */
    private function applySorting($query, Request $request)
    {
        $sort = $request->input('sort', null);

        if ($sort === 'price_asc' || $sort === 'price_desc') {
            $direction = $sort === 'price_asc' ? 'asc' : 'desc';

            // DÜZELTME: urun_fiyatlar tablosunu kullan
            $priceSub = \App\Models\UrunFiyat::select('maliyet')
                ->from('urun_fiyatlar', 'urun_fiyat')
                ->join('urun_fiyat_urun', 'urun_fiyat.fiyat_id', '=', 'urun_fiyat_urun.fiyat_id')
                ->whereColumn('urun_fiyat_urun.urun_id', 'urunler.id')
                ->where('fiyat_turu', 'standart')
                ->where(fn($q) => $q->whereNull('urun_fiyat_urun.bitis_tarihi')
                                    ->orWhere('urun_fiyat_urun.bitis_tarihi', '>=', now()))
                ->latest('urun_fiyat_urun.baslangic_tarihi')
                ->limit(1);

            $query->addSelect(['sort_price' => $priceSub])
                  ->orderBy('sort_price', $direction);
        } elseif ($sort === 'name_asc') {
            $query->orderBy('urun_ad', 'asc');
        } elseif ($sort === 'name_desc') {
            $query->orderBy('urun_ad', 'desc');
        } else {
            $query->orderBy('urunler.created_at', 'desc');
        }

        return $query;
    }

    /* ---------------------------------------------------------
     * Public listing methods
     * --------------------------------------------------------- */

    public function index(Request $request)
    {
        $query = $this->buildUrunQuery($request);
        $this->applySorting($query, $request);
        $urunler = $query->paginate(12)->appends($request->query());

        $filterData = $this->getFilterData($request);

        return view('kullanici.urunler.index', array_merge([
            'urunler' => $urunler
        ], $filterData));
    }

    public function kategori($id, Request $request)
    {
        try {
            $kategori = Kategori::with('altKategoriler')->findOrFail($id);
            if (!$request->has('kategori_id')) $request->merge(['kategori_id' => $id]);
            
            $query = $this->buildUrunQuery($request);
            $this->applySorting($query, $request);
            $urunler = $query->paginate(12)->appends($request->query());
            
            $filterData = $this->getFilterData($request, $id, $request->input('alt_kategori_id'));

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
            if (!$request->has('alt_kategori_id')) {
                $request->merge([
                    'alt_kategori_id' => $id,
                    'kategori_id' => $altKategori->kategori_id
                ]);
            }

            $query = $this->buildUrunQuery($request);
            $this->applySorting($query, $request);
            $urunler = $query->paginate(12)->appends($request->query());
            
            $filterData = $this->getFilterData($request, $altKategori->kategori_id, $id);

            return view('kullanici.urunler.index', array_merge([
                'urunler' => $urunler,
                'altKategori' => $altKategori
            ], $filterData));
        } catch (\Exception $e) {
            Log::error('Alt kategori listeleme hatası: ' . $e->getMessage());
            return redirect()->route('urun.index')->with('error', 'Alt kategori bulunamadı.');
        }
    }

    public function ara(Request $request)
    {
        try {
            $q = $request->input('q', '');
            if ($q === '') return redirect()->route('urun.index');

            $query = $this->buildUrunQuery($request);
            $this->applySorting($query, $request);
            $urunler = $query->paginate(12)->appends($request->query());
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

    public function incele($id, Request $request)
    {
        try {
             $urun = Urun::with([
                'altKategori.kategori', 
                'urunKriterDegerleri.kriter', 
                'urunKriterDegerleri.kriterDeger',
                'fiyatlar' => fn($q) => $q->wherePivot('baslangic_tarihi', '<=', now())
                          ->where(fn($sq) => $sq->whereNull('urun_fiyat_urun.bitis_tarihi')
                                ->orWhere('urun_fiyat_urun.bitis_tarihi', '>=', now()))
                          ->latest('urun_fiyat_urun.baslangic_tarihi')
            ])->findOrFail($id);

            $user = auth()->user();
            
            $satisFiyati = $urun->getFiyatForUser($user);
            $standartFiyat = $urun->getStandartFiyat();
            $isBayi = $user && ($user->isBayi() ?? false);
            $bayiFiyat = $isBayi ? $urun->getBayiFiyat() : null;

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

            $isFavorite = $user ? $urun->isFavoriByUser($user) : false;
            
            $benzerUrunler = collect();
            if ($urun->alt_kategori_id) {
                $benzerUrunler = Urun::with(['fiyatlar' => fn($q) => $q->wherePivot('baslangic_tarihi', '<=', now())
                          ->where(fn($sq) => $sq->whereNull('urun_fiyat_urun.bitis_tarihi')
                                ->orWhere('urun_fiyat_urun.bitis_tarihi', '>=', now()))
                          ->latest('urun_fiyat_urun.baslangic_tarihi')])
                ->where('alt_kategori_id', $urun->alt_kategori_id)
                ->where('id', '!=', $urun->id)
                ->limit(8)
                ->get();
            }

            $adet = $request->input('adet', 1);

            return view('kullanici.urunler.index', compact(
                'urun', 'adet', 'benzerUrunler', 'isFavorite',
                'satisFiyati', 'standartFiyat', 'isBayi', 'bayiFiyat', 'kampanya', 'indirimliFiyat'
            ));
        } catch (\Exception $e) {
            Log::error('Ürün detay hatası: ' . $e->getMessage());
            return redirect()->route('urun.index')->with('error', 'Ürün bilgileri yüklenirken bir hata oluştu.');
        }
    }

    /**
     * Get filter data (categories, alt categories, brands, models, kriterler, price range)
     */
    private function getFilterData(Request $request, $kategoriId = null, $altKategoriId = null)
    {
        try {
            $kategoriler = Kategori::orderBy('kategori_ad')->get();

            $effectiveKategoriId = $kategoriId ?? $request->input('kategori_id');
            $effectiveAltKategoriId = $altKategoriId ?? $request->input('alt_kategori_id');

            $altKategoriler = $effectiveKategoriId
                ? AltKategori::where('kategori_id', $effectiveKategoriId)->orderBy('alt_kategori_ad')->get()
                : collect();

            $filteredQuery = $this->buildUrunQuery($request);

            // Marka/model counts
            $baseForBrandModel = $this->buildUrunQuery($request, ['marka', 'model']);

            $markaCounts = (clone $baseForBrandModel)
                ->whereNotNull('marka')->where('marka', '!=', '')
                ->select('marka', DB::raw('count(*) as count'))
                ->groupBy('marka')->orderBy('marka')
                ->pluck('count', 'marka')
                ->toArray();

            $modelCounts = (clone $baseForBrandModel)
                ->whereNotNull('model')->where('model', '!=', '')
                ->select('model', DB::raw('count(*) as count'))
                ->groupBy('model')->orderBy('model')
                ->pluck('count', 'model')
                ->toArray();
                
            // Kriterler
            $kriterler = collect();
            if ($effectiveAltKategoriId) {
                // Kriter filtrelerini dışlayarak, diğer tüm filtrelere uyan ürünleri al
                $baseUrunQueryForKriterCount = $this->buildUrunQuery($request, ['kriterler']);
                $baseUrunQueryForKriterCount->where('alt_kategori_id', $effectiveAltKategoriId);

                $filteredUrunIds = $baseUrunQueryForKriterCount->select('id')->pluck('id')->toArray();
                
                if (!empty($filteredUrunIds)) {
                     $cacheKey = 'kriterler_data_' . $effectiveAltKategoriId . '_f_' . md5(json_encode(array_keys($request->except(['page', 'sort', 'kriterler']))));

                     $kriterler = Cache::remember($cacheKey, 60, function() use ($effectiveAltKategoriId, $filteredUrunIds) {
                        return Kriter::with(['degerler' => function($query) use ($filteredUrunIds) {
                            // KRİTİK DÜZELTME: MSSQL UYUMLU JOIN VE GROUP BY
                            $query->select('kriter_degerleri.*')
                                ->join('urun_kriter_degerleri', 'urun_kriter_degerleri.kriter_deger_id', '=', 'kriter_degerleri.id')
                                ->selectRaw('COUNT(urun_kriter_degerleri.urun_id) as urun_count')
                                ->whereIn('urun_kriter_degerleri.urun_id', $filteredUrunIds)
                                ->groupBy('kriter_degerleri.id', 'kriter_degerleri.kriter_id', 'kriter_degerleri.alt_kategori_id', 'kriter_degerleri.deger', 'kriter_degerleri.created_at', 'kriter_degerleri.updated_at')
                                ->havingRaw('COUNT(urun_kriter_degerleri.urun_id) > 0') 
                                ->orderBy('deger');
                        }])
                        ->where('alt_kategori_id', $effectiveAltKategoriId)
                        ->orderBy('kriter_ad')
                        ->get()
                        ->filter(fn($kriter) => $kriter->degerler->count() > 0)
                        ->values();
                     });
                }
            }

            // Fiyat Aralığı
            $priceRange = $filteredQuery->clone()
                ->join('urun_fiyat_urun', 'urunler.id', '=', 'urun_fiyat_urun.urun_id')
                // DÜZELTME: urun_fiyatlar tablosunu kullan
                ->join('urun_fiyatlar as urun_fiyat', 'urun_fiyat_urun.fiyat_id', '=', 'urun_fiyat.fiyat_id')
                ->where('urun_fiyat.fiyat_turu', 'standart')
                ->where(fn($q) => $q->whereNull('urun_fiyat_urun.bitis_tarihi')
                                    ->orWhere('urun_fiyat_urun.bitis_tarihi', '>=', now()))
                ->selectRaw('MIN(urun_fiyat.maliyet) as min_fiyat, MAX(urun_fiyat.maliyet) as max_fiyat')
                ->first();

            return [
                'kategoriler' => $kategoriler,
                'altKategoriler' => $altKategoriler,
                'markalar' => array_keys($markaCounts),
                'modeller' => array_keys($modelCounts),
                'markaCounts' => $markaCounts,
                'modelCounts' => $modelCounts,
                'kriterler' => $kriterler,
                'minFiyat' => $priceRange->min_fiyat ?? 0,
                'maxFiyat' => $priceRange->max_fiyat ?? 10000,
            ];

        } catch (\Exception $e) {
            Log::error('Filter data error: ' . $e->getMessage());
            return $this->getEmptyFilterData();
        }
    }

    private function getEmptyFilterData()
    {
        return [
            'kategoriler' => Kategori::orderBy('kategori_ad')->get(),
            'altKategoriler' => collect(),
            'markalar' => [],
            'modeller' => [],
            'markaCounts' => [],
            'modelCounts' => [],
            'kriterler' => collect(),
            'minFiyat' => 0,
            'maxFiyat' => 10000,
        ];
    }

    /**
     * AJAX: alt kategorileri döner
     */
    public function getAltKategoriler(Request $request)
    {
        try {
            $kategoriId = $request->get('kategori_id');
            if (!$kategoriId) return response()->json([]);

            $altKategoriler = AltKategori::where('kategori_id', $kategoriId)
                ->select('id','alt_kategori_ad')
                ->orderBy('alt_kategori_ad')
                ->get();

            return response()->json($altKategoriler);
            
        } catch (\Exception $e) {
            Log::error('Alt kategoriler hatası: ' . $e->getMessage());
            return response()->json(['error' => 'Sunucu hatası'], 500);
        }
    }

    /**
     * AJAX: alt kategoriye göre kriterler
     */
    public function getKriterler(Request $request)
    {
        try {
            $altKategoriId = $request->get('alt_kategori_id');
            if (!$altKategoriId) return response()->json([]);

            $base = $this->buildUrunQuery($request, ['kriterler']);
            $base->where('alt_kategori_id', $altKategoriId);
            $filteredUrunIds = $base->select('id')->pluck('id')->toArray();

            if (empty($filteredUrunIds)) return response()->json([]);

             $cacheKey = 'ajax_kriterler_' . $altKategoriId . '_f_' . md5(json_encode(array_keys($request->except(['page', 'sort', 'kriterler']))));

             $kriterler = Cache::remember($cacheKey, 60, function() use ($altKategoriId, $filteredUrunIds) {
                return Kriter::with(['degerler' => function($q) use ($filteredUrunIds) {
                    // KRİTİK DÜZELTME: MSSQL UYUMLU JOIN VE GROUP BY
                    $q->select('kriter_degerleri.*')
                        ->join('urun_kriter_degerleri', 'urun_kriter_degerleri.kriter_deger_id', '=', 'kriter_degerleri.id')
                        ->selectRaw('COUNT(urun_kriter_degerleri.urun_id) as urun_count')
                        ->whereIn('urun_kriter_degerleri.urun_id', $filteredUrunIds)
                        ->groupBy('kriter_degerleri.id', 'kriter_degerleri.kriter_id', 'kriter_degerleri.alt_kategori_id', 'kriter_degerleri.deger', 'kriter_degerleri.created_at', 'kriter_degerleri.updated_at')
                        ->havingRaw('COUNT(urun_kriter_degerleri.urun_id) > 0') 
                        ->orderBy('deger');
                }])
                ->where('alt_kategori_id', $altKategoriId)
                ->orderBy('kriter_ad')
                ->get()
                ->filter(fn($k) => $k->degerler->count() > 0)
                ->values();
             });


            return response()->json($kriterler);
        } catch (\Exception $e) {
            Log::error('Kriterler hatası: ' . $e->getMessage());
            return response()->json(['error' => 'Sunucu Hatası: Kriterler yüklenemedi.'], 500);
        }
    }

    /**
     * AJAX: marka/model filtreleri
     */
    public function getMarkaModel(Request $request)
    {
        try {
            $altKategoriId = $request->get('alt_kategori_id');
            if (!$altKategoriId) return response()->json(['markalar' => [], 'modeller' => []]);

            $base = $this->buildUrunQuery($request, ['marka','model']);
            $base->where('alt_kategori_id', $altKategoriId);
            
             $cacheKey = 'ajax_markamodel_' . $altKategoriId . '_f_' . md5(json_encode(array_keys($request->except(['page', 'sort', 'marka', 'model']))));

             $data = Cache::remember($cacheKey, 60, function() use ($base, $altKategoriId) {

                $markalar = (clone $base)
                    ->whereNotNull('marka')->where('marka','!=','')
                    ->select('marka', DB::raw('count(*) as count'))
                    ->groupBy('marka')->orderBy('marka')
                    ->get()->map(fn($i) => ['marka' => $i->marka, 'count' => (int)$i->count]);
    
                $modeller = (clone $base)
                    ->whereNotNull('model')->where('model','!=','')
                    ->select('model', DB::raw('count(*) as count'))
                    ->groupBy('model')->orderBy('model')
                    ->get()->map(fn($i) => ['model' => $i->model, 'count' => (int)$i->count]);

                return ['markalar' => $markalar, 'modeller' => $modeller];
             });


            return response()->json($data);
        } catch (\Exception $e) {
            Log::error('Marka-Model hatası: ' . $e->getMessage());
            return response()->json(['error' => 'Sunucu Hatası', 'markalar' => [], 'modeller' => []], 500);
        }
    }
}