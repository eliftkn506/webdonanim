<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    /**
     * Toplu atama (mass assignment) yapılabilecek alanlar.
     * Bu diziye eklemediğin alanlar veritabanına kaydedilirken hata verir.
     */
    protected $fillable = [
        'slug',
        'title',
        'content',
        'phone',
        'email',
        'address',
        'google_maps'
    ];
}