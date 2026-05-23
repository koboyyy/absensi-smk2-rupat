<?php

namespace App\Http\Controllers\BK;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\Siswa;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class RekapController extends Controller
{
    public function index(Request $request)
    {
        $bulan = (int) $request->query('bulan', now()->month);

        $tahun = (int) $request->query('tahun', now()->year);

        $jurusan = $request->query('jurusan');

        $kelasId = $request->query('kelas_id');

        $siswaId = $request->query('siswa_id');

        $from = now()
            ->setDate($tahun, $bulan, 1)
            ->startOfMonth()
            ->toDateString();

        $to = now()
            ->setDate($tahun, $bulan, 1)
            ->endOfMonth()
            ->toDateString();

        // DATA FILTER
        $kelasQuery = Kelas::query()
            ->orderBy('tingkat')
            ->orderBy('jurusan')
            ->orderBy('nama_kelas');

        $jurusans = (clone $kelasQuery)
            ->select('jurusan')
            ->distinct()
            ->pluck('jurusan')
            ->values();

        $kelasList = (clone $kelasQuery)->get();

        $siswaList = Siswa::query()
            ->with('kelas')
            ->orderBy('nama_siswa')
            ->get();

        // BASE QUERY
        $base = Absensi::query()

            ->whereBetween('tanggal', [$from, $to])

            ->whereIn('status', ['S', 'I', 'A'])

            ->when($siswaId, function ($q) use ($siswaId) {
                $q->where('absensis.siswa_id', $siswaId);
            })

            ->whereHas('siswa.kelas', function ($q) use ($jurusan, $kelasId) {

                if ($jurusan) {
                    $q->where('jurusan', $jurusan);
                }

                if ($kelasId) {
                    $q->where('kelas_id', $kelasId);
                }
            });

        // SUMMARY
        $summaryByStatus = (clone $base)

            ->selectRaw('status, COUNT(*) as total')

            ->groupBy('status')

            ->pluck('total', 'status')

            ->toArray();

        $summary = [
            'S' => (int) ($summaryByStatus['S'] ?? 0),
            'I' => (int) ($summaryByStatus['I'] ?? 0),
            'A' => (int) ($summaryByStatus['A'] ?? 0),
        ];

        // REKAP PER KELAS
        $perKelas = (clone $base)

            ->join(
                'siswas',
                'absensis.siswa_id',
                '=',
                'siswas.siswa_id'
            )

            ->join(
                'kelas',
                'siswas.kelas_id',
                '=',
                'kelas.kelas_id'
            )

            ->selectRaw('
                kelas.kelas_id,
                kelas.nama_kelas,
                kelas.jurusan,

                SUM(
                    CASE
                        WHEN absensis.status = "S"
                        THEN 1
                        ELSE 0
                    END
                ) as sakit,

                SUM(
                    CASE
                        WHEN absensis.status = "I"
                        THEN 1
                        ELSE 0
                    END
                ) as izin,

                SUM(
                    CASE
                        WHEN absensis.status = "A"
                        THEN 1
                        ELSE 0
                    END
                ) as alfa
            ')

            ->groupBy(
                'kelas.kelas_id',
                'kelas.nama_kelas',
                'kelas.jurusan'
            )

            ->orderBy('kelas.jurusan')

            ->orderBy('kelas.nama_kelas')

            ->get();

        // REKAP PER SISWA
        $perSiswa = (clone $base)

            ->with(['siswa.kelas'])

            ->selectRaw('
                absensis.siswa_id,

                SUM(
                    CASE
                        WHEN status = "S"
                        THEN 1
                        ELSE 0
                    END
                ) as sakit,

                SUM(
                    CASE
                        WHEN status = "I"
                        THEN 1
                        ELSE 0
                    END
                ) as izin,

                SUM(
                    CASE
                        WHEN status = "A"
                        THEN 1
                        ELSE 0
                    END
                ) as alfa
            ')

            ->groupBy('absensis.siswa_id')

            ->orderByDesc('alfa')

            ->orderByDesc('izin')

            ->orderByDesc('sakit')

            ->paginate(15)

            ->withQueryString();

        return view('bk.rekap.index', compact(
            'bulan',
            'tahun',
            'jurusan',
            'kelasId',
            'siswaId',
            'jurusans',
            'kelasList',
            'siswaList',
            'summary',
            'perKelas',
            'perSiswa'
        ));
    }

    public function exportPdf(Request $request)
    {
        $bulan = (int) $request->query('bulan', now()->month);

        $tahun = (int) $request->query('tahun', now()->year);

        $jurusan = $request->query('jurusan');

        $kelasId = $request->query('kelas_id');

        $siswaId = $request->query('siswa_id');

        $from = now()
            ->setDate($tahun, $bulan, 1)
            ->startOfMonth()
            ->toDateString();

        $to = now()
            ->setDate($tahun, $bulan, 1)
            ->endOfMonth()
            ->toDateString();

        // NAMA KELAS
        $namaKelas = 'Semua Kelas';

        if ($kelasId) {

            $namaKelas = Kelas::where(
                'kelas_id',
                $kelasId
            )->value('nama_kelas');
        }

        // BASE QUERY
        $base = Absensi::query()

            ->whereBetween('tanggal', [$from, $to])

            ->whereIn('status', ['S', 'I', 'A'])

            ->when($siswaId, function ($q) use ($siswaId) {
                $q->where('absensis.siswa_id', $siswaId);
            })

            ->whereHas('siswa.kelas', function ($q) use ($jurusan, $kelasId) {

                if ($jurusan) {
                    $q->where('jurusan', $jurusan);
                }

                if ($kelasId) {
                    $q->where('kelas_id', $kelasId);
                }
            });

        // SUMMARY
        $summaryByStatus = (clone $base)

            ->selectRaw('status, COUNT(*) as total')

            ->groupBy('status')

            ->pluck('total', 'status')

            ->toArray();

        $summary = [
            'S' => (int) ($summaryByStatus['S'] ?? 0),
            'I' => (int) ($summaryByStatus['I'] ?? 0),
            'A' => (int) ($summaryByStatus['A'] ?? 0),
        ];

        // DATA PDF
        $perKelas = Siswa::query()

            ->with('kelas')

            ->when($kelasId, function ($q) use ($kelasId) {
                $q->where('kelas_id', $kelasId);
            })

            ->when($jurusan, function ($q) use ($jurusan) {

                $q->whereHas('kelas', function ($k) use ($jurusan) {

                    $k->where('jurusan', $jurusan);

                });
            })

            ->when($siswaId, function ($q) use ($siswaId) {

                $q->where('siswas.siswa_id', $siswaId);

            })

            ->leftJoin('absensis', function ($join) use ($from, $to) {

                $join->on(
                    'siswas.siswa_id',
                    '=',
                    'absensis.siswa_id'
                )

                    ->whereBetween(
                        'absensis.tanggal',
                        [$from, $to]
                    );
            })

            ->selectRaw('
                siswas.nama_siswa,
                siswas.nis,
                siswas.jenis_kelamin,

                SUM(
                    CASE
                        WHEN absensis.status = "S"
                        THEN 1
                        ELSE 0
                    END
                ) as sakit,

                SUM(
                    CASE
                        WHEN absensis.status = "I"
                        THEN 1
                        ELSE 0
                    END
                ) as izin,

                SUM(
                    CASE
                        WHEN absensis.status = "A"
                        THEN 1
                        ELSE 0
                    END
                ) as alfa
            ')

            ->groupBy(
                'siswas.siswa_id',
                'siswas.nama_siswa',
                'siswas.nis',
                'siswas.jenis_kelamin'
            )

            ->orderBy('siswas.nama_siswa')

            ->get();

        $pdf = Pdf::loadView('bk.rekap.pdf', compact(
            'bulan',
            'tahun',
            'jurusan',
            'kelasId',
            'summary',
            'perKelas',
            'namaKelas'
        ))->setPaper('a4', 'portrait');

        return $pdf->download(
            'rekap-absensi.pdf'
        );
    }
}