@extends('layouts.app', ['title' => 'Detail Surat Izin'])

@section('content')
    <!-- Header Section -->
    <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('ortu.surat-izin.index') }}" 
               class="flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-600 shadow-sm transition-all hover:bg-slate-50 hover:text-blue-600 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-400">
                <i class="fa-solid fa-chevron-left text-lg"></i>
            </a>
            <div>
                <div class="text-[10px] font-black uppercase tracking-[0.2em] text-blue-600 dark:text-blue-400">Arsip Digital</div>
                <h1 class="text-2xl font-black text-slate-800 dark:text-white uppercase tracking-tight">Rincian Pengajuan</h1>
            </div>
        </div>
        
        @php
            $statusConfigs = [
                'diterima' => 'bg-emerald-100 text-emerald-700 border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800',
                'ditolak'   => 'bg-rose-100 text-rose-700 border-rose-200 dark:bg-rose-900/30 dark:text-rose-400 dark:border-rose-800',
                'pending'   => 'bg-amber-100 text-amber-700 border-amber-200 dark:bg-amber-900/30 dark:text-amber-400 dark:border-amber-800',
            ];
            $rawStatus = $item->status ?? 'pending';
            $currentClass = $statusConfigs[$rawStatus] ?? $statusConfigs['pending'];
        @endphp
        <div class="inline-flex items-center gap-3 self-start md:self-center px-5 py-2 rounded-2xl border shadow-sm {{ $currentClass }}">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-current opacity-75"></span>
                <span class="relative inline-flex h-2 w-2 rounded-full bg-current"></span>
            </span>
            <span class="text-xs font-black uppercase tracking-widest">{{ $rawStatus }}</span>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
        {{-- Kolom Kiri: Isi Keterangan & Bukti --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="relative overflow-hidden rounded-3xl border border-slate-200 bg-white p-8 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                <div class="mb-6 flex items-center gap-3 text-slate-400">
                    <i class="fa-solid fa-quote-left text-2xl opacity-20"></i>
                    <h3 class="text-xs font-black uppercase tracking-[0.2em]">Alasan / Keterangan</h3>
                </div>
                
                <div class="relative z-10 text-slate-700 dark:text-slate-300 leading-relaxed font-medium">
                    {{ $item->keterangan }}
                </div>

                {{-- Bukti Attachment --}}
                @if($item->file_bukti)
                <div class="mt-10 pt-8 border-t border-slate-100 dark:border-slate-800">
                    <div class="text-[10px] font-black uppercase text-slate-400 mb-4 tracking-widest">Dokumen Pendukung</div>
                    <a href="{{ asset('storage/' . $item->file_bukti) }}" target="_blank" 
                       class="group flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 p-4 transition-all hover:border-blue-500 hover:bg-blue-50/30 dark:border-slate-800 dark:bg-slate-900/50">
                        <div class="flex items-center gap-4">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-600 text-white shadow-lg shadow-blue-600/20 group-hover:scale-110 transition-transform">
                                <i class="fa-solid fa-image text-xl"></i>
                            </div>
                            <div>
                                <div class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-tight">Lampiran_Bukti.jpg</div>
                                <div class="text-[10px] font-bold text-slate-400">Klik untuk melihat dokumen lengkap</div>
                            </div>
                        </div>
                        <i class="fa-solid fa-arrow-up-right-from-square text-slate-300 group-hover:text-blue-500"></i>
                    </a>
                </div>
                @endif
            </div>
        </div>

        {{-- Kolom Kanan: Detail & Status Card --}}
        <div class="space-y-6">
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                <h3 class="mb-6 text-[10px] font-black uppercase tracking-[0.2em] text-blue-600 dark:text-blue-400">Informasi Akademik</h3>
                
                <div class="space-y-6">
                    <div class="flex gap-4">
                        <div class="h-10 w-10 shrink-0 rounded-xl bg-slate-100 dark:bg-white/5 flex items-center justify-center text-slate-500">
                            <i class="fa-solid fa-calendar-check text-sm"></i>
                        </div>
                        <div>
                            <div class="text-[10px] font-black uppercase text-slate-400">Hari & Tanggal</div>
                            <div class="text-sm font-extrabold text-slate-800 dark:text-white">{{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->translatedFormat('l, d F Y') : '-' }}</div>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="h-10 w-10 shrink-0 rounded-xl bg-slate-100 dark:bg-white/5 flex items-center justify-center text-slate-500">
                            <i class="fa-solid fa-graduation-cap text-sm"></i>
                        </div>
                        <div>
                            <div class="text-[10px] font-black uppercase text-slate-400">Siswa Terkait</div>
                            <div class="text-sm font-extrabold text-slate-800 dark:text-white uppercase tracking-tight">{{ $item->siswa?->nama_siswa }}</div>
                            <div class="text-[10px] font-bold text-blue-600/60 uppercase italic">{{ $item->siswa?->kelas?->nama_kelas }}</div>
                        </div>
                    </div>

                    <div class="flex gap-4 border-t border-slate-50 pt-6 dark:border-slate-900">
                        <div class="h-10 w-10 shrink-0 rounded-xl bg-slate-100 dark:bg-white/5 flex items-center justify-center text-slate-500">
                            <i class="fa-solid fa-book text-sm"></i>
                        </div>
                        <div>
                            <div class="text-[10px] font-black uppercase text-slate-400">Mata Pelajaran</div>
                            <div class="text-sm font-extrabold text-slate-800 dark:text-white">{{ $item->jadwal?->mapel?->nama_mapel }}</div>
                            <div class="text-[10px] font-bold text-slate-400 uppercase italic leading-tight">{{ $item->jadwal?->guru?->nama_guru }}</div>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="h-10 w-10 shrink-0 rounded-xl bg-slate-100 dark:bg-white/5 flex items-center justify-center text-slate-500">
                            <i class="fa-solid fa-clock-rotate-left text-sm"></i>
                        </div>
                        <div>
                            <div class="text-[10px] font-black uppercase text-slate-400">Waktu KBM</div>
                            <div class="text-sm font-extrabold text-slate-800 dark:text-white">{{ $item->jadwal?->jam_mulai }} - {{ $item->jadwal?->jam_selesai }} WIB</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Info Alert Box --}}
            <div class="relative overflow-hidden rounded-3xl p-6 transition-all {{ $item->status == 'disetujui' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/20' : ($item->status == 'ditolak' ? 'bg-rose-600 text-white shadow-lg shadow-rose-600/20' : 'bg-slate-800 text-white shadow-lg shadow-slate-800/20') }}">
                <div class="relative z-10">
                    <div class="flex items-center gap-2 mb-3">
                        <i class="fa-solid {{ $item->status == 'disetujui' ? 'fa-circle-check' : ($item->status == 'ditolak' ? 'fa-circle-xmark' : 'fa-circle-info') }} text-lg"></i>
                        <span class="text-[10px] font-black uppercase tracking-widest">Informasi Sistem</span>
                    </div>
                    <p class="text-xs font-medium leading-relaxed opacity-90">
                        @if($item->status == 'disetujui')
                            Berkas telah diverifikasi. Absensi siswa telah diperbarui secara otomatis oleh guru pengampu mata pelajaran.
                        @elseif($item->status == 'ditolak')
                            Pengajuan ditolak. Mohon periksa kembali alasan penolakan atau hubungi pihak sekolah melalui guru piket.
                        @else
                            Menunggu tinjauan guru. Sistem akan mengirim notifikasi setelah status pengajuan Anda berubah.
                        @endif
                    </p>
                </div>
                {{-- Decorative Icon --}}
                <i class="fa-solid fa-shield-halved absolute -bottom-4 -right-4 text-8xl opacity-10 rotate-12"></i>
            </div>
        </div>
    </div>
@endsection