@extends('layouts.app', ['title' => 'Guru - Detail Surat Izin'])

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <div class="text-2xl font-bold text-slate-800 dark:text-white">Detail Surat Izin</div>
            <p class="text-sm text-slate-500 dark:text-slate-400">Tinjau informasi surat sebelum memberikan konfirmasi.</p>
        </div>
        <a href="{{ route('guru.surat-izin.index') }}" 
           class="flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-bold text-slate-700 shadow-sm transition-all hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-300 dark:hover:bg-slate-900">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-4 w-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Kembali
        </a>
    </div>

    <div class="max-w-4xl space-y-6">
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-950">
            <!-- Header Info -->
            <div class="border-b border-slate-100 bg-slate-50/50 px-6 py-4 dark:border-slate-800 dark:bg-slate-900/50">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs uppercase tracking-wider text-slate-400 font-bold">Tanggal Pengajuan</div>
                            <div class="font-bold text-slate-700 dark:text-white">{{ $item->tanggal?->translatedFormat('l, d F Y') }}</div>
                        </div>
                    </div>
                    
                    @php
                        $statusClasses = [
                            'pending' => 'bg-amber-100 text-amber-700 border-amber-200 dark:bg-amber-900/30 dark:text-amber-400 dark:border-amber-800',
                            'diterima' => 'bg-emerald-100 text-emerald-700 border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800',
                            'ditolak' => 'bg-rose-100 text-rose-700 border-rose-200 dark:bg-rose-900/30 dark:text-rose-400 dark:border-rose-800',
                        ];
                    @endphp
                    <span class="rounded-full border px-4 py-1 text-xs font-black uppercase tracking-widest {{ $statusClasses[$item->status] ?? '' }}">
                        {{ $item->status }}
                    </span>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                    <!-- Data Siswa -->
                    <div class="space-y-4">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400">Informasi Siswa</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="text-[11px] font-bold uppercase text-slate-400">Nama Lengkap</label>
                                <p class="font-semibold text-slate-700 dark:text-white">{{ $item->siswa?->nama_siswa }}</p>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-[11px] font-bold uppercase text-slate-400">Kelas</label>
                                    <p class="font-semibold text-slate-700 dark:text-white">{{ $item->siswa?->kelas?->nama_kelas }}</p>
                                </div>
                                <div>
                                    <label class="text-[11px] font-bold uppercase text-slate-400">Orang Tua</label>
                                    <p class="font-semibold text-slate-700 dark:text-white">{{ $item->orangTua?->nama_ortu }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Data Pelajaran -->
                    <div class="space-y-4">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400">Informasi Mata Pelajaran</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="text-[11px] font-bold uppercase text-slate-400">Mapel</label>
                                <p class="font-semibold text-slate-700 dark:text-white">{{ $item->jadwal?->mapel?->nama_mapel }}</p>
                            </div>
                            <div>
                                <label class="text-[11px] font-bold uppercase text-slate-400">Waktu Jadwal</label>
                                <p class="font-semibold text-slate-700 dark:text-white">
                                    {{ $item->jadwal?->hari }}, {{ $item->jadwal?->jam_mulai }} - {{ $item->jadwal?->jam_selesai }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-6 border-slate-100 dark:border-slate-800">

                <!-- Keterangan -->
                <div class="space-y-4">
                    <div>
                        <label class="text-[11px] font-bold uppercase text-slate-400">Keterangan / Alasan</label>
                        <div class="mt-2 rounded-xl bg-slate-50 p-4 text-sm leading-relaxed text-slate-700 dark:bg-slate-900 dark:text-slate-300">
                            {{ $item->keterangan }}
                        </div>
                    </div>

                    <!-- Lampiran -->
                    <div>
                        <label class="text-[11px] font-bold uppercase text-slate-400">Dokumen Lampiran (Bukti)</label>
                        <div class="mt-2">
                            @if($item->file_bukti)
                                <a href="{{ asset('storage/' . $item->file_bukti) }}" target="_blank" 
                                   class="group inline-flex items-center gap-3 rounded-xl border border-slate-200 bg-white p-3 pr-6 transition-all hover:border-blue-400 dark:border-slate-800 dark:bg-slate-950">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-red-50 text-red-600 group-hover:bg-red-600 group-hover:text-white transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-slate-700 dark:text-white">Lihat Lampiran</div>
                                        <div class="text-[10px] uppercase text-slate-400">Klik untuk memperbesar gambar/PDF</div>
                                    </div>
                                </a>
                            @else
                                <div class="flex items-center gap-2 text-sm text-slate-400 italic">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                    </svg>
                                    Tidak ada file bukti yang dilampirkan.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            @if($item->status === 'pending')
                <div class="bg-slate-50 px-6 py-4 dark:bg-slate-900/30 border-t border-slate-100 dark:border-slate-800">
                    <div class="flex flex-col gap-3 md:flex-row md:justify-end">
                        <form method="POST" action="{{ route('guru.surat-izin.reject', $item->surat_id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menolak surat ini?')" class="w-full md:w-auto">
                            @csrf
                            <button class="w-full rounded-xl border-2 border-rose-600 bg-white px-6 py-2.5 text-sm font-bold text-rose-600 transition-all hover:bg-rose-600 hover:text-white active:scale-95 dark:bg-transparent">
                                Tolak Pengajuan
                            </button>
                        </form>
                        <form method="POST" action="{{ route('guru.surat-izin.accept', $item->surat_id) }}" class="w-full md:w-auto">
                            @csrf
                            <button class="w-full rounded-xl bg-emerald-600 px-8 py-2.5 text-sm font-bold text-white shadow-lg shadow-emerald-600/20 transition-all hover:bg-emerald-700 active:scale-95">
                                Terima & Konfirmasi
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <div class="bg-slate-50 px-6 py-4 dark:bg-slate-900/30 border-t border-slate-100 dark:border-slate-800 text-center">
                    <p class="text-sm font-medium text-slate-500">
                        Surat ini telah diproses pada status <span class="font-bold text-slate-700 dark:text-white underline">{{ Str::upper($item->status) }}</span>.
                    </p>
                </div>
            @endif
        </div>
    </div>
@endsection