<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\Urun;
use App\Models\UyumluUrun;
use App\Models\Konfigurasyon;
use App\Models\KonfigurasyonUrun;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class WizardController extends Controller
{
    public function index()
    {
        $kategoriler = Kategori::with('altKategoriler')->get();
        return view('wizard.index', compact('kategoriler'));
    }

    public function getUrunler($altKategoriId, Request $request)
    {
        $selectedUrunIds = $request->query('selected_urun_id');
        $selectedUrunIds = $selectedUrunIds ? explode(',', $selectedUrunIds) : [];

        $uyumlulukKategorileri = ['İşlemci','Anakart','RAM','Ekran Kartı'];

        $altKategori = \App\Models\AltKategori::find($altKategoriId);
        if (!$altKategori) return response()->json([]);

        $urunlerQuery = Urun::where('alt_kategori_id', $altKategoriId);

        if (!empty($selectedUrunIds) && in_array($altKategori->kategori->kategori_ad, $uyumlulukKategorileri)) {
            $hedefAltKategoriIds = [];

            foreach($selectedUrunIds as $id){
                $seciliUrun = Urun::find($id);
                if(!$seciliUrun) continue;

                $uyumluAltKategoriler = UyumluUrun::where('urun_id', $seciliUrun->id)
                    ->pluck('uyumlu_urun_id')
                    ->toArray();

                $hedefAltKategoriIds = array_merge($hedefAltKategoriIds, $uyumluAltKategoriler);
            }

            $hedefAltKategoriIds = array_unique($hedefAltKategoriIds);

            if(!empty($hedefAltKategoriIds)){
                $urunlerQuery->whereIn('id', $hedefAltKategoriIds);
            } else {
                return response()->json([]);
            }
        }

        // Ürünleri aktif fiyatlarıyla beraber çek
        $urunler = $urunlerQuery->with(['fiyatlar' => function($query) {
            $query->wherePivot('baslangic_tarihi', '<=', now())
                  ->where(function($q) {
                        $q->whereNull('urun_fiyat_urun.bitis_tarihi')
                          ->orWhere('urun_fiyat_urun.bitis_tarihi', '>=', now());
                  })
                  ->latest('urun_fiyat_urun.baslangic_tarihi');
        }])->get(['id','urun_ad','marka','model','resim_url']);

        $user = auth()->user();

        // Her ürün için kullanıcıya göre fiyatı hesapla
        $urunler->transform(function($urun) use ($user) {
            // Kullanıcıya göre fiyat al (bayi veya standart)
            $satisFiyati = $urun->getFiyatForUser($user);

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

            $urun->fiyat = $indirimliFiyat > 0 ? $indirimliFiyat : 0;
            $urun->resim = $urun->resim_url ? asset($urun->resim_url) : asset('resimler/default.png');
            
            // Fiyat yok ise belirt
            $urun->fiyat_var = $satisFiyati > 0;
            
            return $urun;
        });

        return response()->json($urunler);
    }
public function konfigurasyonKaydet(Request $request)
    {
        $request->validate([
            'isim' => 'required|string|max:255',
            'urunler' => 'required|array'
        ]);

        $kullanici = Auth::user();

        // Konfigürasyonu oluştur
        $konfig = Konfigurasyon::create([
            'kullanici_id' => $kullanici->id,
            'isim' => $request->isim
        ]);

        $user = auth()->user();

        // Ürünleri ekle - kullanıcıya göre fiyatları hesapla
        foreach ($request->urunler as $urunData) {
            $urun = Urun::find($urunData['id']);
            if(!$urun) continue;

            // Kullanıcıya göre güncel fiyatı hesapla
            $satisFiyati = $urun->getFiyatForUser($user);

            // Kampanya kontrolü
            $kampanya = DB::table('kampanya_indirim')
                ->where('urun_id', $urun->id)
                ->where('aktif', 1)
                ->where('baslangic_tarihi', '<=', now())
                ->where('bitis_tarihi', '>=', now())
                ->first();
            
            $guncelFiyat = $satisFiyati;
            if($kampanya && $satisFiyati > 0) {
                $guncelFiyat = $satisFiyati * (1 - $kampanya->indirim_orani / 100);
            }

            KonfigurasyonUrun::create([
                'konfigürasyon_id' => $konfig->id,
                'urun_id' => $urunData['id'],
                'adet' => $urunData['adet'] ?? 1,
                'fiyat' => $guncelFiyat ?? 0, // === ÇÖZÜM BURADA === (Eğer $guncelFiyat null ise 0 olarak kaydet)
            ]);
        }

        return response()->json([
            'success' => true, 
            'message' => 'Konfigürasyon kaydedildi',
            'redirect_url' => route('profil') // Profil sayfasının URL'i
        ]);
    

       
    }
}