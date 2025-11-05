<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('odeme_bilgileri', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('siparis_id');
            $table->string('odeme_tipi');
            $table->string('kart_son_dort_hanesi')->nullable();
            $table->string('kart_tipi')->nullable();
            $table->string('banka_adi')->nullable();
            $table->string('transaction_id')->nullable();
            $table->decimal('odenen_tutar', 10, 2);
            $table->string('para_birimi', 3)->default('TRY');
            $table->enum('durum', ['basarili', 'beklemede', 'basarisiz', 'iade'])->default('beklemede');
            $table->text('hata_mesaji')->nullable();
            $table->json('gateway_response')->nullable();
            $table->timestamps();

            $table->foreign('siparis_id')->references('id')->on('siparisler')->onDelete('cascade');
            $table->index(['siparis_id', 'durum']);
            $table->index('transaction_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('odeme_bilgileri');
    }
};
