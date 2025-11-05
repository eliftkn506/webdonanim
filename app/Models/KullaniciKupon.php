<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KullaniciKupon extends Model
{
    use HasFactory;

    protected $table = 'kullanici_kuponlar';

    protected $fillable = [
        'user_id',
        'kupon_id',
        'kullanildi',
        'kullanilma_tarihi',
        'atanma_tarihi',
    ];

    protected $casts = [
        'kullanildi' => 'boolean',
        'kullanilma_tarihi' => 'datetime',
        'atanma_tarihi' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kupon()
    {
        return $this->belongsTo(Kupon::class);
    }
}