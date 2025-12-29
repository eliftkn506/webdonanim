<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('kuponlar', function (Blueprint $table) {
            // 1. 'kural_hedefler' sütununu 'kural_kosullari' olarak düzelt
            if (Schema::hasColumn('kuponlar', 'kural_hedefler') && !Schema::hasColumn('kuponlar', 'kural_kosullari')) {
                $table->renameColumn('kural_hedefler', 'kural_kosullari');
            }
            // Eğer hiç yoksa oluştur
            elseif (!Schema::hasColumn('kuponlar', 'kural_kosullari')) {
                $table->json('kural_kosullari')->nullable();
            }

            // 2. Türkçe karakterli 'hariç' sütunlarını 'haric' (İngilizce karakter) yap
            // Kategoriler için
            if (Schema::hasColumn('kuponlar', 'hariç_kategoriler') && !Schema::hasColumn('kuponlar', 'haric_kategoriler')) {
                $table->renameColumn('hariç_kategoriler', 'haric_kategoriler');
            } elseif (!Schema::hasColumn('kuponlar', 'haric_kategoriler')) {
                $table->json('haric_kategoriler')->nullable();
            }

            // Ürünler için
            if (Schema::hasColumn('kuponlar', 'hariç_urunler') && !Schema::hasColumn('kuponlar', 'haric_urunler')) {
                $table->renameColumn('hariç_urunler', 'haric_urunler');
            } elseif (!Schema::hasColumn('kuponlar', 'haric_urunler')) {
                $table->json('haric_urunler')->nullable();
            }

            // 3. Eksik olabilecek diğer sütunlar
            if (!Schema::hasColumn('kuponlar', 'aktif')) {
                $table->boolean('aktif')->default(true);
            }
            if (!Schema::hasColumn('kuponlar', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down()
    {
        // Geri alma işlemi (gerekirse)
    }
};