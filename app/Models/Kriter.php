<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsAdminActivity;

class Kriter extends Model
{
    protected $table = 'kriterler';
    protected $fillable = ['alt_kategori_id', 'kriter_ad'];

    // Kriter bir alt kategoriye ait
    public function altKategori()
    {
        return $this->belongsTo(AltKategori::class, 'alt_kategori_id');
    }

    // Kriterin değerleri
    public function degerler()
    {
        return $this->hasMany(KriterDeger::class, 'kriter_id');
    }

    // Kriterin ürün ilişkisi (pivot)
    public function urunler()
    {
        return $this->belongsToMany(Urun::class, 'urun_kriter_degerleri', 'kriter_id', 'urun_id')
                    ->withPivot('kriter_deger_id')
                    ->withTimestamps();
    }
}
