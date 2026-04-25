<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'kelas';
    protected $primaryKey = 'kelas_id';

    protected $fillable = [
        'nama_kelas',
        'tingkat',
        'jurusan',
    ];

    public function siswas(): HasMany
    {
        return $this->hasMany(Siswa::class, 'kelas_id', 'kelas_id');
    }

    public function jadwals(): HasMany
    {
        return $this->hasMany(Jadwal::class, 'kelas_id', 'kelas_id');
    }

    public function waliKelas(): HasOne
    {
        return $this->hasOne(WaliKelas::class, 'kelas_id', 'kelas_id');
    }

    public function rekapKelases(): HasMany
    {
        return $this->hasMany(RekapKelas::class, 'kelas_id', 'kelas_id');
    }
}
