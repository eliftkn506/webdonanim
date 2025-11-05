<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bayiler', function (Blueprint $table) {
            $table->id();
            $table->string('firma_adi');
            $table->string('yetkili_ad');
            $table->string('yetkili_soyad');
            $table->string('email')->unique();
            $table->string('telefon');
            $table->string('adres')->nullable();
            $table->string('vergi_no')->nullable();
            $table->string('bayi_kodu')->unique(); // admin onayı sonrası üretilecek
            $table->string('sifre'); // hashlenmiş parola
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bayiler');
    }
};
