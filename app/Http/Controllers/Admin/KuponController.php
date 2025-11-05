<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kupon;
use App\Models\User;
use App\Models\KullaniciKupon;
use App\Models\Kategori;
use App\Models\Urun;

class KuponController extends Controller
{
    public function index()
    {
        $kuponlar = Kupon::with('kullanicilar')->orderBy('created_at', 'desc')->get();
        return view('admin.kuponlar.index', compact('kuponlar'));
    }

    public function create()
    {
        $kategoriler = Kategori::all();
        $urunler = Urun::select('id', 'urun_ad')->get();
        return view('admin.kuponlar.create', compact('kategoriler', 'urunler'));
    }

    public function store(Request $request)
    {
        $rules = [
            'kupon_kodu' => 'required|unique:kuponlar,kupon_kodu',
            'baslik' => 'required',
            'kupon_turu' => 'required|in:genel,kullanici_ozel,kural_bazli',
            'indirim_tipi' => 'required|in:yuzde,tutar',
            'indirim_miktari' => 'required|numeric|min:0',
            'minimum_tutar' => 'nullable|numeric|min:0',
            'kullanim_limiti' => 'nullable|numeric|min:1',
            'baslangic_tarihi' => 'required|date',
            'bitis_tarihi' => 'required|date|after_or_equal:baslangic_tarihi',
        ];

        // Kural bazlı kupon için ek validasyon
        if ($request->kupon_turu === 'kural_bazli') {
            $rules['kural_tipi'] = 'required|in:toplam_alisveriş,siparis_adedi,tek_siparis_tutari,belirli_kategori,belirli_urun';
            
            if (in_array($request->kural_tipi, ['toplam_alisveriş', 'tek_siparis_tutari'])) {
                $rules['kural_min_tutar'] = 'required|numeric|min:0';
            }
            
            if ($request->kural_tipi === 'siparis_adedi') {
                $rules['kural_min_siparis'] = 'required|numeric|min:1';
            }
        }

        $validated = $request->validate($rules);

        // Kural hedeflerini hazırla
        $kuralHedefler = null;
        if ($request->kupon_turu === 'kural_bazli') {
            if ($request->kural_tipi === 'belirli_kategori') {
                $kuralHedefler = ['kategoriler' => $request->hedef_kategoriler ?? []];
            } elseif ($request->kural_tipi === 'belirli_urun') {
                $kuralHedefler = ['urunler' => $request->hedef_urunler ?? []];
            }
        }

        $kupon = Kupon::create([
            'kupon_kodu' => $request->kupon_kodu,
            'baslik' => $request->baslik,
            'aciklama' => $request->aciklama,
            'kupon_turu' => $request->kupon_turu,
            'indirim_tipi' => $request->indirim_tipi,
            'indirim_miktari' => $request->indirim_miktari,
            'minimum_tutar' => $request->minimum_tutar,
            'kullanim_limiti' => $request->kullanim_limiti,
            'baslangic_tarihi' => $request->baslangic_tarihi,
            'bitis_tarihi' => $request->bitis_tarihi,
            'aktif' => $request->has('aktif'),
            'kural_tipi' => $request->kural_tipi,
            'kural_min_tutar' => $request->kural_min_tutar,
            'kural_min_siparis' => $request->kural_min_siparis,
            'kural_gun_araligi' => $request->kural_gun_araligi ?? 30,
            'kural_hedefler' => $kuralHedefler,
            'otomatik_ata' => $request->has('otomatik_ata'),
        ]);

        // Kullanıcı özel kupon ise seçilen kullanıcılara ata
        if ($request->kupon_turu === 'kullanici_ozel' && $request->has('secili_kullanicilar')) {
            foreach ($request->secili_kullanicilar as $userId) {
                $kupon->kullaniciyaAta($userId);
            }
        }

        // Kural bazlı ve otomatik atama aktifse, uygun kullanıcılara ata
        if ($request->kupon_turu === 'kural_bazli' && $request->has('otomatik_ata')) {
            $this->kuralBazliKuponlariAta($kupon);
        }

        return redirect()->route('admin.kuponlar.index')->with('success', 'Kupon başarıyla oluşturuldu.');
    }

    public function edit(Kupon $kupon)
    {
        $kategoriler = Kategori::all();
        $urunler = Urun::select('id', 'urun_ad')->get();
        $atananKullanicilar = $kupon->kullanicilar->pluck('id')->toArray();
        
        return view('admin.kuponlar.edit', compact('kupon', 'kategoriler', 'urunler', 'atananKullanicilar'));
    }

    public function update(Request $request, Kupon $kupon)
    {
        $rules = [
            'kupon_kodu' => 'required|unique:kuponlar,kupon_kodu,'.$kupon->id,
            'baslik' => 'required',
            'kupon_turu' => 'required|in:genel,kullanici_ozel,kural_bazli',
            'indirim_tipi' => 'required|in:yuzde,tutar',
            'indirim_miktari' => 'required|numeric|min:0',
            'minimum_tutar' => 'nullable|numeric|min:0',
            'kullanim_limiti' => 'nullable|numeric|min:1',
            'baslangic_tarihi' => 'required|date',
            'bitis_tarihi' => 'required|date|after_or_equal:baslangic_tarihi',
        ];

        if ($request->kupon_turu === 'kural_bazli') {
            $rules['kural_tipi'] = 'required';
        }

        $validated = $request->validate($rules);

        $kuralHedefler = null;
        if ($request->kupon_turu === 'kural_bazli') {
            if ($request->kural_tipi === 'belirli_kategori') {
                $kuralHedefler = ['kategoriler' => $request->hedef_kategoriler ?? []];
            } elseif ($request->kural_tipi === 'belirli_urun') {
                $kuralHedefler = ['urunler' => $request->hedef_urunler ?? []];
            }
        }

        $kupon->update([
            'kupon_kodu' => $request->kupon_kodu,
            'baslik' => $request->baslik,
            'aciklama' => $request->aciklama,
            'kupon_turu' => $request->kupon_turu,
            'indirim_tipi' => $request->indirim_tipi,
            'indirim_miktari' => $request->indirim_miktari,
            'minimum_tutar' => $request->minimum_tutar,
            'kullanim_limiti' => $request->kullanim_limiti,
            'baslangic_tarihi' => $request->baslangic_tarihi,
            'bitis_tarihi' => $request->bitis_tarihi,
            'aktif' => $request->has('aktif'),
            'kural_tipi' => $request->kural_tipi,
            'kural_min_tutar' => $request->kural_min_tutar,
            'kural_min_siparis' => $request->kural_min_siparis,
            'kural_gun_araligi' => $request->kural_gun_araligi ?? 30,
            'kural_hedefler' => $kuralHedefler,
            'otomatik_ata' => $request->has('otomatik_ata'),
        ]);

        // Kullanıcı atamaları güncelle
        if ($request->kupon_turu === 'kullanici_ozel' && $request->has('secili_kullanicilar')) {
            // Mevcut atamaları temizle
            KullaniciKupon::where('kupon_id', $kupon->id)->delete();
            
            // Yeni atamaları yap
            foreach ($request->secili_kullanicilar as $userId) {
                $kupon->kullaniciyaAta($userId);
            }
        }

        return redirect()->route('admin.kuponlar.index')->with('success', 'Kupon başarıyla güncellendi.');
    }

    public function destroy(Kupon $kupon)
    {
        $kupon->delete();
        return redirect()->route('admin.kuponlar.index')->with('success', 'Kupon silindi.');
    }

    /**
     * Kural bazlı kuponları uygun kullanıcılara otomatik ata
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
                // Daha önce atanmamışsa ata
                $atanmisMi = KullaniciKupon::where('user_id', $userId)
                    ->where('kupon_id', $k->id)
                    ->exists();
                
                if (!$atanmisMi) {
                    $k->kullaniciyaAta($userId);
                    $atananSayisi++;
                }
            }
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
                // En az bir siparişi belirtilen tutarı aşan kullanıcılar
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
     * Kullanıcı arama - AJAX
     */
    public function kullaniciAra(Request $request)
    {
        $query = $request->get('q');
        
        $kullanicilar = User::where('name', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->limit(20)
            ->get(['id', 'name', 'email']);

        return response()->json($kullanicilar);
    }
}