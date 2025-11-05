<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UrunVaryasyonKriterDegeri extends Model
{
    use HasFactory;

    protected $table = 'urun_varyasyon_kriter_degerleri';

    protected $fillable = [
        'urun_varyasyon_id',
        'kriter_id',
        'kriter_deger_id',
    ];

    /**
     * Varyasyon kriter değerinin ait olduğu varyasyon
     */
    public function varyasyon()
    {
        return $this->belongsTo(UrunVaryasyon::class, 'urun_varyasyon_id');
    }

    /**
     * İlişkili kriter
     */
    public function kriter()
    {
        return $this->belongsTo(Kriter::class, 'kriter_id');
    }

    /**
     * İlişkili kriter değeri
     */
    public function kriterDeger()
    {
        return $this->belongsTo(KriterDeger::class, 'kriter_deger_id');
    }
}