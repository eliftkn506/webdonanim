<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('sliders', function (Blueprint $table) {
        $table->id();
        $table->string('image'); // Slider resmi
        $table->string('badge_text')->nullable(); // Örn: "OYUN CANAVARI"
        $table->string('badge_color')->default('danger'); // Badge rengi (danger, primary vb.)
        $table->string('title')->nullable(); // Büyük başlık
        $table->text('description')->nullable(); // Alt açıklama yazısı
        $table->string('button_text')->nullable(); // Buton üzerindeki yazı
        $table->string('button_link')->nullable(); // Butonun gideceği adres
        $table->integer('order')->default(0); // Sıralama (Hangi slider önce çıksın)
        $table->boolean('status')->default(1); // 1: Aktif, 0: Pasif
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sliders');
    }
};
