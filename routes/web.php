<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController; // Tambahkan ini
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\MapelController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Guru\AbsensiController;
use App\Http\Controllers\Guru\SuratIzinInboxController;
use App\Http\Controllers\Guru\RekapKehadiranController;
use App\Http\Controllers\OrangTua\SuratIzinController;
use App\Http\Controllers\OrangTua\JadwalAnakController;
use App\Http\Controllers\WaliKelas\ValidasiAbsensiController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('dashboard'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// GRUP UTAMA: Hanya gunakan 'auth' agar semua yang login bisa masuk Dashboard & Profil
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- RUTE PROFIL (Baru ditambahkan agar ikon profil berfungsi) ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // -----------------------------------------------------------------

    // GRUP ADMIN
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        // Route::get('users/export/pdf', [UserController::class, 'exportPdf'])->name('users.export.pdf');
        Route::get('guru/export/pdf', [GuruController::class, 'exportPdf'])->name('guru.export.pdf');
        Route::get('kelas/export/pdf', [KelasController::class, 'exportPdf'])->name('kelas.export.pdf');
        Route::get('mapel/export/pdf', [MapelController::class, 'exportPdf'])->name('mapel.export.pdf');
        Route::get('jadwal/export/pdf', [JadwalController::class, 'exportPdf'])->name('jadwal.export.pdf');
        Route::get('siswa/export/pdf', [SiswaController::class, 'exportPdf'])->name('siswa.export.pdf');

        Route::resource('users', UserController::class);
        Route::get('/admin/users/export/pdf', [UserController::class, 'exportPdf'])->name('users.export.pdf');

        Route::resource('guru', GuruController::class);
        Route::resource('kelas', KelasController::class);
        Route::resource('mapel', MapelController::class);
        Route::resource('jadwal', JadwalController::class);
        Route::resource('siswa', SiswaController::class);


    });

    // GRUP ORANG TUA
    Route::prefix('orang-tua')->name('ortu.')->middleware('role:orang_tua')->group(function () {
        Route::resource('surat-izin', SuratIzinController::class)->only(['index', 'create', 'store', 'show']);
        Route::resource('jadwal', JadwalAnakController::class)->only(['index', 'show']);
    });

    // GRUP GURU
    Route::prefix('guru')->name('guru.')->middleware('role:guru')->group(function () {
        Route::get('absensi', [AbsensiController::class, 'index'])->name('absensi.index');
        Route::get('absensi/jadwal/{jadwal}', [AbsensiController::class, 'showForm'])->name('absensi.form');
        Route::post('absensi/jadwal/{jadwal}', [AbsensiController::class, 'store'])->name('absensi.store');
        Route::get('rekap-kehadiran', [RekapKehadiranController::class, 'index'])->name('rekap-kehadiran.index');
        Route::get('surat-izin', [SuratIzinInboxController::class, 'index'])->name('surat-izin.index');
        Route::get('surat-izin/{suratIzin}', [SuratIzinInboxController::class, 'show'])->name('surat-izin.show');
        Route::post('surat-izin/{suratIzin}/terima', [SuratIzinInboxController::class, 'accept'])->name('surat-izin.accept');
        Route::post('surat-izin/{suratIzin}/tolak', [SuratIzinInboxController::class, 'reject'])->name('surat-izin.reject');
        Route::get('rekap-kehadiran/pdf', [RekapKehadiranController::class, 'exportPdf'])->name('rekap-kehadiran.pdf');
        Route::get('absensi/rekap/{jadwal}', [AbsensiController::class, 'rekapJadwal'])->name('absensi.rekap-jadwal');
        Route::get('absensi/rekap/{jadwal}/pdf', [AbsensiController::class, 'exportPdf'])->name('absensi.rekap-pdf');
    });

    // GRUP WALI KELAS
    Route::prefix('wali-kelas')->name('wali.')->middleware('role:wali_kelas')->group(function () {
        Route::get('validasi', [ValidasiAbsensiController::class, 'index'])->name('validasi.index');
        Route::post('validasi', [ValidasiAbsensiController::class, 'validateBatch'])->name('validasi.batch');
        Route::get('rekap', [ValidasiAbsensiController::class, 'rekap'])->name('rekap.index');
        Route::get('rekap/pdf', [ValidasiAbsensiController::class, 'exportPdf'])->name('rekap.pdf');
    });

    // GRUP BK
    Route::prefix('bk')->name('bk.')->middleware('role:guru_bk')->group(function () {
        Route::get('rekap', [\App\Http\Controllers\BK\RekapController::class, 'index'])->name('rekap.index');
        Route::get('rekap/export/pdf', [\App\Http\Controllers\BK\RekapController::class, 'exportPdf'])->name('rekap.export.pdf');
    });

    // GRUP KEPSEK
    Route::prefix('kepsek')->name('kepsek.')->middleware('role:kepala_sekolah')->group(function () {
        Route::get('laporan', [\App\Http\Controllers\Kepsek\LaporanController::class, 'index'])->name('laporan.index');
        Route::get('laporan/export/pdf', [\App\Http\Controllers\Kepsek\LaporanController::class, 'exportPdf'])->name('laporan.export.pdf');
    });
});