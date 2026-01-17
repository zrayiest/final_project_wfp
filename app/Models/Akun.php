<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Akun extends Model
{
    protected $fillable = [
        'nama',
        'emas',
        'level',
        'level_senjata',
        'level_armor',
        'user_id',
        'suku_id',
        'waktu_update_emas'
    ];

    public function suku(): BelongsTo
    {
        return $this->belongsTo(Suku::class);
    }

    public static function getMusuhEligible()
    {
        $user = Auth::user();
        $hasil = self::where('jumlah_pasukan_tersedia', '>', 0)
            ->where('user_id', '<>', $user->id)
            ->get();
        $musuhs = array();
        foreach ($hasil as $itemHasil) {
            $musuhs[] = [
                'nama' => $itemHasil->nama,
                'suku' => $itemHasil->suku->nama
            ];
        }
        return $musuhs;
    }

    public function getTierInfo($levelItem)
    {
        if ($levelItem <= 20) {
            return [
                'nama' => 'Besi',
                'warna' => 'secondary',
                'icon' => '🪨'
            ];
        } elseif ($levelItem <= 40) {
            return [
                'nama' => 'Silver',
                'warna' => 'info',
                'icon' => '🥈'
            ];
        } elseif ($levelItem <= 80) {
            return [
                'nama' => 'Emas',
                'warna' => 'warning',
                'icon' => '👑'
            ];
        } else {
            return [
                'nama' => 'Diamond',
                'warna' => 'primary',
                'icon' => '💎'
            ];
        }
    }


    public function cekPrediksiLawan($musuh)
    {

        $tipe = 'melee';
        $baseAtk = $this->suku->serang_melee;

        if ($this->suku->nama == 'Marksman') {
            $tipe = 'range';
            $baseAtk = $this->suku->serang_range;
        } elseif ($this->suku->nama == 'Mage') {
            $tipe = 'magic';
            $baseAtk = $this->suku->serang_magic;
        }

        $heroAtk = $baseAtk + ($this->level * 2) + ($this->level_senjata * 5);
        $totalAtk = $heroAtk * $this->jumlah_pasukan_tersedia;

        $baseDef = 0;
        if ($tipe == 'range') $baseDef = $musuh->suku->tahan_range;
        elseif ($tipe == 'magic') $baseDef = $musuh->suku->tahan_magic;
        else $baseDef = $musuh->suku->tahan_melee;

        $heroDef = $baseDef + ($musuh->level * 2) + ($musuh->level_armor * 5);
        $totalDef = $heroDef * $musuh->jumlah_pasukan_tersedia;


        if ($totalAtk > ($totalDef * 1.5)) {
            return [
                'status' => 'MENANG MUDAH',
                'warna' => 'success', // Hijau
                'pesan' => "Tipe seranganmu ($tipe) adalah kelemahan fatal musuh ini! Sikat!"
            ];
        } elseif ($totalAtk > $totalDef) {
            return [
                'status' => 'MENANG TIPIS',
                'warna' => 'primary', // Biru
                'pesan' => "Kamu unggul sedikit. Kemungkinan menang, tapi pasukanmu akan banyak gugur."
            ];
        } elseif ($totalAtk > ($totalDef * 0.8)) {
            return [
                'status' => 'SERI / BERISIKO',
                'warna' => 'warning', // Kuning
                'pesan' => "Pertahanan ($tipe) musuh cukup keras. Bisa jadi seri atau kalah."
            ];
        } else {
            return [
                'status' => 'BAHAYA / KALAH',
                'warna' => 'danger', // Merah
                'pesan' => "Jangan bunuh diri! Armor musuh terlalu tebal untuk ditembus pasukanmu."
            ];
        }
    }
}
