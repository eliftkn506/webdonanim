<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('degerlendirmeler', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Yorumu yapan kullanıcı
            $table->foreignId('urun_id')->constrained('urunler')->onDelete('cascade'); // Hangi ürüne yapıldığı
            $table->integer('puan'); // 1-5 arası yıldız
            $table->text('yorum')->nullable(); // Yorum metni
            $table->boolean('onay')->default(false); // Admin onayı (varsayılan: onaylanmamış)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('degerlendirmeler');
    }
};