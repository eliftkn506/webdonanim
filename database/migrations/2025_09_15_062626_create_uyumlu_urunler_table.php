<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('uyumlu_urunler', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('urun_id');
            $table->unsignedBigInteger('uyumlu_urun_id');
            $table->timestamps();

            // Foreign keyler NO ACTION ile
            $table->foreign('urun_id')
                  ->references('id')->on('urunler')
                  ->onDelete('NO ACTION')
                  ->onUpdate('NO ACTION');

            $table->foreign('uyumlu_urun_id')
                  ->references('id')->on('urunler')
                  ->onDelete('NO ACTION')
                  ->onUpdate('NO ACTION');

            // Aynı ilişki tekrarını engelle
            $table->unique(['urun_id','uyumlu_urun_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('uyumlu_urunler');
    }
};
