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
        Schema::create('faturalar', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('siparis_id');
            $table->string('fatura_no')->unique();
            $table->string('unvan');
            $table->string('vergi_dairesi')->nullable();
            $table->string('vergi_no')->nullable();
            $table->string('tc_kimlik_no', 11)->nullable();
            $table->text('fatura_adresi');
            $table->decimal('ara_toplam', 10, 2);
            $table->decimal('kdv_tutari', 10, 2);
            $table->decimal('genel_toplam', 10, 2);
            $table->enum('fatura_tipi', ['bireysel', 'kurumsal'])->default('bireysel');
            $table->boolean('e_fatura_gonderildi')->default(false);
            $table->timestamp('e_fatura_tarih')->nullable();
            $table->timestamps();

            $table->foreign('siparis_id')->references('id')->on('siparisler')->onDelete('cascade');
            $table->index('fatura_no');
            $table->index('siparis_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('faturalar');
    }
};
