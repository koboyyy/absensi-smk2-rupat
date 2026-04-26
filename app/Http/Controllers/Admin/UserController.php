<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\OrangTua;
use App\Models\Guru;
use App\Models\Kelas;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = User::query()->orderBy('role')->orderBy('username')->paginate(15);
        return view('admin.users.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = ['admin', 'guru', 'wali_kelas', 'kepala_sekolah', 'orang_tua', 'guru_bk'];
        $kelas = Kelas::all();
        return view('admin.users.create', compact('roles', 'kelas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', 'in:admin,guru,wali_kelas,kepala_sekolah,orang_tua,guru_bk'],
            'status' => ['required', 'in:aktif,nonaktif'],
            'nama' => ['nullable', 'string', 'max:255'],
            'no_hp' => ['nullable', 'string', 'max:30'],
            'alamat' => ['nullable', 'string'],
            // Tambahkan input kelas_id jika role adalah wali_kelas
            'kelas_id' => ['nullable', 'exists:kelas,kelas_id'],
        ]);

        DB::transaction(function () use ($data) {
            // 1. Buat akun di users
            $user = User::create([
                'username' => $data['username'],
                'password' => Hash::make($data['password']),
                'role' => $data['role'],
                'status' => $data['status'],
            ]);

            // 2. Buat data profil Guru (untuk semua role pendidikan)
            if (in_array($data['role'], ['guru', 'wali_kelas', 'kepala_sekolah', 'guru_bk'])) {
                $guru = Guru::create([
                    'user_id' => $user->id,
                    'nama_guru' => $data['nama'] ?? $data['username'],
                    'nip' => null,
                    'jenis_kelamin' => 'L',
                    'no_hp' => $data['no_hp'] ?? null,
                    'alamat' => $data['alamat'] ?? null,
                ]);

                // 3. LOGIKA BARU: Jika role wali_kelas, isi tabel wali_kelas
                if ($data['role'] === 'wali_kelas') {
                    \App\Models\WaliKelas::create([
                        'guru_id' => $guru->guru_id,
                        'kelas_id' => $data['kelas_id'] ?? 1, // Pastikan ada input kelas_id dari form
                    ]);
                }
            } elseif ($data['role'] === 'orang_tua') {
                OrangTua::create([
                    'user_id' => $user->id,
                    'nama_ortu' => $data['nama'] ?? $data['username'],
                    'no_hp' => $data['no_hp'] ?? null,
                    'alamat' => $data['alamat'] ?? null,
                ]);
            }
        });

        return redirect()->route('admin.users.index')->with('success', 'Akun berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('admin.users.edit', $id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $item = User::query()->findOrFail($id);
        $roles = ['admin', 'guru', 'wali_kelas', 'kepala_sekolah', 'orang_tua', 'guru_bk'];
        return view('admin.users.edit', compact('item', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $item = User::query()->findOrFail($id);
        $data = $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $item->id],
            'password' => ['nullable', 'string', 'min:6'],
            'role' => ['required', 'in:admin,guru,wali_kelas,kepala_sekolah,orang_tua,guru_bk'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);

        $oldRole = $item->role;

        $update = [
            'username' => $data['username'],
            'role' => $data['role'],
            'status' => $data['status'],
        ];
        if (!empty($data['password'])) {
            $update['password'] = Hash::make($data['password']);
        }
        $item->update($update);

        // Jika role berubah, sinkronkan tabel profil
        if ($oldRole !== $data['role']) {
            // Hapus profil lama
            if ($oldRole === 'orang_tua') {
                OrangTua::where('user_id', $item->id)->delete();
            } elseif (in_array($oldRole, ['guru', 'wali_kelas', 'kepala_sekolah', 'guru_bk'])) {
                Guru::where('user_id', $item->id)->delete();
            }

            // Buat profil baru
            if ($data['role'] === 'orang_tua') {
                OrangTua::create([
                    'user_id' => $item->id,
                    'nama_ortu' => $data['username'],
                    'no_hp' => null,
                    'alamat' => null,
                ]);
            } elseif (in_array($data['role'], ['guru', 'wali_kelas', 'kepala_sekolah', 'guru_bk'])) {
                Guru::create([
                    'user_id' => $item->id,
                    'nama_guru' => $data['username'],
                    'nip' => null,
                    'jenis_kelamin' => 'L',
                    'no_hp' => null,
                    'alamat' => null,
                ]);
            }
        }

        return redirect()->route('admin.users.index')->with('success', 'Akun berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = User::query()->findOrFail($id);

        // Hapus profil terkait (FK sudah cascadeOnDelete, tapi ini lebih eksplisit)
        if ($item->role === 'orang_tua') {
            OrangTua::where('user_id', $item->id)->delete();
        } elseif (in_array($item->role, ['guru', 'wali_kelas', 'kepala_sekolah', 'guru_bk'])) {
            Guru::where('user_id', $item->id)->delete();
        }

        $item->delete();

        return redirect()->route('admin.users.index')->with('success', 'Akun berhasil dihapus.');
    }

    public function exportPdf()
    {
        $items = User::query()->orderBy('role')->orderBy('username')->get();
        $pdf = Pdf::loadView('admin.users.pdf', compact('items'))->setPaper('a4', 'portrait');
        return $pdf->download('users.pdf');
    }
}