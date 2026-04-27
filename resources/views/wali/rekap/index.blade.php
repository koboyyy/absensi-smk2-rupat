@extends('layouts.app', ['title' => 'Wali Kelas - Rekap Bulanan'])

@section('content')
    <div class="mb-4 flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
        <div>
            <div class="text-xl font-bold">Rekap Bulanan Kelas</div>
            <div class="text-sm text-slate-500 dark:text-slate-400">Rekap total H/S/I/A untuk kelas binaan.</div>
        </div>
        <form method="GET" action="{{ route('wali.rekap.index') }}" class="flex items-end gap-2">
            <div>
                <label class="mb-1 block text-xs text-slate-500 dark:text-slate-400">Bulan</label>
                <input type="number" min="1" max="12" name="bulan" value="{{ $bulan }}"
                       class="w-28 rounded-xl border border-slate-200 bg-white px-4 py-2 dark:border-slate-800 dark:bg-slate-950">
            </div>
            <div>
                <label class="mb-1 block text-xs text-slate-500 dark:text-slate-400">Tahun</label>
                <input type="number" min="2020" max="2100" name="tahun" value="{{ $tahun }}"
                       class="w-32 rounded-xl border border-slate-200 bg-white px-4 py-2 dark:border-slate-800 dark:bg-slate-950">
            </div>
            <button class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-950 dark:hover:bg-slate-900">
                Tampilkan
            </button>

            <a href="{{ route('wali.rekap.pdf', ['bulan' => $bulan, 'tahun' => $tahun]) }}" 
       target="_blank"
       class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700 transition-colors flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
        </svg>
        Cetak PDF
    </a>
        </form>
    </div>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-950">
            <div class="text-sm text-slate-500 dark:text-slate-400">Hadir</div>
            <div class="mt-2 text-3xl font-extrabold text-blue-700 dark:text-blue-400">{{ $rekap['hadir'] }}</div>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-950">
            <div class="text-sm text-slate-500 dark:text-slate-400">Sakit</div>
            <div class="mt-2 text-3xl font-extrabold text-blue-700 dark:text-blue-400">{{ $rekap['sakit'] }}</div>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-950">
            <div class="text-sm text-slate-500 dark:text-slate-400">Izin</div>
            <div class="mt-2 text-3xl font-extrabold text-blue-700 dark:text-blue-400">{{ $rekap['izin'] }}</div>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-950">
            <div class="text-sm text-slate-500 dark:text-slate-400">Alfa</div>
            <div class="mt-2 text-3xl font-extrabold text-blue-700 dark:text-blue-400">{{ $rekap['alfa'] }}</div>
        </div>
    </div>

    <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-950">
        <div class="text-sm text-slate-500 dark:text-slate-400">
            Rekap ini otomatis tersimpan ke tabel <code>rekap_kelas</code> (per kelas/bulan/tahun).
        </div>
    </div>
@endsection

