@extends('layouts.app', ['title' => 'Detail Surat Izin'])

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('ortu.surat-izin.index') }}" class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 transition-all hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-400">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </a>
            <h1 class="text-xl font-bold text-slate-900 dark:text-white">Detail Surat Izin</h1>
        </div>
        
        @php
            $statusBadge = match($item->status) {
                'disetujui' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                'ditolak'   => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                default     => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
            };
        @endphp
        <span class="{{ $statusBadge }} rounded-full px-4 py-1 text-xs font-bold uppercase tracking-widest">
            {{ $item->status ?? 'Pending' }}
        </span>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Kolom Kiri: Informasi Utama --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                <div class="mb-6 border-b border-slate-100 pb-4 dark:border-slate-800">
                    <h3 class="font-bold text-slate-900 dark:text-white uppercase tracking-tight">Isi Keterangan</h3>
                </div>
                <div class="text-slate-700 dark:text-slate-300 leading-relaxed whitespace-pre-line text-sm">
                    {{ $item->keterangan }}
                </div>
                
                @if($item->file_bukti)
                <div class="mt-8 pt-6 border-t border-slate-100 dark:border-slate-800">
                    <div class="text-xs font-bold uppercase text-slate-400 mb-3">Dokumen Lampiran / Bukti</div>
                    <a href="{{ asset('storage/' . $item->file_bukti) }}" target="_blank" class="inline-flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-blue-600 transition-all hover:bg-blue-50 dark:border-slate-800 dark:bg-slate-900 dark:text-blue-400 dark:hover:bg-blue-900/20">
                        <i class="fa-solid fa-file-invoice text-lg"></i>
                        <span>Lihat Bukti Foto / Surat</span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                    </a>
                </div>
                @endif
            </div>
        </div>

        {{-- Kolom Kanan: Meta Data --}}
        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                <h3 class="mb-4 text-xs font-bold uppercase tracking-widest text-slate-400">Informasi Pengajuan</h3>
                
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <i class="fa-solid fa-calendar-day mt-1 text-blue-500"></i>
                        <div>
                            <div class="text-[10px] uppercase text-slate-400">Tanggal Izin</div>
                            <div class="text-sm font-bold">{{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->translatedFormat('l, d F Y') : '-' }}</div>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <i class="fa-solid fa-user-graduate mt-1 text-blue-500"></i>
                        <div>
                            <div class="text-[10px] uppercase text-slate-400">Nama Siswa</div>
                            <div class="text-sm font-bold">{{ $item->siswa?->nama_siswa }}</div>
                            <div class="text-xs text-slate-500">{{ $item->siswa?->kelas?->nama_kelas }}</div>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <i class="fa-solid fa-book-bookmark mt-1 text-blue-500"></i>
                        <div>
                            <div class="text-[10px] uppercase text-slate-400">Mata Pelajaran</div>
                            <div class="text-sm font-bold">{{ $item->jadwal?->mapel?->nama_mapel }}</div>
                            <div class="text-[11px] text-slate-500 italic">{{ $item->jadwal?->guru?->nama_guru }}</div>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 pt-2">
                        <i class="fa-solid fa-clock mt-1 text-blue-500"></i>
                        <div>
                            <div class="text-[10px] uppercase text-slate-400">Jam Pelajaran</div>
                            <div class="text-xs font-bold">{{ $item->jadwal?->jam_mulai }} - {{ $item->jadwal?->jam_selesai }} WIB</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pesan Status --}}
            <div class="rounded-2xl p-4 {{ $item->status == 'disetujui' ? 'bg-green-50 text-green-800 dark:bg-green-900/20 dark:text-green-300' : ($item->status == 'ditolak' ? 'bg-red-50 text-red-800 dark:bg-red-900/20 dark:text-red-300' : 'bg-blue-50 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300') }}">
                <div class="flex gap-3">
                    <i class="fa-solid {{ $item->status == 'disetujui' ? 'fa-circle-check' : ($item->status == 'ditolak' ? 'fa-circle-xmark' : 'fa-circle-info') }} mt-0.5"></i>
                    <p class="text-xs leading-relaxed">
                        @if($item->status == 'disetujui')
                            Surat izin telah diverifikasi oleh guru pengampu. Kehadiran siswa pada jam ini telah disesuaikan.
                        @elseif($item->status == 'ditolak')
                            Surat izin ditolak. Silakan hubungi guru terkait untuk informasi lebih lanjut.
                        @else
                            Menunggu verifikasi dari guru mata pelajaran. Anda dapat membatalkan surat ini di halaman riwayat sebelum diverifikasi.
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection