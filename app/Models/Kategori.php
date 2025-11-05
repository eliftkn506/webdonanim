<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsAdminActivity;

class Kategori extends Model
{
    protected $table = 'kategoriler';
    protected $fillable = ['kategori_ad'];

    // Bir kategori birden fazla alt kategoriye sahip olabilir
    public function altKategoriler()
    {
        return $this->hasMany(AltKategori::class, 'kategori_id');
    }
}
