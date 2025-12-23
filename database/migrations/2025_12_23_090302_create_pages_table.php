<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('pages', function (Blueprint $table) {
        $table->id();
        $table->string('slug')->unique(); // 'hakkimizda' veya 'iletisim'
        $table->string('title');
        $table->text('content')->nullable(); // Uzun metinler için
        // İletişim sayfası için ekstra alanlar (Sadece iletisim slug'ında kullanılacak)
        $table->string('phone')->nullable();
        $table->string('email')->nullable();
        $table->text('address')->nullable();
        $table->text('google_maps')->nullable(); // iframe kodu için
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
