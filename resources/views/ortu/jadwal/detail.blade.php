@extends ('layouts.app', ['title' => 'Detail Kehadiran'])

@section ('content')
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">
            Lihat detail kehadiran
        </h1>
        <a
            href="{{ route('ortu.jadwal.index') }}"
            class="text-sm font-medium text-blue-600 hover:text-blue-700"
        >
            &larr; Kembali
        </a>
    </div>
    <div
        class="max-w-4xl overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-950"
    >
        {{-- Header Tabel --}}
        <div class="bg-slate-100 px-4 py-2 dark:bg-slate-900">
            <span
                class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase"
                >Detail kehadiran</span
            >
        </div>

        {{-- List Kehadiran --}}
        <div class="divide-y divide-slate-100 dark:divide-slate-800">
            @forelse ($absensi as $absen)
                <div
                    class="flex items-center justify-between px-4 py-4 hover:bg-slate-50 dark:hover:bg-slate-900/50"
                >
                    <div class="flex flex-col">
                        <span
                            class="text-sm font-medium text-slate-900 dark:text-white"
                        >
                            {{ \Carbon\Carbon::parse($absen->tanggal)->translatedFormat('l, d F Y') }}, {{ $jadwal->jam_mulai }}
                        </span>
                    </div>

                    <div class="flex items-center gap-6">
                        <span
                            class="text-[10px] font-bold uppercase tracking-wider 
                        {{ $absen->status == 'H' ? 'text-green-500' : 'text-red-500' }}"
                        >
                            {{ $absen->status == 'H' ? 'HADIR' : ($absen->status == 'S' ? 'SAKIT' : ($absen->status == 'I' ? 'IZIN' : 'ALFA')) }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-slate-500">
                    Belum ada riwayat kehadiran untuk mata pelajaran ini.
                </div>
            @endforelse
        </div>

        {{-- Pagination Navigasi --}}
        @if ($absensi->hasPages())
            <div
                class="border-t border-slate-100 bg-slate-50/50 px-4 py-3 dark:border-slate-800 dark:bg-slate-900/20"
            >
                {{ $absensi->links() }}
            </div>
        @endif

        {{-- Area Kosong di Bawah (Hanya tampil jika data sedikit/halaman terakhir) --}}
        @if ($absensi->count() < 5)
            <div
                class="h-32 bg-slate-50/50 dark:bg-slate-900/20 flex items-center justify-center border-t border-slate-100 dark:border-slate-800"
            >
                <div class="relative w-full h-full opacity-10"></div>
            </div>
        @endif
    </div>
@endsection
