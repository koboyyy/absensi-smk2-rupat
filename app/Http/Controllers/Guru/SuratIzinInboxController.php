<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\SuratIzin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuratIzinInboxController extends Controller
{
    public function index(Request $request)
    {
        $guruId = Guru::query()->where('user_id', Auth::id())->value('guru_id');

        $status = $request->query('status', 'pending');
        if (! in_array($status, ['pending', 'diterima', 'ditolak'], true)) {
            $status = 'pending';
        }

        $items = SuratIzin::query()
            ->with(['siswa.kelas', 'orangTua', 'jadwal.mapel'])
            ->where('status', $status)
            ->whereHas('jadwal', fn ($q) => $q->where('guru_id', $guruId))
            ->orderByDesc('tanggal')
            ->paginate(15)
            ->withQueryString();

        return view('guru.surat_izin.index', compact('items', 'status'));
    }

    public function show(SuratIzin $suratIzin)
    {
        $guruId = Guru::query()->where('user_id', Auth::id())->value('guru_id');
        $suratIzin->load(['siswa.kelas', 'orangTua', 'jadwal.mapel', 'jadwal.kelas', 'jadwal.guru']);

        abort_unless($suratIzin->jadwal?->guru_id === $guruId, 403);

        return view('guru.surat_izin.show', ['item' => $suratIzin]);
    }

    public function accept(SuratIzin $suratIzin)
    {
        $guruId = Guru::query()->where('user_id', Auth::id())->value('guru_id');
        $suratIzin->load('jadwal');
        abort_unless($suratIzin->jadwal?->guru_id === $guruId, 403);

        $suratIzin->update(['status' => 'diterima']);

        return redirect()->route('guru.surat-izin.show', $suratIzin->surat_id)->with('success', 'Surat izin diterima.');
    }

    public function reject(SuratIzin $suratIzin)
    {
        $guruId = Guru::query()->where('user_id', Auth::id())->value('guru_id');
        $suratIzin->load('jadwal');
        abort_unless($suratIzin->jadwal?->guru_id === $guruId, 403);

        $suratIzin->update(['status' => 'ditolak']);

        return redirect()->route('guru.surat-izin.show', $suratIzin->surat_id)->with('success', 'Surat izin ditolak.');
    }
}
