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
        Schema::create('laporan_perangs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('penyerang_id');
            $table->unsignedBigInteger('musuh_id');
            $table->unsignedBigInteger('pemenang_id');

            $table->integer('emas_jarahan')->default(0);
            $table->integer('pasukan_mati_penyerang')->default(0);
            $table->integer('pasukan_mati_musuh')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_perangs');
    }
};
