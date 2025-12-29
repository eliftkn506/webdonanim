<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KuponKullanim extends Model
{
    use HasFactory;

    protected $table = 'kupon_kullanimlari';

    protected $fillable = [
        'kupon_id',
        'user_id',
        'siparis_id',
        'siparis_tutari',
        'indirim_tutari',
        'ip_adresi',
    ];

    protected $casts = [
        'siparis_tutari' => 'decimal:2',
        'indirim_tutari' => 'decimal:2',
    ];

    // İlişkiler
    public function kupon()
    {
        return $this->belongsTo(Kupon::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function siparis()
    {
        return $this->belongsTo(Siparis::class);
    }
}