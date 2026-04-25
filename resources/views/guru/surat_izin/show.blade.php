@extends('layouts.app', ['title' => 'Guru - Detail Surat Izin'])

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <div class="text-xl font-bold">Detail Surat Izin</div>
        <a href="{{ route('guru.surat-izin.index') }}" class="rounded-lg px-3 py-2 text-sm font-semibold hover:bg-slate-100 dark:hover:bg-slate-900">Kembali</a>
    </div>

    <div class="max-w-3xl space-y-3 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-950">
        <div class="grid grid-cols-1 gap-3 md:grid-cols-3">
            <div>
                <div class="text-xs text-slate-500 dark:text-slate-400">Tanggal</div>
                <div class="font-semibold">{{ $item->tanggal?->format('Y-m-d') }}</div>
            </div>
            <div>
                <div class="text-xs text-slate-500 dark:text-slate-400">Status</div>
                <div class="font-semibold">{{ $item->status }}</div>
            </div>
            <div>
                <div class="text-xs text-slate-500 dark:text-slate-400">Kelas</div>
                <div class="font-semibold">{{ $item->siswa?->kelas?->nama_kelas }}</div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
            <div>
                <div class="text-xs text-slate-500 dark:text-slate-400">Siswa</div>
                <div class="font-semibold">{{ $item->siswa?->nama_siswa }}</div>
            </div>
            <div>
                <div class="text-xs text-slate-500 dark:text-slate-400">Orang Tua</div>
                <div class="font-semibold">{{ $item->orangTua?->nama_ortu }}</div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
            <div>
                <div class="text-xs text-slate-500 dark:text-slate-400">Mapel</div>
                <div class="font-semibold">{{ $item->jadwal?->mapel?->nama_mapel }}</div>
            </div>
            <div>
                <div class="text-xs text-slate-500 dark:text-slate-400">Jadwal</div>
                <div class="font-semibold">{{ $item->jadwal?->hari }} ({{ $item->jadwal?->jam_mulai }}-{{ $item->jadwal?->jam_selesai }})</div>
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

        <div class="flex flex-col gap-2 pt-2 md:flex-row">
            @if($item->status === 'pending')
                <form method="POST" action="{{ route('guru.surat-izin.accept', $item->surat_id) }}">
                    @csrf
                    <button class="w-full rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700 md:w-auto">Terima</button>
                </form>
                <form method="POST" action="{{ route('guru.surat-izin.reject', $item->surat_id) }}" onsubmit="return confirm('Tolak surat ini?')">
                    @csrf
                    <button class="w-full rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700 md:w-auto">Tolak</button>
                </form>
            @else
                <div class="text-sm text-slate-500 dark:text-slate-400">Surat sudah diproses.</div>
            @endif
        </div>
    </div>
@endsection

