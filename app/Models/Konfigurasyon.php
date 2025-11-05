<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsAdminActivity;

class Konfigurasyon extends Model
{
    use HasFactory;

    protected $table = 'konfigürasyonlar'; // tablo adı

    protected $fillable = [
        'kullanici_id',
        'isim',
    ];

    // Kullanıcı ile ilişki
    public function kullanici()
    {
        return $this->belongsTo(User::class, 'kullanici_id');
    }

    // Konfigürasyona ait ürünler
    public function urunler()
    {
        return $this->hasMany(KonfigurasyonUrun::class, 'konfigürasyon_id');
    }
    protected static function booted()
{
    static::deleting(function ($konfig) {
        // Konfigürasyon silinirken bağlı ürünleri de sil
        $konfig->urunler()->delete();
    });
}

}
