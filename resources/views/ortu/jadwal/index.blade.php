@extends('layouts.app', ['title' => 'Jadwal Pelajaran Anak'])

@section('content')
    <div class="mb-6 flex items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Jadwal Pelajaran</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Daftar jadwal pelajaran anak Anda di sekolah.</p>
        </div>
    </div>

    {{-- Alert Info --}}
    <div class="mb-6 rounded-xl bg-blue-50 p-4 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800">
        <div class="flex gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-sm text-blue-800 dark:text-blue-300">
                Menampilkan jadwal untuk: <span class="font-bold">{{ $siswa->nama_siswa }}</span> (Kelas {{ $siswa->kelas->nama_kelas }})
            </p>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-950">
        <table class="w-full text-left text-sm text-slate-700 dark:text-slate-300">
            <thead class="bg-slate-50 text-slate-600 dark:bg-slate-900/40 dark:text-slate-300">
            <tr>
                <th class="px-4 py-3 font-semibold uppercase tracking-wider">Hari</th>
                <th class="px-4 py-3 font-semibold uppercase tracking-wider">Jam</th>
                <th class="px-4 py-3 font-semibold uppercase tracking-wider">Mata Pelajaran</th>
                <th class="px-4 py-3 font-semibold uppercase tracking-wider">Guru</th>
                <th class="px-4 py-3 text-right font-semibold uppercase tracking-wider">Detail</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
            @forelse($items as $item)
                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/50 transition-colors">
                    <td class="px-4 py-4">
                        <span class="inline-flex items-center rounded-md bg-slate-100 px-2 py-1 text-xs font-bold text-slate-700 dark:bg-slate-800 dark:text-slate-300">
                            {{ $item->hari }}
                        </span>
                    </td>
                    <td class="px-4 py-4 font-mono text-xs">
                        {{ $item->jam_mulai }} — {{ $item->jam_selesai }}
                    </td>
                    <td class="px-4 py-4 font-semibold text-slate-900 dark:text-white">
                        {{ $item->mapel?->nama_mapel }}
                    </td>
                    <td class="px-4 py-4 text-slate-500 dark:text-slate-400 text-xs">
                        {{ $item->guru?->nama_guru }}
                    </td>
                    <td class="px-4 py-4 text-right">
                        <a href="{{ route('ortu.jadwal.show', $item->jadwal_id) }}" class="inline-flex h-8 w-8 items-center justify-center rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-blue-600 dark:text-blue-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-slate-500">
                        Belum ada jadwal pelajaran yang tersedia.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{-- Navigasi Halaman jika datanya banyak --}}
    @if($items->hasPages())
        <div class="mt-4">
            {{ $items->links() }}
        </div>
    @endif
@endsection