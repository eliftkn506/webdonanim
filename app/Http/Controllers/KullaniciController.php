<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Konfigurasyon;
use App\Models\KonfigurasyonUrun;
use App\Models\FavoriUrun;
use App\Models\Siparis; // Yeni: Sipariş modeli




class KullaniciController extends Controller
{
    
public function profil()
{
   $kullanici = Auth::user();

    $konfiglar = Konfigurasyon::where('kullanici_id', $kullanici->id)
        ->with(['urunler.urun'])
        ->orderBy('created_at', 'desc')
        ->get();

    $favoriUrunler = FavoriUrun::where('user_id', $kullanici->id)
        ->with('urun')
        ->orderBy('created_at', 'desc')
        ->get();

    // Yeni: Kullanıcının siparişleri
    $siparisler = Siparis::where('user_id', $kullanici->id)
        ->with(['urunler.urun'])
        ->orderBy('created_at', 'desc')
        ->get();

    return view('kullanici.profil', compact('kullanici', 'konfiglar', 'favoriUrunler', 'siparisler'));
}
    // GEREKSIZ - Bu metodları kaldır, FavoriController kullanılacak
    // toggleFavorite, favoriSil metodları FavoriController'da zaten var

    public function sil($id, Request $request)
{
    DB::beginTransaction();
    try {
        $konfig = Konfigurasyon::with('urunler')->findOrFail($id);

        if ($konfig->kullanici_id != Auth::id()) {
            return $request->ajax()
                ? response()->json(['success' => false, 'message' => 'Bu konfigürasyonu silme yetkiniz yok.'])
                : back()->with('error', 'Bu konfigürasyonu silme yetkiniz yok.');
        }

        $konfigIsim = $konfig->isim;
        $konfig->delete(); // bağlı ürünler otomatik silinecek

        DB::commit();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => "\"$konfigIsim\" başarıyla silindi."]);
        }
        return back()->with('success', "\"$konfigIsim\" başarıyla silindi.");
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Konfigürasyon silme hatası', [
            'konfig_id' => $id,
            'user_id' => Auth::id(),
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile(),
        ]);

        return $request->ajax()
            ? response()->json(['success' => false, 'message' => 'Konfigürasyon silinirken bir hata oluştu.'])
            : back()->with('error', 'Konfigürasyon silinirken bir hata oluştu.');
    }
}


    // Konfigürasyonu sepete ekleme
    public function konfigSepeteEkle(Request $request, $id)
    { 
        try {
            $konfig = Konfigurasyon::where('id', $id)
                ->where('kullanici_id', Auth::id())
                ->with(['urunler.urun'])
                ->first();

            if (!$konfig) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Konfigürasyon bulunamadı veya bu işlem için yetkiniz yok.'
                    ], 404);
                }
                return redirect()->back()->with('error', 'Konfigürasyon bulunamadı.');
            }

            if ($konfig->urunler->count() == 0) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Bu konfigürasyonda ürün bulunmuyor.'
                    ], 400);
                }
                return redirect()->back()->with('error', 'Bu konfigürasyonda ürün bulunmuyor.');
            }

            $sepet = session('sepet', []);
            $eklenenUrunSayisi = 0;
            $toplamTutar = 0;

            foreach ($konfig->urunler as $ku) {
                $urun = $ku->urun;
                $urunId = $urun->id;

                if (isset($sepet[$urunId])) {
                    $sepet[$urunId]['adet'] += $ku->adet;
                } else {
                    $sepet[$urunId] = [
                        'id' => $urunId,
                        'urun_ad' => $urun->urun_ad,
                        'fiyat' => $ku->fiyat,
                        'adet' => $ku->adet,
                        'resim_url' => $urun->resim_url ?? '',
                        'marka' => $urun->marka ?? '',
                        'model' => $urun->model ?? ''
                    ];
                }

                $eklenenUrunSayisi += $ku->adet;
                $toplamTutar += $ku->fiyat * $ku->adet;
            }

            session(['sepet' => $sepet]);
            $sepetCount = array_sum(array_column($sepet, 'adet'));

            Log::info("Konfigürasyon sepete eklendi", [
                'konfig_id' => $id,
                'konfig_isim' => $konfig->isim,
                'eklenen_urun_sayisi' => $eklenenUrunSayisi,
                'toplam_tutar' => $toplamTutar,
                'kullanici_id' => Auth::id()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'sepetCount' => $sepetCount,
                    'message' => '"' . $konfig->isim . '" konfigürasyonu sepete eklendi! (' . $eklenenUrunSayisi . ' ürün)',
                    'eklenenUrunSayisi' => $eklenenUrunSayisi,
                    'toplamTutar' => number_format($toplamTutar, 2)
                ]);
            }

            return redirect()->back()->with('success', 
                '"' . $konfig->isim . '" konfigürasyonu sepete eklendi! (' . $eklenenUrunSayisi . ' ürün - ₺' . number_format($toplamTutar, 2) . ')'
            );

        } catch (\Exception $e) {
            Log::error('Konfigürasyon sepete ekleme hatası: ' . $e->getMessage(), [
                'konfig_id' => $id,
                'kullanici_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Konfigürasyon sepete eklenirken bir hata oluştu. Lütfen tekrar deneyin.'
                ], 500);
            }

            return redirect()->back()->with('error', 'Konfigürasyon sepete eklenirken bir hata oluştu.');
        }
    }
}