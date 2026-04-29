<?php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\OrangTua;
use App\Models\Siswa;
use App\Models\SuratIzin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SuratIzinController extends Controller
{
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

    public function store(Request $request)
    {
        $ortuId = OrangTua::query()->where('user_id', Auth::id())->value('ortu_id');

        if (!$ortuId) {
            return back()->withErrors(['error' => 'Profil orang tua tidak ditemukan.']);
        }

        $data = $request->validate([
            'siswa_id' => ['required', 'exists:siswas,siswa_id'],
            'jadwal_id' => ['required', 'array'], // Modifikasi: Validasi sebagai array
            'jadwal_id.*' => ['exists:jadwals,jadwal_id'],
            'tanggal' => ['required', 'date'],
            'keterangan' => ['nullable', 'string'],
            'file_bukti' => ['nullable', 'file', 'max:2048', 'mimes:jpg,jpeg,png,pdf'],
        ]);

        $siswaOwned = Siswa::query()->where('siswa_id', $data['siswa_id'])->where('ortu_id', $ortuId)->exists();
        abort_unless($siswaOwned, 403);

        $path = null;
        if ($request->hasFile('file_bukti')) {
            $path = $request->file('file_bukti')->store('surat_izin', 'public');
        }

        // Modifikasi: Looping untuk menyimpan banyak jadwal sekaligus
        foreach ($data['jadwal_id'] as $idJadwal) {
            SuratIzin::create([
                'siswa_id' => $data['siswa_id'],
                'ortu_id' => $ortuId,
                'jadwal_id' => $idJadwal,
                'tanggal' => $data['tanggal'],
                'keterangan' => $data['keterangan'] ?? null,
                'file_bukti' => $path,
                'status' => 'pending',
            ]);
        }

        return redirect()->route('ortu.surat-izin.index')->with('success', 'Surat izin berhasil dikirim untuk ' . count($data['jadwal_id']) . ' mata pelajaran.');
    }

    public function show(string $id)
    {
        $ortuId = OrangTua::query()->where('user_id', Auth::id())->value('ortu_id');
        $item = SuratIzin::query()
            ->with(['siswa.kelas', 'jadwal.mapel', 'jadwal.guru'])
            ->where('ortu_id', $ortuId)
            ->findOrFail($id);

        return view('ortu.surat_izin.show', compact('item'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ortuId = OrangTua::query()->where('user_id', Auth::id())->value('ortu_id');
        $item = SuratIzin::query()->where('ortu_id', $ortuId)->findOrFail($id);

        // Hanya boleh hapus jika status masih pending
        if ($item->status !== 'pending') {
            return back()->with('error', 'Surat yang sudah diproses tidak dapat dihapus.');
        }

        // Hapus file bukti jika ada
        if ($item->file_bukti) {
            Storage::disk('public')->delete($item->file_bukti);
        }

        $item->delete();

        return redirect()->route('ortu.surat-izin.index')->with('success', 'Surat izin berhasil dibatalkan.');
    }
}