<?php

namespace App\Http\Controllers\WaliKelas;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Guru;
use App\Models\RekapKelas;
use App\Models\WaliKelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ValidasiAbsensiController extends Controller
{
    public function index(Request $request)
    {
        $guruId = Guru::query()->where('user_id', Auth::id())->value('guru_id');
        $kelasId = WaliKelas::query()->where('guru_id', $guruId)->value('kelas_id');

        // Cek apa yang kosong
        if (!$guruId) {
            return "Error: User ini tidak ditemukan di tabel GURU.";
        }
        if (!$kelasId) {
            return "Error: Guru ini tidak terdaftar sebagai WALI KELAS di tabel wali_kelas.";
        }

        abort_unless((bool) $kelasId, 403);

        $tanggal = $request->query('tanggal', now()->toDateString());

        $items = Absensi::query()
            ->with(['siswa', 'jadwal.mapel'])
            ->whereDate('tanggal', $tanggal)
            ->whereHas('siswa', fn($q) => $q->where('kelas_id', $kelasId))
            ->orderBy('jadwal_id')
            ->orderBy('siswa_id')
            ->get();

        return view('wali.validasi.index', compact('items', 'tanggal'));
    }

    public function validateBatch(Request $request)
    {
        $data = $request->validate([
            'absensi_id' => ['required', 'array'],
            'absensi_id.*' => ['integer'],
            'tanggal' => ['required', 'date'],
        ]);

        $guruId = Guru::query()->where('user_id', Auth::id())->value('guru_id');
        $kelasId = WaliKelas::query()->where('guru_id', $guruId)->value('kelas_id');
        abort_unless((bool) $kelasId, 403);

        $tanggal = $data['tanggal'];

        DB::transaction(function () use ($data, $kelasId, $tanggal) {
            Absensi::query()
                ->whereIn('absensi_id', $data['absensi_id'])
                ->whereDate('tanggal', $tanggal)
                ->whereHas('siswa', fn($q) => $q->where('kelas_id', $kelasId))
                ->update(['wali_validasi' => true]);
        });

        return back()->with('success', 'Absensi berhasil divalidasi.');
    }

    public function rekap(Request $request)
    {
        $user = Auth::user();
        $guru = $user->guru;
        $waliKelas = $guru ? $guru->waliKelas : null;
        $kelas = $waliKelas ? $waliKelas->kelas : null;

        if (!$kelas) {
            abort(403, "Anda bukan wali kelas.");
        }

        $kelasId = $kelas->kelas_id;
        $bulan = (int) $request->query('bulan', now()->month);
        $tahun = (int) $request->query('tahun', now()->year);

        $from = now()->setDate($tahun, $bulan, 1)->startOfMonth()->toDateString();
        $to = now()->setDate($tahun, $bulan, 1)->endOfMonth()->toDateString();

        // 1. Ambil Statistik Global Kelas (untuk Stats Cards)
        $rows = Absensi::query()
            ->whereBetween('tanggal', [$from, $to])
            ->whereHas('siswa', fn($q) => $q->where('kelas_id', $kelasId))
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $rekap = [
            'hadir' => (int) ($rows['H'] ?? 0),
            'sakit' => (int) ($rows['S'] ?? 0),
            'izin' => (int) ($rows['I'] ?? 0),
            'alfa' => (int) ($rows['A'] ?? 0),
        ];

        // 2. Ambil Statistik Per Siswa (untuk Tabel Individu)
        $siswas = $kelas->siswas()->orderBy('nama_siswa')->get();
        $totalPerSiswa = [];

        foreach ($siswas as $siswa) {
            // Hitung statistik absensi siswa tersebut dalam rentang bulan yang dipilih
            $absensiSiswa = Absensi::query()
                ->where('siswa_id', $siswa->siswa_id)
                ->whereBetween('tanggal', [$from, $to])
                ->selectRaw('status, COUNT(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status')
                ->toArray();

            $totalPerSiswa[$siswa->siswa_id] = [
                'H' => $absensiSiswa['H'] ?? 0,
                'S' => $absensiSiswa['S'] ?? 0,
                'I' => $absensiSiswa['I'] ?? 0,
                'A' => $absensiSiswa['A'] ?? 0,
            ];
        }

        // simpan/refresh rekap_kelas (History untuk admin)
        RekapKelas::query()->updateOrCreate(
            ['kelas_id' => $kelasId, 'bulan' => $bulan, 'tahun' => $tahun],
            $rekap
        );

        // Kirim $totalPerSiswa ke View
        return view('wali.rekap.index', compact('bulan', 'tahun', 'rekap', 'kelas', 'totalPerSiswa'));
    }
    public function exportPdf(Request $request)
    {
        $bulan = $request->get('bulan', date('m'));
        $tahun = $request->get('tahun', date('Y'));

        // Pastikan relasi waliKelas dan kelas sudah didefinisikan di model Guru
        $guru = Auth::user()->guru;
        $waliKelas = $guru->waliKelas;
        $kelas = $waliKelas->kelas;
        $siswas = $kelas->siswas()->orderBy('nama_siswa')->get();

        $jumlahHari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
        $dataAbsen = [];
        $totalPerSiswa = [];

        foreach ($siswas as $siswa) {
            $totalPerSiswa[$siswa->siswa_id] = ['H' => 0, 'S' => 0, 'I' => 0, 'A' => 0];

            for ($tgl = 1; $tgl <= $jumlahHari; $tgl++) {
                // Gunakan format Y-m-d untuk query ke database
                $tanggalFull = sprintf('%04d-%02d-%02d', $tahun, $bulan, $tgl);

                // Pastikan kolom 'tanggal' di database sesuai dengan format $tanggalFull
                $absen = \App\Models\Absensi::where('siswa_id', $siswa->siswa_id)
                    ->whereDate('tanggal', $tanggalFull)
                    ->first();

                if ($absen) {
                    $status = $absen->status;
                    $dataAbsen[$siswa->siswa_id][$tgl] = $status;
                    $totalPerSiswa[$siswa->siswa_id][$status]++;
                } else {
                    $dataAbsen[$siswa->siswa_id][$tgl] = '';
                }
            }
        }

        $pdf = \PDF::loadView('wali.rekap.pdf', [
            'siswas' => $siswas,
            'dataAbsen' => $dataAbsen,
            'totalPerSiswa' => $totalPerSiswa,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'nama_kelas' => $kelas->nama_kelas,
            'jumlahHari' => $jumlahHari,
            'guru' => $guru // Data guru untuk tanda tangan
        ])->setPaper('a4', 'landscape');

        return $pdf->stream("Rekap_Absensi_{$kelas->nama_kelas}.pdf");
    }
}
