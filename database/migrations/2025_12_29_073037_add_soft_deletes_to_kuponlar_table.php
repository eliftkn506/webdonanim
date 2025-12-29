<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('kuponlar', function (Blueprint $table) {
        $table->softDeletes(); // Bu satır deleted_at sütununu ekler
    });
}

public function down(): void
{
    Schema::table('kuponlar', function (Blueprint $table) {
        $table->dropSoftDeletes();
    });
}
};
