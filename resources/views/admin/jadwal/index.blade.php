@extends('layouts.app', ['title' => 'Admin - Jadwal'])

@section('content')
    <div class="mb-4 flex items-center justify-between gap-3">
        <div>
            <div class="text-xl font-bold">Jadwal Pelajaran</div>
            <div class="text-sm text-slate-500 dark:text-slate-400">Kelola jadwal per kelas.</div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.jadwal.export.pdf') }}" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-semibold hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-950 dark:hover:bg-slate-900">
                Export PDF
            </a>
            <a href="{{ route('admin.jadwal.create') }}" class="rounded-lg bg-blue-600 px-3 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                Tambah
            </a>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-950">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-slate-600 dark:bg-slate-900/40 dark:text-slate-300">
            <tr>
                <th class="px-4 py-3">Hari</th>
                <th class="px-4 py-3">Jam</th>
                <th class="px-4 py-3">Kelas</th>
                <th class="px-4 py-3">Mapel</th>
                <th class="px-4 py-3">Guru</th>
                <th class="px-4 py-3 text-right">Aksi</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
            @foreach($items as $item)
                <tr>
                    <td class="px-4 py-3">{{ $item->hari }}</td>
                    <td class="px-4 py-3 font-mono">{{ $item->jam_mulai }} - {{ $item->jam_selesai }}</td>
                    <td class="px-4 py-3">{{ $item->kelas?->nama_kelas }}</td>
                    <td class="px-4 py-3">{{ $item->mapel?->nama_mapel }}</td>
                    <td class="px-4 py-3">{{ $item->guru?->nama_guru }}</td>
                    <td class="px-4 py-3">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.jadwal.edit', $item->jadwal_id) }}" class="rounded-lg px-3 py-2 text-xs font-semibold hover:bg-slate-100 dark:hover:bg-slate-900">Edit</a>
                            <form method="POST" action="{{ route('admin.jadwal.destroy', $item->jadwal_id) }}" onsubmit="return confirm('Hapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="rounded-lg bg-red-600 px-3 py-2 text-xs font-semibold text-white hover:bg-red-700">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $items->links() }}</div>
@endsection

