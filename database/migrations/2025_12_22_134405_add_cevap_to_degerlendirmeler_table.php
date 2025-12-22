<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
{
    Schema::table('degerlendirmeler', function (Blueprint $table) {
        $table->text('cevap')->nullable()->after('yorum'); // Cevap sütunu eklendi
    });
}

public function down()
{
    Schema::table('degerlendirmeler', function (Blueprint $table) {
        $table->dropColumn('cevap');
    });
}
};
