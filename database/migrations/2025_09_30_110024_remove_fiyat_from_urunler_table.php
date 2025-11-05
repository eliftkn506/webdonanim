<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('urunler', function (Blueprint $table) {
            $table->dropColumn('fiyat'); // urunler tablosundaki fiyat kolonunu kaldır
        });
    }

    public function down()
    {
        Schema::table('urunler', function (Blueprint $table) {
            $table->decimal('fiyat', 10, 2)->default(0); // rollback için fiyat kolonunu ekle
        });
    }
};
