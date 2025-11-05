<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsAdminActivity;

class UrunFiyatGecmisi extends Model
{
    use HasFactory;

    protected $table = 'urun_fiyat_gecmisi';

    protected $fillable = [
        'urun_id',
        'eski_fiyat',
        'yeni_fiyat',
        'degisiklik_sebebi',
        'degistiren_user_id',
    ];

    public function urun()
    {
        return $this->belongsTo(Urun::class, 'urun_id');
    }

    public function degistirenUser()
    {
        return $this->belongsTo(User::class, 'degistiren_user_id');
    }
}
