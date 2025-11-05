<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('urun_fiyatlar', function (Blueprint $table) {
            // Nullable yapıyoruz ki mevcut kayıtlar sorun yaratmasın
            $table->unsignedBigInteger('urun_id')->nullable()->after('fiyat_id');
            
            // Foreign key ekliyoruz
            $table->foreign('urun_id')
                  ->references('id')
                  ->on('urunler')
                  ->onDelete('no action');
        });
    }

    public function down()
    {
        Schema::table('urun_fiyatlar', function (Blueprint $table) {
            $table->dropForeign(['urun_id']);
            $table->dropColumn('urun_id');
        });
    }
};
