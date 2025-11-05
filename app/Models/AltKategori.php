<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AltKategori extends Model
{
    protected $table = 'alt_kategoriler';
    protected $fillable = ['kategori_id', 'alt_kategori_ad'];

    // Alt kategori bir kategoriye ait
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    // Alt kategoriye bağlı kriterler
    public function kriterler()
    {
        return $this->hasMany(Kriter::class, 'alt_kategori_id');
    }

    // Alt kategoriye bağlı kriter değerleri
    public function kriterDegerleri()
    {
        return $this->hasMany(KriterDeger::class, 'alt_kategori_id');
    }

    // Alt kategoriye ait ürünler
    public function urunler()
    {
        return $this->hasMany(Urun::class, 'alt_kategori_id');
    }
}
