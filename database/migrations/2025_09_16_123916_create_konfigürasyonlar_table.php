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
    Schema::create('konfigürasyonlar', function (Blueprint $table) {
        $table->id(); // id
        $table->unsignedBigInteger('kullanici_id'); // hangi kullanıcıya ait
        $table->string('isim'); // konfigürasyon ismi
        $table->timestamps();

        $table->foreign('kullanici_id')->references('id')->on('users')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
