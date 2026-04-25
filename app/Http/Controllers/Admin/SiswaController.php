<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\OrangTua;
use App\Models\Siswa;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Siswa::query()
            ->with(['kelas', 'orangTua'])
            ->orderBy('nis')
            ->paginate(15);

        return view('admin.siswa.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kelas = Kelas::query()->orderBy('tingkat')->orderBy('jurusan')->orderBy('nama_kelas')->get();
        $ortu = OrangTua::query()->orderBy('nama_ortu')->get();
        return view('admin.siswa.create', compact('kelas', 'ortu'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nis' => ['required', 'string', 'max:50', 'unique:siswas,nis'],
            'nama_siswa' => ['required', 'string', 'max:255'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'alamat' => ['nullable', 'string'],
            'kelas_id' => ['required', 'exists:kelas,kelas_id'],
            'ortu_id' => ['nullable', 'exists:orang_tuas,ortu_id'],
            'foto' => ['nullable', 'string', 'max:255'],
        ]);

        Siswa::create($data);

        return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('admin.siswa.edit', $id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $item = Siswa::query()->findOrFail($id);
        $kelas = Kelas::query()->orderBy('tingkat')->orderBy('jurusan')->orderBy('nama_kelas')->get();
        $ortu = OrangTua::query()->orderBy('nama_ortu')->get();
        return view('admin.siswa.edit', compact('item', 'kelas', 'ortu'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $item = Siswa::query()->findOrFail($id);
        $data = $request->validate([
            'nis' => ['required', 'string', 'max:50', 'unique:siswas,nis,' . $item->siswa_id . ',siswa_id'],
            'nama_siswa' => ['required', 'string', 'max:255'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'alamat' => ['nullable', 'string'],
            'kelas_id' => ['required', 'exists:kelas,kelas_id'],
            'ortu_id' => ['nullable', 'exists:orang_tuas,ortu_id'],
            'foto' => ['nullable', 'string', 'max:255'],
        ]);

        $item->update($data);

        return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = Siswa::query()->findOrFail($id);
        $item->delete();

        return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil dihapus.');
    }

    public function exportPdf()
    {
        $items = Siswa::query()->with(['kelas', 'orangTua'])->orderBy('nis')->get();
        $pdf = Pdf::loadView('admin.siswa.pdf', compact('items'))->setPaper('a4', 'landscape');
        return $pdf->download('siswa.pdf');
    }
}
