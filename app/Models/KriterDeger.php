<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsAdminActivity;

class KriterDeger extends Model
{
    protected $table = 'kriter_degerleri';
    protected $fillable = ['kriter_id', 'alt_kategori_id', 'deger'];

    // Kriter değeri bir kritere ait
    public function kriter()
    {
        return $this->belongsTo(Kriter::class, 'kriter_id');
    }

    // Kriter değeri bir alt kategoriye ait
    public function altKategori()
    {
        return $this->belongsTo(AltKategori::class, 'alt_kategori_id');
    }

    // Kriter değeri ürünlerle pivot
    public function urunler()
    {
        return $this->belongsToMany(Urun::class, 'urun_kriter_degerleri', 'kriter_deger_id', 'urun_id')
                    ->withPivot('kriter_id')
                    ->withTimestamps();
    }
}
