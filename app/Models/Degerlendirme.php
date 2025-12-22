<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Degerlendirme extends Model
{
    use HasFactory;

    protected $table = 'degerlendirmeler';

    protected $fillable = [
        'user_id',
        'urun_id',
        'puan',
        'yorum',
        'cevap',
        'onay',
    ];

    // İlişkiler
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function urun()
    {
        return $this->belongsTo(Urun::class, 'urun_id');
    }
}