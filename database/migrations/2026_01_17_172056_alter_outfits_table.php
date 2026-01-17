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
        Schema::table('outfits', function (Blueprint $table) {
            $table->integer('bonus_serangan')->default(0)->after('nama_outfit');
            $table->integer('bonus_pertahanan')->default(0)->after('bonus_serangan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('outfits', function (Blueprint $table) {
            $table->dropColumn(['bonus_serangan', 'bonus_pertahanan']);
        });
    }
};
