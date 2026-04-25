<?php

namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $bulan = (int) $request->query('bulan', now()->month);
        $tahun = (int) $request->query('tahun', now()->year);

        $from = now()->setDate($tahun, $bulan, 1)->startOfMonth()->toDateString();
        $to = now()->setDate($tahun, $bulan, 1)->endOfMonth()->toDateString();

        $summaryByStatus = Absensi::query()
            ->whereBetween('tanggal', [$from, $to])
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $summary = [
            'H' => (int) ($summaryByStatus['H'] ?? 0),
            'S' => (int) ($summaryByStatus['S'] ?? 0),
            'I' => (int) ($summaryByStatus['I'] ?? 0),
            'A' => (int) ($summaryByStatus['A'] ?? 0),
        ];

        $perJurusan = Absensi::query()
            ->whereBetween('tanggal', [$from, $to])
            ->join('siswas', 'absensis.siswa_id', '=', 'siswas.siswa_id')
            ->join('kelas', 'siswas.kelas_id', '=', 'kelas.kelas_id')
            ->selectRaw('kelas.jurusan,
                SUM(CASE WHEN absensis.status = "H" THEN 1 ELSE 0 END) as hadir,
                SUM(CASE WHEN absensis.status = "S" THEN 1 ELSE 0 END) as sakit,
                SUM(CASE WHEN absensis.status = "I" THEN 1 ELSE 0 END) as izin,
                SUM(CASE WHEN absensis.status = "A" THEN 1 ELSE 0 END) as alfa')
            ->groupBy('kelas.jurusan')
            ->orderBy('kelas.jurusan')
            ->get();

        $perKelas = Absensi::query()
            ->whereBetween('tanggal', [$from, $to])
            ->join('siswas', 'absensis.siswa_id', '=', 'siswas.siswa_id')
            ->join('kelas', 'siswas.kelas_id', '=', 'kelas.kelas_id')
            ->selectRaw('kelas.jurusan, kelas.nama_kelas,
                SUM(CASE WHEN absensis.status = "H" THEN 1 ELSE 0 END) as hadir,
                SUM(CASE WHEN absensis.status = "S" THEN 1 ELSE 0 END) as sakit,
                SUM(CASE WHEN absensis.status = "I" THEN 1 ELSE 0 END) as izin,
                SUM(CASE WHEN absensis.status = "A" THEN 1 ELSE 0 END) as alfa')
            ->groupBy('kelas.jurusan', 'kelas.nama_kelas')
            ->orderBy('kelas.jurusan')
            ->orderBy('kelas.nama_kelas')
            ->get();

        return view('kepsek.laporan.index', compact('bulan', 'tahun', 'summary', 'perJurusan', 'perKelas'));
    }

    public function exportPdf(Request $request)
    {
        $bulan = (int) $request->query('bulan', now()->month);
        $tahun = (int) $request->query('tahun', now()->year);

        $from = now()->setDate($tahun, $bulan, 1)->startOfMonth()->toDateString();
        $to = now()->setDate($tahun, $bulan, 1)->endOfMonth()->toDateString();

        $summaryByStatus = Absensi::query()
            ->whereBetween('tanggal', [$from, $to])
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $summary = [
            'H' => (int) ($summaryByStatus['H'] ?? 0),
            'S' => (int) ($summaryByStatus['S'] ?? 0),
            'I' => (int) ($summaryByStatus['I'] ?? 0),
            'A' => (int) ($summaryByStatus['A'] ?? 0),
        ];

        $perJurusan = Absensi::query()
            ->whereBetween('tanggal', [$from, $to])
            ->join('siswas', 'absensis.siswa_id', '=', 'siswas.siswa_id')
            ->join('kelas', 'siswas.kelas_id', '=', 'kelas.kelas_id')
            ->selectRaw('kelas.jurusan,
                SUM(CASE WHEN absensis.status = "H" THEN 1 ELSE 0 END) as hadir,
                SUM(CASE WHEN absensis.status = "S" THEN 1 ELSE 0 END) as sakit,
                SUM(CASE WHEN absensis.status = "I" THEN 1 ELSE 0 END) as izin,
                SUM(CASE WHEN absensis.status = "A" THEN 1 ELSE 0 END) as alfa')
            ->groupBy('kelas.jurusan')
            ->orderBy('kelas.jurusan')
            ->get();

        $perKelas = Absensi::query()
            ->whereBetween('tanggal', [$from, $to])
            ->join('siswas', 'absensis.siswa_id', '=', 'siswas.siswa_id')
            ->join('kelas', 'siswas.kelas_id', '=', 'kelas.kelas_id')
            ->selectRaw('kelas.jurusan, kelas.nama_kelas,
                SUM(CASE WHEN absensis.status = "H" THEN 1 ELSE 0 END) as hadir,
                SUM(CASE WHEN absensis.status = "S" THEN 1 ELSE 0 END) as sakit,
                SUM(CASE WHEN absensis.status = "I" THEN 1 ELSE 0 END) as izin,
                SUM(CASE WHEN absensis.status = "A" THEN 1 ELSE 0 END) as alfa')
            ->groupBy('kelas.jurusan', 'kelas.nama_kelas')
            ->orderBy('kelas.jurusan')
            ->orderBy('kelas.nama_kelas')
            ->get();

        $pdf = Pdf::loadView('kepsek.laporan.pdf', compact('bulan', 'tahun', 'summary', 'perJurusan', 'perKelas'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('laporan-kepsek.pdf');
    }
}
