<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('urun_varyasyon_kriter_degerleri', function (Blueprint $table) {
            $table->id();
            $table->foreignId('urun_varyasyon_id')->constrained('urun_varyasyonlar')->onDelete('no action');
            $table->foreignId('kriter_id')->constrained('kriterler')->onDelete('cascade');
            $table->foreignId('kriter_deger_id')->constrained('kriter_degerleri')->onDelete('no action');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('urun_varyasyon_kriter_degerleri');
    }
};
