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
    public function index(Request $request)
    {
        $search = $request->search;

        $kelasId = $request->kelas_id;

        $jurusan = $request->jurusan;

        // LIST KELAS
        $kelasList = Kelas::query()
            ->orderBy('tingkat')
            ->orderBy('jurusan')
            ->orderBy('nama_kelas')
            ->get();

        // LIST JURUSAN
        $jurusans = Kelas::query()
            ->select('jurusan')
            ->distinct()
            ->pluck('jurusan');

        $items = Siswa::query()

            ->with(['kelas', 'orangTua'])

            // SEARCH
            ->when($search, function ($query) use ($search) {

                $query->where('nis', 'like', '%' . $search . '%')

                    ->orWhere('nama_siswa', 'like', '%' . $search . '%')

                    ->orWhere('jenis_kelamin', 'like', '%' . $search . '%')

                    ->orWhere('alamat', 'like', '%' . $search . '%')

                    ->orWhereHas('kelas', function ($q) use ($search) {

                        $q->where('nama_kelas', 'like', '%' . $search . '%')

                            ->orWhere('jurusan', 'like', '%' . $search . '%');

                    })

                    ->orWhereHas('orangTua', function ($q) use ($search) {

                        $q->where('nama_ortu', 'like', '%' . $search . '%');

                    });
            })

            // FILTER KELAS
            ->when($kelasId, function ($query) use ($kelasId) {

                $query->where('kelas_id', $kelasId);

            })

            // FILTER JURUSAN
            ->when($jurusan, function ($query) use ($jurusan) {

                $query->whereHas('kelas', function ($q) use ($jurusan) {

                    $q->where('jurusan', $jurusan);

                });

            })

            ->orderBy('nis')

            ->paginate(15)

            ->withQueryString();

        return view('admin.siswa.index', compact(
            'items',
            'search',
            'kelasId',
            'jurusan',
            'kelasList',
            'jurusans'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kelas = Kelas::query()
            ->orderBy('tingkat')
            ->orderBy('nama_kelas')
            ->get();

        $ortu = OrangTua::query()
            ->orderBy('nama_ortu')
            ->get();

        return view('admin.siswa.create', compact(
            'kelas',
            'ortu'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nis' => [
                'required',
                'string',
                'max:50',
                'unique:siswas,nis'
            ],

            'nama_siswa' => [
                'required',
                'string',
                'max:255'
            ],

            'jenis_kelamin' => [
                'required',
                'in:L,P'
            ],

            'alamat' => [
                'nullable',
                'string'
            ],

            'kelas_id' => [
                'required',
                'exists:kelas,kelas_id'
            ],

            'ortu_id' => [
                'required',
                'exists:orang_tuas,ortu_id'
            ],

            'foto' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg',
                'max:2048'
            ],
        ]);

        // UPLOAD FOTO
        if ($request->hasFile('foto')) {

            $file = $request->file('foto');

            $filename =
                time() .
                '_' .
                $request->nis .
                '.' .
                $file->getClientOriginalExtension();

            $file->storeAs(
                'siswa',
                $filename,
                'public'
            );

            // VALIDASI FILE TERSIMPAN
            if (
                !Storage::disk('public')
                    ->exists('siswa/' . $filename)
            ) {

                return back()

                    ->withInput()

                    ->withErrors([
                        'foto' =>
                            'Gagal menulis file. Pastikan permission storage benar.'
                    ]);
            }

            $data['foto'] = $filename;
        }

        Siswa::create($data);

        return redirect()

            ->route('admin.siswa.index')

            ->with(
                'success',
                'Data siswa berhasil ditambahkan.'
            );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $item = Siswa::query()->findOrFail($id);

        $kelas = Kelas::query()
            ->orderBy('tingkat')
            ->orderBy('nama_kelas')
            ->get();

        $ortu = OrangTua::query()
            ->orderBy('nama_ortu')
            ->get();

        return view('admin.siswa.edit', compact(
            'item',
            'kelas',
            'ortu'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $item = Siswa::query()->findOrFail($id);

        $data = $request->validate([

            'nis' => [
                'required',
                'string',
                'max:50',
                'unique:siswas,nis,' .
                $item->siswa_id .
                ',siswa_id'
            ],

            'nama_siswa' => [
                'required',
                'string',
                'max:255'
            ],

            'jenis_kelamin' => [
                'required',
                'in:L,P'
            ],

            'alamat' => [
                'nullable',
                'string'
            ],

            'kelas_id' => [
                'required',
                'exists:kelas,kelas_id'
            ],

            'ortu_id' => [
                'nullable',
                'exists:orang_tuas,ortu_id'
            ],

            'foto' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg',
                'max:2048'
            ],
        ]);

        // UPDATE FOTO
        if ($request->hasFile('foto')) {

            // HAPUS FOTO LAMA
            if ($item->foto) {

                Storage::disk('public')
                    ->delete('siswa/' . $item->foto);
            }

            $file = $request->file('foto');

            $filename =
                time() .
                '_' .
                $request->nis .
                '.' .
                $file->getClientOriginalExtension();

            $file->storeAs(
                'siswa',
                $filename,
                'public'
            );

            $data['foto'] = $filename;
        }

        $item->update($data);

        return redirect()

            ->route('admin.siswa.index')

            ->with(
                'success',
                'Data siswa berhasil diperbarui.'
            );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = Siswa::query()->findOrFail($id);

        // HAPUS FOTO
        if ($item->foto) {

            Storage::disk('public')
                ->delete('siswa/' . $item->foto);
        }

        $item->delete();

        return redirect()

            ->route('admin.siswa.index')

            ->with(
                'success',
                'Data siswa berhasil dihapus.'
            );
    }

    /**
     * Redirect to edit page
     */
    public function show(string $id)
    {
        return redirect()->route(
            'admin.siswa.edit',
            $id
        );
    }

    /**
     * Export PDF
     */
    public function exportPdf(Request $request)
    {
        $search = $request->search;

        $kelasId = $request->kelas_id;

        $jurusan = $request->jurusan;

        $items = Siswa::query()

            ->with(['kelas', 'orangTua'])

            // SEARCH
            ->when($search, function ($query) use ($search) {

                $query->where('nis', 'like', '%' . $search . '%')

                    ->orWhere('nama_siswa', 'like', '%' . $search . '%')

                    ->orWhere('jenis_kelamin', 'like', '%' . $search . '%')

                    ->orWhere('alamat', 'like', '%' . $search . '%')

                    ->orWhereHas('kelas', function ($q) use ($search) {

                        $q->where('nama_kelas', 'like', '%' . $search . '%')

                            ->orWhere('jurusan', 'like', '%' . $search . '%');

                    })

                    ->orWhereHas('orangTua', function ($q) use ($search) {

                        $q->where('nama_ortu', 'like', '%' . $search . '%');

                    });
            })

            // FILTER KELAS
            ->when($kelasId, function ($query) use ($kelasId) {

                $query->where('kelas_id', $kelasId);

            })

            // FILTER JURUSAN
            ->when($jurusan, function ($query) use ($jurusan) {

                $query->whereHas('kelas', function ($q) use ($jurusan) {

                    $q->where('jurusan', $jurusan);

                });

            })

            ->orderBy('nis')

            ->get();

        $pdf = Pdf::loadView(
            'admin.siswa.pdf',
            compact('items')
        )->setPaper('a4', 'landscape');

        return $pdf->download(
            'siswa-smkn2-rupat.pdf'
        );
    }
}