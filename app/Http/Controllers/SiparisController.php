<?php

namespace App\Http\Controllers;

use App\Models\Siparis;
use App\Models\SiparisUrunu;
use App\Models\Fatura;
use App\Models\OdemeBilgisi;
use App\Models\Kupon;
use App\Models\Urun;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SiparisController extends Controller
{
    /**
     * Sipariş oluşturma sayfası (Checkout)
     */
    public function olustur()
    {
        $sepet = session('sepet', []);

        if (empty($sepet)) {
            return redirect()->route('sepet.index')->with('error', 'Sepetiniz boş.');
        }

        $toplam = 0;
        $kdvToplam = 0;
        $normalizedSepet = [];

        foreach ($sepet as $key => $item) {
            $urun = Urun::find($item['id'] ?? $item['urun_id'] ?? $key);
            if(!$urun) continue;

            $guncelFiyat = $this->hesaplaGuncelFiyat($urun);

            $adet = intval($item['adet'] ?? 1);
            $normalizedSepet[] = [
                'id' => $urun->id,
                'isim' => $urun->urun_ad,
                'fiyat' => $guncelFiyat,
                'adet' => $adet,
                'resim' => $urun->resim_url,
            ];

            $toplam += $guncelFiyat * $adet;
            $kdvToplam += 0;
        }

        // Kullanıcının geçerli ve henüz kullanmadığı kuponlarını getir
        $kullaniciId = Auth::id();
        $kuponlar = Kupon::where('aktif', true)
            ->where('baslangic_tarihi', '<=', now())
            ->where('bitis_tarihi', '>=', now())
            ->where(function($q) use ($kullaniciId) {
                // Genel kuponlar (herkese açık)
                $q->where('kupon_turu', 'genel')
                  // VEYA kullanıcıya atanmış ve henüz kullanılmamış kuponlar
                  ->orWhereHas('kullanicilar', function($q2) use ($kullaniciId) {
                      $q2->where('user_id', $kullaniciId)
                         ->where('kullanildi', false);
                  });
            })
            ->get();

        return view('kullanici.siparis_olustur', [
            'sepet' => $normalizedSepet,
            'toplam' => $toplam,
            'kdvToplam' => $kdvToplam,
            'kuponlar' => $kuponlar,
        ]);
    }

    /**
     * Siparişi tamamla ve veritabanına kaydet
     */
    public function tamamla(Request $request)
    {
        $rules = [
            'ad_soyad' => 'required|string|max:255',
            'telefon' => 'required|string|max:20',
            'kargo_adresi' => 'required|string',
            'odeme_yontemi' => 'required|string|in:kredi_karti,havale,kapida_odeme',
            'fatura_tipi' => 'nullable|in:bireysel,kurumsal'
        ];

        if ($request->odeme_yontemi === 'kredi_karti') {
            $rules = array_merge($rules, [
                'kart_isim' => 'required|string|max:255',
                'kart_no' => 'required|string|min:16',
                'kart_cvv' => 'required|string|min:3|max:4',
                'kart_tarih' => 'required|string|size:5',
            ]);
        }

        $validated = $request->validate($rules);

        $sepet = session('sepet', []);
        if (empty($sepet)) {
            return redirect()->route('sepet.index')->with('error', 'Sepetiniz boş.');
        }

        DB::beginTransaction();
        try {
            $araToplam = 0;
            $kdvToplam = 0;

            // Sepetteki ürünlerin kullanıcıya göre güncel fiyatlarını hesapla
            $guncelSepet = [];
            foreach ($sepet as $item) {
                $urun = Urun::find($item['id'] ?? $item['urun_id']);
                if(!$urun) continue;

                $guncelFiyat = $this->hesaplaGuncelFiyat($urun);
                $adet = intval($item['adet'] ?? 1);
                
                $itemTotal = $guncelFiyat * $adet;
                $araToplam += $itemTotal;

                $guncelSepet[] = [
                    'id' => $urun->id,
                    'urun_ad' => $urun->urun_ad,
                    'fiyat' => $guncelFiyat,
                    'adet' => $adet,
                    'resim_url' => $urun->resim_url
                ];
            }

            // Kupon kontrolü ve indirim hesaplama
            $kuponIndirim = 0;
            $kuponKodu = $request->kupon_kodu ?? null;
            
            if ($kuponKodu) {
                $kupon = Kupon::where('kupon_kodu', $kuponKodu)
                    ->where('aktif', true)
                    ->where('baslangic_tarihi', '<=', now())
                    ->where('bitis_tarihi', '>=', now())
                    ->first();

                if ($kupon && $araToplam >= ($kupon->minimum_tutar ?? 0)) {
                    $kuponIndirim = ($kupon->indirim_tipi === 'yuzde') 
                        ? ($araToplam * $kupon->indirim_miktari) / 100 
                        : floatval($kupon->indirim_miktari);
                    
                    $kuponIndirim = min($kuponIndirim, $araToplam);
                    
                    // Kupon kullanımını işaretle
                    if ($kupon->kupon_turu !== 'genel') {
                        DB::table('kullanici_kuponlar')
                            ->where('user_id', Auth::id())
                            ->where('kupon_id', $kupon->id)
                            ->update(['kullanildi' => true, 'kullanilma_tarihi' => now()]);
                    }
                    $kupon->increment('kullanilan_adet');
                }
            }

            $genelToplam = $araToplam + $kdvToplam - $kuponIndirim;

            // Sipariş kaydı oluştur
            $siparis = Siparis::create([
                'user_id' => Auth::id(),
                'siparis_no' => 'SIP-' . strtoupper(Str::random(8)),
                'toplam_tutar' => round($araToplam, 2),
                'kdv_tutari' => round($kdvToplam, 2),
                'kargo_ucreti' => 0,
                'indirim_tutari' => round($kuponIndirim, 2),
                'kupon_kodu' => $kuponKodu,
                'durum' => $request->odeme_yontemi === 'kredi_karti' ? 'odeme_bekliyor' : 'beklemede',
                'odeme_tipi' => $request->odeme_yontemi,
                'odeme_durumu' => $request->odeme_yontemi === 'kredi_karti' ? 'isleniyor' : 'beklemede',
                'kargo_adresi' => $request->kargo_adresi,
                'fatura_adresi' => $request->fatura_adresi ?? $request->kargo_adresi,
                'notlar' => $request->siparis_notu,
            ]);

            // Sipariş ürünlerini kaydet
            foreach ($guncelSepet as $item) {
                SiparisUrunu::create([
                    'siparis_id' => $siparis->id,
                    'urun_id' => $item['id'],
                    'adet' => $item['adet'],
                    'birim_fiyat' => round($item['fiyat'], 2),
                    'toplam_fiyat' => round($item['fiyat'] * $item['adet'], 2),
                    'kdv_orani' => 0,
                    'kdv_tutari' => 0,
                    'indirim_orani' => 0,
                    'indirim_tutari' => 0,
                ]);
            }

            // Fatura oluşturma
            Fatura::create([
                'siparis_id' => $siparis->id,
                'fatura_no' => 'FTR-' . date('Y') . '-' . str_pad($siparis->id, 6, '0', STR_PAD_LEFT),
                'unvan' => $request->ad_soyad,
                'fatura_adresi' => $request->fatura_adresi ?? $request->kargo_adresi,
                'genel_toplam' => round($genelToplam, 2),
                'fatura_tipi' => $request->fatura_tipi ?? 'bireysel',
            ]);

            // Ödeme kaydı
            OdemeBilgisi::create([
                'siparis_id' => $siparis->id,
                'odeme_tipi' => $request->odeme_yontemi,
                'odenen_tutar' => round($genelToplam, 2),
                'para_birimi' => 'TRY',
                'durum' => 'beklemede',
            ]);

            if ($request->odeme_yontemi === 'kredi_karti') {
                $this->processCardPayment($request, $siparis, null);
            }

            DB::commit();
            session()->forget('sepet');
            return redirect()->route('siparis.basarili', $siparis->id);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Sipariş oluşturma hatası: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Sipariş oluşturulurken hata oluştu.');
        }
    }

    private function processCardPayment($request, $siparis, $odemeBilgisi)
    {
        // Simülasyon: Kart geçerliyse onaylanır
        $siparis->update(['odeme_durumu' => 'odendi', 'durum' => 'onaylandi']);
    }

    public function basarili($id)
    {
        $siparis = Siparis::with(['urunler.urun', 'user'])->findOrFail($id);
        return view('kullanici.siparis_basarili', compact('siparis'));
    }

    public function detay($id)
    {
        $siparis = Siparis::with(['urunler.urun', 'user'])->findOrFail($id);
        $odemeBilgisi = OdemeBilgisi::where('siparis_id', $siparis->id)->first();
        $fatura = Fatura::where('siparis_id', $siparis->id)->first();
        return view('kullanici.siparis_detay', compact('siparis', 'odemeBilgisi', 'fatura'));
    }

    public function siparislerim()
    {
        $siparisler = Siparis::with(['urunler.urun'])->where('user_id', Auth::id())->orderBy('created_at', 'desc')->paginate(10);
        return view('kullanici.siparislerim', compact('siparisler'));
    }

    public function kuponKontrol(Request $request)
    {
        try {
            $request->validate([
                'kupon_kodu' => 'required|string',
                'sepet_toplami' => 'required|numeric'
            ]);

            $kupon = Kupon::where('kupon_kodu', $request->kupon_kodu)
                ->where('aktif', true)
                ->where('baslangic_tarihi', '<=', now())
                ->where('bitis_tarihi', '>=', now())
                ->first();

            if (!$kupon) {
                return response()->json(['success' => false, 'message' => 'Geçersiz kupon kodu']);
            }

            // Kullanıcı yetki kontrolü
            if ($kupon->kupon_turu !== 'genel') {
                $check = DB::table('kullanici_kuponlar')
                    ->where('user_id', Auth::id())
                    ->where('kupon_id', $kupon->id)
                    ->where('kullanildi', false)
                    ->exists();
                if(!$check) return response()->json(['success' => false, 'message' => 'Bu kupon hesabınıza tanımlı değil.']);
            }

            if ($request->sepet_toplami < ($kupon->minimum_tutar ?? 0)) {
                return response()->json(['success' => false, 'message' => 'Minimum harcama: ₺' . $kupon->minimum_tutar]);
            }

            $indirim = ($kupon->indirim_tipi === 'yuzde') 
                ? ($request->sepet_toplami * $kupon->indirim_miktari) / 100 
                : $kupon->indirim_miktari;

            return response()->json([
                'success' => true,
                'indirim' => round($indirim, 2),
                'yeni_toplam' => round($request->sepet_toplami - $indirim, 2),
                'message' => $kupon->baslik . ' uygulandı!'
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Hata oluştu'], 500);
        }
    }

    private function hesaplaGuncelFiyat($urun)
    {
        $user = auth()->user();
        $satisFiyati = $urun->getFiyatForUser($user);
        $kampanya = DB::table('kampanya_indirim')->where('urun_id', $urun->id)->where('aktif', 1)->where('baslangic_tarihi', '<=', now())->where('bitis_tarihi', '>=', now())->first();
        if($kampanya && $satisFiyati > 0) {
            $satisFiyati = $satisFiyati * (1 - $kampanya->indirim_orani / 100);
        }
        return round($satisFiyati, 2);
    }
}