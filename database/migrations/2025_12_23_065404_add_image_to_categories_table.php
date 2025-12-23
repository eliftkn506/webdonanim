<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    // 'categories' yerine 'kategoriler' yazıyoruz
    Schema::table('kategoriler', function (Blueprint $table) {
        $table->string('image')->nullable()->after('name'); // 'name' alanı yoksa 'ad' olabilir, kontrol et.
    });
}

public function down()
{
    Schema::table('kategoriler', function (Blueprint $table) {
        $table->dropColumn('image');
    });
}
};