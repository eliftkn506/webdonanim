<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Kupon extends Model
{
    use HasFactory;

    protected $table = 'kuponlar';

    protected $fillable = [
        'kupon_kodu',
        'baslik',
        'aciklama', 
        'indirim_tipi',
        'indirim_miktari',
        'minimum_tutar',
        'kullanim_limiti',
        'kullanilan_adet',
        'baslangic_tarihi',
        'bitis_tarihi',
        'aktif',
        'kupon_turu',
        'kural_tipi',
        'kural_min_tutar',
        'kural_min_siparis',
        'kural_gun_araligi',
        'kural_hedefler',
        'otomatik_ata',
    ];

    protected $casts = [
        'aktif' => 'boolean',
        'otomatik_ata' => 'boolean',
        'kural_hedefler' => 'array',
        'baslangic_tarihi' => 'datetime',
        'bitis_tarihi' => 'datetime',
        'indirim_miktari' => 'decimal:2',
        'minimum_tutar' => 'decimal:2',
        'kural_min_tutar' => 'decimal:2',
    ];

    public function kullanicilar()
    {
        return $this->belongsToMany(User::class, 'kullanici_kuponlar')
                    ->withPivot(['kullanildi', 'kullanilma_tarihi', 'atanma_tarihi'])
                    ->withTimestamps();
    }

    public function kullaniciyaAta($userId)
    {
        // SQL Server için updateOrInsert kullanımı daha güvenlidir
        return DB::table('kullanici_kuponlar')->updateOrInsert(
            ['user_id' => $userId, 'kupon_id' => $this->id],
            [
                'kullanildi' => 0, 
                'atanma_tarihi' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]
        );
    }

    public function kullan($userId)
    {
        $this->increment('kullanilan_adet');
        if ($this->kupon_turu !== 'genel') {
            DB::table('kullanici_kuponlar')
                ->where('user_id', $userId)
                ->where('kupon_id', $this->id)
                ->update([
                    'kullanildi' => 1,
                    'kullanilma_tarihi' => now(),
                ]);
        }
    }
}