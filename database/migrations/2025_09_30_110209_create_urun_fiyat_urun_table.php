<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('urun_fiyat_urun', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('urun_id');
            $table->unsignedBigInteger('fiyat_id');
            $table->dateTime('baslangic_tarihi')->nullable();
            $table->dateTime('bitis_tarihi')->nullable();
            $table->timestamps();

            // Foreign keyler
            $table->foreign('urun_id')->references('id')->on('urunler')->onDelete('cascade');
            $table->foreign('fiyat_id')->references('fiyat_id')->on('urun_fiyatlar')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('urun_fiyat_urun');
    }
};
