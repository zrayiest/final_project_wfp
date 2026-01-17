<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanPerang extends Model
{
    protected $guarded = [];


    public function penyerang()
    {
        return $this->belongsTo(Akun::class, 'penyerang_id');
    }


    public function musuh()
    {
        return $this->belongsTo(Akun::class, 'musuh_id');
    }
}
