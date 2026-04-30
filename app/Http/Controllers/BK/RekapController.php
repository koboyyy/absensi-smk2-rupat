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

        $from = now()->setDate($tahun, $bulan, 1)->startOfMonth()->toDateString();
        $to = now()->setDate($tahun, $bulan, 1)->endOfMonth()->toDateString();

        $kelasQuery = Kelas::query()->orderBy('tingkat')->orderBy('jurusan')->orderBy('nama_kelas');
        $jurusans = (clone $kelasQuery)->select('jurusan')->distinct()->pluck('jurusan')->values();
        $kelasList = (clone $kelasQuery)->get();

        $base = Absensi::query()
            ->whereBetween('tanggal', [$from, $to])
            ->whereIn('status', ['S', 'I', 'A'])
            ->whereHas('siswa.kelas', function ($q) use ($jurusan, $kelasId) {
                if ($jurusan) {
                    $q->where('jurusan', $jurusan);
                }
                if ($kelasId) {
                    $q->where('kelas_id', $kelasId);
                }
            });

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

        $perKelas = (clone $base)
            ->join('siswas', 'absensis.siswa_id', '=', 'siswas.siswa_id')
            ->join('kelas', 'siswas.kelas_id', '=', 'kelas.kelas_id')
            ->selectRaw('kelas.kelas_id, kelas.nama_kelas, kelas.jurusan,
                SUM(CASE WHEN absensis.status = "S" THEN 1 ELSE 0 END) as sakit,
                SUM(CASE WHEN absensis.status = "I" THEN 1 ELSE 0 END) as izin,
                SUM(CASE WHEN absensis.status = "A" THEN 1 ELSE 0 END) as alfa')
            ->groupBy('kelas.kelas_id', 'kelas.nama_kelas', 'kelas.jurusan')
            ->orderBy('kelas.jurusan')
            ->orderBy('kelas.nama_kelas')
            ->get();

        $perSiswa = (clone $base)
            ->with(['siswa.kelas'])
            ->selectRaw('siswa_id,
                SUM(CASE WHEN status = "S" THEN 1 ELSE 0 END) as sakit,
                SUM(CASE WHEN status = "I" THEN 1 ELSE 0 END) as izin,
                SUM(CASE WHEN status = "A" THEN 1 ELSE 0 END) as alfa')
            ->groupBy('siswa_id')
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
            'jurusans',
            'kelasList',
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

        $from = now()->setDate($tahun, $bulan, 1)->startOfMonth()->toDateString();
        $to = now()->setDate($tahun, $bulan, 1)->endOfMonth()->toDateString();

        // 1. Ambil Nama Kelas untuk Judul Header
        $namaKelas = 'Semua Kelas';
        if ($kelasId) {
            $namaKelas = Kelas::where('kelas_id', $kelasId)->value('nama_kelas');
        }

        // 2. Query Summary Total (S/I/A)
        $base = Absensi::query()
            ->whereBetween('tanggal', [$from, $to])
            ->whereIn('status', ['S', 'I', 'A'])
            ->whereHas('siswa.kelas', function ($q) use ($jurusan, $kelasId) {
                if ($jurusan)
                    $q->where('jurusan', $jurusan);
                if ($kelasId)
                    $q->where('kelas_id', $kelasId);
            });

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

        // 3. Query Utama untuk Tabel (Per Siswa sesuai format gambar)
        // Kita join ke tabel siswas agar mendapatkan semua siswa di kelas tersebut
        // meskipun siswa tersebut tidak punya catatan absen (hadir terus)
        $perKelas = Siswa::query()
            ->with('kelas')
            ->when($kelasId, fn($q) => $q->where('kelas_id', $kelasId))
            ->when($jurusan, fn($q) => $q->whereHas('kelas', fn($k) => $k->where('jurusan', $jurusan)))
            ->leftJoin('absensis', function ($join) use ($from, $to) {
                $join->on('siswas.siswa_id', '=', 'absensis.siswa_id')
                    ->whereBetween('absensis.tanggal', [$from, $to]);
            })
            ->selectRaw('
                siswas.nama_siswa, 
                siswas.nis, 
                siswas.jenis_kelamin,
                SUM(CASE WHEN absensis.status = "S" THEN 1 ELSE 0 END) as sakit,
                SUM(CASE WHEN absensis.status = "I" THEN 1 ELSE 0 END) as izin,
                SUM(CASE WHEN absensis.status = "A" THEN 1 ELSE 0 END) as alfa
            ')
            ->groupBy('siswas.siswa_id', 'siswas.nama_siswa', 'siswas.nis', 'siswas.jenis_kelamin')
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

        return $pdf->download('rekap-absen-' . $namaKelas . '.pdf');
    }
}
