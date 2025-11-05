<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('favoriurunler', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');   // Kullanıcı ID
            $table->string('ad_soyad');              // Kullanıcı adı soyadı
            $table->unsignedBigInteger('urun_id');  // Ürün ID
            $table->string('urun_ad');              // Ürün adı
            $table->timestamps();

            // İlişkiler (foreign key)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('urun_id')->references('id')->on('urunler')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favoriurunler');
    }
};
