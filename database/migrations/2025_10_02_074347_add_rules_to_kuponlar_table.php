<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
{
    Schema::table('kuponlar', function (Blueprint $table) {
        $table->enum('kupon_turu', ['genel', 'kullanici_ozel', 'kural_bazli'])->default('genel')->after('kupon_kodu');
        $table->enum('kural_tipi', ['toplam_alisveriş','siparis_adedi','tek_siparis_tutari','belirli_kategori','belirli_urun'])->nullable()->after('kupon_turu');
        $table->decimal('kural_min_tutar', 10, 2)->nullable()->after('kural_tipi');
        $table->integer('kural_min_siparis')->nullable()->after('kural_min_tutar');
        $table->integer('kural_gun_araligi')->nullable()->after('kural_min_siparis')->comment('Son X gün içinde');
        $table->json('kural_hedefler')->nullable()->after('kural_gun_araligi');
        $table->boolean('otomatik_ata')->default(false)->after('kural_hedefler');
    });

    Schema::create('kullanici_kuponlar', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('kupon_id')->constrained('kuponlar')->onDelete('cascade');
        $table->boolean('kullanildi')->default(false);
        $table->timestamp('kullanilma_tarihi')->nullable();
        $table->timestamp('atanma_tarihi')->useCurrent();
        $table->timestamps();
        $table->unique(['user_id', 'kupon_id']);
    });
}

public function down()
{
    Schema::dropIfExists('kullanici_kuponlar');
    Schema::table('kuponlar', function (Blueprint $table) {
        $table->dropColumn([
            'kupon_turu',
            'kural_tipi',
            'kural_min_tutar',
            'kural_min_siparis',
            'kural_gun_araligi',
            'kural_hedefler',
            'otomatik_ata'
        ]);
    });
}
};
