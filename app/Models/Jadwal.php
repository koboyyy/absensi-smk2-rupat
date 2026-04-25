<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $primaryKey = 'jadwal_id';

    protected $fillable = [
        'kelas_id',
        'guru_id',
        'mapel_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
    ];

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id', 'kelas_id');
    }

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'guru_id', 'guru_id');
    }

    public function mapel(): BelongsTo
    {
        return $this->belongsTo(Mapel::class, 'mapel_id', 'mapel_id');
    }

    public function absensis(): HasMany
    {
        return $this->hasMany(Absensi::class, 'jadwal_id', 'jadwal_id');
    }

    public function suratIzins(): HasMany
    {
        return $this->hasMany(SuratIzin::class, 'jadwal_id', 'jadwal_id');
    }
}
