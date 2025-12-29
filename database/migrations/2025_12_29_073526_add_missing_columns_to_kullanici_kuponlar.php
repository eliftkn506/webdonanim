<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kullanici_kuponlar', function (Blueprint $table) {
            
            // Kullanım sayısı (Kaç kere kullandı?)
            if (!Schema::hasColumn('kullanici_kuponlar', 'kullanim_sayisi')) {
                $table->integer('kullanim_sayisi')->default(0);
            }

            // Toplam kazanç (Bu kupondan ne kadar indirim aldı?) - Hata logunda bu da istenmiş
            if (!Schema::hasColumn('kullanici_kuponlar', 'toplam_kazanc')) {
                $table->decimal('toplam_kazanc', 10, 2)->default(0);
            }

            // Son kullanım tarihi
            if (!Schema::hasColumn('kullanici_kuponlar', 'son_kullanim_tarihi')) {
                $table->timestamp('son_kullanim_tarihi')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('kullanici_kuponlar', function (Blueprint $table) {
            // Geri alma işlemi
            if (Schema::hasColumn('kullanici_kuponlar', 'kullanim_sayisi')) {
                $table->dropColumn('kullanim_sayisi');
            }
            if (Schema::hasColumn('kullanici_kuponlar', 'toplam_kazanc')) {
                $table->dropColumn('toplam_kazanc');
            }
            if (Schema::hasColumn('kullanici_kuponlar', 'son_kullanim_tarihi')) {
                $table->dropColumn('son_kullanim_tarihi');
            }
        });
    }
};