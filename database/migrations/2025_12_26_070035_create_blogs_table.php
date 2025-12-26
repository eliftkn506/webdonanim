<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('baslik');
            $table->string('slug')->unique(); // Link yapısı için
            $table->text('ozet')->nullable(); // Kartlarda görünecek kısa yazı
            $table->longText('icerik'); // Detaylı içerik
            $table->string('resim')->nullable();
            $table->string('yazar')->default('Admin');
            $table->boolean('aktif')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('blogs');
    }
};