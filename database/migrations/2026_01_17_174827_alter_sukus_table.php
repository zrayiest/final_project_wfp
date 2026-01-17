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
        Schema::table('sukus', function (Blueprint $table) {
            $table->integer('serang_melee')->default(10);
            $table->integer('serang_range')->default(10);
            $table->integer('serang_magic')->default(10);

            $table->integer('tahan_melee')->default(10);
            $table->integer('tahan_range')->default(10);
            $table->integer('tahan_magic')->default(10);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sukus', function (Blueprint $table) {
            $table->dropColumn([
                'serang_melee',
                'serang_range',
                'serang_magic',
                'tahan_melee',
                'tahan_range',
                'tahan_magic'
            ]);
        });
    }
};
