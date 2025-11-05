<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('urun_varyasyonlar', function (Blueprint $table) {
            // Ana ürün bilgilerini ekle (unique constraint olmadan)
            $table->string('urun_ad')->after('urun_id')->nullable();
            $table->string('marka')->after('urun_ad')->nullable();
            $table->string('model')->after('marka')->nullable();
            $table->text('aciklama')->after('model')->nullable();
            $table->string('resim_url')->after('aciklama')->nullable();
            $table->string('barkod_no', 100)->after('resim_url')->nullable();
            // Index ekle ama unique değil
            $table->index('barkod_no');
        });
    }

    public function down()
    {
        Schema::table('urun_varyasyonlar', function (Blueprint $table) {
            $table->dropIndex(['barkod_no']);
            $table->dropColumn([
                'urun_ad',
                'marka',
                'model',
                'aciklama',
                'resim_url',
                'barkod_no'
            ]);
        });
    }
};