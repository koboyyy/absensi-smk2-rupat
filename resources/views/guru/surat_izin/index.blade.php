@extends('layouts.app', ['title' => 'Guru - Surat Izin'])

@section('content')
    <div class="mb-4 flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
        <div>
            <div class="text-xl font-bold">Inbox Surat Izin</div>
            <div class="text-sm text-slate-500 dark:text-slate-400">Surat izin/sakit dari orang tua untuk jadwal Anda.</div>
        </div>
        <div class="flex items-center gap-2">
            @foreach(['pending' => 'Pending', 'diterima' => 'Diterima', 'ditolak' => 'Ditolak'] as $k => $lbl)
                <a href="{{ route('guru.surat-izin.index', ['status' => $k]) }}"
                   class="rounded-xl px-3 py-2 text-sm font-semibold {{ $status===$k ? 'bg-blue-600 text-white' : 'border border-slate-200 bg-white hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-950 dark:hover:bg-slate-900' }}">
                    {{ $lbl }}
                </a>
            @endforeach
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-950">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-slate-600 dark:bg-slate-900/40 dark:text-slate-300">
            <tr>
                <th class="px-4 py-3">Tanggal</th>
                <th class="px-4 py-3">Siswa</th>
                <th class="px-4 py-3">Kelas</th>
                <th class="px-4 py-3">Mapel</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3 text-right">Aksi</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
            @forelse($items as $item)
                <tr>
                    <td class="px-4 py-3">{{ $item->tanggal?->format('Y-m-d') }}</td>
                    <td class="px-4 py-3 font-medium">{{ $item->siswa?->nama_siswa }}</td>
                    <td class="px-4 py-3">{{ $item->siswa?->kelas?->nama_kelas }}</td>
                    <td class="px-4 py-3">{{ $item->jadwal?->mapel?->nama_mapel }}</td>
                    <td class="px-4 py-3">{{ $item->status }}</td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('guru.surat-izin.show', $item->surat_id) }}" class="rounded-lg px-3 py-2 text-xs font-semibold hover:bg-slate-100 dark:hover:bg-slate-900">Detail</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-6 text-center text-slate-500 dark:text-slate-400">Tidak ada data.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $items->links() }}</div>
@endsection

