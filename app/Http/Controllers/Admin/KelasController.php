<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;

        $items = Kelas::query()
            ->when($search, function ($query) use ($search) {
                $query->where('nama_kelas', 'like', '%' . $search . '%')
                    ->orWhere('tingkat', 'like', '%' . $search . '%')
                    ->orWhere('jurusan', 'like', '%' . $search . '%');
            })
            ->orderBy('tingkat')
            ->orderBy('jurusan')
            ->orderBy('nama_kelas')
            ->paginate(15)
            ->withQueryString();

        return view('admin.kelas.index', compact('items', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.kelas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_kelas' => ['required', 'string', 'max:255', 'unique:kelas,nama_kelas'],
            'tingkat' => ['required', 'in:10,11,12'],
            'jurusan' => ['required', 'string', 'max:255'],
        ]);

        Kelas::create($data);

        return redirect()->route('admin.kelas.index')->with('success', 'Data kelas berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('admin.kelas.edit', $id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $item = Kelas::query()->findOrFail($id);
        return view('admin.kelas.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $item = Kelas::query()->findOrFail($id);
        $data = $request->validate([
            'nama_kelas' => ['required', 'string', 'max:255', 'unique:kelas,nama_kelas,' . $item->kelas_id . ',kelas_id'],
            'tingkat' => ['required', 'in:10,11,12'],
            'jurusan' => ['required', 'string', 'max:255'],
        ]);

        $item->update($data);

        return redirect()->route('admin.kelas.index')->with('success', 'Data kelas berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = Kelas::query()->findOrFail($id);
        $item->delete();

        return redirect()->route('admin.kelas.index')->with('success', 'Data kelas berhasil dihapus.');
    }

    public function exportPdf()
    {
        $items = Kelas::query()->orderBy('tingkat')->orderBy('jurusan')->orderBy('nama_kelas')->get();
        $pdf = Pdf::loadView('admin.kelas.pdf', compact('items'))->setPaper('a4', 'portrait');
        return $pdf->download('kelas.pdf');
    }
}
