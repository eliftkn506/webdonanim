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
    Schema::table('kuponlar', function (Blueprint $table) {
        // Eğer tablonuzda 'kullanim_limiti' yoksa ->after('...') kısmını silebilirsiniz.
        $table->integer('kullanici_basina_limit')->default(1)->after('kullanim_limiti');
    });
}

public function down()
{
    Schema::table('kuponlar', function (Blueprint $table) {
        $table->dropColumn('kullanici_basina_limit');
    });
}
};
