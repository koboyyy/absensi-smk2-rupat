<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\OrangTua;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\WaliKelas;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;

        $items = User::query()
            ->when($search, function ($query) use ($search) {
                $query->where('username', 'like', '%' . $search . '%')
                    ->orWhere('role', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%');
            })
            ->orderBy('role')
            ->orderBy('username')
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', compact('items', 'search'));
    }

    public function create()
    {
        $roles = ['admin', 'guru', 'wali_kelas', 'kepala_sekolah', 'orang_tua', 'guru_bk'];
        $kelas = Kelas::all();
        return view('admin.users.create', compact('roles', 'kelas'));
    }

    public function edit(string $id)
    {
        $item = User::query()->findOrFail($id);
        $kelas = Kelas::all();
        $roles = ['admin', 'guru', 'wali_kelas', 'kepala_sekolah', 'orang_tua', 'guru_bk'];

        // Ambil kelas_id jika user ini adalah wali kelas untuk ditampilkan di form
        if ($item->role === 'wali_kelas' && $item->guru) {
            $currentWali = WaliKelas::where('guru_id', $item->guru->guru_id)->first();
            $item->kelas_id = $currentWali ? $currentWali->kelas_id : null;
        }

        return view('admin.users.edit', compact('item', 'roles', 'kelas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', 'in:admin,guru,wali_kelas,kepala_sekolah,orang_tua,guru_bk'],
            'status' => ['required', 'in:aktif,nonaktif'],
            // Tambahkan NIP ke validasi: wajib diisi untuk Kepsek, BK, dan Wali Kelas
            'nip' => ['required_if:role,kepala_sekolah,guru_bk,wali_kelas', 'nullable', 'string', 'max:50'],
            'kelas_id' => ['required_if:role,wali_kelas', 'nullable', 'exists:kelas,kelas_id'],
            'nama' => ['nullable', 'string', 'max:255'],
            'no_hp' => ['nullable', 'string', 'max:30'],
            'alamat' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($data) {
            // 1. Buat akun di users
            $user = User::create([
                'username' => $data['username'],
                'password' => Hash::make($data['password']),
                'role' => $data['role'],
                'status' => $data['status'],
            ]);

            // 2. Buat data profil
            if (in_array($data['role'], ['guru', 'wali_kelas', 'kepala_sekolah', 'guru_bk'])) {
                $guru = Guru::create([
                    'user_id' => $user->id,
                    'nama_guru' => $data['nama'] ?? $data['username'],
                    'nip' => $data['nip'] ?? null, // Simpan NIP di sini
                    'jenis_kelamin' => 'L',
                    'no_hp' => $data['no_hp'] ?? null,
                    'alamat' => $data['alamat'] ?? null,
                ]);

                // 3. Jika role wali_kelas, hubungkan ke tabel wali_kelas
                if ($data['role'] === 'wali_kelas' && !empty($data['kelas_id'])) {
                    WaliKelas::updateOrCreate(
                        ['kelas_id' => $data['kelas_id']],
                        ['guru_id' => $guru->guru_id]
                    );
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

    public function update(Request $request, string $id)
    {
        $item = User::query()->findOrFail($id);
        $data = $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $item->id],
            'password' => ['nullable', 'string', 'min:6'],
            'role' => ['required', 'in:admin,guru,wali_kelas,kepala_sekolah,orang_tua,guru_bk'],
            'status' => ['required', 'in:aktif,nonaktif'],
            'nip' => ['required_if:role,kepala_sekolah,guru_bk,wali_kelas', 'nullable', 'string', 'max:50'],
            'kelas_id' => ['required_if:role,wali_kelas', 'nullable', 'exists:kelas,kelas_id'],
        ]);

        DB::transaction(function () use ($item, $data) {
            $oldRole = $item->role;

            // 1. Update User
            $updateData = [
                'username' => $data['username'],
                'role' => $data['role'],
                'status' => $data['status'],
            ];
            if (!empty($data['password'])) {
                $updateData['password'] = Hash::make($data['password']);
            }
            $item->update($updateData);

            // 2. Sinkronisasi Profil Jika Role Berubah
            if ($oldRole !== $data['role']) {
                $this->handleRoleSwitch($item, $oldRole, $data['role']);
            }

            // 3. Update NIP di tabel Guru (jika rolenya masuk kelompok guru)
            if (in_array($data['role'], ['guru', 'wali_kelas', 'kepala_sekolah', 'guru_bk'])) {
                Guru::where('user_id', $item->id)->update([
                    'nip' => $data['nip'] ?? null
                ]);
            }

            // 4. Update data Wali Kelas
            if ($data['role'] === 'wali_kelas') {
                $guru = Guru::where('user_id', $item->id)->first();
                if ($guru) {
                    WaliKelas::where('guru_id', $guru->guru_id)->delete();
                    WaliKelas::updateOrCreate(
                        ['kelas_id' => $data['kelas_id']],
                        ['guru_id' => $guru->guru_id]
                    );
                }
            } else {
                $guru = Guru::where('user_id', $item->id)->first();
                if ($guru) {
                    WaliKelas::where('guru_id', $guru->guru_id)->delete();
                }
            }
        });

        return redirect()->route('admin.users.index')->with('success', 'Akun berhasil diperbarui.');
    }

    /**
     * Helper untuk menangani perpindahan profil saat role berubah
     */
    private function handleRoleSwitch($user, $oldRole, $newRole)
    {
        // Hapus Profil Lama
        if ($oldRole === 'orang_tua') {
            OrangTua::where('user_id', $user->id)->delete();
        } elseif (in_array($oldRole, ['guru', 'wali_kelas', 'kepala_sekolah', 'guru_bk'])) {
            // Jika pindah sesama rumpun guru, tidak perlu hapus tabel Guru,
            // tapi jika pindah ke admin/ortu, baru hapus.
            if (!in_array($newRole, ['guru', 'wali_kelas', 'kepala_sekolah', 'guru_bk'])) {
                Guru::where('user_id', $user->id)->delete();
            }
        }

        // Buat Profil Baru (Jika belum ada)
        if ($newRole === 'orang_tua') {
            OrangTua::firstOrCreate(['user_id' => $user->id], [
                'nama_ortu' => $user->username,
            ]);
        } elseif (in_array($newRole, ['guru', 'wali_kelas', 'kepala_sekolah', 'guru_bk'])) {
            Guru::firstOrCreate(['user_id' => $user->id], [
                'nama_guru' => $user->username,
                'jenis_kelamin' => 'L',
            ]);
        }
    }

    public function destroy(string $id)
    {
        $item = User::query()->findOrFail($id);

        DB::transaction(function () use ($item) {
            if ($item->role === 'orang_tua') {
                OrangTua::where('user_id', $item->id)->delete();
            } elseif (in_array($item->role, ['guru', 'wali_kelas', 'kepala_sekolah', 'guru_bk'])) {
                $guru = Guru::where('user_id', $item->id)->first();
                if ($guru) {
                    WaliKelas::where('guru_id', $guru->guru_id)->delete();
                    $guru->delete();
                }
            }
            $item->delete();
        });

        return redirect()->route('admin.users.index')->with('success', 'Akun berhasil dihapus.');
    }

    public function exportPdf()
    {
        $items = User::query()
            ->orderBy('role')
            ->orderBy('username')
            ->get();

        $pdf = Pdf::loadView('admin.users.pdf', compact('items'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('data-users.pdf');
    }

}