<?php

namespace App\Http\Controllers\Guru;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AbsensiController extends Controller
{
    public function index()
    {
        $guruId = Guru::query()
            ->where('user_id', Auth::id())
            ->value('guru_id');

        $jadwals = Jadwal::query()

            ->with([
                'kelas',
                'mapel'
            ])

            ->where(
                'guru_id',
                $guruId
            )

            ->orderBy('hari')

            ->orderBy('jam_mulai')

            ->get();

        return view(
            'guru.absensi.index',
            compact('jadwals')
        );
    }

    public function showForm(
        Request $request,
        Jadwal $jadwal
    ) {

        /**
         * VALIDASI GURU
         */
        $guruId = Guru::query()

            ->where(
                'user_id',
                Auth::id()
            )

            ->value('guru_id');

        abort_unless(
            $jadwal->guru_id === $guruId,
            403
        );

        /**
         * CEK JADWAL SUDAH SELESAI
         */
        if ($jadwal->is_selesai) {

            return redirect()

                ->route('dashboard')

                ->with(
                    'error',
                    'Jam pelajaran sudah selesai.'
                );
        }

        /**
         * TANGGAL
         */
        $tanggal = $request->query(
            'tanggal',
            now()->toDateString()
        );

        /**
         * SISWA
         */
        $siswas = Siswa::query()

            ->where(
                'kelas_id',
                $jadwal->kelas_id
            )

            ->select(
                'siswa_id',
                'nama_siswa',
                'nis',
                'foto',
                'kelas_id'
            )

            ->orderBy('nama_siswa')

            ->get();

        /**
         * EXISTING ABSENSI
         */
        $existing = Absensi::query()

            ->where(
                'jadwal_id',
                $jadwal->jadwal_id
            )

            ->whereDate(
                'tanggal',
                $tanggal
            )

            ->get()

            ->keyBy('siswa_id');

        return view(
            'guru.absensi.form',
            compact(
                'jadwal',
                'tanggal',
                'siswas',
                'existing'
            )
        );
    }

    public function store(
        Request $request,
        Jadwal $jadwal
    ) {

        /**
         * VALIDASI GURU
         */
        $guruId = Guru::query()

            ->where(
                'user_id',
                Auth::id()
            )

            ->value('guru_id');

        abort_unless(
            $jadwal->guru_id === $guruId,
            403
        );

        /**
         * CEK JADWAL SUDAH SELESAI
         */
        if ($jadwal->is_selesai) {

            return back()->with(
                'error',
                'Jam pelajaran sudah selesai.'
            );
        }

        /**
         * VALIDASI
         */
        $data = $request->validate([

            'tanggal' => [
                'required',
                'date'
            ],

            'status' => [
                'required',
                'array'
            ],

            'status.*' => [
                'required',
                'in:H,I,S,A'
            ],

            'foto_bukti' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg',
                'max:2048'
            ],

        ]);

        /**
         * TANGGAL
         */
        $tanggal = $data['tanggal'];

        /**
         * AMBIL ABSENSI PERTAMA
         */
        $firstAbsensi = Absensi::query()

            ->where(
                'jadwal_id',
                $jadwal->jadwal_id
            )

            ->whereDate(
                'tanggal',
                $tanggal
            )

            ->first();

        /**
         * FOTO LAMA
         */
        $path =
            $firstAbsensi->foto_bukti
            ?? null;

        /**
         * FOTO BARU
         */
        if (
            $request->hasFile(
                'foto_bukti'
            )
        ) {

            /**
             * HAPUS FOTO LAMA
             */
            if (

                $path &&

                Storage::disk('public')
                    ->exists($path)

            ) {

                Storage::disk('public')
                    ->delete($path);
            }

            /**
             * UPLOAD BARU
             */
            $path = $request

                ->file('foto_bukti')

                ->store(
                    'absensi_bukti',
                    'public'
                );
        }

        /**
         * SISWA VALID
         */
        $kelasSiswaIds = Siswa::query()

            ->where(
                'kelas_id',
                $jadwal->kelas_id
            )

            ->pluck('siswa_id')

            ->all();

        $allowed =
            array_flip(
                $kelasSiswaIds
            );

        /**
         * TRANSACTION
         */
        DB::transaction(function () use ($data, $jadwal, $tanggal, $path, $allowed) {

            foreach (
                $data['status']
                as $siswaId => $status
            ) {

                $siswaId =
                    (int) $siswaId;

                /**
                 * VALIDASI SISWA
                 */
                if (
                    !isset(
                    $allowed[$siswaId]
                )
                ) {

                    continue;
                }

                /**
                 * SIMPAN ABSENSI
                 */
                Absensi::updateOrCreate(

                    [

                        'siswa_id' =>
                            $siswaId,

                        'jadwal_id' =>
                            $jadwal->jadwal_id,

                        'tanggal' =>
                            $tanggal,

                    ],

                    [

                        'status' =>
                            $status,

                        'foto_bukti' =>
                            $path,

                        'status_kirim' =>
                            true,

                    ]
                );
            }
        });

        return redirect()

            ->route(
                'guru.absensi.form',
                [
                    'jadwal' =>
                        $jadwal->jadwal_id,

                    'tanggal' =>
                        $tanggal
                ]
            )

            ->with(
                'success',
                'Absensi dan bukti KBM berhasil disimpan.'
            );
    }

    public function rekapJadwal(
        Request $request,
        Jadwal $jadwal
    ) {

        /**
         * VALIDASI GURU
         */
        $guruId = Guru::query()

            ->where(
                'user_id',
                Auth::id()
            )

            ->value('guru_id');

        abort_unless(
            $jadwal->guru_id === $guruId,
            403
        );

        /**
         * FILTER
         */
        $bulan = $request->query(
            'bulan',
            date('m')
        );

        $tahun = $request->query(
            'tahun',
            date('Y')
        );

        /**
         * SISWA
         */
        $siswas = Siswa::query()

            ->where(
                'kelas_id',
                $jadwal->kelas_id
            )

            ->orderBy('nama_siswa')

            ->get();

        /**
         * REKAP
         */
        $rekap = Absensi::query()

            ->where(
                'jadwal_id',
                $jadwal->jadwal_id
            )

            ->whereMonth(
                'tanggal',
                $bulan
            )

            ->whereYear(
                'tanggal',
                $tahun
            )

            ->select(

                'siswa_id',

                DB::raw("
                    SUM(
                        CASE
                            WHEN status = 'H'
                            THEN 1
                            ELSE 0
                        END
                    ) as total_hadir
                "),

                DB::raw("
                    SUM(
                        CASE
                            WHEN status = 'S'
                            THEN 1
                            ELSE 0
                        END
                    ) as total_sakit
                "),

                DB::raw("
                    SUM(
                        CASE
                            WHEN status = 'I'
                            THEN 1
                            ELSE 0
                        END
                    ) as total_izin
                "),

                DB::raw("
                    SUM(
                        CASE
                            WHEN status = 'A'
                            THEN 1
                            ELSE 0
                        END
                    ) as total_alfa
                ")

            )

            ->groupBy('siswa_id')

            ->get()

            ->keyBy('siswa_id');

        return view(
            'guru.absensi.rekap_jadwal',
            compact(
                'jadwal',
                'siswas',
                'rekap',
                'bulan',
                'tahun'
            )
        );
    }

    public function exportPdf(
        Request $request,
        Jadwal $jadwal
    ) {

        /**
         * VALIDASI GURU
         */
        $guruId = Guru::query()

            ->where(
                'user_id',
                Auth::id()
            )

            ->value('guru_id');

        abort_unless(
            $jadwal->guru_id === $guruId,
            403
        );

        /**
         * FILTER
         */
        $bulan = $request->query(
            'bulan',
            date('m')
        );

        $tahun = $request->query(
            'tahun',
            date('Y')
        );

        /**
         * SISWA
         */
        $siswas = Siswa::query()

            ->where(
                'kelas_id',
                $jadwal->kelas_id
            )

            ->orderBy('nama_siswa')

            ->get();

        /**
         * REKAP
         */
        $rekap = Absensi::query()

            ->where(
                'jadwal_id',
                $jadwal->jadwal_id
            )

            ->whereMonth(
                'tanggal',
                $bulan
            )

            ->whereYear(
                'tanggal',
                $tahun
            )

            ->select(

                'siswa_id',

                DB::raw("
                    SUM(
                        CASE
                            WHEN status = 'H'
                            THEN 1
                            ELSE 0
                        END
                    ) as total_hadir
                "),

                DB::raw("
                    SUM(
                        CASE
                            WHEN status = 'S'
                            THEN 1
                            ELSE 0
                        END
                    ) as total_sakit
                "),

                DB::raw("
                    SUM(
                        CASE
                            WHEN status = 'I'
                            THEN 1
                            ELSE 0
                        END
                    ) as total_izin
                "),

                DB::raw("
                    SUM(
                        CASE
                            WHEN status = 'A'
                            THEN 1
                            ELSE 0
                        END
                    ) as total_alfa
                ")

            )

            ->groupBy('siswa_id')

            ->get()

            ->keyBy('siswa_id');

        /**
         * PDF
         */
        $pdf = Pdf::loadView(
            'guru.absensi.rekap_pdf',
            compact(
                'jadwal',
                'siswas',
                'rekap',
                'bulan',
                'tahun'
            )
        );

        /**
         * FILE NAME
         */
        $fileName =
            'Rekap_Absen_' .

            str_replace(
                ' ',
                '_',
                $jadwal->mapel->nama_mapel
            )

            . '_' .

            $bulan .

            '_' .

            $tahun .

            '.pdf';

        return $pdf->download(
            $fileName
        );
    }

    /**
     * =====================================
     * SELESAIKAN PELAJARAN
     * =====================================
     */
    public function selesai(
        Jadwal $jadwal
    ) {

        /**
         * VALIDASI GURU
         */
        $guruId = Guru::query()

            ->where(
                'user_id',
                Auth::id()
            )

            ->value('guru_id');

        abort_unless(
            $jadwal->guru_id === $guruId,
            403
        );

        /**
         * UPDATE STATUS
         */
        $jadwal->update([

            'is_selesai' => true

        ]);

        return back()->with(

            'success',
            'Jam pelajaran berhasil diselesaikan.'

        );
    }
}