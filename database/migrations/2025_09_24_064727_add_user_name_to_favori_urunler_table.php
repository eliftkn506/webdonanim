<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::table('favori_urunler', function (Blueprint $table) {
        $table->string('user_name')->after('user_id'); // kullanıcı adını ekledik
    });
}

public function down(): void
{
    Schema::table('favori_urunler', function (Blueprint $table) {
        $table->dropColumn('user_name');
    });
}

};
