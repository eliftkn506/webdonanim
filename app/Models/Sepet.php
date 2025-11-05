<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsAdminActivity;

class Sepet extends Model
{
    use HasFactory;

    // SQL Server’daki doğru tablo adı
    protected $table = 'sepet'; // veya 'sepetler' tablo adın neyse onu yaz

    protected $fillable = ['user_id','urun_id','adet'];

    public function urun(){
        return $this->belongsTo(Urun::class, 'urun_id');
    }
}
