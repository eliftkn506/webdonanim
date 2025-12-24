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
        $request->validate([
            'kupon_kodu' => 'required|unique:kuponlar,kupon_kodu',
            'baslik' => 'required',
            'indirim_tipi' => 'required|in:yuzde,tutar',
            'indirim_miktari' => 'required|numeric|min:0',
            'baslangic_tarihi' => 'required|date',
            'bitis_tarihi' => 'required|date|after_or_equal:baslangic_tarihi',
            'kupon_turu' => 'required|in:genel,kullanici_ozel,kural_bazli',
        ]);

        DB::beginTransaction();
        try {
            $kuralHedefler = null;
            if ($request->kupon_turu === 'kural_bazli') {
                if ($request->kural_tipi === 'belirli_kategori' && $request->hedef_kategoriler) {
                    $kuralHedefler = json_encode(['kategoriler' => $request->hedef_kategoriler]);
                } elseif ($request->kural_tipi === 'belirli_urun' && $request->hedef_urunler) {
                    $kuralHedefler = json_encode(['urunler' => $request->hedef_urunler]);
                }
            }

            $kupon = new Kupon();
            $kupon->kupon_kodu = strtoupper($request->kupon_kodu);
            $kupon->baslik = $request->baslik;
            $kupon->aciklama = $request->aciklama;
            $kupon->kupon_turu = $request->kupon_turu;
            $kupon->indirim_tipi = $request->indirim_tipi;
            $kupon->indirim_miktari = $request->indirim_miktari;
            $kupon->minimum_tutar = $request->minimum_tutar ?? 0;
            $kupon->kullanim_limiti = $request->kullanim_limiti ?? null;
            $kupon->kullanilan_adet = 0;
            $kupon->baslangic_tarihi = $request->baslangic_tarihi;
            $kupon->bitis_tarihi = $request->bitis_tarihi;
            $kupon->aktif = $request->has('aktif') ? 1 : 0;
            $kupon->kural_tipi = $request->kural_tipi;
            $kupon->kural_min_tutar = $request->kural_min_tutar ?? 0;
            $kupon->kural_min_siparis = $request->kural_min_siparis ?? 0;
            $kupon->kural_gun_araligi = $request->kural_gun_araligi ?? 30;
            $kupon->kural_hedefler = $kuralHedefler;
            $kupon->otomatik_ata = $request->has('otomatik_ata') ? 1 : 0;
            $kupon->save();

            if ($request->kupon_turu === 'kullanici_ozel' && $request->has('secili_kullanicilar')) {
                foreach ($request->secili_kullanicilar as $userId) {
                    $kupon->kullaniciyaAta($userId);
                }
            }

            if ($request->kupon_turu === 'kural_bazli' && $request->has('otomatik_ata')) {
                $uygunKullanicilar = $this->uygunKullanicilariGetir($kupon);
                foreach ($uygunKullanicilar as $userId) {
                    $kupon->kullaniciyaAta($userId);
                }
            }

            DB::commit();
            return redirect()->route('admin.kuponlar.index')->with('success', 'Kupon oluşturuldu.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Hata: ' . $e->getMessage());
        }
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
        $request->validate([
            'kupon_kodu' => 'required|unique:kuponlar,kupon_kodu,'.$kupon->id,
            'baslik' => 'required',
            'kupon_turu' => 'required|in:genel,kullanici_ozel,kural_bazli',
            'indirim_tipi' => 'required|in:yuzde,tutar',
            'indirim_miktari' => 'required|numeric|min:0',
            'baslangic_tarihi' => 'required|date',
            'bitis_tarihi' => 'required|date|after_or_equal:baslangic_tarihi',
        ]);

        DB::beginTransaction();
        try {
            $kuralHedefler = null;
            if ($request->kupon_turu === 'kural_bazli') {
                if ($request->kural_tipi === 'belirli_kategori' && $request->hedef_kategoriler) {
                    $kuralHedefler = json_encode(['kategoriler' => $request->hedef_kategoriler]);
                } elseif ($request->kural_tipi === 'belirli_urun' && $request->hedef_urunler) {
                    $kuralHedefler = json_encode(['urunler' => $request->hedef_urunler]);
                }
            }

            $kupon->update([
                'kupon_kodu' => strtoupper($request->kupon_kodu),
                'baslik' => $request->baslik,
                'aciklama' => $request->aciklama,
                'kupon_turu' => $request->kupon_turu,
                'indirim_tipi' => $request->indirim_tipi,
                'indirim_miktari' => $request->indirim_miktari,
                'minimum_tutar' => $request->minimum_tutar ?? 0,
                'kullanim_limiti' => $request->kullanim_limiti,
                'baslangic_tarihi' => $request->baslangic_tarihi,
                'bitis_tarihi' => $request->bitis_tarihi,
                'aktif' => $request->has('aktif') ? 1 : 0,
                'kural_tipi' => $request->kural_tipi,
                'kural_min_tutar' => $request->kural_min_tutar ?? 0,
                'kural_min_siparis' => $request->kural_min_siparis ?? 0,
                'kural_gun_araligi' => $request->kural_gun_araligi ?? 30,
                'kural_hedefler' => $kuralHedefler,
                'otomatik_ata' => $request->has('otomatik_ata') ? 1 : 0,
            ]);

            if ($request->kupon_turu === 'kullanici_ozel' && $request->has('secili_kullanicilar')) {
                DB::table('kullanici_kuponlar')->where('kupon_id', $kupon->id)->delete();
                foreach ($request->secili_kullanicilar as $userId) {
                    $kupon->kullaniciyaAta($userId);
                }
            }

            DB::commit();
            return redirect()->route('admin.kuponlar.index')->with('success', 'Kupon güncellendi.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Hata: ' . $e->getMessage());
        }
    }

    public function destroy(Kupon $kupon)
    {
        $kupon->delete();
        return redirect()->route('admin.kuponlar.index')->with('success', 'Kupon silindi.');
    }

    public function kuralBazliKuponlariAta(Request $request)
    {
        try {
            $kuponlar = Kupon::where('kupon_turu', 'kural_bazli')->where('aktif', true)->get();
            $atananSayisi = 0;

            foreach ($kuponlar as $kupon) {
                $uygunKullanicilar = $this->uygunKullanicilariGetir($kupon);
                foreach ($uygunKullanicilar as $userId) {
                    $varmi = DB::table('kullanici_kuponlar')->where(['user_id' => $userId, 'kupon_id' => $kupon->id])->exists();
                    if (!$varmi) {
                        $kupon->kullaniciyaAta($userId);
                        $atananSayisi++;
                    }
                }
            }

            return response()->json(['success' => true, 'atanan_sayisi' => $atananSayisi]);
        } catch (\Exception $e) {
            Log::error('Kupon Ata Hatası: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    private function uygunKullanicilariGetir(Kupon $kupon)
    {
        $baslangicTarihi = now()->subDays($kupon->kural_gun_araligi ?? 30);
        $kural = str_replace(['i', 'ş'], ['ı', 's'], strtolower($kupon->kural_tipi));

        $query = DB::table('users')
            ->join('siparisler', 'users.id', '=', 'siparisler.user_id')
            ->where('siparisler.odeme_durumu', 'odendi')
            ->where('siparisler.created_at', '>=', $baslangicTarihi);

        if ($kural == 'toplam_alisveris' || $kural == 'toplam_alisverıs') {
            return $query->select('users.id')
                ->groupBy('users.id')
                ->havingRaw('SUM(siparisler.toplam_tutar) >= ?', [$kupon->kural_min_tutar])
                ->pluck('id')->toArray();
        } 
        
        if ($kural == 'siparis_adedi') {
            return $query->select('users.id')
                ->groupBy('users.id')
                ->havingRaw('COUNT(siparisler.id) >= ?', [$kupon->kural_min_siparis])
                ->pluck('id')->toArray();
        }

        return [];
    }

    public function kullaniciAra(Request $request)
    {
        $query = $request->get('q');
        return response()->json(User::where('name', 'LIKE', "%$query%")->orWhere('email', 'LIKE', "%$query%")->limit(10)->get(['id', 'name', 'email']));
    }
}