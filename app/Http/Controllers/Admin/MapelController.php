<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mapel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class MapelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;

        $items = Mapel::query()
            ->when($search, function ($query) use ($search) {
                $query->where('kode_mapel', 'like', '%' . $search . '%')
                    ->orWhere('nama_mapel', 'like', '%' . $search . '%');
            })
            ->orderBy('kode_mapel')
            ->orderBy('nama_mapel')
            ->paginate(15)
            ->withQueryString();

        return view('admin.mapel.index', compact('items', 'search'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.mapel.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_mapel' => ['required', 'string', 'max:255'],
            'kode_mapel' => ['required', 'string', 'max:50', 'unique:mapels,kode_mapel'],
        ]);

        Mapel::create($data);

        return redirect()->route('admin.mapel.index')->with('success', 'Data mapel berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('admin.mapel.edit', $id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $item = Mapel::query()->findOrFail($id);
        return view('admin.mapel.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $item = Mapel::query()->findOrFail($id);
        $data = $request->validate([
            'nama_mapel' => ['required', 'string', 'max:255'],
            'kode_mapel' => ['required', 'string', 'max:50', 'unique:mapels,kode_mapel,' . $item->mapel_id . ',mapel_id'],
        ]);

        $item->update($data);

        return redirect()->route('admin.mapel.index')->with('success', 'Data mapel berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = Mapel::query()->findOrFail($id);
        $item->delete();

        return redirect()->route('admin.mapel.index')->with('success', 'Data mapel berhasil dihapus.');
    }

    public function exportPdf()
    {
        $items = Mapel::query()->orderBy('kode_mapel')->get();
        $pdf = Pdf::loadView('admin.mapel.pdf', compact('items'))->setPaper('a4', 'portrait');
        return $pdf->download('mapel.pdf');
    }
}
