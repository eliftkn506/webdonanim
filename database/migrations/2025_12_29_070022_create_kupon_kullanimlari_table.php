<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kupon_kullanimlari', function (Blueprint $table) {
            $table->id();

            // Kupon silinirse loglar da silinsin (Burada sorun yok)
            $table->foreignId('kupon_id')
                  ->constrained('kuponlar')
                  ->cascadeOnDelete();

            // User silinirse, SQL Server hatasını önlemek için 'no action' yapıyoruz.
            // Kullanıcı silinmeden önce bu logları manuel silmeniz gerekebilir veya hata verir.
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('no action'); 

            // Sipariş silinirse, döngüye girmemesi için 'no action' yapıyoruz.
            $table->foreignId('siparis_id')
                  ->constrained('siparisler')
                  ->onDelete('no action');

            $table->decimal('siparis_tutari', 10, 2);
            $table->decimal('indirim_tutari', 10, 2);

            $table->string('ip_adresi', 45)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kupon_kullanimlari');
    }
};