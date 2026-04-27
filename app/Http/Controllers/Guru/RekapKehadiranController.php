<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RekapKehadiranController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user->guru) {
            return redirect()->route('dashboard')->with('error', 'Data profil Guru tidak ditemukan.');
        }

        $guruId = $user->guru->guru_id;
        $tanggal = $request->get('tanggal', date('Y-m-d'));

        // GANTI 'tanggal_absen' dengan nama kolom yang benar di tabel Anda (misal: 'tanggal')
        $namaKolomTanggal = 'tanggal';

        $items = Siswa::whereHas('absensi', function ($query) use ($guruId, $tanggal, $namaKolomTanggal) {
            $query->whereHas('jadwal', function ($q) use ($guruId) {
                $q->where('guru_id', $guruId);
            })->whereDate($namaKolomTanggal, $tanggal);
        })
            ->withCount([
                'absensi as total_hadir' => fn($q) => $q->where('status', 'H')->whereDate($namaKolomTanggal, $tanggal),
                'absensi as total_sakit' => fn($q) => $q->where('status', 'S')->whereDate($namaKolomTanggal, $tanggal),
                'absensi as total_izin' => fn($q) => $q->where('status', 'I')->whereDate($namaKolomTanggal, $tanggal),
                'absensi as total_alfa' => fn($q) => $q->where('status', 'A')->whereDate($namaKolomTanggal, $tanggal),
            ])
            ->paginate(10)
            ->withQueryString();

        return view('guru.rekap-kehadiran.index', compact('items', 'tanggal'));
    }

    public function exportPdf(Request $request)
    {
        $user = Auth::user();
        $guruId = $user->guru->guru_id;
        $tanggal = $request->get('tanggal', date('Y-m-d'));
        $namaKolomTanggal = 'tanggal'; // Sesuaikan dengan kolom di tabel absensis Anda

        $items = Siswa::whereHas('absensi', function ($query) use ($guruId, $tanggal, $namaKolomTanggal) {
            $query->whereHas('jadwal', function ($q) use ($guruId) {
                $q->where('guru_id', $guruId);
            })->whereDate($namaKolomTanggal, $tanggal);
        })
            ->withCount([
                'absensi as total_hadir' => fn($q) => $q->where('status', 'H')->whereDate($namaKolomTanggal, $tanggal),
                'absensi as total_sakit' => fn($q) => $q->where('status', 'S')->whereDate($namaKolomTanggal, $tanggal),
                'absensi as total_izin' => fn($q) => $q->where('status', 'I')->whereDate($namaKolomTanggal, $tanggal),
                'absensi as total_alfa' => fn($q) => $q->where('status', 'A')->whereDate($namaKolomTanggal, $tanggal),
            ])->get();

        // Menggunakan library barryvdh/laravel-dompdf
        $pdf = \PDF::loadView('guru.rekap-kehadiran.pdf', compact('items', 'tanggal', 'user'));
        return $pdf->stream('Rekap_Kehadiran_' . $tanggal . '.pdf');
    }
}