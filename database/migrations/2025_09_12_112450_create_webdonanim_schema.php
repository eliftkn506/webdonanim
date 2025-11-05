<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Kategoriler
        Schema::create('kategoriler', function (Blueprint $table) {
            $table->id();
            $table->string('kategori_ad')->unique();
            $table->timestamps();
        });

        // Alt Kategoriler
        Schema::create('alt_kategoriler', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('kategoriler')->onDelete('cascade');
            $table->string('alt_kategori_ad');
            $table->timestamps();
        });

        // Kriterler
        Schema::create('kriterler', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alt_kategori_id')->constrained('alt_kategoriler')->onDelete('cascade');
            $table->string('kriter_ad');
            $table->timestamps();
        });

        // Kriter Değerleri
        Schema::create('kriter_degerleri', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kriter_id')->constrained('kriterler')->onDelete('cascade');
            $table->foreignId('alt_kategori_id')->constrained('alt_kategoriler')->onDelete('no action');
            $table->string('deger');
            $table->timestamps();
        });

        // Ürünler
        Schema::create('urunler', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alt_kategori_id')->constrained('alt_kategoriler')->onDelete('cascade');
            $table->string('urun_ad');
            $table->string('marka');
            $table->string('model');
            $table->decimal('fiyat', 10, 2);
            $table->text('aciklama')->nullable();
            $table->timestamps();
        });

        // Ürün-Kriter Değerleri (pivot tablo)
        Schema::create('urun_kriter_degerleri', function (Blueprint $table) {
            $table->id();
            $table->foreignId('urun_id')->constrained('urunler')->onDelete('cascade');

            // Burada cascade kaldırdık çünkü SQL Server aynı anda iki cascade path'e izin vermiyor
            $table->foreignId('kriter_id')->constrained('kriterler')->onDelete('no action');
            $table->foreignId('kriter_deger_id')->constrained('kriter_degerleri')->onDelete('no action');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('urun_kriter_degerleri');
        Schema::dropIfExists('urunler');
        Schema::dropIfExists('kriter_degerleri');
        Schema::dropIfExists('kriterler');
        Schema::dropIfExists('alt_kategoriler');
        Schema::dropIfExists('kategoriler');
    }
};
