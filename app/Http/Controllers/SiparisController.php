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

    // Kullanıcının geçerli kuponlarını getir
    $kullaniciId = Auth::id();
    $kuponlar = Kupon::where('aktif', true)
        ->where('baslangic_tarihi', '<=', now())
        ->where('bitis_tarihi', '>=', now())
        ->where(function($q) use ($kullaniciId) {
            // Genel kuponlar veya kullanıcıya özel kuponlar
            $q->where('kupon_turu', 'genel')
              ->orWhereHas('kullanicilar', function($q2) use ($kullaniciId) {
                  $q2->where('user_id', $kullaniciId);
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
                $urun = Urun::find($item['id']);
                if(!$urun) continue;

                // Kullanıcıya göre güncel fiyat
                $guncelFiyat = $this->hesaplaGuncelFiyat($urun);
                $adet = intval($item['adet'] ?? 1);
                
                $itemTotal = $guncelFiyat * $adet;
                
                // Fiyat zaten vergi dahil, ekstra KDV hesaplama
                $kdv = 0;
                
                $araToplam += $itemTotal;
                $kdvToplam += $kdv;

                $guncelSepet[] = [
                    'id' => $item['id'],
                    'urun_ad' => $item['urun_ad'] ?? $urun->urun_ad,
                    'fiyat' => $guncelFiyat,
                    'adet' => $adet,
                    'resim_url' => $item['resim_url'] ?? $urun->resim_url,
                    'marka' => $item['marka'] ?? $urun->marka,
                    'model' => $item['model'] ?? $urun->model
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
                    ->where(function($q) {
                        $q->whereNull('kullanim_limiti')
                          ->orWhereRaw('kullanilan_adet < kullanim_limiti');
                    })
                    ->first();

                if ($kupon && $araToplam >= ($kupon->minimum_tutar ?? 0)) {
                    if ($kupon->indirim_tipi === 'yuzde') {
                        $kuponIndirim = ($araToplam * $kupon->indirim_miktari) / 100;
                    } else {
                        $kuponIndirim = floatval($kupon->indirim_miktari);
                    }
                    
                    $kuponIndirim = min($kuponIndirim, $araToplam);
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
                $birimFiyat = $item['fiyat'];
                $adet = $item['adet'];
                $toplamFiyat = $birimFiyat * $adet;
                $kdvTutari = 0; // Fiyat zaten vergi dahil

                SiparisUrunu::create([
                    'siparis_id' => $siparis->id,
                    'urun_id' => $item['id'],
                    'adet' => $adet,
                    'birim_fiyat' => round($birimFiyat, 2),
                    'toplam_fiyat' => round($toplamFiyat, 2),
                    'kdv_orani' => 0,
                    'kdv_tutari' => round($kdvTutari, 2),
                    'indirim_orani' => 0,
                    'indirim_tutari' => 0,
                ]);
            }

            // Fatura kaydı oluştur
            $fatura = Fatura::create([
                'siparis_id' => $siparis->id,
                'fatura_no' => 'FTR-' . date('Y') . '-' . str_pad($siparis->id, 6, '0', STR_PAD_LEFT),
                'unvan' => $request->ad_soyad,
                'vergi_dairesi' => $request->vergi_dairesi ?? null,
                'vergi_no' => $request->vergi_no ?? null,
                'tc_kimlik_no' => $request->tc_kimlik_no ?? null,
                'fatura_adresi' => $request->fatura_adresi ?? $request->kargo_adresi,
                'ara_toplam' => round($araToplam, 2),
                'kdv_tutari' => round($kdvToplam, 2),
                'genel_toplam' => round($genelToplam, 2),
                'fatura_tipi' => $request->fatura_tipi ?? 'bireysel',
                'e_fatura_gonderildi' => false,
                'e_fatura_tarih' => null,
            ]);

            // Ödeme bilgisi kaydı
            $odemeBilgisi = OdemeBilgisi::create([
                'siparis_id' => $siparis->id,
                'odeme_tipi' => $request->odeme_yontemi,
                'kart_son_dort_hanesi' => null,
                'kart_tipi' => null,
                'banka_adi' => null,
                'transaction_id' => null,
                'odenen_tutar' => round($genelToplam, 2),
                'para_birimi' => 'TRY',
                'durum' => 'beklemede',
                'hata_mesaji' => null,
                'gateway_response' => null,
            ]);

            // Kredi kartı ödemesi
            if ($request->odeme_yontemi === 'kredi_karti') {
                $this->processCardPayment($request, $siparis, $odemeBilgisi);
            }

            DB::commit();

            // Sepeti temizle
            session()->forget('sepet');

            return redirect()->route('siparis.basarili', $siparis->id)
                ->with('success', 'Siparişiniz başarıyla oluşturuldu. Sipariş No: ' . $siparis->siparis_no);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Sipariş oluşturma hatası: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Sipariş oluşturulurken hata oluştu. Lütfen tekrar deneyin.');
        }
    }

    /**
     * Kredi kartı ödeme işlemi
     */
    private function processCardPayment($request, $siparis, $odemeBilgisi)
    {
        $kartNo = str_replace([' ', '-'], '', $request->kart_no);

        if (strlen($kartNo) < 15 || strlen($kartNo) > 16) {
            throw new \Exception('Geçersiz kart numarası');
        }

        $odemeBilgisi->update([
            'kart_son_dort_hanesi' => substr($kartNo, -4),
            'kart_tipi' => $this->kartTipiBelirle($kartNo),
            'transaction_id' => 'TXN-' . strtoupper(Str::random(10)),
        ]);

        $testKartlari = ['4111111111111111', '5555555555554444'];

        if (in_array($kartNo, $testKartlari)) {
            $odemeBilgisi->update([
                'durum' => 'basarili',
                'gateway_response' => [
                    'status' => 'success',
                    'message' => 'Test kartı ile ödeme başarılı',
                    'processed_at' => now()->toISOString()
                ]
            ]);

            $siparis->update([
                'odeme_durumu' => 'odendi',
                'durum' => 'onaylandi'
            ]);
        } else {
            $basarili = rand(1, 10) <= 9;

            if ($basarili) {
                $odemeBilgisi->update([
                    'durum' => 'basarili',
                    'gateway_response' => [
                        'status' => 'success',
                        'message' => 'Ödeme başarıyla işlendi',
                        'processed_at' => now()->toISOString()
                    ]
                ]);

                $siparis->update([
                    'odeme_durumu' => 'odendi',
                    'durum' => 'onaylandi'
                ]);
            } else {
                $odemeBilgisi->update([
                    'durum' => 'hata',
                    'hata_mesaji' => 'Kart limiti yetersiz veya kart blokeli',
                    'gateway_response' => [
                        'status' => 'failed',
                        'error_code' => 'INSUFFICIENT_FUNDS',
                        'processed_at' => now()->toISOString()
                    ]
                ]);

                throw new \Exception('Ödeme işlemi başarısız: Kart limiti yetersiz veya kart blokeli');
            }
        }
    }

    /**
     * Kart tipi belirleme
     */
    private function kartTipiBelirle($kartNo)
    {
        $firstDigit = substr($kartNo, 0, 1);
        switch ($firstDigit) {
            case '4':
                return 'Visa';
            case '5':
                return 'MasterCard';
            case '3':
                return 'American Express';
            default:
                return 'Diğer';
        }
    }

    /**
     * Sipariş başarılı sayfası
     */
    public function basarili($id)
    {
        $siparis = Siparis::with(['urunler.urun', 'user'])->findOrFail($id);
        return view('kullanici.siparis_basarili', compact('siparis'));
    }

    /**
     * Sipariş detayı
     */
    public function detay($id)
    {
        $siparis = Siparis::with(['urunler.urun', 'user'])->findOrFail($id);
        $odemeBilgisi = OdemeBilgisi::where('siparis_id', $siparis->id)->first();
        $fatura = Fatura::where('siparis_id', $siparis->id)->first();

        return view('kullanici.siparis_detay', compact('siparis', 'odemeBilgisi', 'fatura'));
    }

    /**
     * Kullanıcının tüm siparişleri
     */
    public function siparislerim()
    {
        $siparisler = Siparis::with(['urunler.urun'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('kullanici.siparislerim', compact('siparisler'));
    }

    /**
     * Kupon kontrol - AJAX endpoint
     */
    public function kuponKontrol(Request $request)
    {
        try {
            $request->validate([
                'kupon_kodu' => 'required|string',
                'sepet_toplami' => 'required|numeric|min:0'
            ]);

            $kuponKodu = $request->kupon_kodu;
            $sepetToplami = floatval($request->sepet_toplami);

            $kupon = Kupon::where('kupon_kodu', $kuponKodu)
                ->where('aktif', true)
                ->where('baslangic_tarihi', '<=', now())
                ->where('bitis_tarihi', '>=', now())
                ->where(function($q) {
                    $q->whereNull('kullanim_limiti')
                      ->orWhereRaw('kullanilan_adet < kullanim_limiti');
                })
                ->first();

            if (!$kupon) {
                return response()->json([
                    'success' => false,
                    'message' => 'Geçersiz veya süresi dolmuş kupon kodu'
                ]);
            }

            if ($sepetToplami < ($kupon->minimum_tutar ?? 0)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Minimum ' . number_format($kupon->minimum_tutar, 2) . ' ₺ alışveriş yapmalısınız'
                ]);
            }

            $indirim = 0;
            if ($kupon->indirim_tipi === 'yuzde') {
                $indirim = ($sepetToplami * floatval($kupon->indirim_miktari)) / 100;
            } else {
                $indirim = floatval($kupon->indirim_miktari);
            }

            $indirim = min($indirim, $sepetToplami);

            return response()->json([
                'success' => true,
                'indirim' => round($indirim, 2),
                'yeni_toplam' => round($sepetToplami - $indirim, 2),
                'message' => $kupon->baslik . ' kuponu uygulandı!'
            ]);

        } catch (\Exception $e) {
            Log::error('Kupon kontrolü hatası: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Kupon kontrolünde hata oluştu'
            ], 500);
        }
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