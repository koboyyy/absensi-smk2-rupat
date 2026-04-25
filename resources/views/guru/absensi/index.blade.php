@extends('layouts.app', ['title' => 'Guru - Absensi'])

@section('content')
    <div class="mb-4">
        <div class="text-xl font-bold">Absensi Siswa</div>
        <div class="text-sm text-slate-500 dark:text-slate-400">Pilih jadwal untuk melakukan absensi.</div>
    </div>

    <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
        @foreach($jadwals as $j)
            <a href="{{ route('guru.absensi.form', ['jadwal' => $j->jadwal_id, 'tanggal' => now()->toDateString()]) }}"
               class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm hover:border-blue-300 dark:border-slate-800 dark:bg-slate-950 dark:hover:border-blue-700">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <div class="text-sm text-slate-500 dark:text-slate-400">{{ $j->hari }} • {{ $j->jam_mulai }} - {{ $j->jam_selesai }}</div>
                        <div class="mt-1 text-lg font-bold">{{ $j->mapel?->nama_mapel }}</div>
                        <div class="text-sm font-semibold text-blue-700 dark:text-blue-400">{{ $j->kelas?->nama_kelas }}</div>
                    </div>
                    <div class="rounded-xl bg-blue-600 px-3 py-2 text-sm font-semibold text-white">Absen</div>
                </div>
            </a>
        @endforeach
    </div>
@endsection

