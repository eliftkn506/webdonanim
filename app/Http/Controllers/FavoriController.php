<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\FavoriUrun;
use App\Models\Urun;

class FavoriController extends Controller
{
    /**
     * Favorilere ekle/çıkar (Toggle) - Ana fonksiyon
     */
    public function toggle(Request $request)
    {
        if (!Auth::check()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bu işlem için giriş yapmanız gerekiyor.',
                    'redirect' => route('login')
                ], 401);
            }
            return redirect()->route('login')->with('message', 'Favorilere eklemek için giriş yapmalısınız.');
        }

        try {
            $urunId = $request->input('urun_id');
            $kullaniciId = Auth::id();

            // Validation
            $request->validate([
                'urun_id' => 'required|integer|exists:urunler,id'
            ]);

            Log::info('Favori toggle isteği', [
                'urun_id' => $urunId,
                'kullanici_id' => $kullaniciId,
                'request_data' => $request->all()
            ]);

            // Ürünün varlığını kontrol et
            $urun = Urun::find($urunId);
            if (!$urun) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ürün bulunamadı.'
                ], 404);
            }

            // Favori durumunu kontrol et
            $favori = FavoriUrun::where('user_id', $kullaniciId)
                                ->where('urun_id', $urunId)
                                ->first();

            if ($favori) {
                // Favorilerden çıkar
                $favori->delete();
                Log::info("Ürün favorilerden çıkarıldı", [
                    'urun_id' => $urunId, 
                    'kullanici_id' => $kullaniciId,
                    'urun_ad' => $urun->urun_ad
                ]);
                
                return response()->json([
                    'success' => true,
                    'action' => 'removed',
                    'message' => 'Ürün favorilerden çıkarıldı.',
                    'is_favorite' => false
                ]);
            } else {
                // Favorilere ekle - Database'deki TÜM gerekli sütunlara değer ver
                $favoriData = [
                    'user_id' => $kullaniciId,
                    'ad_soyad' => Auth::user()->name ?? Auth::user()->email ?? 'Kullanıcı',
                    'urun_id' => $urunId,
                    'urun_ad' => $urun->urun_ad
                ];

                $yeniFavori = FavoriUrun::create($favoriData);

                Log::info("Ürün favorilere eklendi", [
                    'urun_id' => $urunId, 
                    'kullanici_id' => $kullaniciId,
                    'urun_ad' => $urun->urun_ad,
                    'favori_id' => $yeniFavori->id
                ]);

                return response()->json([
                    'success' => true,
                    'action' => 'added',
                    'message' => 'Ürün favorilere eklendi.',
                    'is_favorite' => true,
                    'favori_id' => $yeniFavori->id
                ]);
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Favori toggle validation hatası', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Geçersiz ürün ID.'
            ], 422);

        } catch (\Exception $e) {
            Log::error('Favori toggle genel hatası: ' . $e->getMessage(), [
                'urun_id' => $request->input('urun_id'),
                'kullanici_id' => Auth::id(),
                'error_trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'İşlem sırasında bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Favorileri listele
     */
    public function listele()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('message', 'Bu sayfayı görüntülemek için giriş yapmalısınız.');
        }

        $favoriler = FavoriUrun::with('urun')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('kullanici.favorilerim', compact('favoriler'));
    }

    /**
     * Favoriden çıkar (ID ile)
     */
    public function sil(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Bu işlem için giriş yapmanız gerekiyor.'
            ], 401);
        }

        try {
            $favori = FavoriUrun::where('id', $id)
                                ->where('user_id', Auth::id())
                                ->first();

            if (!$favori) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false, 
                        'message' => 'Favori bulunamadı.'
                    ], 404);
                }
                return redirect()->back()->with('error', 'Favori bulunamadı.');
            }

            $urunAd = $favori->urun_ad;
            $favori->delete();

            Log::info("Favori ürün silindi", ['favori_id' => $id, 'kullanici_id' => Auth::id()]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true, 
                    'message' => '"' . $urunAd . '" favorilerden çıkarıldı.'
                ]);
            }

            return redirect()->back()->with('success', '"' . $urunAd . '" favorilerden çıkarıldı.');

        } catch (\Exception $e) {
            Log::error('Favori silme hatası: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Silme işlemi başarısız.'
                ], 500);
            }
            return redirect()->back()->with('error', 'Silme işlemi başarısız.');
        }
    }

    /**
     * Favorilere ekle (Eski method - geriye uyumluluk için)
     */
    public function ekle($urunId)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('message', 'Favorilere eklemek için giriş yapmalısınız.');
        }

        $urun = Urun::findOrFail($urunId);

        // Kullanıcı zaten eklemiş mi?
        $favori = FavoriUrun::where('user_id', Auth::id())
                    ->where('urun_id', $urunId)
                    ->first();

        if ($favori) {
            return back()->with('info', 'Bu ürün zaten favorilerinizde.');
        }

        // Database sütunlarına göre create - TÜM gerekli alanları doldur
        FavoriUrun::create([
            'user_id'   => Auth::id(),
            'ad_soyad'  => Auth::user()->name ?? Auth::user()->email ?? 'Kullanıcı',
            'urun_id'   => $urun->id,
            'urun_ad'   => $urun->urun_ad
        ]);

        return back()->with('success', 'Ürün favorilere eklendi.');
    }

    /**
     * Favoriden çıkar (Ürün ID ile - geriye uyumluluk için)
     */
    public function urunSil($urunId)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Bu işlem için giriş yapmanız gerekiyor.'
            ], 401);
        }

        $favori = FavoriUrun::where('user_id', Auth::id())
                    ->where('urun_id', $urunId)
                    ->first();

        if ($favori) {
            $favori->delete();
            return back()->with('success', 'Ürün favorilerden çıkarıldı.');
        }

        return back()->with('error', 'Ürün favorilerinizde bulunamadı.');
    }

    /**
     * Kullanıcının favori durumunu kontrol et
     */
    public function durumKontrol(Request $request, $urunId)
    {
        if (!Auth::check()) {
            return response()->json(['is_favorite' => false]);
        }

        $isFavorite = FavoriUrun::where('user_id', Auth::id())
                                ->where('urun_id', $urunId)
                                ->exists();

        return response()->json(['is_favorite' => $isFavorite]);
    }

    /**
     * Kullanıcının tüm favorilerini getir (API)
     */
    public function apiFavoriler()
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Giriş yapmanız gerekiyor.'
            ], 401);
        }

        $favoriler = FavoriUrun::with(['urun' => function($query) {
                $query->select('id', 'urun_ad', 'marka', 'model', 'fiyat', 'resim_url', 'stok');
            }])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $favoriler,
            'count' => $favoriler->count()
        ]);
    }

    /**
     * Toplu favori ekleme (API)
     */
    public function topluEkle(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Giriş yapmanız gerekiyor.'
            ], 401);
        }

        try {
            $urunIds = $request->input('urun_ids', []);
            
            if (empty($urunIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ürün ID\'leri gerekli.'
                ], 400);
            }

            $eklenenSayisi = 0;
            $kullaniciId = Auth::id();

            foreach ($urunIds as $urunId) {
                $urun = Urun::find($urunId);
                if (!$urun) continue;

                $mevcutFavori = FavoriUrun::where('user_id', $kullaniciId)
                                         ->where('urun_id', $urunId)
                                         ->first();

                if (!$mevcutFavori) {
                    FavoriUrun::create([
                        'user_id' => $kullaniciId,
                        'ad_soyad' => Auth::user()->name ?? Auth::user()->email ?? 'Kullanıcı',
                        'urun_id' => $urunId,
                        'urun_ad' => $urun->urun_ad
                    ]);
                    $eklenenSayisi++;
                }
            }

            return response()->json([
                'success' => true,
                'message' => "{$eklenenSayisi} ürün favorilere eklendi.",
                'eklenen_sayisi' => $eklenenSayisi
            ]);

        } catch (\Exception $e) {
            Log::error('Toplu favori ekleme hatası: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'İşlem sırasında bir hata oluştu.'
            ], 500);
        }
    }

    /**
     * Toplu favori silme (API)
     */
    public function topluSil(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Giriş yapmanız gerekiyor.'
            ], 401);
        }

        try {
            $favoriIds = $request->input('favori_ids', []);
            
            if (empty($favoriIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Favori ID\'leri gerekli.'
                ], 400);
            }

            $silinenSayisi = FavoriUrun::where('user_id', Auth::id())
                                      ->whereIn('id', $favoriIds)
                                      ->delete();

            return response()->json([
                'success' => true,
                'message' => "{$silinenSayisi} favori silindi.",
                'silinen_sayisi' => $silinenSayisi
            ]);

        } catch (\Exception $e) {
            Log::error('Toplu favori silme hatası: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'İşlem sırasında bir hata oluştu.'
            ], 500);
        }
    }
}