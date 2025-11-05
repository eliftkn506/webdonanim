<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('urun_fiyatlar', function (Blueprint $table) {
            $table->id('fiyat_id');
            $table->enum('fiyat_turu', ['perakende', 'bayi', 'toptan', 'kampanya']);
            $table->decimal('maliyet', 10, 2);
            $table->decimal('kar_orani', 5, 2)->default(0);
            $table->decimal('bayi_indirimi', 5, 2)->default(0);
            $table->decimal('vergi_orani', 5, 2)->default(0);
            $table->timestamps(); // olusturma ve guncelleme tarihleri
        });
    }

    public function down()
    {
        Schema::dropIfExists('urun_fiyatlar');
    }
};
