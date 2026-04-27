<?php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Models\OrangTua;
use App\Models\Jadwal;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalAnakController extends Controller
{
    public function index()
    {
        $ortu = OrangTua::where('user_id', Auth::id())->firstOrFail();
        $siswa = $ortu->siswas()->with('kelas')->first();

        if (!$siswa) {
            return view('ortu.jadwal.index', ['items' => collect([])]);
        }

        // GANTI get() MENJADI paginate()
        $items = Jadwal::with(['mapel', 'guru'])
            ->where('kelas_id', $siswa->kelas_id)
            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
            ->orderBy('jam_mulai')
            ->paginate(10); // Menampilkan 10 data per halaman

        return view('ortu.jadwal.index', compact('items', 'siswa'));
    }

    public function show($id)
    {
        // 1. Ambil data orang tua login
        $ortu = OrangTua::where('user_id', Auth::id())->firstOrFail();

        // 2. Ambil detail jadwal
        $jadwal = Jadwal::with(['mapel', 'guru', 'kelas'])->findOrFail($id);

        // 3. Security Check: Pastikan jadwal yang dilihat adalah milik kelas anaknya
        $isMilikAnak = Siswa::where('ortu_id', $ortu->ortu_id)
            ->where('kelas_id', $jadwal->kelas_id)
            ->exists();

        if (!$isMilikAnak) {
            abort(403, 'Anda tidak memiliki akses ke jadwal ini.');
        }

        // Ambil data siswa terkait untuk ditampilkan di detail
        $siswa = Siswa::where('ortu_id', $ortu->ortu_id)
            ->where('kelas_id', $jadwal->kelas_id)
            ->first();

        return view('ortu.jadwal.detail', compact('jadwal', 'siswa'));
    }
}