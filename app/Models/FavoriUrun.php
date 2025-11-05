<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FavoriUrun extends Model
{
    protected $table = 'favoriUrunler'; // tablonuzun adı
    
    // SORUN BURADAYDİ - ad_soyad alanı eksikti
    protected $fillable = [
        'user_id',
        'urun_id',
        'urun_ad',
        'ad_soyad'  // Bu alan eksikti, bu yüzden hatalar oluyordu
    ];

    // Kullanıcı ilişkisi
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Ürün ilişkisi
    public function urun()
    {
        return $this->belongsTo(Urun::class, 'urun_id');
    }
}