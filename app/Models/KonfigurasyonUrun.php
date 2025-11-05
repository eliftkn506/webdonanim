<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsAdminActivity;

class KonfigurasyonUrun extends Model
{
    use HasFactory;

    protected $table = 'konfigürasyon_urunler'; // tablo adı

    protected $fillable = [
        'konfigürasyon_id',
        'urun_id',
        'adet',
        'fiyat',
    ];

    // Ürün ile ilişki
    public function urun()
    {
        return $this->belongsTo(Urun::class, 'urun_id');
    }

    // Konfigürasyon ile ilişki
    public function konfigürasyon()
    {
        return $this->belongsTo(Konfigurasyon::class, 'konfigürasyon_id');
    }
}
