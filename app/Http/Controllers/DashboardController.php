<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\OrangTua;
use App\Models\Guru;
use App\Models\WaliKelas;
use App\Models\SuratIzin;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // --- LOGIKA REDIRECT BERDASARKAN ROLE ---

        // Kepala Sekolah dan Guru BK langsung ke halaman Rekap
        if ($user->role === 'guru_bk') {
            return redirect()->route('bk.rekap.index'); // Sesuaikan dengan nama route rekap Anda
        }

        // Wali Kelas langsung ke halaman Absensi
        if ($user->role === 'kepala_sekolah') {
            return redirect()->route('kepsek.laporan.index'); // Sesuaikan dengan nama route absensi Anda
        }

        // Wali Kelas langsung ke halaman Absensi
        if ($user->role === 'wali_kelas') {
            return redirect()->route('wali.rekap.index'); // Sesuaikan dengan nama route absensi Anda
        }

        // --- LOGIKA DASHBOARD UNTUK ROLE LAIN (Admin, Guru, Orang Tua) ---

        $guru = Guru::where('user_id', $user->id)->first();
        $waliKelas = $guru ? $guru->waliKelas : null;
        $kelas = $waliKelas ? $waliKelas->kelas : null;

        $stats = [
            'H' => 0,
            'S' => 0,
            'I' => 0,
            'A' => 0,
        ];

        // Admin: total global (Jika Admin tidak di-redirect)
        if ($user->role === 'admin') {
            // $stats = Absensi::query()
            //     ->selectRaw('status, COUNT(*) as total')
            //     ->groupBy('status')
            //     ->pluck('total', 'status')
            //     ->toArray() + $stats;
            return redirect()->route('admin.users.index');
        }

        // Guru: total absensi yang terkait jadwal guru tersebut
        if ($user->role === 'guru') {
            $guruId = $guru ? $guru->guru_id : null;
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
            // $ortuId = OrangTua::query()->where('user_id', $user->id)->value('ortu_id');
            // if ($ortuId) {
            //     $stats = Absensi::query()
            //         ->whereHas('siswa', fn($q) => $q->where('ortu_id', $ortuId))
            //         ->selectRaw('status, COUNT(*) as total')
            //         ->groupBy('status')
            //         ->pluck('total', 'status')
            //         ->toArray() + $stats;
            // }
            return redirect()->route('ortu.jadwal.index');
        }

        // Menghitung surat izin pending khusus untuk Guru
        $unreadSuratCount = 0;
        if ($user->role === 'guru' && $guru) {
            $unreadSuratCount = SuratIzin::whereHas('jadwal', function ($q) use ($guru) {
                $q->where('guru_id', $guru->guru_id);
            })->where('status', 'pending')->count();
        }

        return view('dashboard', compact('user', 'stats', 'kelas', 'unreadSuratCount'));
    }
}