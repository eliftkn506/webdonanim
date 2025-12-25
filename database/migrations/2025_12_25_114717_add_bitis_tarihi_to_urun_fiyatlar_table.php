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
        Schema::table('urun_fiyatlar', function (Blueprint $table) {
            
            // 1. baslangic_tarihi kontrolü
            if (!Schema::hasColumn('urun_fiyatlar', 'baslangic_tarihi')) {
                // Sütun yoksa ekle
                $table->date('baslangic_tarihi')->nullable();
            } else {
                // Sütun varsa null olabilir yap (change)
                $table->date('baslangic_tarihi')->nullable()->change();
            }

            // 2. bitis_tarihi kontrolü
            if (!Schema::hasColumn('urun_fiyatlar', 'bitis_tarihi')) {
                // Sütun yoksa ekle
                $table->date('bitis_tarihi')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('urun_fiyatlar', function (Blueprint $table) {
            // Geri alırken sütunları sil
            if (Schema::hasColumn('urun_fiyatlar', 'bitis_tarihi')) {
                $table->dropColumn('bitis_tarihi');
            }
            // baslangic_tarihi'ni silmiyoruz çünkü belki önceden vardı,
            // ama projeye göre komple kaldırmak isterseniz burayı açabilirsiniz:
            // $table->dropColumn('baslangic_tarihi');
        });
    }
};