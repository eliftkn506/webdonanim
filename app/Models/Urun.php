<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\UrunKriterDegeri;
use App\Traits\LogsAdminActivity;
use App\Models\User;


class Urun extends Model
{
    use HasFactory;

    protected $table = 'urunler';
    
    protected $fillable = [
        'alt_kategori_id',  
        'urun_ad',
        'marka',
        'model',
        'aciklama',
        'resim_url',
        'barkod_no',
        'stok',
    ];

    /* ======================
     |  İLİŞKİLER
     ====================== */

    // Ürün bir alt kategoriye ait
    public function altKategori()
    {
        return $this->belongsTo(AltKategori::class, 'alt_kategori_id');
    }

    // Ürünün kriter değerleri (pivot tablo ile)
    public function kriterDegerleri()
    {
        return $this->belongsToMany(
            KriterDeger::class,
            'urun_kriter_degerleri',
            'urun_id',
            'kriter_deger_id'
        )->withPivot('kriter_id')
         ->withTimestamps();
    }

    // Ürünün varyasyonları (fiyat ve stok bilgisi buradan alınacak)
    public function varyasyonlar()
    {
        return $this->hasMany(UrunVaryasyon::class, 'urun_id');
    }

    // Ürünün uyumlu ürünleri (benim ürünümden uyumlu olanlar)
    public function uyumluUrunler()
    {
        return $this->hasMany(UyumluUrun::class, 'urun_id');
    }

    // Ürünün başka ürünler tarafından uyumlu olarak gösterilmesi
    public function uyumluOldugumUrunler()
    {
        return $this->hasMany(UyumluUrun::class, 'uyumlu_urun_id');
    }

    // Kolayca tüm uyumlu ürünleri çekmek için helper
    public function tumUyumluUrunler()
    {
        return $this->belongsToMany(
            Urun::class,
            'uyumlu_urunler',
            'urun_id',
            'uyumlu_urun_id'
        )->withTimestamps();
    }

    public function urunKriterDegerleri()
    {
        return $this->hasMany(UrunKriterDegeri::class, 'urun_id', 'id')->with('kriter', 'kriterDeger');
    }

    // Bir ürünün kampanyaları / indirimleri
    public function kampanyalar()
    {
        return $this->hasMany(KampanyaIndirim::class, 'urun_id');
    }

    // Ürünü favorilere ekleyen kullanıcılar
    public function favoriler()
    {
        return $this->hasMany(FavoriUrun::class, 'urun_id');
    }

    // Ürünün fiyatları (pivot tablo ile)
    public function fiyatlar()
    {
        return $this->belongsToMany(
            UrunFiyat::class, 
            'urun_fiyat_urun', 
            'urun_id',
            'fiyat_id',
            'id',
            'fiyat_id'
        )
        ->withPivot('baslangic_tarihi', 'bitis_tarihi')
        ->withTimestamps();
    }

    /* ======================
     |  FİYAT METODLARI
     ====================== */

    /**
     * Aktif fiyatı getir (genel)
     */
    public function aktifFiyat()
    {
        return $this->fiyatlar()
                    ->wherePivot('baslangic_tarihi', '<=', now())
                    ->where(function($query) {
                        $query->whereNull('urun_fiyat_urun.bitis_tarihi')
                              ->orWhere('urun_fiyat_urun.bitis_tarihi', '>=', now());
                    })
                    ->latest('urun_fiyat_urun.baslangic_tarihi')
                    ->first();
    }

    /**
     * Belirli fiyat türüne göre aktif fiyat getir
     */
    public function aktifFiyatByTur($fiyatTuru = 'standart')
    {
        return $this->fiyatlar()
                    ->where('fiyat_turu', $fiyatTuru)
                    ->wherePivot('baslangic_tarihi', '<=', now())
                    ->where(function($query) {
                        $query->whereNull('urun_fiyat_urun.bitis_tarihi')
                              ->orWhere('urun_fiyat_urun.bitis_tarihi', '>=', now());
                    })
                    ->latest('urun_fiyat_urun.baslangic_tarihi')
                    ->first();
    }

    /**
     * Kullanıcıya göre ürün fiyatını getir
     * @param User|null $user
     * @return float|null
     */
    // Urun modelinde
public function getFiyatForUser($user = null): ?float
{
    // 1) Eğer parametre verilmemişse, oturumdaki kullanıcıyı al
    if (is_null($user)) {
        $user = auth()->user();
    }
    // 2) Eğer numeric bir id verilmişse, User modelinden çek (opsiyonel)
    elseif (is_numeric($user)) {
        $user = \App\Models\User::find($user);
    }
    // 3) artık $user ya User nesnesi ya da null

    // Güvenli çağrı: null-safe operator ile kontrol et (PHP 8+)
    $isBayi = ($user?->isBayi()) ?? false; // $user null ise false olur

    $fiyatTuru = $isBayi ? 'bayi' : 'standart';

    // İlgili fiyatı getir
    $fiyat = $this->aktifFiyatByTur($fiyatTuru);

    // Bayi fiyatı yoksa standart fiyatı kullan
    if (!$fiyat && $fiyatTuru === 'bayi') {
        $fiyat = $this->aktifFiyatByTur('standart');
    }

    if (!$fiyat) {
        return null;
    }

    return $this->hesaplaFiyat($fiyat);
}


    /**
     * Standart fiyatı getir
     * @return float|null
     */
    public function getStandartFiyat(): ?float
    {
        $fiyat = $this->aktifFiyatByTur('standart');
        return $fiyat ? $this->hesaplaFiyat($fiyat) : null;
    }

    /**
     * Bayi fiyatını getir
     * @return float|null
     */
    public function getBayiFiyat(): ?float
    {
        $fiyat = $this->aktifFiyatByTur('bayi');
        return $fiyat ? $this->hesaplaFiyat($fiyat) : null;
    }

    /**
     * Kampanya fiyatını getir
     * @return float|null
     */
    public function getKampanyaFiyat(): ?float
    {
        $fiyat = $this->aktifFiyatByTur('kampanya');
        return $fiyat ? $this->hesaplaFiyat($fiyat) : null;
    }

    /**
     * Fiyat hesaplama (maliyet + kar + vergi)
     * @param UrunFiyat $fiyat
     * @return float
     */
    private function hesaplaFiyat($fiyat): float
    {
        $maliyet = $fiyat->maliyet;
        $karOrani = $fiyat->kar_orani;
        $bayiIndirimi = $fiyat->bayi_indirimi ?? 0;
        $vergiOrani = $fiyat->vergi_orani;

        // Temel fiyat = Maliyet + Kar
        $temelFiyat = $maliyet + ($maliyet * $karOrani / 100);
        
        // Vergi dahil fiyat
        $vergiDahilFiyat = $temelFiyat + ($temelFiyat * $vergiOrani / 100);

        // Bayi fiyatı ise indirim uygula
        if ($fiyat->fiyat_turu === 'bayi' && $bayiIndirimi > 0) {
            return round($vergiDahilFiyat - ($vergiDahilFiyat * $bayiIndirimi / 100), 2);
        }

        return round($vergiDahilFiyat, 2);
    }

    /**
     * Hesaplanmış satış fiyatı (Attribute - geriye uyumluluk için)
     * Kullanıcıya göre fiyat döner
     */
    public function getSatisFiyatiAttribute()
    {
        return $this->getFiyatForUser();
    }

    /**
     * İndirim yüzdesini hesapla (bayi için)
     * @return float|null
     */
    public function getIndirimYuzdesi(): ?float
    {
        $standartFiyat = $this->getStandartFiyat();
        $bayiFiyat = $this->getBayiFiyat();

        if (!$standartFiyat || !$bayiFiyat || $standartFiyat <= $bayiFiyat) {
            return null;
        }

        return round((($standartFiyat - $bayiFiyat) / $standartFiyat) * 100, 2);
    }

    /**
     * Kullanıcının bu ürünü favorilere ekleyip eklemediğini kontrol et
     * @param User|null $user
     * @return bool
     */
    public function isFavoriByUser($user = null): bool
    {
        $user = $user ?? auth()->user();
        
        if (!$user) {
            return false;
        }

        return $this->favoriler()->where('user_id', $user->id)->exists();
    }

    /**
     * Ürünün stokta olup olmadığını kontrol et
     * @return bool
     */
    public function hasStock(): bool
    {
        return $this->stok > 0;
    }
}