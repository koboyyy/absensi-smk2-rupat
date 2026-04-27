@extends('layouts.app', ['title' => 'Detail Jadwal Pelajaran'])

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Jadwal Pelajaran</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Informasi jadwal belajar anak Anda</p>
        </div>
        <a href="{{ route('ortu.jadwal.index') }}" class="flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-300 dark:hover:bg-slate-900 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
    </div>

    <div class="max-w-4xl">
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-950 overflow-hidden">
            <div class="bg-slate-50 p-6 border-b border-slate-100 dark:bg-slate-900/50 dark:border-slate-800">
                <div class="flex items-center gap-4">
                    <div class="h-12 w-12 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-medium uppercase tracking-wider text-slate-400">Siswa</div>
                        <div class="text-lg font-bold text-slate-900 dark:text-white">{{ $siswa->nama_siswa }}</div>
                        <div class="text-sm text-slate-500 italic">{{ $siswa->kelas->nama_kelas }}</div>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                    <div class="space-y-4">
                        <div>
                            <label class="text-xs font-medium uppercase tracking-wider text-slate-400">Hari</label>
                            <div class="mt-1 flex items-center gap-2 font-semibold text-slate-900 dark:text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ $jadwal->hari }}
                            </div>
                        </div>
                        <div>
                            <label class="text-xs font-medium uppercase tracking-wider text-slate-400">Jam Pelajaran</label>
                            <div class="mt-1 font-semibold text-slate-900 dark:text-white">
                                {{ $jadwal->jam_mulai }} — {{ $jadwal->jam_selesai }}
                                <span class="ml-2 text-xs font-normal text-slate-400">(WIB)</span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="text-xs font-medium uppercase tracking-wider text-slate-400">Mata Pelajaran</label>
                            <div class="mt-1 font-bold text-blue-600 dark:text-blue-400 text-lg">
                                {{ $jadwal->mapel->nama_mapel }}
                            </div>
                        </div>
                        <div>
                            <label class="text-xs font-medium uppercase tracking-wider text-slate-400">Guru Pengampu</label>
                            <div class="mt-1 font-semibold text-slate-900 dark:text-white">
                                {{ $jadwal->guru->nama_guru }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-slate-100 dark:border-slate-800">
                    <div class="rounded-xl bg-blue-50 p-4 dark:bg-blue-900/20">
                        <div class="flex gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 dark:text-blue-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="text-sm text-blue-800 dark:text-blue-300">
                                <strong>Catatan:</strong> Pastikan siswa hadir 15 menit sebelum jam pelajaran dimulai. Ruangan kelas berada di blok <strong>{{ $jadwal->ruangan ?? 'Gedung Utama' }}</strong>.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection