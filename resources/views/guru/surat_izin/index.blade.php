@extends('layouts.app', ['title' => 'Guru - Surat Izin'])

@section('content')
    <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
        <div>
            <div class="text-2xl font-bold text-slate-800 dark:text-white">Inbox Surat Izin</div>
            <div class="text-sm text-slate-500 dark:text-slate-400">Kelola surat izin dan sakit dari orang tua siswa.</div>
        </div>
        <div class="flex items-center gap-2 bg-slate-100 dark:bg-slate-900 p-1 rounded-2xl">
            @foreach(['pending' => 'Pending', 'diterima' => 'Diterima', 'ditolak' => 'Ditolak'] as $k => $lbl)
                <a href="{{ route('guru.surat-izin.index', ['status' => $k]) }}"
                   class="rounded-xl px-4 py-2 text-xs font-bold transition-all {{ $status === $k ? 'bg-white dark:bg-slate-800 shadow-sm text-blue-600 dark:text-blue-400' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300' }}">
                    {{ $lbl }}
                </a>
            @endforeach
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-950">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-600 dark:bg-slate-900/60 dark:text-slate-400 uppercase text-[11px] tracking-wider font-semibold">
                <tr>
                    <th class="px-6 py-4">Tanggal</th>
                    <th class="px-6 py-4">Siswa</th>
                    <th class="px-6 py-4">Kelas</th>
                    <th class="px-6 py-4">Mapel</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($items as $item)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-slate-600 dark:text-slate-300">
                            {{ $item->tanggal?->translatedFormat('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-bold text-slate-800 dark:text-white">{{ $item->siswa?->nama_siswa }}</div>
                            <div class="text-[11px] text-slate-400 uppercase tracking-tighter">{{ $item->siswa?->nisn }}</div>
                        </td>
                        <td class="px-6 py-4 text-slate-600 dark:text-slate-400">{{ $item->siswa?->kelas?->nama_kelas }}</td>
                        <td class="px-6 py-4 text-slate-600 dark:text-slate-400">{{ $item->jadwal?->mapel?->nama_mapel }}</td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $statusClasses = [
                                    'pending' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 border-amber-200 dark:border-amber-800',
                                    'diterima' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800',
                                    'ditolak' => 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400 border-rose-200 dark:border-rose-800',
                                ];
                                $currentClass = $statusClasses[$item->status] ?? 'bg-slate-100 text-slate-700 border-slate-200';
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $currentClass }}">
                                {{ Str::headline($item->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('guru.surat-izin.show', $item->surat_id) }}" 
                               class="inline-flex items-center gap-1.5 rounded-xl bg-blue-50 dark:bg-blue-900/20 px-4 py-2 text-xs font-bold text-blue-600 dark:text-blue-400 hover:bg-blue-600 hover:text-white dark:hover:bg-blue-600 dark:hover:text-white transition-all shadow-sm">
                                <span>Detail</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3 h-3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                </svg>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div class="rounded-full bg-slate-100 dark:bg-slate-900 p-3 mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-slate-400">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 9v.906a2.25 2.25 0 01-1.183 1.981l-6.478 3.488M2.25 9v.906a2.25 2.25 0 001.183 1.981l6.478 3.488m8.839 2.51l-4.66-2.51m0 0l-1.023-.55a2.25 2.25 0 00-2.134 0l-1.022.55m0 0l-4.661 2.51m16.5 1.615a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V8.844a2.25 2.25 0 011.183-1.981l7.5-4.04a2.25 2.25 0 012.134 0l7.5 4.04a2.25 2.25 0 011.183 1.981V16.5z" />
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-slate-500 dark:text-slate-400">Tidak ada surat izin yang ditemukan.</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $items->links() }}</div>
@endsection