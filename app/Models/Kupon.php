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
        'indirim_miktari' => 'decimal:2',
        'minimum_tutar' => 'decimal:2',
        'kural_min_tutar' => 'decimal:2',
    ];

    public function kullanicilar()
    {
        return $this->belongsToMany(User::class, 'kullanici_kuponlar')
                    ->withPivot(['kullanildi', 'kullanilma_tarihi', 'atanma_tarihi'])
                    ->withTimestamps();
    }

    /**
     * Kullanıcıya kupon ata
     */
    public function kullaniciyaAta($userId)
    {
        // Zaten atanmışsa tekrar atama
        $exists = DB::table('kullanici_kuponlar')
            ->where('user_id', $userId)
            ->where('kupon_id', $this->id)
            ->exists();
            
        if ($exists) {
            return false;
        }

        return DB::table('kullanici_kuponlar')->insert([
            'user_id' => $userId,
            'kupon_id' => $this->id,
            'kullanildi' => 0,
            'kullanilma_tarihi' => null,
            'atanma_tarihi' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Kuponu kullan - hem genel kuponlar hem de kullanıcıya özel kuponlar için
     */
    public function kullan($userId)
    {
        // Kullanım sayısını artır
        $this->increment('kullanilan_adet');
        
        // Eğer kupon genel değilse (kullanıcıya özel veya kural bazlı), kullanıcı-kupon ilişkisini güncelle
        if ($this->kupon_turu !== 'genel') {
            DB::table('kullanici_kuponlar')
                ->where('user_id', $userId)
                ->where('kupon_id', $this->id)
                ->update([
                    'kullanildi' => 1,
                    'kullanilma_tarihi' => now(),
                    'updated_at' => now()
                ]);
        }
    }

    /**
     * Kullanıcının bu kuponu kullanıp kullanmadığını kontrol et
     */
    public function kullaniciKullandiMi($userId)
    {
        if ($this->kupon_turu === 'genel') {
            // Genel kuponlar için kullanım limiti kontrolü
            if ($this->kullanim_limiti && $this->kullanilan_adet >= $this->kullanim_limiti) {
                return true;
            }
            return false;
        }

        // Özel ve kural bazlı kuponlar için kullanıcı-kupon ilişkisini kontrol et
        $kullanim = DB::table('kullanici_kuponlar')
            ->where('user_id', $userId)
            ->where('kupon_id', $this->id)
            ->first();

        if (!$kullanim) {
            return true; // Kullanıcıya atanmamış
        }

        return $kullanim->kullanildi == 1;
    }

    /**
     * Kuponun aktif olup olmadığını kontrol et
     */
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

    /**
     * Kullanıcı için kupon kullanılabilir mi?
     */
    public function kullaniciIcinGecerliMi($userId, $sepetTutari = 0)
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
                'mesaj' => 'Bu kuponu daha önce kullandınız veya size atanmamış.'
            ];
        }

        return [
            'gecerli' => true,
            'mesaj' => 'Kupon geçerli!'
        ];
    }
}