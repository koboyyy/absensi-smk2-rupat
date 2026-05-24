<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Guru;
use App\Models\SuratIzin;
use App\Models\Jadwal;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        /**
         * USER LOGIN
         */
        $user = Auth::user();

        /**
         * BELUM LOGIN
         */
        if (!$user) {

            return redirect()
                ->route('login');
        }

        /**
         * ROLE AKTIF
         */
        $activeRole = session(
            'active_role'
        );

        /**
         * BELUM PILIH ROLE
         */
        if (!$activeRole) {

            // JIKA HANYA 1 ROLE
            if (
                count($user->roles ?? []) === 1
            ) {

                $activeRole =
                    $user->roles[0];

                session([
                    'active_role' =>
                        $activeRole
                ]);

            } else {

                return redirect()
                    ->route('role.select');
            }
        }

        /**
         * VALIDASI ROLE
         */
        if (
            !in_array(
                $activeRole,
                $user->roles ?? []
            )
        ) {

            session()->forget(
                'active_role'
            );

            return redirect()
                ->route('role.select');
        }

        /**
         * =====================================
         * REDIRECT ROLE KHUSUS
         * =====================================
         */

        /**
         * ADMIN
         */
        if (
            $activeRole === 'admin'
        ) {

            return redirect()
                ->route(
                    'admin.users.index'
                );
        }

        /**
         * GURU BK
         */
        if (
            $activeRole === 'guru_bk'
        ) {

            return redirect()
                ->route(
                    'bk.rekap.index'
                );
        }

        /**
         * KEPALA SEKOLAH
         */
        if (
            $activeRole === 'kepala_sekolah'
        ) {

            return redirect()
                ->route(
                    'kepsek.laporan.index'
                );
        }

        /**
         * WALI KELAS
         */
        if (
            $activeRole === 'wali_kelas'
        ) {

            return redirect()
                ->route(
                    'wali.rekap.index'
                );
        }

        /**
         * DEFAULT DATA
         */
        $stats = [

            'H' => 0,
            'S' => 0,
            'I' => 0,
            'A' => 0,

        ];

        /**
         * DEFAULT
         */
        $kelas = null;

        $jadwals = collect();

        $unreadSuratCount = 0;

        /**
         * DATA GURU
         */
        $guru = Guru::where(
            'user_id',
            $user->id
        )->first();

        /**
         * =====================================
         * ROLE GURU
         * =====================================
         */
        if (
            $activeRole === 'guru' &&
            $guru
        ) {

            /**
             * RAW DATA ABSENSI
             */
            $rawStats = Absensi::query()

                ->whereHas(
                    'jadwal',
                    function ($q) use ($guru) {

                        $q->where(
                            'guru_id',
                            $guru->guru_id
                        );

                    }
                )

                ->selectRaw(
                    'status, COUNT(*) as total'
                )

                ->groupBy('status')

                ->pluck(
                    'total',
                    'status'
                )

                ->toArray();

            /**
             * FORMAT FIXED ARRAY
             */
            $stats = [

                'H' => (int) (
                    $rawStats['H'] ?? 0
                ),

                'S' => (int) (
                    $rawStats['S'] ?? 0
                ),

                'I' => (int) (
                    $rawStats['I'] ?? 0
                ),

                'A' => (int) (
                    $rawStats['A'] ?? 0
                ),

            ];

            /**
             * =====================================
             * JADWAL HARI INI
             * =====================================
             */

            /**
             * HARI INDONESIA
             */
            Carbon::setLocale('id');

            $hariIni = now()
                ->translatedFormat('l');

            /**
             * AMBIL JADWAL
             */
            $jadwals = Jadwal::query()

                ->with([
                    'kelas',
                    'mapel'
                ])

                ->where(
                    'guru_id',
                    $guru->guru_id
                )

                ->where(
                    'hari',
                    $hariIni
                )

                ->orderBy(
                    'jam_mulai'
                )

                ->get()

                ->map(function ($jadwal) {

                    /**
                     * WAKTU SEKARANG
                     */
                    $currentTime =
                        Carbon::now();

                    /**
                     * FORMAT JAM
                     */
                    $jamMulai =
                        Carbon::createFromFormat(
                            'H:i',
                            substr(
                                $jadwal->jam_mulai,
                                0,
                                5
                            )
                        );

                    $jamSelesai =
                        Carbon::createFromFormat(
                            'H:i',
                            substr(
                                $jadwal->jam_selesai,
                                0,
                                5
                            )
                        );

                    /**
                     * STATUS
                     */
                    if (
                        $currentTime->lt(
                            $jamMulai
                        )
                    ) {

                        $jadwal->status_pelajaran =
                            'belum_mulai';

                    } elseif (

                        $currentTime->between(
                            $jamMulai,
                            $jamSelesai
                        )

                    ) {

                        $jadwal->status_pelajaran =
                            'berlangsung';

                    } else {

                        $jadwal->status_pelajaran =
                            'selesai';
                    }

                    return $jadwal;
                });

            /**
             * SURAT IZIN PENDING
             */
            $unreadSuratCount =
                SuratIzin::query()

                    ->whereHas(
                        'jadwal',
                        function ($q) use ($guru) {

                            $q->where(
                                'guru_id',
                                $guru->guru_id
                            );

                        }
                    )

                    ->where(
                        'status',
                        'pending'
                    )

                    ->count();

            /**
             * DATA WALI KELAS
             */
            $waliKelas =
                $guru->waliKelas;

            /**
             * DATA KELAS
             */
            $kelas =
                $waliKelas
                ? $waliKelas->kelas
                : null;
        }

        /**
         * VIEW
         */
        return view(
            'dashboard',
            compact(
                'user',
                'stats',
                'kelas',
                'jadwals',
                'unreadSuratCount',
                'activeRole'
            )
        );
    }
}