@extends('layouts.app', ['title' => 'Detail Kehadiran'])

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Lihat detail kehadiran</h1>
        <a href="{{ route('ortu.jadwal.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">
            &larr; Kembali
        </a>
    </div>

    <div class="max-w-4xl overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-950">
        {{-- Header Tabel --}}
        <div class="bg-slate-100 px-4 py-2 dark:bg-slate-900">
            <span class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase">Detail kehadiran</span>
        </div>

        {{-- List Kehadiran --}}
        <div class="divide-y divide-slate-100 dark:divide-slate-800">
            {{-- Contoh Data Sesuai Gambar --}}
            {{-- Loop data absensi di sini --}}
            @forelse($absensi as $absen)
            <div class="flex items-center justify-between px-4 py-4 hover:bg-slate-50 dark:hover:bg-slate-900/50">
                <div class="flex flex-col">
                    <span class="text-sm font-medium text-slate-900 dark:text-white">
                        {{ \Carbon\Carbon::parse($absen->tanggal)->translatedFormat('l, d F Y') }}, {{ $jadwal->jam_mulai }}
                    </span>
                </div>
                
                <div class="flex items-center gap-6">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-green-500">
                        {{ $absen->status ?? 'HADIR' }}
                    </span>
                    
                    {{-- Ikon Sampah Sesuai Referensi --}}
                    {{-- <button class="text-slate-400 hover:text-red-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button> --}}
                </div>
            </div>
            @empty
            <div class="p-8 text-center text-slate-500">
                Belum ada riwayat kehadiran untuk mata pelajaran ini.
            </div>
            @endforelse
        </div>
        
        {{-- Area Kosong di Bawah (Sesuai Gambar dengan Cross) --}}
        <div class="h-32 bg-slate-50/50 dark:bg-slate-900/20 flex items-center justify-center border-t border-slate-100 dark:border-slate-800">
             <div class="relative w-full h-full opacity-10">
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-full h-[1px] bg-slate-400 rotate-12"></div>
                </div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-full h-[1px] bg-slate-400 -rotate-12"></div>
                </div>
             </div>
        </div>
    </div>
@endsection