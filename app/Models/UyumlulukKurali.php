<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UyumlulukKurali extends Model
{
    use HasFactory;

    protected $table = 'uyumluluk_kurallari';

    protected $fillable = [
        'ana_kategori_id',
        'hedef_kategori_id',
        'ana_kriter_id',
        'hedef_kriter_id',
    ];

    // DÜZELTME: Kategori yerine AltKategori modeline bağlanmalı
    public function anaKategori()
    {
        return $this->belongsTo(AltKategori::class, 'ana_kategori_id');
    }

    public function hedefKategori()
    {
        return $this->belongsTo(AltKategori::class, 'hedef_kategori_id');
    }

    public function anaKriter()
    {
        return $this->belongsTo(Kriter::class, 'ana_kriter_id');
    }

    public function hedefKriter()
    {
        return $this->belongsTo(Kriter::class, 'hedef_kriter_id');
    }
}