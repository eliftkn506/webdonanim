<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsAdminActivity;

class UyumluUrun extends Model
{
    use HasFactory;

    protected $table = 'uyumlu_urunler';

    protected $fillable = ['urun_id', 'uyumlu_urun_id'];

    public $timestamps = false;

    // İlişkiler
    public function urun()
    {
        return $this->belongsTo(Urun::class, 'urun_id');
    }

    public function uyumluUrun()
    {
        return $this->belongsTo(Urun::class, 'uyumlu_urun_id');
    }
}
