<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Suku;

class SukuAttributesSeeder extends Seeder
{
    public function run()
    {
        Suku::where('nama', 'Marksman')->update([
            'serang_melee' => 20,
            'serang_range' => 90,
            'serang_magic' => 20,
            'tahan_melee'  => 30,
            'tahan_range'  => 30,
            'tahan_magic'  => 30,
        ]);

        Suku::where('nama', 'Tank')->update([
            'serang_melee' => 30,
            'serang_range' => 20,
            'serang_magic' => 20,
            'tahan_melee'  => 90,
            'tahan_range'  => 90,
            'tahan_magic'  => 85,
        ]);


        Suku::where('nama', 'Mage')->update([
            'serang_melee' => 15,
            'serang_range' => 20,
            'serang_magic' => 95,
            'tahan_melee'  => 20,
            'tahan_range'  => 20,
            'tahan_magic'  => 40,
        ]);


        Suku::where('nama', 'Warrior')->update([
            'serang_melee' => 85,
            'serang_range' => 15,
            'serang_magic' => 10,
            'tahan_melee'  => 80,
            'tahan_range'  => 30,
            'tahan_magic'  => 25,
        ]);
    }
}
