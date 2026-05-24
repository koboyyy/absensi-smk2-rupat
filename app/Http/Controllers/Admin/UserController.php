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
     * LIST USER
     */
    public function index(Request $request)
    {
        $search = $request->search;

        $items = User::query()
            ->when($search, function ($query) use ($search) {

                $query->where('username', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%')
                    ->orWhereJsonContains('roles', $search);

            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', compact('items', 'search'));
    }

    /**
     * FORM CREATE
     */
    public function create()
    {
        $roles = [
            'guru',
            'wali_kelas',
            'kepala_sekolah',
            'orang_tua',
            'guru_bk'
        ];

        $kelas = Kelas::all();

        return view('admin.users.create', compact('roles', 'kelas'));
    }

    /**
     * STORE USER
     */
    public function store(Request $request)
    {
        $data = $request->validate([

            'username' => [
                'required',
                'string',
                'max:255',
                'unique:users,username'
            ],

            'password' => [
                'required',
                'string',
                'min:6'
            ],

            'roles' => [
                'required',
                'array'
            ],

            'roles.*' => [
                'in:admin,guru,wali_kelas,kepala_sekolah,orang_tua,guru_bk'
            ],

            'status' => [
                'required',
                'in:aktif,nonaktif'
            ],

            'nama' => [
                'nullable',
                'string',
                'max:255'
            ],

            'nip' => [
                'nullable',
                'string',
                'max:50'
            ],

            'kelas_id' => [
                'nullable',
                'exists:kelas,kelas_id'
            ],

            'no_hp' => [
                'nullable',
                'string',
                'max:30'
            ],

            'alamat' => [
                'nullable',
                'string'
            ],

        ]);

        DB::transaction(function () use ($data) {

            /**
             * CREATE USER
             */
            $user = User::create([
                'username' => $data['username'],
                'password' => Hash::make($data['password']),
                'roles' => $data['roles'],
                'status' => $data['status'],
            ]);

            /**
             * ROLE GURU / BK / WALI / KEPSEK
             */
            $isGuruGroup = collect($data['roles'])->intersect([
                'guru',
                'wali_kelas',
                'kepala_sekolah',
                'guru_bk'
            ])->isNotEmpty();

            if ($isGuruGroup) {

                $guru = Guru::create([
                    'user_id' => $user->id,
                    'nama_guru' => $data['nama'] ?? $data['username'],
                    'nip' => $data['nip'] ?? null,
                    'jenis_kelamin' => 'L',
                    'no_hp' => $data['no_hp'] ?? null,
                    'alamat' => $data['alamat'] ?? null,
                ]);

                /**
                 * JIKA WALI KELAS
                 */
                if (
                    in_array('wali_kelas', $data['roles']) &&
                    !empty($data['kelas_id'])
                ) {

                    WaliKelas::updateOrCreate(
                        [
                            'kelas_id' => $data['kelas_id']
                        ],
                        [
                            'guru_id' => $guru->guru_id
                        ]
                    );
                }
            }

            /**
             * ROLE ORANG TUA
             */
            if (in_array('orang_tua', $data['roles'])) {

                OrangTua::create([
                    'user_id' => $user->id,
                    'nama_ortu' => $data['nama'] ?? $data['username'],
                    'no_hp' => $data['no_hp'] ?? null,
                    'alamat' => $data['alamat'] ?? null,
                ]);
            }

        });

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Akun berhasil dibuat.');
    }

    /**
     * FORM EDIT
     */
    public function edit(string $id)
    {
        $item = User::findOrFail($id);

        $roles = [
            'admin',
            'guru',
            'wali_kelas',
            'kepala_sekolah',
            'orang_tua',
            'guru_bk'
        ];

        $kelas = Kelas::all();

        /**
         * AMBIL KELAS WALI
         */
        if (
            $item->guru &&
            in_array('wali_kelas', $item->roles ?? [])
        ) {

            $wali = WaliKelas::where(
                'guru_id',
                $item->guru->guru_id
            )->first();

            $item->kelas_id = $wali?->kelas_id;
        }

        return view('admin.users.edit', compact(
            'item',
            'roles',
            'kelas'
        ));
    }

    /**
     * UPDATE USER
     */
    public function update(Request $request, string $id)
    {
        $item = User::findOrFail($id);

        $data = $request->validate([

            'username' => [
                'required',
                'string',
                'max:255',
                'unique:users,username,' . $item->id
            ],

            'password' => [
                'nullable',
                'string',
                'min:6'
            ],

            'roles' => [
                'required',
                'array'
            ],

            'roles.*' => [
                'in:admin,guru,wali_kelas,kepala_sekolah,orang_tua,guru_bk'
            ],

            'status' => [
                'required',
                'in:aktif,nonaktif'
            ],

            'nip' => [
                'nullable',
                'string',
                'max:50'
            ],

            'kelas_id' => [
                'nullable',
                'exists:kelas,kelas_id'
            ],

        ]);

        DB::transaction(function () use ($item, $data) {

            /**
             * UPDATE USER
             */
            $updateData = [
                'username' => $data['username'],
                'roles' => $data['roles'],
                'status' => $data['status'],
            ];

            if (!empty($data['password'])) {
                $updateData['password'] = Hash::make($data['password']);
            }

            $item->update($updateData);

            /**
             * CEK ROLE GURU GROUP
             */
            $isGuruGroup = collect($data['roles'])->intersect([
                'guru',
                'wali_kelas',
                'kepala_sekolah',
                'guru_bk'
            ])->isNotEmpty();

            /**
             * JIKA ADA ROLE GURU
             */
            if ($isGuruGroup) {

                $guru = Guru::firstOrCreate(
                    [
                        'user_id' => $item->id
                    ],
                    [
                        'nama_guru' => $item->username,
                        'jenis_kelamin' => 'L'
                    ]
                );

                $guru->update([
                    'nip' => $data['nip'] ?? null
                ]);

                /**
                 * UPDATE WALI KELAS
                 */
                if (
                    in_array('wali_kelas', $data['roles']) &&
                    !empty($data['kelas_id'])
                ) {

                    WaliKelas::where(
                        'guru_id',
                        $guru->guru_id
                    )->delete();

                    WaliKelas::updateOrCreate(
                        [
                            'kelas_id' => $data['kelas_id']
                        ],
                        [
                            'guru_id' => $guru->guru_id
                        ]
                    );

                } else {

                    WaliKelas::where(
                        'guru_id',
                        $guru->guru_id
                    )->delete();
                }

            } else {

                /**
                 * HAPUS GURU JIKA TIDAK ADA ROLE GURU
                 */
                $guru = Guru::where(
                    'user_id',
                    $item->id
                )->first();

                if ($guru) {

                    WaliKelas::where(
                        'guru_id',
                        $guru->guru_id
                    )->delete();

                    $guru->delete();
                }
            }

            /**
             * ROLE ORANG TUA
             */
            if (in_array('orang_tua', $data['roles'])) {

                OrangTua::updateOrCreate(
                    [
                        'user_id' => $item->id
                    ],
                    [
                        'nama_ortu' => $item->username
                    ]
                );

            } else {

                OrangTua::where(
                    'user_id',
                    $item->id
                )->delete();
            }

        });

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Akun berhasil diperbarui.');
    }

    /**
     * DELETE USER
     */
    public function destroy(string $id)
    {
        $item = User::findOrFail($id);

        DB::transaction(function () use ($item) {

            /**
             * DELETE ORANG TUA
             */
            OrangTua::where(
                'user_id',
                $item->id
            )->delete();

            /**
             * DELETE GURU
             */
            $guru = Guru::where(
                'user_id',
                $item->id
            )->first();

            if ($guru) {

                WaliKelas::where(
                    'guru_id',
                    $guru->guru_id
                )->delete();

                $guru->delete();
            }

            /**
             * DELETE USER
             */
            $item->delete();

        });

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Akun berhasil dihapus.');
    }

    /**
     * EXPORT PDF
     */
    public function exportPdf()
    {
        $items = User::latest()->get();

        $pdf = Pdf::loadView(
            'admin.users.pdf',
            compact('items')
        )->setPaper('a4', 'portrait');

        return $pdf->download('data-users.pdf');
    }
}