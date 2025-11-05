<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('konfigürasyon_urunler', function (Blueprint $table) {
        $table->id(); 
        $table->unsignedBigInteger('konfigürasyon_id'); // hangi konfigürasyona ait
        $table->unsignedBigInteger('urun_id'); // ürün ID
        $table->integer('adet')->default(1); // ürün adedi
        $table->decimal('fiyat', 10, 2); // o anki fiyat
        $table->timestamps();

        $table->foreign('konfigürasyon_id')->references('id')->on('konfigürasyonlar')->onDelete('cascade');
        $table->foreign('urun_id')->references('id')->on('urunler')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
