<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\OrangTua;
use App\Models\Siswa;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Tambahkan relasi 'jurusan' jika kelas memilikinya, agar query lebih efisien
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
        // Mengambil data pendukung untuk form tambah
        $kelas = Kelas::query()->orderBy('tingkat')->orderBy('nama_kelas')->get();
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
            'ortu_id' => ['required', 'exists:orang_tuas,ortu_id'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $request->nis . '.' . $file->getClientOriginalExtension();

            // Simpan ke storage/app/public/siswa
            $file->storeAs('siswa', $filename, 'public');

            // Cek permission folder
            if (!Storage::disk('public')->exists('siswa/' . $filename)) {
                return back()->withInput()->withErrors(['foto' => 'Gagal menulis file. Pastikan folder storage memiliki izin akses (chmod).']);
            }

            $data['foto'] = $filename;
        }

        Siswa::create($data);

        return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $item = Siswa::query()->findOrFail($id);
        $kelas = Kelas::query()->orderBy('tingkat')->orderBy('nama_kelas')->get();
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
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($item->foto) {
                Storage::disk('public')->delete('siswa/' . $item->foto);
            }

            $file = $request->file('foto');
            $filename = time() . '_' . $request->nis . '.' . $file->getClientOriginalExtension();
            $file->storeAs('siswa', $filename, 'public');

            $data['foto'] = $filename;
        }

        $item->update($data);

        return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = Siswa::query()->findOrFail($id);

        if ($item->foto) {
            Storage::disk('public')->delete('siswa/' . $item->foto);
        }

        $item->delete();

        return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil dihapus.');
    }

    public function show(string $id)
    {
        return redirect()->route('admin.siswa.edit', $id);
    }

    public function exportPdf()
    {
        // Pastikan relasi juga dimuat di PDF
        $items = Siswa::query()->with(['kelas', 'orangTua'])->orderBy('nis')->get();
        $pdf = Pdf::loadView('admin.siswa.pdf', compact('items'))->setPaper('a4', 'landscape');
        return $pdf->download('siswa-smkn2-rupat.pdf');
    }
}