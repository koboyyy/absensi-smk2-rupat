<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $primaryKey = 'siswa_id';

    protected $fillable = [
        'nis',
        'nama_siswa',
        'jenis_kelamin',
        'alamat',
        'kelas_id',
        'ortu_id',
        'foto',
    ];

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id', 'kelas_id');
    }

    public function orangTua(): BelongsTo
    {
        return $this->belongsTo(OrangTua::class, 'ortu_id', 'ortu_id');
    }

    public function absensis(): HasMany
    {
        return $this->hasMany(Absensi::class, 'siswa_id', 'siswa_id');
    }

    public function suratIzins(): HasMany
    {
        return $this->hasMany(SuratIzin::class, 'siswa_id', 'siswa_id');
    }
}
