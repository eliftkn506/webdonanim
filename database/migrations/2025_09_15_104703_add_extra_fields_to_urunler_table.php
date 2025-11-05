<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('urunler', function (Blueprint $table) {
            $table->string('resim_url')->nullable()->after('aciklama'); 
            
            // önce nullable + index olmadan ekliyoruz
            $table->string('barkod_no')->nullable()->after('resim_url'); 
            
            $table->integer('stok')->default(0)->after('barkod_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('urunler', function (Blueprint $table) {
            $table->dropColumn(['resim_url', 'barkod_no', 'stok']);
        });
    }
};
