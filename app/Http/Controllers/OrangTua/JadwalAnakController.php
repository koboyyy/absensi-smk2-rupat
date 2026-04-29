<?php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Models\OrangTua;
use App\Models\Jadwal;
use App\Models\Siswa;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalAnakController extends Controller
{
    public function index()
    {
        $ortu = OrangTua::where('user_id', Auth::id())->firstOrFail();
        $siswa = $ortu->siswas()->with('kelas')->first();

        if (!$siswa) {
            return view('ortu.jadwal.index', [
                'items' => collect([]),
                'siswa' => null
            ]);
        }

        // Nama relasi diubah dari 'absensi' menjadi 'absensis' sesuai Model Jadwal
        $items = Jadwal::with(['mapel', 'guru'])
            ->where('kelas_id', $siswa->kelas_id)
            ->withCount([
                'absensis as hadir_count' => function ($q) use ($siswa) {
                    $q->where('siswa_id', $siswa->siswa_id)->where('status', 'H');
                },
                'absensis as sakit_count' => function ($q) use ($siswa) {
                    $q->where('siswa_id', $siswa->siswa_id)->where('status', 'S');
                },
                'absensis as izin_count' => function ($q) use ($siswa) {
                    $q->where('siswa_id', $siswa->siswa_id)->where('status', 'I');
                },
                'absensis as alfa_count' => function ($q) use ($siswa) {
                    $q->where('siswa_id', $siswa->siswa_id)->where('status', 'A');
                },
            ])
            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
            ->orderBy('jam_mulai')
            ->paginate(10);

        return view('ortu.jadwal.index', compact('items', 'siswa'));
    }

    public function show($id)
    {
        $ortu = OrangTua::where('user_id', Auth::id())->firstOrFail();
        $jadwal = Jadwal::with(['mapel', 'guru', 'kelas'])->findOrFail($id);

        $siswa = Siswa::where('ortu_id', $ortu->ortu_id)
            ->where('kelas_id', $jadwal->kelas_id)
            ->first();

        if (!$siswa) {
            abort(403, 'Anda tidak memiliki akses ke jadwal ini.');
        }

        // Di sini variabel $absensi didefinisikan untuk halaman detail
        $absensi = Absensi::where('jadwal_id', $id)
            ->where('siswa_id', $siswa->siswa_id)
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('ortu.jadwal.detail', compact('jadwal', 'siswa', 'absensi'));
    }
}