<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kupon;
use App\Models\User;
use App\Models\KullaniciKupon;
use App\Models\Kategori;
use App\Models\Urun;
use App\Models\Siparis; 
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KuponController extends Controller
{
    /**
     * Tüm kuponları listele
     */
    public function index()
    {
        $kuponlar = Kupon::with('kullanicilar')->orderBy('created_at', 'desc')->get();
        return view('admin.kuponlar.index', compact('kuponlar'));
    }

    /**
     * Yeni kupon oluşturma sayfası
     */
    public function create()
    {
        $kategoriler = Kategori::all();
        $urunler = Urun::select('id', 'urun_ad')->get();
        return view('admin.kuponlar.create', compact('kategoriler', 'urunler'));
    }
public function store(Request $request)
{
    // Validasyon kısmında indirim_tipi'nin tam olarak ne olduğunu kontrol edin
    $request->validate([
        'kupon_kodu' => 'required|unique:kuponlar,kupon_kodu',
        'indirim_tipi' => 'required|in:yuzde,tutar', // Veritabanındaki kısıtlama ile aynı olmalı
        'indirim_miktari' => 'required|numeric|min:0',
        'baslangic_tarihi' => 'required|date',
        'bitis_tarihi' => 'required|date|after_or_equal:baslangic_tarihi',
    ]);

    // SQL Server kısıtlamalarını aşmak için veriyi manuel temizleyerek kaydediyoruz
    $kupon = new Kupon();
    $kupon->kupon_kodu = strtoupper($request->kupon_kodu);
    $kupon->baslik = $request->baslik;
    $kupon->aciklama = $request->aciklama;
    $kupon->kupon_turu = $request->kupon_turu;
    $kupon->indirim_tipi = $request->indirim_tipi; // 'yuzde' veya 'tutar'
    $kupon->indirim_miktari = $request->indirim_miktari;
    
    // Boş (null) gelirse SQL Server hata verir, bu yüzden 0 atıyoruz
    $kupon->minimum_tutar = $request->minimum_tutar ?? 0;
    $kupon->kullanim_limiti = $request->kullanim_limiti ?? 0;
    $kupon->kullanilan_adet = 0;
    
    $kupon->baslangic_tarihi = $request->baslangic_tarihi;
    $kupon->bitis_tarihi = $request->bitis_tarihi;
    $kupon->aktif = $request->has('aktif') ? 1 : 0;
    
    // Kural bazlı alanlar
    $kupon->kural_tipi = $request->kural_tipi;
    $kupon->kural_min_tutar = $request->kural_min_tutar ?? 0;
    $kupon->kural_min_siparis = $request->kural_min_siparis ?? 0;
    $kupon->kural_gun_araligi = $request->kural_gun_araligi ?? 30;
    $kupon->otomatik_ata = $request->has('otomatik_ata') ? 1 : 0;

    $kupon->save();

    return redirect()->route('admin.kuponlar.index')->with('success', 'Kupon başarıyla oluşturuldu.');
}

    /**
     * Kupon düzenleme sayfası
     */
    public function edit(Kupon $kupon)
    {
        $kategoriler = Kategori::all();
        $urunler = Urun::select('id', 'urun_ad')->get();
        $atananKullanicilar = $kupon->kullanicilar->pluck('id')->toArray();
        
        return view('admin.kuponlar.edit', compact('kupon', 'kategoriler', 'urunler', 'atananKullanicilar'));
    }

    /**
     * Kuponu güncelle
     */
    public function update(Request $request, Kupon $kupon)
    {
        $rules = [
            'kupon_kodu' => 'required|unique:kuponlar,kupon_kodu,'.$kupon->id,
            'baslik' => 'required',
            'kupon_turu' => 'required|in:genel,kullanici_ozel,kural_bazli',
            'indirim_tipi' => 'required|in:yuzde,tutar',
            'indirim_miktari' => 'required|numeric|min:0',
            'baslangic_tarihi' => 'required|date',
            'bitis_tarihi' => 'required|date|after_or_equal:baslangic_tarihi',
        ];

        $request->validate($rules);

        $data = $this->prepareData($request);
        $kupon->update($data);

        // Kullanıcı atamalarını güncelle
        if ($request->kupon_turu === 'kullanici_ozel' && $request->has('secili_kullanicilar')) {
            DB::table('kullanici_kuponlar')->where('kupon_id', $kupon->id)->delete();
            foreach ($request->secili_kullanicilar as $userId) {
                $kupon->kullaniciyaAta($userId);
            }
        }

        return redirect()->route('admin.kuponlar.index')->with('success', 'Kupon başarıyla güncellendi.');
    }

    /**
     * SQL Server için veriyi temizleyen yardımcı fonksiyon
     */
    private function prepareData(Request $request)
    {
        $kuralHedefler = null;
        if ($request->kupon_turu === 'kural_bazli') {
            if ($request->kural_tipi === 'belirli_kategori') {
                $kuralHedefler = ['kategoriler' => $request->hedef_kategoriler ?? []];
            } elseif ($request->kural_tipi === 'belirli_urun') {
                $kuralHedefler = ['urunler' => $request->hedef_urunler ?? []];
            }
        }

        return [
            'kupon_kodu'        => strtoupper($request->kupon_kodu),
            'baslik'            => $request->baslik,
            'aciklama'          => $request->aciklama,
            'kupon_turu'        => $request->kupon_turu,
            'indirim_tipi'      => $request->indirim_tipi,
            'indirim_miktari'   => $request->indirim_miktari,
            'minimum_tutar'     => $request->minimum_tutar ?? 0,
            'kullanim_limiti'   => $request->kullanim_limiti ?? 0,
            'baslangic_tarihi'  => $request->baslangic_tarihi,
            'bitis_tarihi'      => $request->bitis_tarihi,
            'aktif'             => $request->has('aktif') ? 1 : 0,
            'kural_tipi'        => $request->kural_tipi ?? null,
            'kural_min_tutar'   => $request->kural_min_tutar ?? 0,
            'kural_min_siparis' => $request->kural_min_siparis ?? 0,
            'kural_gun_araligi' => $request->kural_gun_araligi ?? 30,
            'kural_hedefler'    => $kuralHedefler,
            'otomatik_ata'      => $request->has('otomatik_ata') ? 1 : 0,
        ];
    }

    /**
     * Kuponu sil
     */
    public function destroy(Kupon $kupon)
    {
        $kupon->delete();
        return redirect()->route('admin.kuponlar.index')->with('success', 'Kupon silindi.');
    }

    /**
     * Kural bazlı kuponları otomatik ata
     */
    public function kuralBazliKuponlariAta(Kupon $kupon = null)
    {
        $kuponlar = $kupon ? collect([$kupon]) : Kupon::where('kupon_turu', 'kural_bazli')
            ->where('otomatik_ata', true)
            ->where('aktif', true)
            ->get();

        $atananSayisi = 0;

        foreach ($kuponlar as $k) {
            $uygunKullanicilar = $this->uygunKullanicilariGetir($k);
            foreach ($uygunKullanicilar as $userId) {
                $atanmisMi = DB::table('kullanici_kuponlar')
                    ->where('user_id', $userId)
                    ->where('kupon_id', $k->id)
                    ->exists();
                
                if (!$atanmisMi) {
                    $k->kullaniciyaAta($userId);
                    $atananSayisi++;
                }
            }
        }

        if (request()->ajax()) {
            return response()->json(['atanan_sayisi' => $atananSayisi]);
        }

        return $atananSayisi;
    }

    /**
     * Kurala uygun kullanıcıları getir
     */
    private function uygunKullanicilariGetir(Kupon $kupon)
    {
        $gunAraligi = $kupon->kural_gun_araligi ?? 30;
        $baslangicTarihi = now()->subDays($gunAraligi);

        switch ($kupon->kural_tipi) {
            case 'toplam_alisveriş':
                return User::whereHas('siparisler', function($q) use ($baslangicTarihi, $kupon) {
                    $q->where('odeme_durumu', 'odendi')
                      ->where('created_at', '>=', $baslangicTarihi)
                      ->havingRaw('SUM(toplam_tutar + kdv_tutari - indirim_tutari) >= ?', [$kupon->kural_min_tutar]);
                })->pluck('id')->toArray();

            case 'siparis_adedi':
                return User::whereHas('siparisler', function($q) use ($baslangicTarihi) {
                    $q->where('odeme_durumu', 'odendi')
                      ->where('created_at', '>=', $baslangicTarihi);
                }, '>=', $kupon->kural_min_siparis)
                ->pluck('id')->toArray();

            case 'tek_siparis_tutari':
                return User::whereHas('siparisler', function($q) use ($baslangicTarihi, $kupon) {
                    $q->where('odeme_durumu', 'odendi')
                      ->where('created_at', '>=', $baslangicTarihi)
                      ->whereRaw('(toplam_tutar + kdv_tutari - indirim_tutari) >= ?', [$kupon->kural_min_tutar]);
                })->pluck('id')->toArray();

            default:
                return [];
        }
    }

    /**
     * Kullanıcı arama (AJAX)
     */
    public function kullaniciAra(Request $request)
    {
        $query = $request->get('q');
        if(!$query) return response()->json([]);
        
        $kullanicilar = User::where('name', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->limit(20)
            ->get(['id', 'name', 'email']);

        return response()->json($kullanicilar);
    }
}