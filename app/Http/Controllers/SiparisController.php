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

        $kullaniciId = Auth::id();
        $now = now();
        
        // 1. Genel Kuponlar
        $genelKuponlar = Kupon::where('aktif', true)
            ->where('baslangic_tarihi', '<=', $now)
            ->where('bitis_tarihi', '>=', $now)
            ->where('kupon_turu', 'genel')
            ->where(function($q) {
                $q->whereNull('kullanim_limiti')
                  ->orWhereRaw('kullanilan_adet < kullanim_limiti');
            })
            ->get();

        // 2. Kullanıcıya Özel Atanmış Kuponlar (Pivot tablodan kontrol)
        $ozelKuponlar = Kupon::where('aktif', true)
            ->where('baslangic_tarihi', '<=', $now)
            ->where('bitis_tarihi', '>=', $now)
            ->where('kupon_turu', 'kullanici_ozel')
            ->whereHas('kullanicilar', function($q) use ($kullaniciId) {
                $q->where('user_id', $kullaniciId)->where('kullanildi', 0);
            })
            ->get();

        // 3. Kural Bazlı Kuponlar (DİNAMİK KONTROL)
        // Veritabanında atanmış olmasına bakmaksızın, şartları o an sağlayanları getiriyoruz.
        $tumKuralBazli = Kupon::where('aktif', true)
            ->where('baslangic_tarihi', '<=', $now)
            ->where('bitis_tarihi', '>=', $now)
            ->where('kupon_turu', 'kural_bazli')
            ->get();

        // PHP tarafında filtreleme yapıyoruz
        $uygunKuralBazli = $tumKuralBazli->filter(function($kupon) use ($toplam, $kullaniciId) {
            // A. Kullanıcı bu kuponu daha önce kullanmış mı?
            $kullanilmis = DB::table('kullanici_kuponlar')
                ->where('user_id', $kullaniciId)
                ->where('kupon_id', $kupon->id)
                ->where('kullanildi', 1)
                ->exists();
            
            if ($kullanilmis) return false;

            // B. Sepet tutarı, kuponun genel minimum tutarını karşılıyor mu?
            if ($kupon->minimum_tutar > 0 && $toplam < $kupon->minimum_tutar) {
                return false;
            }

            // C. Kural bazlı özel limit kontrolü (Örn: toplam_alisveris kuralı için limit)
            $kuralTipi = strtolower($kupon->kural_tipi ?? '');
            if (str_contains($kuralTipi, 'alisveris') || str_contains($kuralTipi, 'tutar')) {
                 if ($toplam < ($kupon->kural_min_tutar ?? 0)) {
                     return false;
                 }
            }

            return true;
        });

        // Tüm kuponları birleştir
        $kuponlar = collect()
            ->merge($genelKuponlar)
            ->merge($ozelKuponlar)
            ->merge($uygunKuralBazli)
            ->unique('id');

        return view('kullanici.siparis_olustur', [
            'sepet' => $normalizedSepet,
            'toplam' => $toplam,
            'kdvToplam' => $kdvToplam,
            'kuponlar' => $kuponlar,
        ]);
    }

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
        $request->validate($rules);

        $sepet = session('sepet', []);
        if (empty($sepet)) return redirect()->route('sepet.index');

        DB::beginTransaction();
        try {
            $araToplam = 0;
            $kdvToplam = 0;
            $guncelSepet = [];

            // Sepet Hesaplama
            foreach ($sepet as $item) {
                $urun = Urun::find($item['id']);
                if(!$urun) continue;
                $guncelFiyat = $this->hesaplaGuncelFiyat($urun);
                $adet = intval($item['adet'] ?? 1);
                $itemTotal = $guncelFiyat * $adet;
                
                $araToplam += $itemTotal;
                $kdvToplam += 0;

                $guncelSepet[] = [
                    'id' => $item['id'],
                    'urun_ad' => $item['urun_ad'] ?? $urun->urun_ad,
                    'fiyat' => $guncelFiyat,
                    'adet' => $adet,
                ];
            }

            // --- KUPON İŞLEMLERİ ---
            $kuponIndirim = 0;
            $kuponKodu = $request->kupon_kodu ?? null;
            $kullaniciId = Auth::id();
            $kuponNesnesi = null;
            
            if ($kuponKodu) {
                $kupon = Kupon::where('kupon_kodu', $kuponKodu)
                    ->where('aktif', true)
                    ->where('baslangic_tarihi', '<=', now())
                    ->where('bitis_tarihi', '>=', now())
                    ->first();

                if ($kupon) {
                    $kuponKullanilabilir = false;
                    
                    // 1. Genel Kupon
                    if ($kupon->kupon_turu === 'genel') {
                        $kuponKullanilabilir = true;
                    } 
                    // 2. Kural Bazlı veya Özel
                    else {
                        // Önce kullanılmış mı diye bak
                        $kullanilmis = DB::table('kullanici_kuponlar')
                            ->where('user_id', $kullaniciId)
                            ->where('kupon_id', $kupon->id)
                            ->where('kullanildi', 1)
                            ->exists();

                        if (!$kullanilmis) {
                            // Kural Bazlı ise ve şartları sağlıyorsa izin ver
                            if ($kupon->kupon_turu === 'kural_bazli') {
                                if ($araToplam >= ($kupon->minimum_tutar ?? 0)) {
                                    $kuponKullanilabilir = true;
                                    
                                    // Veritabanında atama kaydı yoksa oluştur (ki kullanıldı işaretleyebilelim)
                                    $kayitVarMi = DB::table('kullanici_kuponlar')
                                        ->where('user_id', $kullaniciId)
                                        ->where('kupon_id', $kupon->id)
                                        ->exists();
                                    
                                    if (!$kayitVarMi) {
                                        $kupon->kullaniciyaAta($kullaniciId);
                                    }
                                }
                            } 
                            // Kullanıcıya özel atanmışsa
                            else {
                                $atanmis = DB::table('kullanici_kuponlar')
                                    ->where('user_id', $kullaniciId)
                                    ->where('kupon_id', $kupon->id)
                                    ->exists();
                                if ($atanmis) $kuponKullanilabilir = true;
                            }
                        }
                    }

                    if ($kuponKullanilabilir && $araToplam >= ($kupon->minimum_tutar ?? 0)) {
                        if ($kupon->indirim_tipi === 'yuzde') {
                            $kuponIndirim = ($araToplam * $kupon->indirim_miktari) / 100;
                        } else {
                            $kuponIndirim = floatval($kupon->indirim_miktari);
                        }
                        $kuponIndirim = min($kuponIndirim, $araToplam);
                        $kuponNesnesi = $kupon;
                    }
                }
            }

            $genelToplam = $araToplam + $kdvToplam - $kuponIndirim;

            // Sipariş Oluşturma
            $siparis = Siparis::create([
                'user_id' => $kullaniciId,
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

            // Sipariş Ürünleri
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

            // Kuponu Kullanıldı İşaretle
            if ($kuponNesnesi) {
                $kuponNesnesi->kullan($kullaniciId);
            }

            // Fatura ve Ödeme
            Fatura::create([
                'siparis_id' => $siparis->id,
                'fatura_no' => 'FTR-' . date('Y') . '-' . str_pad($siparis->id, 6, '0', STR_PAD_LEFT),
                'unvan' => $request->ad_soyad,
                'vergi_dairesi' => $request->vergi_dairesi,
                'vergi_no' => $request->vergi_no,
                'tc_kimlik_no' => $request->tc_kimlik_no,
                'fatura_adresi' => $request->fatura_adresi ?? $request->kargo_adresi,
                'ara_toplam' => round($araToplam, 2),
                'kdv_tutari' => round($kdvToplam, 2),
                'genel_toplam' => round($genelToplam, 2),
                'fatura_tipi' => $request->fatura_tipi ?? 'bireysel',
                'e_fatura_gonderildi' => false,
                'e_fatura_tarih' => null,
            ]);

            $odemeBilgisi = OdemeBilgisi::create([
                'siparis_id' => $siparis->id,
                'odeme_tipi' => $request->odeme_yontemi,
                'odenen_tutar' => round($genelToplam, 2),
                'para_birimi' => 'TRY',
                'durum' => 'beklemede',
            ]);

            if ($request->odeme_yontemi === 'kredi_karti') {
                $this->processCardPayment($request, $siparis, $odemeBilgisi);
            }

            DB::commit();
            session()->forget('sepet');

            return redirect()->route('siparis.basarili', $siparis->id)->with('success', 'Siparişiniz başarıyla oluşturuldu. Sipariş No: ' . $siparis->siparis_no);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Sipariş hatası: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Hata: ' . $e->getMessage());
        }
    }

    // AJAX İÇİN KUPON KONTROL
    public function kuponKontrol(Request $request)
    {
        try {
            $request->validate([
                'kupon_kodu' => 'required|string',
                'sepet_toplami' => 'required|numeric|min:0'
            ]);

            $kupon = Kupon::where('kupon_kodu', $request->kupon_kodu)
                ->where('aktif', true)
                ->where('baslangic_tarihi', '<=', now())
                ->where('bitis_tarihi', '>=', now())
                ->first();

            if (!$kupon) {
                return response()->json(['success' => false, 'message' => 'Geçersiz kupon kodu.']);
            }

            $kullaniciId = Auth::id();

            // Kural Bazlı veya Özel Kupon Kontrolü
            if ($kupon->kupon_turu !== 'genel') {
                // 1. Daha önce kullanılmış mı?
                $kullanilmis = DB::table('kullanici_kuponlar')
                    ->where('user_id', $kullaniciId)
                    ->where('kupon_id', $kupon->id)
                    ->where('kullanildi', 1)
                    ->exists();

                if ($kullanilmis) {
                    return response()->json(['success' => false, 'message' => 'Bu kuponu daha önce kullandınız.']);
                }

                // 2. Kural Bazlı İse ve Şartlar Sağlanıyorsa İZİN VER
                if ($kupon->kupon_turu === 'kural_bazli') {
                    // Atanmış olması gerekmez, limit kontrolü yeterli
                } 
                // 3. Kullanıcı Özel ise, kesinlikle atanmış olmalı
                else if ($kupon->kupon_turu === 'kullanici_ozel') {
                    $atanmis = DB::table('kullanici_kuponlar')
                        ->where('user_id', $kullaniciId)
                        ->where('kupon_id', $kupon->id)
                        ->exists();
                    if (!$atanmis) {
                        return response()->json(['success' => false, 'message' => 'Bu kupon size özel tanımlanmamış.']);
                    }
                }
            }

            // Minimum Tutar Kontrolü
            if ($request->sepet_toplami < ($kupon->minimum_tutar ?? 0)) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Sepet tutarı en az ' . number_format($kupon->minimum_tutar, 2) . ' ₺ olmalıdır.'
                ]);
            }

            // İndirim Hesapla
            $indirim = ($kupon->indirim_tipi === 'yuzde') 
                ? ($request->sepet_toplami * $kupon->indirim_miktari / 100) 
                : $kupon->indirim_miktari;

            $indirim = min($indirim, $request->sepet_toplami);

            return response()->json([
                'success' => true,
                'indirim' => round($indirim, 2),
                'yeni_toplam' => round($request->sepet_toplami - $indirim, 2),
                'message' => $kupon->baslik . ' uygulandı!'
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Sistem hatası: ' . $e->getMessage()]);
        }
    }

    private function processCardPayment($request, $siparis, $odemeBilgisi)
    {
        $kartNo = str_replace([' ', '-'], '', $request->kart_no);
        
        // Basit validasyon
        if (strlen($kartNo) < 15 || strlen($kartNo) > 16) {
            throw new \Exception('Geçersiz kart numarası');
        }

        $odemeBilgisi->update([
            'kart_son_dort_hanesi' => substr($kartNo, -4),
            'kart_tipi' => $this->kartTipiBelirle($kartNo),
            'transaction_id' => 'TXN-' . strtoupper(Str::random(10)),
            'durum' => 'basarili',
            'gateway_response' => ['status' => 'success', 'message' => 'Ödeme başarılı']
        ]);

        $siparis->update([
            'odeme_durumu' => 'odendi',
            'durum' => 'onaylandi'
        ]);
    }

    private function kartTipiBelirle($kartNo)
    {
        $firstDigit = substr($kartNo, 0, 1);
        switch ($firstDigit) {
            case '4': return 'Visa';
            case '5': return 'MasterCard';
            case '3': return 'American Express';
            default: return 'Diğer';
        }
    }

    private function hesaplaGuncelFiyat($urun)
    {
        $fiyat = $urun->getFiyatForUser(auth()->user());
        
        $kampanya = DB::table('kampanya_indirim')
            ->where('urun_id', $urun->id)
            ->where('aktif', 1)
            ->where('baslangic_tarihi', '<=', now())
            ->where('bitis_tarihi', '>=', now())
            ->first();
        
        if($kampanya && $fiyat > 0) {
            $fiyat = $fiyat * (1 - $kampanya->indirim_orani / 100);
        }
        
        return round($fiyat, 2);
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
        $siparisler = Siparis::with(['urunler.urun'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('kullanici.siparislerim', compact('siparisler'));
    }
}