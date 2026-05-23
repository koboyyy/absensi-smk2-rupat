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
    public function index(Request $request)
    {
        $ortu = OrangTua::where('user_id', Auth::id())->firstOrFail();

        // Semua anak
        $siswas = $ortu->siswas()
            ->with('kelas')
            ->get();

        // siswa dipilih
        $selectedSiswaId = $request->siswa_id;

        // jika belum pilih siswa
        if (!$selectedSiswaId) {

            return view('ortu.jadwal.index', [
                'showSelectAnak' => true,
                'siswas' => $siswas,
                'items' => collect([]),
                'siswa' => null,
                'selectedSiswaId' => null
            ]);
        }

        // ambil siswa
        $siswa = $siswas->where('siswa_id', $selectedSiswaId)->first();

        if (!$siswa) {
            abort(404);
        }

        // jadwal
        $items = Jadwal::with(['mapel', 'guru'])

            ->where('kelas_id', $siswa->kelas_id)

            ->withCount([

                'absensis as hadir_count' => function ($q) use ($siswa) {
                    $q->where('siswa_id', $siswa->siswa_id)
                        ->where('status', 'H');
                },

                'absensis as sakit_count' => function ($q) use ($siswa) {
                    $q->where('siswa_id', $siswa->siswa_id)
                        ->where('status', 'S');
                },

                'absensis as izin_count' => function ($q) use ($siswa) {
                    $q->where('siswa_id', $siswa->siswa_id)
                        ->where('status', 'I');
                },

                'absensis as alfa_count' => function ($q) use ($siswa) {
                    $q->where('siswa_id', $siswa->siswa_id)
                        ->where('status', 'A');
                },

            ])

            ->orderByRaw("
            FIELD(
                hari,
                'Senin',
                'Selasa',
                'Rabu',
                'Kamis',
                'Jumat',
                'Sabtu',
                'Minggu'
            )
        ")

            ->orderBy('jam_mulai')

            ->paginate(10)

            ->withQueryString();

        return view('ortu.jadwal.index', [

            'showSelectAnak' => false,
            'items' => $items,
            'siswa' => $siswa,
            'siswas' => $siswas,
            'selectedSiswaId' => $selectedSiswaId

        ]);
    }

    public function show(Request $request, $id)
    {
        $ortu = OrangTua::where('user_id', Auth::id())->firstOrFail();

        $jadwal = Jadwal::with(['mapel', 'guru', 'kelas'])
            ->findOrFail($id);

        $siswa = Siswa::where('ortu_id', $ortu->ortu_id)
            ->where('siswa_id', $request->siswa_id)
            ->first();

        if (!$siswa) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        $absensi = Absensi::where('jadwal_id', $id)
            ->where('siswa_id', $siswa->siswa_id)
            ->orderBy('tanggal', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('ortu.jadwal.detail', compact(
            'jadwal',
            'siswa',
            'absensi'
        ));
    }
}