<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('uyumluluk_kurallari', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ana_kategori_id');
            $table->unsignedBigInteger('hedef_kategori_id');
            $table->unsignedBigInteger('ana_kriter_id');
            $table->unsignedBigInteger('hedef_kriter_id');
            $table->timestamps();

            // Foreign keyler NO ACTION ile
            $table->foreign('ana_kategori_id')
                ->references('id')->on('kategoriler')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('hedef_kategori_id')
                ->references('id')->on('kategoriler')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('ana_kriter_id')
                ->references('id')->on('kriterler')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('hedef_kriter_id')
                ->references('id')->on('kriterler')
                ->onDelete('no action')
                ->onUpdate('no action');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('uyumluluk_kurallari');
    }
};
