<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Outfit extends Model
{
    use HasFactory;

    protected $table = 'outfits';

    protected $fillable = [
        'suku_id',
        'bagian',
        'nama_outfit',
        'bonus_serangan',
        'bonus_pertahanan'
    ];

    public function suku()
    {
        return $this->belongsTo(Suku::class);
    }
}
