<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Mapel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;

        $kelasId = $request->kelas_id;

        $jurusan = $request->jurusan;

        // LIST FILTER KELAS
        $kelasList = Kelas::query()
            ->orderBy('tingkat')
            ->orderBy('jurusan')
            ->orderBy('nama_kelas')
            ->get();

        // LIST FILTER JURUSAN
        $jurusans = Kelas::query()
            ->select('jurusan')
            ->distinct()
            ->pluck('jurusan');

        $items = Jadwal::query()

            ->with(['kelas', 'guru', 'mapel'])

            // SEARCH
            ->when($search, function ($query) use ($search) {

                $query->where('hari', 'like', '%' . $search . '%')

                    ->orWhereHas('kelas', function ($q) use ($search) {

                        $q->where('nama_kelas', 'like', '%' . $search . '%');

                    })

                    ->orWhereHas('guru', function ($q) use ($search) {

                        $q->where('nama_guru', 'like', '%' . $search . '%');

                    })

                    ->orWhereHas('mapel', function ($q) use ($search) {

                        $q->where('nama_mapel', 'like', '%' . $search . '%');

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

            ->orderByRaw("
                FIELD(
                    hari,
                    'Senin',
                    'Selasa',
                    'Rabu',
                    'Kamis',
                    'Jumat',
                    'Sabtu',
                    'Minggu'
                )
            ")

            ->orderBy('jam_mulai')

            ->paginate(15)

            ->withQueryString();

        return view('admin.jadwal.index', compact(
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
            ->orderBy('jurusan')
            ->orderBy('nama_kelas')
            ->get();

        $guru = Guru::query()
            ->orderBy('nama_guru')
            ->get();

        $mapel = Mapel::query()
            ->orderBy('kode_mapel')
            ->get();

        return view('admin.jadwal.create', compact(
            'kelas',
            'guru',
            'mapel'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'kelas_id' => ['required', 'exists:kelas,kelas_id'],
            'guru_id' => ['required', 'exists:gurus,guru_id'],
            'mapel_id' => ['required', 'exists:mapels,mapel_id'],
            'hari' => ['required', 'string', 'max:15'],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
        ]);

        Jadwal::create($data);

        return redirect()
            ->route('admin.jadwal.index')
            ->with('success', 'Data jadwal berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('admin.jadwal.edit', $id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $item = Jadwal::query()->findOrFail($id);

        $kelas = Kelas::query()
            ->orderBy('tingkat')
            ->orderBy('jurusan')
            ->orderBy('nama_kelas')
            ->get();

        $guru = Guru::query()
            ->orderBy('nama_guru')
            ->get();

        $mapel = Mapel::query()
            ->orderBy('kode_mapel')
            ->get();

        return view('admin.jadwal.edit', compact(
            'item',
            'kelas',
            'guru',
            'mapel'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $item = Jadwal::query()->findOrFail($id);

        $data = $request->validate([
            'kelas_id' => ['required', 'exists:kelas,kelas_id'],
            'guru_id' => ['required', 'exists:gurus,guru_id'],
            'mapel_id' => ['required', 'exists:mapels,mapel_id'],
            'hari' => ['required', 'string', 'max:15'],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
        ]);

        $item->update($data);

        return redirect()
            ->route('admin.jadwal.index')
            ->with('success', 'Data jadwal berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = Jadwal::query()->findOrFail($id);

        $item->delete();

        return redirect()
            ->route('admin.jadwal.index')
            ->with('success', 'Data jadwal berhasil dihapus.');
    }

    /**
     * Export PDF
     */
    public function exportPdf(Request $request)
    {
        $kelasId = $request->kelas_id;

        $jurusan = $request->jurusan;

        $search = $request->search;

        $items = Jadwal::query()

            ->with(['kelas', 'guru', 'mapel'])

            ->when($search, function ($query) use ($search) {

                $query->where('hari', 'like', '%' . $search . '%')

                    ->orWhereHas('kelas', function ($q) use ($search) {

                        $q->where('nama_kelas', 'like', '%' . $search . '%');

                    })

                    ->orWhereHas('guru', function ($q) use ($search) {

                        $q->where('nama_guru', 'like', '%' . $search . '%');

                    })

                    ->orWhereHas('mapel', function ($q) use ($search) {

                        $q->where('nama_mapel', 'like', '%' . $search . '%');

                    });
            })

            ->when($kelasId, function ($query) use ($kelasId) {

                $query->where('kelas_id', $kelasId);

            })

            ->when($jurusan, function ($query) use ($jurusan) {

                $query->whereHas('kelas', function ($q) use ($jurusan) {

                    $q->where('jurusan', $jurusan);

                });

            })

            ->orderByRaw("
                FIELD(
                    hari,
                    'Senin',
                    'Selasa',
                    'Rabu',
                    'Kamis',
                    'Jumat',
                    'Sabtu',
                    'Minggu'
                )
            ")

            ->orderBy('jam_mulai')

            ->get();

        $pdf = Pdf::loadView(
            'admin.jadwal.pdf',
            compact('items')
        )->setPaper('a4', 'landscape');

        return $pdf->download('jadwal.pdf');
    }
}