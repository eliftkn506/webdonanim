<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UrunVaryasyon extends Model
{
    use HasFactory;

    protected $table = 'urun_varyasyonlar';

    protected $fillable = [
        'urun_id',
        'stok',
        'marka',
        'model',
        'aciklama',
        'resim_url',
        'bakod_no',
    ];

    // Varyasyon hangi ürüne ait
    public function urun()
    {
        return $this->belongsTo(Urun::class, 'urun_id');
    }

    // Varyasyonun kriter değerleri (pivot tablo ile)
    public function kriterDegerleri()
    {
        return $this->belongsToMany(
            KriterDeger::class,
            'urun_varyasyon_kriter_degerleri',
            'urun_varyasyon_id',
            'kriter_deger_id'
        )->withPivot('kriter_id')
         ->withTimestamps();
    }
}
