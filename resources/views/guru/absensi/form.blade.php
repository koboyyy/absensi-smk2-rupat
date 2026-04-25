@extends('layouts.app', ['title' => 'Guru - Input Absensi'])

@section('content')
    <div class="mb-4 flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
        <div>
            <div class="text-sm text-slate-500 dark:text-slate-400">{{ $jadwal->hari }} • {{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</div>
            <div class="text-xl font-bold">Absensi: {{ $jadwal->mapel?->nama_mapel }} ({{ $jadwal->kelas?->nama_kelas }})</div>
        </div>

        <form method="GET" action="{{ route('guru.absensi.form', $jadwal->jadwal_id) }}" class="flex items-center gap-2">
            <input type="date" name="tanggal" value="{{ $tanggal }}"
                   class="rounded-xl border border-slate-200 bg-white px-4 py-2 dark:border-slate-800 dark:bg-slate-950">
            <button class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-950 dark:hover:bg-slate-900">
                Tampilkan
            </button>
        </form>
    </div>

    <form method="POST" action="{{ route('guru.absensi.store', $jadwal->jadwal_id) }}" enctype="multipart/form-data"
          class="space-y-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-950">
        @csrf
        <input type="hidden" name="tanggal" value="{{ $tanggal }}">

        <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
            <div class="text-sm text-slate-500 dark:text-slate-400">
                Kode: <span class="font-semibold text-slate-900 dark:text-slate-100">H</span>adir,
                <span class="font-semibold text-slate-900 dark:text-slate-100">S</span>akit,
                <span class="font-semibold text-slate-900 dark:text-slate-100">I</span>zin,
                <span class="font-semibold text-slate-900 dark:text-slate-100">A</span>lfa
            </div>
            <div>
                <input type="file" name="foto_bukti"
                       class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm dark:border-slate-800 dark:bg-slate-950">
                @error('foto_bukti')<div class="mt-1 text-sm text-red-600">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 dark:border-slate-800">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-600 dark:bg-slate-900/40 dark:text-slate-300">
                <tr>
                    <th class="px-4 py-3">NIS</th>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Status</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                @foreach($siswas as $s)
                    @php($cur = $existing[$s->siswa_id]->status ?? 'H')
                    <tr>
                        <td class="px-4 py-3 font-mono">{{ $s->nis }}</td>
                        <td class="px-4 py-3 font-medium">{{ $s->nama_siswa }}</td>
                        <td class="px-4 py-3">
                            <select name="status[{{ $s->siswa_id }}]"
                                    class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm dark:border-slate-800 dark:bg-slate-950">
                                @foreach(['H'=>'Hadir','S'=>'Sakit','I'=>'Izin','A'=>'Alfa'] as $k => $lbl)
                                    <option value="{{ $k }}" @selected(old("status.$s->siswa_id", $cur)===$k)>{{ $k }} - {{ $lbl }}</option>
                                @endforeach
                            </select>
                            @error("status.$s->siswa_id")<div class="mt-1 text-sm text-red-600">{{ $message }}</div>@enderror
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        @error('status')<div class="text-sm text-red-600">{{ $message }}</div>@enderror

        <div class="flex items-center justify-between">
            <a href="{{ route('guru.absensi.index') }}" class="rounded-xl px-4 py-2 text-sm font-semibold hover:bg-slate-100 dark:hover:bg-slate-900">Kembali</a>
            <button class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Simpan Absensi</button>
        </div>
    </form>
@endsection

