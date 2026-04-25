<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class OrangTua extends Model
{
    protected $primaryKey = 'ortu_id';

    protected $fillable = [
        'user_id',
        'nama_ortu',
        'no_hp',
        'alamat',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function siswas(): HasMany
    {
        return $this->hasMany(Siswa::class, 'ortu_id', 'ortu_id');
    }

    public function suratIzins(): HasMany
    {
        return $this->hasMany(SuratIzin::class, 'ortu_id', 'ortu_id');
    }
}
