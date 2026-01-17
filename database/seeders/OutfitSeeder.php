<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OutfitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $parts = ['kepala' => 'Helm', 'badan' => 'Zirah', 'tangan' => 'Senjata', 'kaki' => 'Sepatu'];

        $sukus = \App\Models\Suku::all();

        foreach ($sukus as $suku) {
            $suku->outfits()->delete();

            foreach ($parts as $bagian => $nama) {
                \App\Models\Outfit::create([
                    'suku_id' => $suku->id,
                    'bagian' => $bagian,
                    'nama_outfit' => $nama,
                    'bonus_serangan' => ($bagian == 'tangan') ? 10 : 0,
                    'bonus_pertahanan' => ($bagian != 'tangan') ? 5 : 0,
                ]);
            }
        }
    }
}
