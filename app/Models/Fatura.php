<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fatura extends Model
{
    use HasFactory;

    protected $table = 'faturalar';

    protected $fillable = [
        'siparis_id',
        'fatura_no',
        'unvan',
        'vergi_dairesi',
        'vergi_no',
        'tc_kimlik_no',
        'fatura_adresi',
        'ara_toplam',
        'kdv_tutari',
        'genel_toplam',
        'fatura_tipi',
        'e_fatura_gonderildi',
        'e_fatura_tarih',
    ];

    protected $casts = [
        'e_fatura_gonderildi' => 'boolean',
        'e_fatura_tarih' => 'datetime',
    ];

    // Sipariş ile ilişki
    public function siparis()
    {
        return $this->belongsTo(Siparis::class, 'siparis_id');
    }
}
