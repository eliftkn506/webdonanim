<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsAdminActivity;

class SiparisUrunu extends Model
{
    use HasFactory;

    protected $table = 'siparis_urunleri';

    protected $fillable = [
        'siparis_id',
        'urun_id',
        'adet',
        'birim_fiyat',
        'toplam_fiyat',
        'kdv_orani',
        'kdv_tutari',
        'indirim_orani',
        'indirim_tutari',
    ];

    public function siparis()
    {
        return $this->belongsTo(Siparis::class, 'siparis_id');
    }

    public function urun()
    {
        return $this->belongsTo(Urun::class, 'urun_id');
    }
}
