<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsAdminActivity;

class UyumlulukKurali extends Model
{
    use HasFactory;

    // Hangi tabloya bağlanacağını belirt
    protected $table = 'uyumluluk_kurallari';

    // Hangi alanların doldurulabilir olduğunu belirt
    protected $fillable = [
        'ana_kategori_id',
        'hedef_kategori_id',
        'ana_kriter_id',
        'hedef_kriter_id',
    ];

    // Eğer created_at ve updated_at otomatik kullanılacaksa, timestamp zaten true
    public $timestamps = true;

    // Ana kategori ilişkisi
    public function anaKategori()
    {
        return $this->belongsTo(Kategori::class, 'ana_kategori_id');
    }

    // Hedef kategori ilişkisi
    public function hedefKategori()
    {
        return $this->belongsTo(Kategori::class, 'hedef_kategori_id');
    }

    // Ana kriter ilişkisi
    public function anaKriter()
    {
        return $this->belongsTo(Kriter::class, 'ana_kriter_id');
    }

    // Hedef kriter ilişkisi
    public function hedefKriter()
    {
        return $this->belongsTo(Kriter::class, 'hedef_kriter_id');
    }
}
