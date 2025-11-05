<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Bayi extends Authenticatable
{
    use HasFactory;

    protected $table = 'bayiler';

    protected $fillable = [
        'firma_adi',
        'yetkili_ad',
        'yetkili_soyad',
        'email',
        'telefon',
        'adres',
        'vergi_no',
        'bayi_kodu',
        'sifre', // hashed
    ];

    protected $hidden = [
        'sifre',
    ];

    // Laravel default password field kullanmak için
    public function getAuthPassword()
    {
        return $this->sifre;
    }
}
