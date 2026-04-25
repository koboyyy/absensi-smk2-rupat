<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GuruController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Guru::query()->with('user')->orderBy('nama_guru')->paginate(15);
        return view('admin.guru.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.guru.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'password' => ['required', 'string', 'min:6'],
            'status' => ['required', 'in:aktif,nonaktif'],
            'nama_guru' => ['required', 'string', 'max:255'],
            'nip' => ['nullable', 'string', 'max:50'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'no_hp' => ['nullable', 'string', 'max:30'],
            'alamat' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($data) {
            $user = User::create([
                'username' => $data['username'],
                'password' => $data['password'],
                'role' => 'guru',
                'status' => $data['status'],
            ]);

            Guru::create([
                'user_id' => $user->id,
                'nama_guru' => $data['nama_guru'],
                'nip' => $data['nip'] ?? null,
                'jenis_kelamin' => $data['jenis_kelamin'],
                'no_hp' => $data['no_hp'] ?? null,
                'alamat' => $data['alamat'] ?? null,
            ]);
        });

        return redirect()->route('admin.guru.index')->with('success', 'Data guru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('admin.guru.edit', $id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $item = Guru::query()->with('user')->findOrFail($id);
        return view('admin.guru.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $item = Guru::query()->with('user')->findOrFail($id);
        $data = $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $item->user_id],
            'password' => ['nullable', 'string', 'min:6'],
            'status' => ['required', 'in:aktif,nonaktif'],
            'nama_guru' => ['required', 'string', 'max:255'],
            'nip' => ['nullable', 'string', 'max:50'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'no_hp' => ['nullable', 'string', 'max:30'],
            'alamat' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($item, $data) {
            $item->user->update([
                'username' => $data['username'],
                'status' => $data['status'],
            ]);

            if (! empty($data['password'])) {
                $item->user->update(['password' => $data['password']]);
            }

            $item->update([
                'nama_guru' => $data['nama_guru'],
                'nip' => $data['nip'] ?? null,
                'jenis_kelamin' => $data['jenis_kelamin'],
                'no_hp' => $data['no_hp'] ?? null,
                'alamat' => $data['alamat'] ?? null,
            ]);
        });

        return redirect()->route('admin.guru.index')->with('success', 'Data guru berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = Guru::query()->with('user')->findOrFail($id);
        DB::transaction(function () use ($item) {
            $item->delete();
            $item->user?->delete();
        });

        return redirect()->route('admin.guru.index')->with('success', 'Data guru berhasil dihapus.');
    }

    public function exportPdf()
    {
        $items = Guru::query()->with('user')->orderBy('nama_guru')->get();
        $pdf = Pdf::loadView('admin.guru.pdf', compact('items'))->setPaper('a4', 'landscape');
        return $pdf->download('guru.pdf');
    }
}
