<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Kupon extends Model
{
    use HasFactory;

    protected $table = 'kuponlar';

    protected $fillable = [
        'kupon_kodu',
        'baslik',
        'aciklama', 
        'indirim_tipi',
        'indirim_miktari',
        'minimum_tutar',
        'kullanim_limiti',
        'kullanilan_adet',
        'baslangic_tarihi',
        'bitis_tarihi',
        'aktif',
        'kupon_turu',
        'kural_tipi',
        'kural_min_tutar',
        'kural_min_siparis',
        'kural_gun_araligi',
        'kural_hedefler',
        'otomatik_ata',
    ];

    protected $casts = [
        'aktif' => 'boolean',
        'otomatik_ata' => 'boolean',
        'kural_hedefler' => 'array',
        'baslangic_tarihi' => 'datetime',
        'bitis_tarihi' => 'datetime',
    ];

    // Kullanıcı-Kupon ilişkisi
    public function kullanicilar()
    {
        return $this->belongsToMany(User::class, 'kullanici_kuponlar')
                    ->withPivot(['kullanildi', 'kullanilma_tarihi', 'atanma_tarihi'])
                    ->withTimestamps();
    }

    /**
     * Kullanıcının bu kuponu kullanıp kullanmadığını kontrol et
     */
    public function kullanildiMi($userId)
    {
        return $this->kullanicilar()
                    ->wherePivot('user_id', $userId)
                    ->wherePivot('kullanildi', true)
                    ->exists();
    }

    /**
     * Kuponun kullanıcı için geçerli olup olmadığını kontrol et
     */
    public function kullaniciIcinGecerliMi($userId)
    {
        // Kupon aktif mi?
        if (!$this->aktif) {
            return ['gecerli' => false, 'mesaj' => 'Kupon aktif değil'];
        }

        // Tarih kontrolü
        if (now()->lt($this->baslangic_tarihi) || now()->gt($this->bitis_tarihi)) {
            return ['gecerli' => false, 'mesaj' => 'Kupon süresi geçmiş veya henüz başlamamış'];
        }

        // Kullanım limiti kontrolü (genel)
        if ($this->kullanim_limiti && $this->kullanilan_adet >= $this->kullanim_limiti) {
            return ['gecerli' => false, 'mesaj' => 'Kupon kullanım limiti doldu'];
        }

        // Kupon türüne göre kontrol
        switch ($this->kupon_turu) {
            case 'genel':
                // Genel kuponlar herkes için geçerli
                return ['gecerli' => true, 'mesaj' => 'Kupon kullanılabilir'];

            case 'kullanici_ozel':
                // Kullanıcıya özel atanmış mı?
                $atanmisMi = KullaniciKupon::where('user_id', $userId)
                    ->where('kupon_id', $this->id)
                    ->exists();

                if (!$atanmisMi) {
                    return ['gecerli' => false, 'mesaj' => 'Bu kupon size özel değil'];
                }

                // Daha önce kullanılmış mı?
                $kullanilmisMi = KullaniciKupon::where('user_id', $userId)
                    ->where('kupon_id', $this->id)
                    ->where('kullanildi', true)
                    ->exists();

                if ($kullanilmisMi) {
                    return ['gecerli' => false, 'mesaj' => 'Bu kuponu daha önce kullandınız'];
                }

                return ['gecerli' => true, 'mesaj' => 'Kupon kullanılabilir'];

            case 'kural_bazli':
                // Kural kontrolü
                return $this->kuralKontrol($userId);

            default:
                return ['gecerli' => false, 'mesaj' => 'Geçersiz kupon türü'];
        }
    }

    /**
     * Kural bazlı kupon kontrolü
     */
    private function kuralKontrol($userId)
    {
        $user = User::find($userId);
        if (!$user) {
            return ['gecerli' => false, 'mesaj' => 'Kullanıcı bulunamadı'];
        }

        $gunAraligi = $this->kural_gun_araligi ?? 30;
        $baslangicTarihi = Carbon::now()->subDays($gunAraligi);

        switch ($this->kural_tipi) {
            case 'toplam_alisveriş':
                // Son X gün içindeki toplam alışveriş tutarı
                $toplamAlisveriş = Siparis::where('user_id', $userId)
                    ->where('odeme_durumu', 'odendi')
                    ->where('created_at', '>=', $baslangicTarihi)
                    ->sum(DB::raw('toplam_tutar + kdv_tutari - indirim_tutari'));

                if ($toplamAlisveriş < $this->kural_min_tutar) {
                    return [
                        'gecerli' => false, 
                        'mesaj' => "Son {$gunAraligi} gün içinde en az ₺" . number_format($this->kural_min_tutar, 2) . " alışveriş yapmalısınız. Mevcut: ₺" . number_format($toplamAlisveriş, 2)
                    ];
                }
                break;

            case 'siparis_adedi':
                // Son X gün içindeki sipariş adedi
                $siparisAdedi = Siparis::where('user_id', $userId)
                    ->where('odeme_durumu', 'odendi')
                    ->where('created_at', '>=', $baslangicTarihi)
                    ->count();

                if ($siparisAdedi < $this->kural_min_siparis) {
                    return [
                        'gecerli' => false, 
                        'mesaj' => "Son {$gunAraligi} gün içinde en az {$this->kural_min_siparis} sipariş vermelisiniz. Mevcut: {$siparisAdedi}"
                    ];
                }
                break;

            case 'tek_siparis_tutari':
                // Tek bir siparişte minimum tutar (sepet kontrolünde yapılacak)
                // Bu kontrol sipariş tamamlama sırasında yapılacak
                return ['gecerli' => true, 'mesaj' => 'Sipariş tutarı kontrolü yapılacak'];

            case 'belirli_kategori':
                // Sepette belirli kategoriden ürün var mı kontrolü
                // Bu kontrol sipariş tamamlama sırasında yapılacak
                return ['gecerli' => true, 'mesaj' => 'Kategori kontrolü yapılacak'];

            case 'belirli_urun':
                // Sepette belirli ürün var mı kontrolü
                // Bu kontrol sipariş tamamlama sırasında yapılacak
                return ['gecerli' => true, 'mesaj' => 'Ürün kontrolü yapılacak'];

            default:
                return ['gecerli' => false, 'mesaj' => 'Geçersiz kural tipi'];
        }

        // Kullanıcıya özel atanmış mı kontrol et
        $atanmisMi = KullaniciKupon::where('user_id', $userId)
            ->where('kupon_id', $this->id)
            ->exists();

        if (!$atanmisMi) {
            return ['gecerli' => false, 'mesaj' => 'Bu kupon size özel değil'];
        }

        // Daha önce kullanılmış mı?
        $kullanilmisMi = KullaniciKupon::where('user_id', $userId)
            ->where('kupon_id', $this->id)
            ->where('kullanildi', true)
            ->exists();

        if ($kullanilmisMi) {
            return ['gecerli' => false, 'mesaj' => 'Bu kuponu daha önce kullandınız'];
        }

        return ['gecerli' => true, 'mesaj' => 'Kupon kullanılabilir'];
    }

    /**
     * Kullanıcıya kuponu ata
     */
    public function kullaniciyaAta($userId)
    {
        return KullaniciKupon::firstOrCreate([
            'user_id' => $userId,
            'kupon_id' => $this->id,
        ], [
            'kullanildi' => false,
            'atanma_tarihi' => now(),
        ]);
    }

    /**
     * Kuponu kullan
     */
    public function kullan($userId)
    {
        // Genel kullanım sayacını artır
        $this->increment('kullanilan_adet');

        // Kullanıcı özel ise pivot tabloyu güncelle
        if ($this->kupon_turu !== 'genel') {
            KullaniciKupon::where('user_id', $userId)
                ->where('kupon_id', $this->id)
                ->update([
                    'kullanildi' => true,
                    'kullanilma_tarihi' => now(),
                ]);
        }
    }
}