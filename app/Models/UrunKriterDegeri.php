<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Traits\LogsAdminActivity;

class UrunKriterDegeri extends Pivot
{
    protected $table = 'urun_kriter_degerleri';
    protected $fillable = ['urun_id', 'kriter_id', 'kriter_deger_id'];
    public $timestamps = false;

    // İlişkiler
    public function kriter()
    {
        return $this->belongsTo(Kriter::class, 'kriter_id');
    }

    public function kriterDeger()
    {
        return $this->belongsTo(KriterDeger::class, 'kriter_deger_id');
    }

}
