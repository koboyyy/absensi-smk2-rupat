<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\OrangTua;
use App\Models\Guru;
use App\Models\WaliKelas;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $guru = Guru::where('user_id', $user->id)->first();
        $waliKelas = $guru ? $guru->waliKelas : null;
        $kelas = $waliKelas ? $waliKelas->kelas : null;


        $stats = [
            'H' => 0,
            'S' => 0,
            'I' => 0,
            'A' => 0,
        ];

        if (!$user) {
            return redirect()->route('login');
        }

        // Admin/Kepsek: total global
        if (in_array($user->role, ['admin', 'kepala_sekolah'], true)) {
            $stats = Absensi::query()
                ->selectRaw('status, COUNT(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status')
                ->toArray() + $stats;
        }

        // Guru/BK: total absensi yang terkait jadwal guru
        if (in_array($user->role, ['guru', 'guru_bk'], true)) {
            $guruId = Guru::query()->where('user_id', $user->id)->value('guru_id');
            if ($guruId) {
                $stats = Absensi::query()
                    ->whereHas('jadwal', fn($q) => $q->where('guru_id', $guruId))
                    ->selectRaw('status, COUNT(*) as total')
                    ->groupBy('status')
                    ->pluck('total', 'status')
                    ->toArray() + $stats;
            }
        }

        // Orang tua: absensi anak-anaknya
        if ($user->role === 'orang_tua') {
            $ortuId = OrangTua::query()->where('user_id', $user->id)->value('ortu_id');
            if ($ortuId) {
                $stats = Absensi::query()
                    ->whereHas('siswa', fn($q) => $q->where('ortu_id', $ortuId))
                    ->selectRaw('status, COUNT(*) as total')
                    ->groupBy('status')
                    ->pluck('total', 'status')
                    ->toArray() + $stats;
            }
        }

        // Wali kelas: absensi siswa di kelas binaan
        if ($user->role === 'wali_kelas') {
            $guruId = Guru::query()->where('user_id', $user->id)->value('guru_id');
            $kelasId = $guruId ? WaliKelas::query()->where('guru_id', $guruId)->value('kelas_id') : null;
            if ($kelasId) {
                $stats = Absensi::query()
                    ->whereHas('siswa', fn($q) => $q->where('kelas_id', $kelasId))
                    ->selectRaw('status, COUNT(*) as total')
                    ->groupBy('status')
                    ->pluck('total', 'status')
                    ->toArray() + $stats;
            }
        }

        $unreadSuratCount = 0;
        if ($user->role === 'guru') {
            $guruId = \App\Models\Guru::where('user_id', $user->id)->value('guru_id');
            $unreadSuratCount = \App\Models\SuratIzin::whereHas('jadwal', function ($q) use ($guruId) {
                $q->where('guru_id', $guruId);
            })->where('status', 'pending')->count();
        }

        return view('dashboard', compact('user', 'stats', 'kelas', 'unreadSuratCount'));

        return view('dashboard', [
            'user' => $user,
            'stats' => $stats,
            'kelas' => $kelas
        ]);
    }
}
