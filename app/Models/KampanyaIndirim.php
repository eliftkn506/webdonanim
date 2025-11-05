<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsAdminActivity;

class KampanyaIndirim extends Model
{
    use HasFactory;

    protected $table = 'kampanya_indirim';

    protected $fillable = [
        'urun_id',
        'kampanya_adi',
        'indirim_orani',
        'yeni_fiyat',
        'baslangic_tarihi',
        'bitis_tarihi',
        'aktif',
    ];

    // Kampanya hangi ürüne ait
    public function urun()
    {
        return $this->belongsTo(Urun::class, 'urun_id');
    }
}
