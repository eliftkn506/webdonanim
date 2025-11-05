<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('urun_varyasyonlar', function (Blueprint $table) {
            if (Schema::hasColumn('urun_varyasyonlar', 'fiyat')) {
                $table->dropColumn('fiyat');
            }
            if (Schema::hasColumn('urun_varyasyonlar', 'kdv')) {
                $table->dropColumn('kdv');
            }
        });
    }

    public function down(): void
    {
        Schema::table('urun_varyasyonlar', function (Blueprint $table) {
            $table->decimal('fiyat', 10, 2)->nullable();
            $table->decimal('kdv', 5, 2)->default(20);
        });
    }
};
