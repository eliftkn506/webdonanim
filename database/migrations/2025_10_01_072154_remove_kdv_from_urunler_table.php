<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('urunler', function (Blueprint $table) {
            $table->dropColumn('kdv');
        });
    }

    public function down()
    {
        Schema::table('urunler', function (Blueprint $table) {
            $table->decimal('kdv', 5, 2)->nullable(); // geri almak istersen
        });
    }
};
