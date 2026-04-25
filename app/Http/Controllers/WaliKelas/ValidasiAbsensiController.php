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
        abort_unless((bool) $kelasId, 403);

        $tanggal = $request->query('tanggal', now()->toDateString());

        $items = Absensi::query()
            ->with(['siswa', 'jadwal.mapel'])
            ->whereDate('tanggal', $tanggal)
            ->whereHas('siswa', fn ($q) => $q->where('kelas_id', $kelasId))
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
                ->whereHas('siswa', fn ($q) => $q->where('kelas_id', $kelasId))
                ->update(['wali_validasi' => true]);
        });

        return back()->with('success', 'Absensi berhasil divalidasi.');
    }

    public function rekap(Request $request)
    {
        $guruId = Guru::query()->where('user_id', Auth::id())->value('guru_id');
        $kelasId = WaliKelas::query()->where('guru_id', $guruId)->value('kelas_id');
        abort_unless((bool) $kelasId, 403);

        $bulan = (int) $request->query('bulan', now()->month);
        $tahun = (int) $request->query('tahun', now()->year);

        $from = now()->setDate($tahun, $bulan, 1)->startOfMonth()->toDateString();
        $to = now()->setDate($tahun, $bulan, 1)->endOfMonth()->toDateString();

        $rows = Absensi::query()
            ->whereBetween('tanggal', [$from, $to])
            ->whereHas('siswa', fn ($q) => $q->where('kelas_id', $kelasId))
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

        // simpan/refresh rekap_kelas
        RekapKelas::query()->updateOrCreate(
            ['kelas_id' => $kelasId, 'bulan' => $bulan, 'tahun' => $tahun],
            $rekap
        );

        return view('wali.rekap.index', compact('bulan', 'tahun', 'rekap'));
    }
}
