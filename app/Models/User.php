<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

// Modeller
use App\Models\FavoriUrun;
use App\Models\Siparis;
use App\Models\Kupon;
use App\Models\KuponKullanimi;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /* -------------------------------------------------------------------------- */
    /* İLİŞKİLER                                 */
    /* -------------------------------------------------------------------------- */
    
    /**
     * Kullanıcının favori ürünleri
     */
    public function favoriler()
    {
        return $this->hasMany(FavoriUrun::class, 'user_id');
    }

    /**
     * Kullanıcının siparişleri
     */
    public function siparisler()
    {
        return $this->hasMany(Siparis::class);
    }

    /**
     * Kullanıcının sahip olduğu tüm kuponlar (Pivot tablosu üzerinden)
     */
    public function kuponlar()
    {
        return $this->belongsToMany(Kupon::class, 'kullanici_kuponlar')
                    ->withPivot(['kullanildi', 'kullanim_sayisi', 'kullanilma_tarihi', 'atanma_tarihi'])
                    ->withTimestamps();
    }

    /**
     * Kullanıcının kupon kullanım geçmişi (Log tablosu)
     */
    public function kuponKullanimlari()
    {
        return $this->hasMany(KuponKullanim::class);
    }

    /**
     * Kullanıcının aktif ve kullanılmamış kuponlarını getiren yardımcı ilişki
     */
    public function kullanimliKuponlar()
    {
        return $this->belongsToMany(Kupon::class, 'kullanici_kuponlar')
                    ->wherePivot('kullanildi', false)
                    ->where('aktif', true)
                    ->where('baslangic_tarihi', '<=', now())
                    ->where('bitis_tarihi', '>=', now())
                    ->withPivot(['atanma_tarihi', 'kullanim_sayisi'])
                    ->withTimestamps();
    }

    /* -------------------------------------------------------------------------- */
    /* YARDIMCI METODLAR                             */
    /* -------------------------------------------------------------------------- */
    
    /**
     * Kullanıcının bayi olup olmadığını kontrol et
     */
    public function isBayi(): bool
    {
        return $this->role === 'bayi';
    }

    /**
     * Kullanıcının admin olup olmadığını kontrol et
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Kullanıcının göreceği fiyat türünü belirle
     */
    public function getFiyatTuru(): string
    {
        return $this->isBayi() ? 'bayi' : 'standart';
    }
}