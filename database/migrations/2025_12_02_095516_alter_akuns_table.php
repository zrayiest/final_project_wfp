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
        Schema::table('akuns', function (Blueprint $table) {
            $table->datetime('waktu_bangun_barak')->nullable();
            $table->datetime('waktu_bangun_pasukan')->nullable();
            $table->integer('antrian_pasukan_dibangun')->default(0);
            $table->integer('jumlah_pasukan_tersedia')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('akuns', function (Blueprint $table) {
            
            $table->dropColumn('waktu_bangun_barak');
            $table->dropColumn('waktu_bangun_pasukan');
            $table->dropColumn('antrian_pasukan_dibangun');
            $table->dropColumn('jumlah_pasukan_tersedia');
        });
    }
};
