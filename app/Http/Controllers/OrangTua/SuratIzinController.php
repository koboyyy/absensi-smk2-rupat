<?php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\OrangTua;
use App\Models\Siswa;
use App\Models\SuratIzin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuratIzinController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ortuId = OrangTua::query()->where('user_id', Auth::id())->value('ortu_id');
        $items = SuratIzin::query()
            ->with(['siswa', 'jadwal.mapel'])
            ->where('ortu_id', $ortuId)
            ->orderByDesc('tanggal')
            ->paginate(15);

        return view('ortu.surat_izin.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $ortuId = OrangTua::query()->where('user_id', Auth::id())->value('ortu_id');
        $siswas = Siswa::query()->where('ortu_id', $ortuId)->with('kelas')->orderBy('nama_siswa')->get();

        $kelasIds = $siswas->pluck('kelas_id')->filter()->unique()->values();
        $jadwals = $kelasIds->isEmpty()
            ? collect()
            : Jadwal::query()->with(['kelas', 'mapel', 'guru'])->whereIn('kelas_id', $kelasIds)->orderBy('hari')->orderBy('jam_mulai')->get();

        return view('ortu.surat_izin.create', compact('siswas', 'jadwals'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $ortuId = OrangTua::query()->where('user_id', Auth::id())->value('ortu_id');

        // Tambahkan ini untuk cek
        if (!$ortuId) {
            return back()->withErrors(['error' => 'Profil orang tua tidak ditemukan. Silakan hubungi admin.']);
        }


        $data = $request->validate([
            'siswa_id' => ['required', 'exists:siswas,siswa_id'],
            'jadwal_id' => ['required', 'exists:jadwals,jadwal_id'],
            'tanggal' => ['required', 'date'],
            'keterangan' => ['nullable', 'string'],
            'file_bukti' => ['nullable', 'file', 'max:2048'],
        ]);

        // pastikan siswa milik orang tua yang login
        $siswaOwned = Siswa::query()->where('siswa_id', $data['siswa_id'])->where('ortu_id', $ortuId)->exists();
        abort_unless($siswaOwned, 403);

        $path = null;
        if ($request->hasFile('file_bukti')) {
            $path = $request->file('file_bukti')->store('surat_izin', 'public');
        }

        SuratIzin::create([
            'siswa_id' => $data['siswa_id'],
            'ortu_id' => $ortuId,
            'jadwal_id' => $data['jadwal_id'],
            'tanggal' => $data['tanggal'],
            'keterangan' => $data['keterangan'] ?? null,
            'file_bukti' => $path,
            'status' => 'pending',
        ]);

        return redirect()->route('ortu.surat-izin.index')->with('success', 'Surat izin berhasil dikirim.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ortuId = OrangTua::query()->where('user_id', Auth::id())->value('ortu_id');
        $item = SuratIzin::query()->with(['siswa', 'jadwal.mapel', 'jadwal.guru'])->where('ortu_id', $ortuId)->findOrFail($id);
        return view('ortu.surat_izin.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        abort(404);
    }
}
