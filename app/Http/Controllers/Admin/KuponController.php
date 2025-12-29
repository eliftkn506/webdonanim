<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kupon;
use App\Models\User;
use App\Models\Kategori;
use App\Models\Urun;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class KuponController extends Controller
{
    public function index(Request $request)
    {
        $query = Kupon::with(['kullanicilar' => function($q) {
            $q->select('users.id', 'users.name');
        }])->orderBy('created_at', 'desc');

        // Filtreleme
        if ($request->filled('durum')) {
            if ($request->durum === 'aktif') {
                $query->aktif();
            } elseif ($request->durum === 'pasif') {
                $query->where('aktif', false);
            } elseif ($request->durum === 'suresi_dolmus') {
                $query->where('bitis_tarihi', '<', now());
            }
        }

        if ($request->filled('tur')) {
            $query->where('kupon_turu', $request->tur);
        }

        if ($request->filled('arama')) {
            $query->where(function($q) use ($request) {
                $q->where('kupon_kodu', 'LIKE', "%{$request->arama}%")
                  ->orWhere('baslik', 'LIKE', "%{$request->arama}%");
            });
        }

        $kuponlar = $query->paginate(15);

        return view('admin.kuponlar.index', compact('kuponlar'));
    }

    public function create()
    {
        // DÜZELTME: 'aktif' sütunu olmadığı için where('aktif', true) kaldırıldı.
        $kategoriler = Kategori::orderBy('kategori_ad')->get();
        
        // Ürünlerde durum sütunu varsa bu kalabilir, yoksa bunu da kaldırmalısınız.
        $urunler = Urun::select('id', 'urun_ad')->orderBy('urun_ad')->get();
        
        return view('admin.kuponlar.create', compact('kategoriler', 'urunler'));
    }

   public function store(Request $request)
{
    $validated = $request->validate([
        'kupon_kodu' => 'required|unique:kuponlar,kupon_kodu|max:50',
        'baslik' => 'required|max:255',
        'indirim_tipi' => 'required|in:yuzde,tutar',
        'indirim_miktari' => 'required|numeric|min:0',
        'maksimum_indirim' => 'nullable|numeric|min:0',
        'minimum_tutar' => 'nullable|numeric|min:0',
        'kullanim_limiti' => 'nullable|integer|min:1',
        'kullanici_basina_limit' => 'required|integer|min:1',
        'baslangic_tarihi' => 'required|date',
        'bitis_tarihi' => 'required|date|after_or_equal:baslangic_tarihi',
        'kupon_turu' => 'required|in:genel,kullanici_ozel,kural_bazli',
    ]);

    DB::beginTransaction();
    try {
        $kuralKosullari = null;
        
        if ($request->kupon_turu === 'kural_bazli') {
            $kuralKosullari = $this->kuralKosullariOlustur($request);
        }

        // JSON formatına çevir
        $hedefKategoriler = $request->hedef_kategoriler ? json_encode($request->hedef_kategoriler) : null;
        $hedefUrunler = $request->hedef_urunler ? json_encode($request->hedef_urunler) : null;
        $haricKategoriler = $request->haric_kategoriler ? json_encode($request->haric_kategoriler) : null;
        $haricUrunler = $request->haric_urunler ? json_encode($request->haric_urunler) : null;

        $kupon = Kupon::create([
            'kupon_kodu' => strtoupper($request->kupon_kodu),
            'baslik' => $request->baslik,
            'aciklama' => $request->aciklama,
            'kupon_turu' => $request->kupon_turu,
            'indirim_tipi' => $request->indirim_tipi,
            'indirim_miktari' => $request->indirim_miktari,
            'maksimum_indirim' => $request->maksimum_indirim,
            'minimum_tutar' => $request->minimum_tutar ?? 0,
            'kullanim_limiti' => $request->kullanim_limiti,
            'kullanici_basina_limit' => $request->kullanici_basina_limit,
            'kullanilan_adet' => 0,
            'baslangic_tarihi' => $request->baslangic_tarihi,
            'bitis_tarihi' => $request->bitis_tarihi,
            'aktif' => $request->has('aktif') ? 1 : 0,
            'kural_kosullari' => $kuralKosullari ? json_encode($kuralKosullari) : null,
            'otomatik_ata' => $request->has('otomatik_ata') ? 1 : 0,
            'hedef_kategoriler' => $hedefKategoriler,
            'hedef_urunler' => $hedefUrunler,
            'haric_kategoriler' => $haricKategoriler,
            'haric_urunler' => $haricUrunler,
            'toplam_indirim_tutari' => 0,
            'toplam_kullanan_kisi' => 0,
        ]);

        // Debug - kayıt başarılı mı kontrol et
        if (!$kupon->id) {
            throw new \Exception('Kupon kaydedilemedi');
        }

        // Kullanıcıya özel kuponlar
        if ($request->kupon_turu === 'kullanici_ozel' && $request->filled('secili_kullanicilar')) {
            $atananlar = $kupon->kullanicilariAta($request->secili_kullanicilar);
            session()->flash('info', "{$atananlar} kullanıcıya kupon atandı.");
        }

        // Kural bazlı otomatik atama
        if ($request->kupon_turu === 'kural_bazli' && $request->has('otomatik_ata')) {
            $atananlar = $this->kuralBazliAta($kupon);
            session()->flash('info', "{$atananlar} kullanıcıya otomatik kupon atandı.");
        }

        DB::commit();
        
        return redirect()->route('admin.kuponlar.index')
                       ->with('success', 'Kupon başarıyla oluşturuldu. (ID: ' . $kupon->id . ')');
                       
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Kupon oluşturma hatası: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        
        return back()->withInput()
                    ->with('error', 'Hata: ' . $e->getMessage());
    }
}
    public function edit(Kupon $kupon)
    {
        // DÜZELTME: 'aktif' sütunu kaldırıldı.
        $kategoriler = Kategori::orderBy('kategori_ad')->get();
        $urunler = Urun::select('id', 'urun_ad')->orderBy('urun_ad')->get();
        
        $atananKullanicilar = $kupon->kullanicilar()
                                    ->select('users.id', 'users.name', 'users.email')
                                    ->get();

        return view('admin.kuponlar.edit', compact('kupon', 'kategoriler', 'urunler', 'atananKullanicilar'));
    }

    public function update(Request $request, Kupon $kupon)
    {
        $validated = $request->validate([
            'kupon_kodu' => 'required|max:50|unique:kuponlar,kupon_kodu,'.$kupon->id,
            'baslik' => 'required|max:255',
            'kupon_turu' => 'required|in:genel,kullanici_ozel,kural_bazli',
            'indirim_tipi' => 'required|in:yuzde,tutar',
            'indirim_miktari' => 'required|numeric|min:0',
            'maksimum_indirim' => 'nullable|numeric|min:0',
            'minimum_tutar' => 'nullable|numeric|min:0',
            'kullanim_limiti' => 'nullable|integer|min:1',
            'kullanici_basina_limit' => 'required|integer|min:1',
            'baslangic_tarihi' => 'required|date',
            'bitis_tarihi' => 'required|date|after_or_equal:baslangic_tarihi',
        ]);

        DB::beginTransaction();
        try {
            $kuralKosullari = null;
            
            if ($request->kupon_turu === 'kural_bazli') {
                $kuralKosullari = $this->kuralKosullariOlustur($request);
            }

            $kupon->update([
                'kupon_kodu' => strtoupper($request->kupon_kodu),
                'baslik' => $request->baslik,
                'aciklama' => $request->aciklama,
                'kupon_turu' => $request->kupon_turu,
                'indirim_tipi' => $request->indirim_tipi,
                'indirim_miktari' => $request->indirim_miktari,
                'maksimum_indirim' => $request->maksimum_indirim,
                'minimum_tutar' => $request->minimum_tutar ?? 0,
                'kullanim_limiti' => $request->kullanim_limiti,
                'kullanici_basina_limit' => $request->kullanici_basina_limit,
                'baslangic_tarihi' => $request->baslangic_tarihi,
                'bitis_tarihi' => $request->bitis_tarihi,
                'aktif' => $request->has('aktif'),
                'kural_kosullari' => $kuralKosullari,
                'otomatik_ata' => $request->has('otomatik_ata'),
                'hedef_kategoriler' => $request->hedef_kategoriler,
                'hedef_urunler' => $request->hedef_urunler,
                'hariç_kategoriler' => $request->haric_kategoriler,
                'hariç_urunler' => $request->haric_urunler,
            ]);

            // Kullanıcıya özel kuponlar için güncelleme
            if ($request->kupon_turu === 'kullanici_ozel' && $request->filled('secili_kullanicilar')) {
                DB::table('kullanici_kuponlar')
                  ->where('kupon_id', $kupon->id)
                  ->delete();
                
                $atananlar = $kupon->kullanicilariAta($request->secili_kullanicilar);
                session()->flash('info', "{$atananlar} kullanıcıya kupon atandı.");
            }

            DB::commit();
            return redirect()->route('admin.kuponlar.index')
                             ->with('success', 'Kupon başarıyla güncellendi.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Kupon güncelleme hatası: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Hata: ' . $e->getMessage());
        }
    }

    public function destroy(Kupon $kupon)
    {
        try {
            $kupon->delete();
            return redirect()->route('admin.kuponlar.index')
                             ->with('success', 'Kupon başarıyla silindi.');
        } catch (\Exception $e) {
            return back()->with('error', 'Kupon silinirken hata oluştu: ' . $e->getMessage());
        }
    }

    // Kullanıcı arama (AJAX)
    public function kullaniciAra(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $kullanicilar = User::where(function($q) use ($query) {
                            $q->where('name', 'LIKE', "%{$query}%")
                              ->orWhere('email', 'LIKE', "%{$query}%");
                        })
                        ->select('id', 'name', 'email')
                        ->limit(20)
                        ->get();

        return response()->json($kullanicilar);
    }

    // Kural bazlı kuponları otomatik ata
    public function kuralBazliKuponlariAta(Request $request)
    {
        try {
            $kuponlar = Kupon::kuralBazli()->aktif()->get();
            $toplamAtanan = 0;

            foreach ($kuponlar as $kupon) {
                $atananlar = $this->kuralBazliAta($kupon);
                $toplamAtanan += $atananlar;
                
                $kupon->update(['son_atama_tarihi' => now()]);
            }

            return response()->json([
                'success' => true,
                'message' => "{$toplamAtanan} kullanıcıya kupon atandı",
                'atanan_sayisi' => $toplamAtanan
            ]);
        } catch (\Exception $e) {
            Log::error('Otomatik kupon atama hatası: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Tekil kupon için kural çalıştır
    public function tekilKuralCalistir(Kupon $kupon)
    {
        try {
            if ($kupon->kupon_turu !== 'kural_bazli') {
                return response()->json([
                    'success' => false,
                    'message' => 'Bu kupon kural bazlı değil'
                ], 400);
            }

            $atananlar = $this->kuralBazliAta($kupon);
            $kupon->update(['son_atama_tarihi' => now()]);

            return response()->json([
                'success' => true,
                'message' => "{$atananlar} kullanıcıya kupon atandı",
                'atanan_sayisi' => $atananlar
            ]);
        } catch (\Exception $e) {
            Log::error('Tekil kural çalıştırma hatası: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Kuralları oluştur
    private function kuralKosullariOlustur(Request $request)
    {
        $kosullar = [];

        if ($request->filled('kural_min_siparis_tutari')) {
            $kosullar['min_siparis_tutari'] = $request->kural_min_siparis_tutari;
        }

        if ($request->filled('kural_min_siparis_adedi')) {
            $kosullar['min_siparis_adedi'] = $request->kural_min_siparis_adedi;
        }

        if ($request->filled('kural_tarih_araligi')) {
            $kosullar['tarih_araligi'] = $request->kural_tarih_araligi;
        } else {
            $kosullar['tarih_araligi'] = 30; // Varsayılan 30 gün
        }

        if ($request->has('kural_ilk_alisveris')) {
            $kosullar['ilk_alisveris'] = true;
        }

        if ($request->has('kural_dogum_gunu')) {
            $kosullar['dogum_gunu'] = true;
            if ($request->filled('kural_dogum_gunu_aralik')) {
                $kosullar['dogum_gunu_aralik'] = $request->kural_dogum_gunu_aralik;
            }
        }

        if ($request->filled('kural_min_urun_adedi')) {
            $kosullar['min_urun_adedi'] = $request->kural_min_urun_adedi;
        }

        if ($request->has('kural_inaktif_musteri')) {
            $kosullar['inaktif_musteri'] = true;
            if ($request->filled('kural_inaktif_gun')) {
                $kosullar['inaktif_gun'] = $request->kural_inaktif_gun;
            }
        }

        return empty($kosullar) ? null : $kosullar;
    }

    // Kural bazlı kullanıcıları bul ve ata
    private function kuralBazliAta(Kupon $kupon)
    {
        $kosullar = $kupon->kural_kosullari;
        
        if (empty($kosullar)) {
            return 0;
        }

        $tarihAraligi = $kosullar['tarih_araligi'] ?? 30;
        $baslangicTarihi = now()->subDays($tarihAraligi);

        $query = User::query();

        // İlk alışveriş yapanlar
        if (isset($kosullar['ilk_alisveris']) && $kosullar['ilk_alisveris']) {
            $query->whereDoesntHave('siparisler');
        } else {
            // Sipariş geçmişi olan kullanıcılar
            $query->whereHas('siparisler', function($q) use ($baslangicTarihi, $kosullar) {
                $q->where('odeme_durumu', 'odendi')
                  ->where('created_at', '>=', $baslangicTarihi);

                // Minimum sipariş tutarı
                if (isset($kosullar['min_siparis_tutari'])) {
                    $q->havingRaw('SUM(toplam_tutar) >= ?', [$kosullar['min_siparis_tutari']]);
                }
            });

            // Minimum sipariş adedi
            if (isset($kosullar['min_siparis_adedi'])) {
                $query->has('siparisler', '>=', $kosullar['min_siparis_adedi']);
            }
        }

        // Doğum günü olanlar
        if (isset($kosullar['dogum_gunu']) && $kosullar['dogum_gunu']) {
            $aralik = $kosullar['dogum_gunu_aralik'] ?? 7;
            $bugun = now();
            
            $query->where(function($q) use ($bugun, $aralik) {
                for ($i = 0; $i <= $aralik; $i++) {
                    $tarih = $bugun->copy()->addDays($i);
                    $q->orWhereRaw('DAY(dogum_tarihi) = ? AND MONTH(dogum_tarihi) = ?', 
                                  [$tarih->day, $tarih->month]);
                }
            });
        }

        // İnaktif müşteriler
        if (isset($kosullar['inaktif_musteri']) && $kosullar['inaktif_musteri']) {
            $inaktifGun = $kosullar['inaktif_gun'] ?? 60;
            $query->whereHas('siparisler', function($q) use ($inaktifGun) {
                $q->where('created_at', '<', now()->subDays($inaktifGun));
            })
            ->whereDoesntHave('siparisler', function($q) use ($inaktifGun) {
                $q->where('created_at', '>=', now()->subDays($inaktifGun));
            });
        }

        // Zaten kuponu olanları hariç tut
        $query->whereDoesntHave('kuponlar', function($q) use ($kupon) {
            $q->where('kupon_id', $kupon->id);
        });

        $uygunKullanicilar = $query->pluck('id')->toArray();

        return $kupon->kullanicilariAta($uygunKullanicilar);
    }
    
    // İstatistik metodunu controller'ın sonuna ekleyin
    public function istatistikler(Kupon $kupon)
    {
         $kullanimlar = $kupon->kullanimlar()
                             ->with('user:id,name,email')
                             ->orderBy('created_at', 'desc')
                             ->paginate(20);

        $istatistikler = [
            'toplam_kullanim' => $kupon->kullanilan_adet,
            'toplam_indirim' => $kupon->toplam_indirim_tutari,
            'toplam_kullanan' => $kupon->toplam_kullanan_kisi,
            'ortalama_siparis' => $kupon->kullanimlar()->avg('siparis_tutari'),
            'ortalama_indirim' => $kupon->kullanimlar()->avg('indirim_tutari'),
        ];

        return view('admin.kuponlar.istatistikler', compact('kupon', 'kullanimlar', 'istatistikler'));
    }
}
