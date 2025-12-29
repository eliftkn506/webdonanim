<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('kuponlar', function (Blueprint $table) {
            
            // Kontrollü Ekleme: Sütun yoksa ekle
            if (!Schema::hasColumn('kuponlar', 'kural_tipi')) {
                $table->string('kural_tipi')->nullable()->after('kupon_turu');
            }

            if (!Schema::hasColumn('kuponlar', 'kural_min_tutar')) {
                $table->decimal('kural_min_tutar', 10, 2)->nullable()->after('kural_tipi');
            }

            if (!Schema::hasColumn('kuponlar', 'kural_min_siparis')) {
                $table->integer('kural_min_siparis')->nullable()->after('kural_min_tutar');
            }

            if (!Schema::hasColumn('kuponlar', 'kural_gun_araligi')) {
                $table->integer('kural_gun_araligi')->nullable()->after('kural_min_siparis');
            }

            if (!Schema::hasColumn('kuponlar', 'kural_hedefler')) {
                $table->json('kural_hedefler')->nullable()->after('kural_gun_araligi');
            }

            if (!Schema::hasColumn('kuponlar', 'maksimum_indirim')) {
                $table->decimal('maksimum_indirim', 10, 2)->nullable()->after('indirim_miktari');
            }
        });
    }

    public function down()
    {
        Schema::table('kuponlar', function (Blueprint $table) {
            $columns = [
                'kural_tipi',
                'kural_min_tutar',
                'kural_min_siparis',
                'kural_gun_araligi',
                'kural_hedefler',
                'maksimum_indirim'
            ];

            // Sadece var olanları sil
            foreach ($columns as $column) {
                if (Schema::hasColumn('kuponlar', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};