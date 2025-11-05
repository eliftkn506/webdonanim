<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('favori_urunler', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('urun_id');
            $table->string('urun_ad');
            $table->timestamps();

            // ilişkiler
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('urun_id')->references('id')->on('urunler')->onDelete('cascade');

            // aynı kullanıcı aynı ürünü birden fazla eklemesin
            $table->unique(['user_id', 'urun_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favori_urunler');
    }
};
