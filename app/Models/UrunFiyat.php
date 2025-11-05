<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UrunFiyat extends Model
{
    use HasFactory;

    protected $table = 'urun_fiyatlar';
    protected $primaryKey = 'fiyat_id';

    protected $fillable = [
        'urun_id',
        'fiyat_turu',
        'maliyet',
        'kar_orani',
        'bayi_indirimi',
        'vergi_orani'
    ];

    public function urun()
    {
        return $this->belongsTo(Urun::class, 'urun_id', 'id');
    }
    // UrunFiyat.php
    public function urunler()
    {
        return $this->belongsToMany(Urun::class, 'urun_fiyat_urun', 'fiyat_id', 'urun_id')
                    ->withPivot('baslangic_tarihi', 'bitis_tarihi')
                    ->withTimestamps();
    }

}

