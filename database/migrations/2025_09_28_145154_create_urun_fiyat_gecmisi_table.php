<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('urun_fiyat_gecmisi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('urun_id')->constrained('urunler')->onDelete('cascade');
            $table->decimal('eski_fiyat', 10, 2);
            $table->decimal('yeni_fiyat', 10, 2);
            $table->string('degisiklik_sebebi')->nullable();
            $table->foreignId('degistiren_user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('urun_fiyat_gecmisi');
    }
};
