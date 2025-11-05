<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\Urun;
use App\Models\User;


class SepetController extends Controller
{
    // Sepet sayfası
    public function index()
    {
        $sepetler = session('sepet', []);
        $sepetCount = array_sum(array_column($sepetler, 'adet') ?: [0]);
        $toplam = 0;

        // Sepetteki her ürünün güncel fiyatını hesapla
        foreach ($sepetler as $key => &$item) {
            $urun = Urun::find($item['id']);
            
            if($urun) {
                // Güncel fiyatı kullanıcıya göre hesapla
                $guncelFiyat = $this->hesaplaGuncelFiyat($urun);
                
                // Sepetteki fiyatı güncelle
                $item['fiyat'] = $guncelFiyat;
                $sepetler[$key] = $item;
            }
            
            $fiyat = $item['fiyat'] ?? 0;
            $adet = $item['adet'] ?? 1;
            $toplam += $fiyat * $adet;
        }

        // Güncellenen sepeti session'a kaydet
        session(['sepet' => $sepetler]);

        return view('kullanici.sepet', compact('sepetler', 'sepetCount', 'toplam'));
    }

    // Sepete Ürün Ekle
    public function ekle(Request $request)
    {
        $id = $request->input('id');
        $urun_ad = $request->input('urun_ad');
        $adet = intval($request->input('adet', 1));
        $resim_url = $request->input('resim_url', '');
        $marka = $request->input('marka', '');
        $model = $request->input('model', '');

        if(!$id || !$urun_ad) {
            return response()->json([
                'success' => false,
                'message' => 'Ürün bilgileri eksik!'
            ], 400);
        }

        // Ürünü veritabanından çek ve güncel fiyatı hesapla
        $urun = Urun::find($id);
        if(!$urun) {
            return response()->json([
                'success' => false,
                'message' => 'Ürün bulunamadı!'
            ], 404);
        }

        // Kullanıcıya göre güncel fiyatı hesapla
        $guncelFiyat = $this->hesaplaGuncelFiyat($urun);
        
        if($guncelFiyat <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Bu ürün için fiyat tanımlanmamış!'
            ], 400);
        }

        $sepet = session('sepet', []);

        // Eğer ürün zaten varsa adet artır
        if(isset($sepet[$id])) {
            $sepet[$id]['adet'] += $adet;
            $sepet[$id]['fiyat'] = $guncelFiyat; // Fiyatı güncelle
        } else {
            $sepet[$id] = [
                'id' => $id,
                'urun_ad' => $urun_ad,
                'fiyat' => $guncelFiyat, // Kullanıcıya özel fiyat
                'adet' => $adet,
                'resim_url' => $resim_url,
                'marka' => $marka,
                'model' => $model
            ];
        }

        session(['sepet' => $sepet]);
        $sepetCount = array_sum(array_column($sepet, 'adet'));

        return response()->json([
            'success' => true,
            'sepetCount' => $sepetCount,
            'message' => 'Ürün sepete eklendi!'
        ]);
    }

    // Sepetten ürün sil
    public function sil($id)
    {
        $sepet = session('sepet', []);

        if(isset($sepet[$id])) {
            unset($sepet[$id]);
            session(['sepet' => $sepet]);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

    // Sepetteki ürün adedini güncelle
    public function guncelle(Request $request, $id)
    {
        $adet = intval($request->input('adet', 1));
        $sepet = session('sepet', []);

        if(isset($sepet[$id])) {
            // Ürünün güncel fiyatını kullanıcıya göre kontrol et
            $urun = Urun::find($id);
            if($urun) {
                $guncelFiyat = $this->hesaplaGuncelFiyat($urun);
                $sepet[$id]['fiyat'] = $guncelFiyat;
            }
            
            $sepet[$id]['adet'] = $adet;
            session(['sepet' => $sepet]);
        }

        $sepetCount = array_sum(array_column($sepet, 'adet'));

        return response()->json([
            'success' => true,
            'sepetCount' => $sepetCount
        ]);
    }

    // Sepeti tamamen temizle
    public function temizle()
    {
        session()->forget('sepet');
        return redirect()->back()->with('success', 'Sepetiniz temizlendi.');
    }

    // Konfigürasyonu komple sepete ekle
    public function konfigEkle(Request $request)
    {
        $konfigId = $request->input('konfig_id');

        if (!$konfigId) {
            return response()->json([
                'success' => false,
                'message' => 'Konfigürasyon ID eksik!'
            ], 400);
        }

        $konfig = \App\Models\Konfigurasyon::with('urunler.urun')->find($konfigId);

        if (!$konfig) {
            return response()->json([
                'success' => false,
                'message' => 'Konfigürasyon bulunamadı!'
            ], 404);
        }

        $sepet = session('sepet', []);

        foreach ($konfig->urunler as $ku) {
            $urun = $ku->urun;
            if (!$urun) continue;

            $id = $urun->id;
            $adet = $ku->adet ?? 1;

            // Kullanıcıya göre güncel fiyatı hesapla
            $guncelFiyat = $this->hesaplaGuncelFiyat($urun);

            // Eğer ürün zaten varsa adedi artır
            if(isset($sepet[$id])) {
                $sepet[$id]['adet'] += $adet;
                $sepet[$id]['fiyat'] = $guncelFiyat; // Fiyatı güncelle
            } else {
                $sepet[$id] = [
                    'id' => $id,
                    'urun_ad' => $urun->urun_ad,
                    'fiyat' => $guncelFiyat,
                    'adet' => $adet,
                    'resim_url' => $urun->resim_url ?? '',
                    'marka' => $urun->marka ?? '',
                    'model' => $urun->model ?? ''
                ];
            }
        }

        session(['sepet' => $sepet]);
        $sepetCount = array_sum(array_column($sepet, 'adet'));

        return response()->json([
            'success' => true,
            'sepetCount' => $sepetCount,
            'message' => 'Konfigürasyon sepete eklendi!'
        ]);
    }

    /**
     * Helper: Ürünün güncel satış fiyatını kullanıcıya göre hesapla
     */
    private function hesaplaGuncelFiyat($urun)
    {
        $user = auth()->user();
        
        // Kullanıcıya göre fiyat al (bayi veya standart)
        $satisFiyati = $urun->getFiyatForUser($user);
        
        if($satisFiyati <= 0) {
            return 0;
        }

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

        return round($indirimliFiyat, 2);
    }
}