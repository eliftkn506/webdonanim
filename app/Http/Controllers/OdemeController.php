<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siparis;
use App\Models\OdemeBilgisi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OdemeController extends Controller
{
    // Ödeme sayfasını göster
    public function index($siparis_id)
    {
        $siparis = Siparis::with('urunler.urun')->findOrFail($siparis_id);

        if ($siparis->user_id !== Auth::id()) {
            abort(403, 'Bu siparişe erişim yetkiniz yok.');
        }

        return view('kullanici.odeme', compact('siparis'));
    }

    // Ödeme işlemini başlat
    public function odemeYap(Request $request, $siparis_id)
    {
        $siparis = Siparis::findOrFail($siparis_id);

        if ($siparis->user_id !== Auth::id()) {
            abort(403, 'Bu siparişe erişim yetkiniz yok.');
        }

        $request->validate([
            'odeme_tipi' => 'required|in:kredi_karti,havale,kapida_odeme',
        ]);

        $odemeTipi = $request->odeme_tipi;
        $odemeBilgi = new OdemeBilgisi();
        $odemeBilgi->siparis_id = $siparis->id;
        $odemeBilgi->odeme_tipi = $odemeTipi;
        $odemeBilgi->odenen_tutar = $siparis->toplam_tutar + $siparis->kdv_tutari;
        $odemeBilgi->para_birimi = 'TRY';
        $odemeBilgi->durum = 'beklemede';

        if ($odemeTipi === 'kredi_karti') {
            $request->validate([
                'kart_isim' => 'required|string|max:255',
                'kart_no' => 'required|string|size:19',
                'cvv' => 'required|string|size:3',
                'kart_ay' => 'required|string|size:2',
                'kart_yil' => 'required|string|size:4',
            ]);

            // Kart son 4 haneyi al
            $odemeBilgi->kart_son_dort_hanesi = substr(str_replace(' ', '', $request->kart_no), -4);
            $odemeBilgi->kart_tipi = $this->kartTipiBelirle($request->kart_no);

            // Kredi kartı simülasyonu
            $this->processCardPayment($request, $siparis, $odemeBilgi);
        } else {
            // Havale veya kapıda ödeme
            $odemeBilgi->banka_adi = $request->banka_adi ?? null;
            $odemeBilgi->durum = 'beklemede';
            $odemeBilgi->save();

            $siparis->update([
                'odeme_tipi' => $odemeTipi,
                'odeme_durumu' => 'beklemede',
                'durum' => 'beklemede',
            ]);
        }

        return redirect()->route('siparis.basarili', $siparis->id)
            ->with('success', 'Ödeme işlemi başarıyla kaydedildi.');
    }

    // Kredi kartı ödeme işlemi simülasyonu
    private function processCardPayment($request, $siparis, $odemeBilgi)
    {
        $kartNo = str_replace(' ', '', $request->kart_no);

        if (strlen($kartNo) !== 16) {
            $odemeBilgi->durum = 'hata';
            $odemeBilgi->hata_mesaji = 'Geçersiz kart numarası';
            $odemeBilgi->save();
            throw new \Exception('Geçersiz kart numarası');
        }

        // Test kartları için otomatik onay
        $testKartlari = ['4111111111111111', '5555555555554444'];

        $odemeBilgi->transaction_id = 'TXN-' . strtoupper(Str::random(10));

        if (in_array($kartNo, $testKartlari)) {
            $odemeBilgi->durum = 'basarili';
            $siparis->update([
                'odeme_durumu' => 'odendi',
                'durum' => 'onaylandi'
            ]);
        } else {
            // Simülasyon: gerçek kart işlem başarılı
            $odemeBilgi->durum = 'basarili';
            $siparis->update([
                'odeme_durumu' => 'odendi',
                'durum' => 'onaylandi'
            ]);
        }

        $odemeBilgi->gateway_response = ['simulasyon' => true];
        $odemeBilgi->save();
    }

    // Kart tipi belirleme (basit örnek)
    private function kartTipiBelirle($kartNo)
    {
        $firstDigit = $kartNo[0];
        switch ($firstDigit) {
            case '4':
                return 'Visa';
            case '5':
                return 'Mastercard';
            default:
                return 'Diğer';
        }
    }
}
