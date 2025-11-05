<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsAdminActivity;

class Siparis extends Model
{
    use HasFactory;

    protected $table = 'siparisler';

    protected $fillable = [
        'user_id',
        'siparis_no',
        'toplam_tutar',
        'kdv_tutari',
        'kargo_ucreti',
        'indirim_tutari',
        'kupon_kodu',
        'durum',
        'odeme_tipi',
        'odeme_durumu',
        'kargo_adresi',
        'fatura_adresi',
        'notlar',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function urunler()
    {
        return $this->hasMany(SiparisUrunu::class, 'siparis_id');
    }
}
