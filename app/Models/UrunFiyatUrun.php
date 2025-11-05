<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UrunFiyatUrun extends Pivot
{
    protected $table = 'urun_fiyat_urun';

    protected $fillable = [
        'urun_id',
        'fiyat_id',
        'baslangic_tarihi',
        'bitis_tarihi',
    ];

    public $timestamps = true;
}
