<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suku extends Model
{
    use HasFactory;
    protected $table = 'sukus';
    protected $fillable =
    [
        'nama',
        'serang_melee',
        'serang_range',
        'serang_magic',
        'tahan_melee',
        'tahan_range',
        'tahan_magic'
    ];
    public function outfits()
    {
        return $this->hasMany(Outfit::class);
    }
}
