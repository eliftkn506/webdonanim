<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BayiBasvuru extends Model
{
    use HasFactory;

    protected $table = 'bayi_basvurular';

    protected $fillable = [
        'firma_adi',
        'yetkili_ad',
        'yetkili_soyad',
        'email',
        'telefon',
        'adres',
        'vergi_no',
        'durum',
        'user_id', // Oluşturulan kullanıcı ID'si
    ];

    protected $attributes = [
        'durum' => 'beklemede',
    ];

    // Durum sabitleri
    const DURUM_BEKLEMEDE = 'beklemede';
    const DURUM_ONAYLANDI = 'onaylandi';
    const DURUM_REDDEDILDI = 'reddedildi';

    /**
     * Başvuruya bağlı kullanıcı
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}