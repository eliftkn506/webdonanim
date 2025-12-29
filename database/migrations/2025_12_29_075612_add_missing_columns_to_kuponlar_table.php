<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // kuponlar tablosuna eksik sütunları ekle
        Schema::table('kuponlar', function (Blueprint $table) {
            if (!Schema::hasColumn('kuponlar', 'kullanici_basina_limit')) {
                $table->integer('kullanici_basina_limit')->default(1)->after('kullanim_limiti');
            }
            
            if (!Schema::hasColumn('kuponlar', 'son_atama_tarihi')) {
                $table->timestamp('son_atama_tarihi')->nullable()->after('otomatik_ata');
            }
            
            if (!Schema::hasColumn('kuponlar', 'hedef_kategoriler')) {
                $table->text('hedef_kategoriler')->nullable()->after('kural_kosullari');
            }
            
            if (!Schema::hasColumn('kuponlar', 'hedef_urunler')) {
                $table->text('hedef_urunler')->nullable()->after('hedef_kategoriler');
            }
            
            if (!Schema::hasColumn('kuponlar', 'toplam_indirim_tutari')) {
                $table->decimal('toplam_indirim_tutari', 12, 2)->default(0)->after('kullanilan_adet');
            }
            
            if (!Schema::hasColumn('kuponlar', 'toplam_kullanan_kisi')) {
                $table->integer('toplam_kullanan_kisi')->default(0)->after('toplam_indirim_tutari');
            }
        });
    }

    public function down()
    {
        Schema::table('kuponlar', function (Blueprint $table) {
            $table->dropColumn([
                'kullanici_basina_limit',
                'son_atama_tarihi',
                'hedef_kategoriler',
                'hedef_urunler',
                'toplam_indirim_tutari',
                'toplam_kullanan_kisi'
            ]);
        });
    }
};