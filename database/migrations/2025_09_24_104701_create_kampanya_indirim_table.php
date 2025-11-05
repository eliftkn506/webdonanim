<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kampanya_indirim', function (Blueprint $table) {
            $table->id();
            $table->foreignId('urun_id')->constrained('urunler')->onDelete('cascade'); // Urun tablosuna bağlandı
            $table->string('kampanya_adi');
            $table->decimal('indirim_orani', 5, 2)->nullable(); // % indirim, örn: 10.50
            $table->decimal('yeni_fiyat', 10, 2)->nullable(); // İndirimli fiyat
            $table->date('baslangic_tarihi')->nullable();
            $table->date('bitis_tarihi')->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kampanya_indirim');
    }
};
