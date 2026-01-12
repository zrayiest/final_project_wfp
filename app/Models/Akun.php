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
        'user_id',
        'suku_id',
        'waktu_update_emas'
    ];

    public function suku(): BelongsTo
    {
        return $this->belongsTo(Suku::class);
    }

    public static function getMusuhEligible() {
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
}
