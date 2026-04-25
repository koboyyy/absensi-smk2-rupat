@extends('layouts.app', ['title' => 'Surat Izin'])

@section('content')
    <div class="mb-4 flex items-center justify-between gap-3">
        <div>
            <div class="text-xl font-bold">Surat Izin / Sakit</div>
            <div class="text-sm text-slate-500 dark:text-slate-400">Kirim bukti izin/sakit ke guru mapel terkait.</div>
        </div>
        <a href="{{ route('ortu.surat-izin.create') }}" class="rounded-lg bg-blue-600 px-3 py-2 text-sm font-semibold text-white hover:bg-blue-700">
            Buat Surat
        </a>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-950">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-slate-600 dark:bg-slate-900/40 dark:text-slate-300">
            <tr>
                <th class="px-4 py-3">Tanggal</th>
                <th class="px-4 py-3">Siswa</th>
                <th class="px-4 py-3">Mapel</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3 text-right">Aksi</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
            @foreach($items as $item)
                <tr>
                    <td class="px-4 py-3">{{ $item->tanggal?->format('Y-m-d') }}</td>
                    <td class="px-4 py-3 font-medium">{{ $item->siswa?->nama_siswa }}</td>
                    <td class="px-4 py-3">{{ $item->jadwal?->mapel?->nama_mapel }}</td>
                    <td class="px-4 py-3">{{ $item->status }}</td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('ortu.surat-izin.show', $item->surat_id) }}" class="rounded-lg px-3 py-2 text-xs font-semibold hover:bg-slate-100 dark:hover:bg-slate-900">Detail</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $items->links() }}</div>
@endsection

