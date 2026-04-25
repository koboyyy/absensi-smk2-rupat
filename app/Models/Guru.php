<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    protected $primaryKey = 'guru_id';

    protected $fillable = [
        'user_id',
        'nama_guru',
        'nip',
        'jenis_kelamin',
        'no_hp',
        'alamat',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function jadwals(): HasMany
    {
        return $this->hasMany(Jadwal::class, 'guru_id', 'guru_id');
    }

    public function waliKelas(): HasOne
    {
        return $this->hasOne(WaliKelas::class, 'guru_id', 'guru_id');
    }
}
