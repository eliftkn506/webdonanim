<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Kupon extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kuponlar';

    protected $fillable = [
        'kupon_kodu',
        'baslik',
        'aciklama',
        'kupon_turu',
        'indirim_tipi',
        'indirim_miktari',
        'maksimum_indirim',
        'minimum_tutar',
        'kullanim_limiti',
        'kullanici_basina_limit',
        'kullanilan_adet',
        'baslangic_tarihi',
        'bitis_tarihi',
        'aktif',
        'kural_kosullari',
        'otomatik_ata',
        'son_atama_tarihi',
        'hedef_kategoriler',
        'hedef_urunler',
        'hariç_kategoriler',
        'hariç_urunler',
        'toplam_indirim_tutari',
        'toplam_kullanan_kisi',
    ];

   protected $casts = [
    'aktif' => 'boolean',
    'otomatik_ata' => 'boolean',
    'kural_kosullari' => 'json', // array yerine json
    'hedef_kategoriler' => 'json',
    'hedef_urunler' => 'json',
    'haric_kategoriler' => 'json',
    'haric_urunler' => 'json',
    'baslangic_tarihi' => 'datetime',
    'bitis_tarihi' => 'datetime',
    'son_atama_tarihi' => 'datetime',
    'indirim_miktari' => 'decimal:2',
    'maksimum_indirim' => 'decimal:2',
    'minimum_tutar' => 'decimal:2',
    'toplam_indirim_tutari' => 'decimal:2',
];

    // İlişkiler
    public function kullanicilar()
    {
        return $this->belongsToMany(User::class, 'kullanici_kuponlar')
                    ->withPivot(['kullanildi', 'kullanim_sayisi', 'kullanilma_tarihi', 'atanma_tarihi', 'son_kullanim_tarihi', 'toplam_kazanc'])
                    ->withTimestamps();
    }

    public function kullanilanKullanicilar()
    {
        return $this->belongsToMany(User::class, 'kullanici_kuponlar')
                    ->wherePivot('kullanildi', true)
                    ->withPivot(['kullanim_sayisi', 'toplam_kazanc']);
    }

    public function kullanimlar()
    {
        return $this->hasMany(KuponKullanim::class, 'kupon_id');
    }

    // Accessor
    public function getKuralAciklamasiAttribute()
    {
        if (!$this->kural_kosullari) {
            return 'Kural tanımlanmamış';
        }

        $kosullar = $this->kural_kosullari;
        $aciklamalar = [];

        if (isset($kosullar['min_siparis_tutari'])) {
            $aciklamalar[] = "Min. {$kosullar['min_siparis_tutari']} ₺ alışveriş";
        }

        if (isset($kosullar['min_siparis_adedi'])) {
            $aciklamalar[] = "Min. {$kosullar['min_siparis_adedi']} sipariş";
        }

        if (isset($kosullar['tarih_araligi'])) {
            $aciklamalar[] = "Son {$kosullar['tarih_araligi']} gün içinde";
        }

        if (isset($kosullar['ilk_alisveris']) && $kosullar['ilk_alisveris']) {
            $aciklamalar[] = "İlk alışveriş yapanlar";
        }

        if (isset($kosullar['dogum_gunu']) && $kosullar['dogum_gunu']) {
            $aciklamalar[] = "Doğum günü olanlar";
        }

        return implode(', ', $aciklamalar);
    }

    // Kullanıcıya kupon ata
    public function kullaniciyaAta($userId)
    {
        $exists = DB::table('kullanici_kuponlar')
            ->where('user_id', $userId)
            ->where('kupon_id', $this->id)
            ->exists();
            
        if ($exists) {
            return false;
        }

        DB::table('kullanici_kuponlar')->insert([
            'user_id' => $userId,
            'kupon_id' => $this->id,
            'kullanildi' => false,
            'kullanim_sayisi' => 0,
            'atanma_tarihi' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return true;
    }

    // Toplu atama
    public function kullanicilariAta(array $userIds)
    {
        $atananlar = 0;
        
        foreach ($userIds as $userId) {
            if ($this->kullaniciyaAta($userId)) {
                $atananlar++;
            }
        }

        return $atananlar;
    }

    // Kuponu kullan
    public function kullan($userId, $siparisId, $siparisTutari, $indirimTutari)
    {
        DB::transaction(function () use ($userId, $siparisId, $siparisTutari, $indirimTutari) {
            // Kullanım sayısını artır
            $this->increment('kullanilan_adet');
            $this->increment('toplam_indirim_tutari', $indirimTutari);

            // Kullanıcı-kupon ilişkisini güncelle
            if ($this->kupon_turu !== 'genel') {
                $kullaniciKupon = DB::table('kullanici_kuponlar')
                    ->where('user_id', $userId)
                    ->where('kupon_id', $this->id)
                    ->first();

                if ($kullaniciKupon) {
                    $kullanildi = ($kullaniciKupon->kullanim_sayisi + 1) >= $this->kullanici_basina_limit;
                    
                    DB::table('kullanici_kuponlar')
                        ->where('user_id', $userId)
                        ->where('kupon_id', $this->id)
                        ->update([
                            'kullanildi' => $kullanildi,
                            'kullanim_sayisi' => DB::raw('kullanim_sayisi + 1'),
                            'kullanilma_tarihi' => $kullanildi ? now() : DB::raw('kullanilma_tarihi'),
                            'son_kullanim_tarihi' => now(),
                            'toplam_kazanc' => DB::raw("toplam_kazanc + {$indirimTutari}"),
                            'updated_at' => now()
                        ]);
                }
            }

            // Kullanım kaydı oluştur
            DB::table('kupon_kullanimlari')->insert([
                'kupon_id' => $this->id,
                'user_id' => $userId,
                'siparis_id' => $siparisId,
                'siparis_tutari' => $siparisTutari,
                'indirim_tutari' => $indirimTutari,
                'ip_adresi' => request()->ip(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Benzersiz kullanan kişi sayısını güncelle
            $this->toplam_kullanan_kisi = DB::table('kupon_kullanimlari')
                ->where('kupon_id', $this->id)
                ->distinct('user_id')
                ->count('user_id');
            $this->save();
        });
    }

    // Kullanıcının bu kuponu kullanıp kullanmadığını kontrol et
    public function kullaniciKullandiMi($userId)
    {
        if ($this->kupon_turu === 'genel') {
            // Genel kuponlar için kullanıcı başına limit kontrolü
            $kullanimSayisi = DB::table('kupon_kullanimlari')
                ->where('kupon_id', $this->id)
                ->where('user_id', $userId)
                ->count();
            
            return $kullanimSayisi >= $this->kullanici_basina_limit;
        }

        // Özel ve kural bazlı kuponlar için
        $kullanim = DB::table('kullanici_kuponlar')
            ->where('user_id', $userId)
            ->where('kupon_id', $this->id)
            ->first();

        if (!$kullanim) {
            return true; // Atanmamış
        }

        return $kullanim->kullanim_sayisi >= $this->kullanici_basina_limit;
    }

    // Kuponun aktif olup olmadığını kontrol et
    public function isActive()
    {
        if (!$this->aktif) {
            return false;
        }

        $now = now();
        if ($now->lt($this->baslangic_tarihi) || $now->gt($this->bitis_tarihi)) {
            return false;
        }

        if ($this->kullanim_limiti && $this->kullanilan_adet >= $this->kullanim_limiti) {
            return false;
        }

        return true;
    }

    // Kullanıcı için kupon geçerli mi?
    public function kullaniciIcinGecerliMi($userId, $sepetTutari = 0, $sepetUrunleri = [])
    {
        // Kupon aktif değilse
        if (!$this->isActive()) {
            return [
                'gecerli' => false,
                'mesaj' => 'Bu kupon geçerli değil veya süresi dolmuş.'
            ];
        }

        // Minimum tutar kontrolü
        if ($sepetTutari < $this->minimum_tutar) {
            return [
                'gecerli' => false,
                'mesaj' => 'Minimum ' . number_format($this->minimum_tutar, 2) . ' ₺ alışveriş yapmalısınız.'
            ];
        }

        // Kullanıcı daha önce kullanmış mı?
        if ($this->kullaniciKullandiMi($userId)) {
            return [
                'gecerli' => false,
                'mesaj' => 'Bu kuponu kullanım hakkınız dolmuştur.'
            ];
        }

        // Hedef kategori/ürün kontrolü
        if (!empty($sepetUrunleri)) {
            if (!$this->sepetUygunMu($sepetUrunleri)) {
                return [
                    'gecerli' => false,
                    'mesaj' => 'Bu kupon sepetinizdeki ürünler için geçerli değil.'
                ];
            }
        }

        return [
            'gecerli' => true,
            'mesaj' => 'Kupon başarıyla uygulandı!'
        ];
    }

    // Sepet ürünleri kupon için uygun mu?
    private function sepetUygunMu($sepetUrunleri)
    {
        // Hedef kategoriler varsa
        if (!empty($this->hedef_kategoriler)) {
            $uygunVarMi = false;
            foreach ($sepetUrunleri as $urun) {
                if (in_array($urun['kategori_id'] ?? null, $this->hedef_kategoriler)) {
                    $uygunVarMi = true;
                    break;
                }
            }
            if (!$uygunVarMi) {
                return false;
            }
        }

        // Hedef ürünler varsa
        if (!empty($this->hedef_urunler)) {
            $uygunVarMi = false;
            foreach ($sepetUrunleri as $urun) {
                if (in_array($urun['urun_id'] ?? null, $this->hedef_urunler)) {
                    $uygunVarMi = true;
                    break;
                }
            }
            if (!$uygunVarMi) {
                return false;
            }
        }

        // Hariç tutulan kategoriler
        if (!empty($this->hariç_kategoriler)) {
            foreach ($sepetUrunleri as $urun) {
                if (in_array($urun['kategori_id'] ?? null, $this->hariç_kategoriler)) {
                    return false;
                }
            }
        }

        // Hariç tutulan ürünler
        if (!empty($this->hariç_urunler)) {
            foreach ($sepetUrunleri as $urun) {
                if (in_array($urun['urun_id'] ?? null, $this->hariç_urunler)) {
                    return false;
                }
            }
        }

        return true;
    }

    // İndirim tutarını hesapla
    public function indirimiHesapla($sepetTutari)
    {
        if ($this->indirim_tipi === 'yuzde') {
            $indirim = ($sepetTutari * $this->indirim_miktari) / 100;
            
            // Maksimum indirim varsa uygula
            if ($this->maksimum_indirim && $indirim > $this->maksimum_indirim) {
                $indirim = $this->maksimum_indirim;
            }
            
            return $indirim;
        }

        // Sabit tutar indirimi
        return min($this->indirim_miktari, $sepetTutari);
    }

    // Scope: Aktif kuponlar
    public function scopeAktif($query)
    {
        return $query->where('aktif', true)
                    ->where('baslangic_tarihi', '<=', now())
                    ->where('bitis_tarihi', '>=', now());
    }

    // Scope: Kural bazlı kuponlar
    public function scopeKuralBazli($query)
    {
        return $query->where('kupon_turu', 'kural_bazli')
                    ->where('otomatik_ata', true);
    }
}