<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    protected $primaryKey = 'mapel_id';

    protected $fillable = [
        'nama_mapel',
        'kode_mapel',
    ];

    public function jadwals(): HasMany
    {
        return $this->hasMany(Jadwal::class, 'mapel_id', 'mapel_id');
    }
}
