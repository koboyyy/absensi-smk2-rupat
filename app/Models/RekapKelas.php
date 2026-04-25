<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class RekapKelas extends Model
{
    protected $table = 'rekap_kelas';
    protected $primaryKey = 'rekap_kelas_id';

    protected $fillable = [
        'kelas_id',
        'bulan',
        'tahun',
        'hadir',
        'izin',
        'sakit',
        'alfa',
    ];

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id', 'kelas_id');
    }
}
