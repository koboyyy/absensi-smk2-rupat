<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

        'is_selesai',

    ];

    /**
     * CAST
     */
    protected $casts = [

        'is_selesai' => 'boolean',

    ];

    /**
     * APPEND
     */
    protected $appends = [

        'status_pelajaran',

        'status_label',

        'status_color',

        'status_icon',

        'is_berlangsung',

    ];

    /**
     * =====================================
     * RELASI
     * =====================================
     */

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(
            Kelas::class,
            'kelas_id',
            'kelas_id'
        );
    }

    public function guru(): BelongsTo
    {
        return $this->belongsTo(
            Guru::class,
            'guru_id',
            'guru_id'
        );
    }

    public function mapel(): BelongsTo
    {
        return $this->belongsTo(
            Mapel::class,
            'mapel_id',
            'mapel_id'
        );
    }

    public function absensis(): HasMany
    {
        return $this->hasMany(
            Absensi::class,
            'jadwal_id',
            'jadwal_id'
        );
    }

    public function suratIzins(): HasMany
    {
        return $this->hasMany(
            SuratIzin::class,
            'jadwal_id',
            'jadwal_id'
        );
    }

    /**
     * =====================================
     * ACCESSOR STATUS PELAJARAN
     * =====================================
     */

    public function getStatusPelajaranAttribute()
    {
        /**
         * JIKA SUDAH DISELESAIKAN MANUAL
         */
        if ($this->is_selesai) {

            return 'selesai';
        }

        /**
         * WAKTU SEKARANG
         */
        $currentTime = Carbon::now();

        /**
         * FORMAT JAM
         */
        $jamMulai = Carbon::createFromFormat(
            'H:i',
            substr(
                $this->jam_mulai,
                0,
                5
            )
        );

        $jamSelesai = Carbon::createFromFormat(
            'H:i',
            substr(
                $this->jam_selesai,
                0,
                5
            )
        );

        /**
         * DEBUG
         */
        /*
        dd([

            'current_time' =>
                $currentTime->format('H:i:s'),

            'jam_mulai' =>
                $jamMulai->format('H:i:s'),

            'jam_selesai' =>
                $jamSelesai->format('H:i:s'),

            'lt_mulai' =>
                $currentTime->lt($jamMulai),

            'between' =>
                $currentTime->between(
                    $jamMulai,
                    $jamSelesai
                ),

            'gt_selesai' =>
                $currentTime->gt($jamSelesai),

        ]);
        */

        /**
         * BELUM MULAI
         */
        if (
            $currentTime->lt(
                $jamMulai
            )
        ) {

            return 'belum_mulai';
        }

        /**
         * BERLANGSUNG
         */
        if (

            $currentTime->between(
                $jamMulai,
                $jamSelesai
            )

        ) {

            return 'berlangsung';
        }

        /**
         * SELESAI
         */
        return 'selesai';
    }

    /**
     * =====================================
     * ACCESSOR STATUS LABEL
     * =====================================
     */

    public function getStatusLabelAttribute()
    {
        return match (
        $this->status_pelajaran
        ) {

            'belum_mulai' =>
            'Belum Mulai',

            'berlangsung' =>
            'Sedang Berlangsung',

            default =>
            'Selesai',

        };
    }

    /**
     * =====================================
     * ACCESSOR STATUS COLOR
     * =====================================
     */

    public function getStatusColorAttribute()
    {
        return match (
        $this->status_pelajaran
        ) {

            'belum_mulai' =>

            'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300',

            'berlangsung' =>

            'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300',

            default =>

            'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-300',

        };
    }

    /**
     * =====================================
     * ACCESSOR STATUS ICON
     * =====================================
     */

    public function getStatusIconAttribute()
    {
        return match (
        $this->status_pelajaran
        ) {

            'belum_mulai' =>
            'fa-clock',

            'berlangsung' =>
            'fa-play',

            default =>
            'fa-check',

        };
    }

    /**
     * =====================================
     * ACCESSOR BUTTON AKTIF
     * =====================================
     */

    public function getIsBerlangsungAttribute()
    {
        return
            $this->status_pelajaran
            === 'berlangsung';
    }
}