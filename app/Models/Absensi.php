<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $primaryKey = 'absensi_id';

    protected $fillable = [
        'siswa_id',
        'jadwal_id',
        'tanggal',
        'status',
        'foto_bukti',
        'status_kirim',
        'wali_validasi',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'status_kirim' => 'boolean',
            'wali_validasi' => 'boolean',
        ];
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id', 'siswa_id');
    }

    public function jadwal(): BelongsTo
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_id', 'jadwal_id');
    }
}
