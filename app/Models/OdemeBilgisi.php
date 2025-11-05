<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OdemeBilgisi extends Model
{
    use HasFactory;

    protected $table = 'odeme_bilgileri';

    protected $fillable = [
        'siparis_id',
        'odeme_tipi',
        'kart_son_dort_hanesi',
        'kart_tipi',
        'banka_adi',
        'transaction_id',
        'odenen_tutar',
        'para_birimi',
        'durum',
        'hata_mesaji',
        'gateway_response',
    ];

    protected $casts = [
        'odenen_tutar' => 'decimal:2',
        'gateway_response' => 'array',
    ];

    // Sipariş ile ilişki
    public function siparis()
    {
        return $this->belongsTo(Siparis::class, 'siparis_id');
    }
}
