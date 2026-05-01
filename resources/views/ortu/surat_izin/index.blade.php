@extends('layouts.app', ['title' => 'Riwayat Surat Izin'])

@section('content')
    <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-blue-600 dark:text-blue-400 mb-1">
                <i class="fa-solid fa-clock-rotate-left"></i>
                Monitoring Izin Siswa
            </div>
            <h1 class="text-3xl font-black text-slate-800 dark:text-white uppercase tracking-tight">Riwayat Surat</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Pantau status persetujuan surat izin/sakit anak Anda di sini.</p>
        </div>
        <a href="{{ route('ortu.surat-izin.create') }}" class="group inline-flex items-center gap-3 rounded-2xl bg-blue-600 px-6 py-3 text-sm font-black uppercase tracking-widest text-white shadow-lg shadow-blue-600/20 transition-all hover:bg-blue-700 hover:-translate-y-1 active:scale-95">
            <i class="fa-solid fa-plus transition-transform group-hover:rotate-90"></i>
            Buat Surat Baru
        </a>
    </div>

    <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-950">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50/50 text-[10px] uppercase tracking-[0.15em] text-slate-400 dark:bg-slate-900/40 font-black">
                    <tr>
                        <th class="px-8 py-5">Tanggal & Nama Siswa</th>
                        <th class="px-6 py-5">Mata Pelajaran</th>
                        <th class="px-6 py-5 text-center">Status</th>
                        <th class="px-8 py-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($items as $item)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/50 transition-colors group">
                            <!-- Kolom Tanggal -->
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="flex flex-col items-center justify-center h-12 w-12 shrink-0 rounded-2xl bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-800/50 shadow-sm">
                                        <span class="text-[10px] font-black uppercase leading-none mb-1">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('M') }}</span>
                                        <span class="text-xl font-black leading-none">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d') }}</span>
                                    </div>
                                    <div>
                                        <div class="font-extrabold text-slate-800 dark:text-white leading-tight uppercase tracking-tight">
                                            {{ $item->siswa?->nama_siswa }}
                                        </div>
                                        <div class="text-[11px] font-bold text-slate-400 mt-0.5 uppercase tracking-tighter">
                                            Tahun: {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('Y') }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Kolom Pelajaran -->
                            <td class="px-6 py-5">
                                <div class="font-bold text-slate-700 dark:text-slate-300">
                                    {{ $item->jadwal?->mapel?->nama_mapel ?? 'Semua Mapel' }}
                                </div>
                                <div class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400 mt-1 uppercase tracking-widest">
                                    <i class="fa-regular fa-clock text-blue-500"></i>
                                    {{ $item->jadwal?->jam_mulai ?? '--:--' }} - {{ $item->jadwal?->jam_selesai ?? '--:--' }}
                                </div>
                            </td>

                            <!-- Kolom Status -->
                            <td class="px-6 py-5 text-center">
                                @php
                                    $statusConfigs = [
                                        'diterima' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 border-emerald-200/50 dark:border-emerald-800/50',
                                        'ditolak'   => 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400 border-rose-200/50 dark:border-rose-800/50',
                                        'pending'   => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 border-amber-200/50 dark:border-amber-800/50',
                                    ];
                                    // Handle jika di database menggunakan istilah 'disetujui' atau 'diterima'
                                    $rawStatus = $item->status ?? 'pending';
                                    $currentClass = $statusConfigs[$rawStatus] ?? $statusConfigs['pending'];
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-xl text-[10px] font-black uppercase tracking-[0.1em] border shadow-sm {{ $currentClass }}">
                                    <span class="mr-1.5 flex h-1.5 w-1.5 rounded-full bg-current"></span>
                                    {{ $rawStatus }}
                                </span>
                            </td>

                            <!-- Kolom Aksi -->
                            <td class="px-8 py-5 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('ortu.surat-izin.show', $item->surat_id) }}" 
                                       class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 transition-all hover:border-blue-500 hover:text-blue-600 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-400 shadow-sm active:scale-90">
                                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="h-20 w-20 rounded-3xl bg-slate-50 dark:bg-white/5 flex items-center justify-center mb-4 border border-dashed border-slate-200 dark:border-slate-800">
                                        <i class="fa-solid fa-paper-plane text-3xl text-slate-200 dark:text-slate-800"></i>
                                    </div>
                                    <h4 class="text-sm font-black uppercase tracking-widest text-slate-400">Belum Ada Riwayat</h4>
                                    <p class="text-xs text-slate-500 mt-1 max-w-xs">Semua surat izin yang Anda kirimkan untuk anak Anda akan muncul di sini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($items->hasPages())
    <div class="mt-8 flex justify-center">
        {{ $items->links() }}
    </div>
    @endif
@endsection