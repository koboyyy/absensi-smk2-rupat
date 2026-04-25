<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AbsensiController extends Controller
{
    public function index()
    {
        $guruId = Guru::query()->where('user_id', Auth::id())->value('guru_id');
        $jadwals = Jadwal::query()
            ->with(['kelas', 'mapel'])
            ->where('guru_id', $guruId)
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->get();

        return view('guru.absensi.index', compact('jadwals'));
    }

    public function showForm(Request $request, Jadwal $jadwal)
    {
        $guruId = Guru::query()->where('user_id', Auth::id())->value('guru_id');
        abort_unless($jadwal->guru_id === $guruId, 403);

        $tanggal = $request->query('tanggal', now()->toDateString());

        $siswas = Siswa::query()
            ->where('kelas_id', $jadwal->kelas_id)
            ->orderBy('nama_siswa')
            ->get();

        $existing = Absensi::query()
            ->where('jadwal_id', $jadwal->jadwal_id)
            ->whereDate('tanggal', $tanggal)
            ->get()
            ->keyBy('siswa_id');

        return view('guru.absensi.form', compact('jadwal', 'tanggal', 'siswas', 'existing'));
    }

    public function store(Request $request, Jadwal $jadwal)
    {
        $guruId = Guru::query()->where('user_id', Auth::id())->value('guru_id');
        abort_unless($jadwal->guru_id === $guruId, 403);

        $data = $request->validate([
            'tanggal' => ['required', 'date'],
            'status' => ['required', 'array'],
            'status.*' => ['required', 'in:H,I,S,A'],
            'foto_bukti' => ['nullable', 'file', 'max:2048'],
        ]);

        $tanggal = $data['tanggal'];
        $path = null;
        if ($request->hasFile('foto_bukti')) {
            $path = $request->file('foto_bukti')->store('absensi_bukti', 'public');
        }

        // Validasi siswa harus berada di kelas jadwal tsb
        $kelasSiswaIds = Siswa::query()
            ->where('kelas_id', $jadwal->kelas_id)
            ->pluck('siswa_id')
            ->all();
        $allowed = array_flip($kelasSiswaIds);

        DB::transaction(function () use ($data, $jadwal, $tanggal, $path, $allowed) {
            foreach ($data['status'] as $siswaId => $status) {
                $siswaId = (int) $siswaId;
                if (! isset($allowed[$siswaId])) {
                    continue;
                }

                Absensi::query()->updateOrCreate(
                    [
                        'siswa_id' => $siswaId,
                        'jadwal_id' => $jadwal->jadwal_id,
                        'tanggal' => $tanggal,
                    ],
                    [
                        'status' => $status,
                        'foto_bukti' => $path,
                        'status_kirim' => true,
                    ]
                );
            }
        });

        return redirect()
            ->route('guru.absensi.form', ['jadwal' => $jadwal->jadwal_id, 'tanggal' => $tanggal])
            ->with('success', 'Absensi berhasil disimpan.');
    }
}
