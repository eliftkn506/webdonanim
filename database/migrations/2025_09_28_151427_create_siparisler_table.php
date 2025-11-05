<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('siparisler', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('siparis_no', 50)->unique();
            $table->decimal('toplam_tutar', 10, 2)->default(0);
            $table->decimal('kdv_tutari', 10, 2)->default(0);
            $table->decimal('kargo_ucreti', 10, 2)->default(0);
            $table->decimal('indirim_tutari', 10, 2)->default(0);
            $table->string('kupon_kodu', 50)->nullable();
            $table->string('durum', 50)->default('beklemede');
            $table->string('odeme_tipi', 50)->nullable();
            $table->string('odeme_durumu', 50)->default('beklemede');
            $table->text('kargo_adresi')->nullable();
            $table->text('fatura_adresi')->nullable();
            $table->text('notlar')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('durum');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siparisler');
    }
};
