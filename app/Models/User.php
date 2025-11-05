<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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

    /* İLİŞKİLER */
    
    public function favoriler()
    {
        return $this->hasMany(FavoriUrun::class, 'user_id');
    }

    /* YARDIMCI METODLAR */
    
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

    /**
 * Kullanıcının siparişleri
 */
public function siparisler()
{
    return $this->hasMany(Siparis::class, 'user_id');
}

/**
 * Kullanıcının kuponları
 */
public function kuponlar()
{
    return $this->belongsToMany(Kupon::class, 'kullanici_kuponlar')
                ->withPivot(['kullanildi', 'kullanilma_tarihi', 'atanma_tarihi'])
                ->withTimestamps();
}

/**
 * Kullanıcının kullanılmamış kuponları
 */
public function kullanimliKuponlar()
{
    return $this->belongsToMany(Kupon::class, 'kullanici_kuponlar')
                ->wherePivot('kullanildi', false)
                ->where('aktif', true)
                ->where('baslangic_tarihi', '<=', now())
                ->where('bitis_tarihi', '>=', now())
                ->withPivot(['atanma_tarihi'])
                ->withTimestamps();
}
}