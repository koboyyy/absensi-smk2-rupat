@extends('layouts.app', ['title' => 'Detail Surat Izin'])

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <div class="text-xl font-bold">Detail Surat</div>
        <a href="{{ route('ortu.surat-izin.index') }}" class="rounded-lg px-3 py-2 text-sm font-semibold hover:bg-slate-100 dark:hover:bg-slate-900">Kembali</a>
    </div>

    <div class="max-w-2xl space-y-3 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-950">
        <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
            <div>
                <div class="text-xs text-slate-500 dark:text-slate-400">Tanggal</div>
                <div class="font-semibold">{{ $item->tanggal?->format('Y-m-d') }}</div>
            </div>
            <div>
                <div class="text-xs text-slate-500 dark:text-slate-400">Status</div>
                <div class="font-semibold">{{ $item->status }}</div>
            </div>
            <div>
                <div class="text-xs text-slate-500 dark:text-slate-400">Siswa</div>
                <div class="font-semibold">{{ $item->siswa?->nama_siswa }}</div>
            </div>
            <div>
                <div class="text-xs text-slate-500 dark:text-slate-400">Mapel / Guru</div>
                <div class="font-semibold">{{ $item->jadwal?->mapel?->nama_mapel }} - {{ $item->jadwal?->guru?->nama_guru }}</div>
            </div>
        </div>

        <div>
            <div class="text-xs text-slate-500 dark:text-slate-400">Keterangan</div>
            <div class="whitespace-pre-line">{{ $item->keterangan }}</div>
        </div>

        <div>
            <div class="text-xs text-slate-500 dark:text-slate-400">File Bukti</div>
            @if($item->file_bukti)
                <a class="text-blue-700 underline dark:text-blue-400" href="{{ asset('storage/' . $item->file_bukti) }}" target="_blank" rel="noreferrer">
                    Lihat / Download
                </a>
            @else
                <div>-</div>
            @endif
        </div>
    </div>
@endsection

