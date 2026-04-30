@extends('layouts.app', ['title' => 'Guru - Input Absensi'])

@section('content')
    <div class="mb-4 flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
        <div>
            <div class="text-sm text-slate-500 dark:text-slate-400">{{ $jadwal->hari }} • {{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</div>
            <div class="text-xl font-bold">Absensi: {{ $jadwal->mapel?->nama_mapel }} ({{ $jadwal->kelas?->nama_kelas }})</div>
        </div>

        <div class="flex gap-4">
            <form method="GET" action="{{ route('guru.absensi.form', $jadwal->jadwal_id) }}" class="flex items-center gap-2">
                <input type="date" name="tanggal" value="{{ $tanggal }}"
                       class="rounded-xl border border-slate-200 bg-white px-4 py-2 dark:border-slate-800 dark:bg-slate-950">
                <button class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-950 dark:hover:bg-slate-900">
                    Tampilkan
                </button>
            </form>

            <a href="{{ route('guru.absensi.rekap-jadwal', $jadwal->jadwal_id) }}" 
               class="flex items-center gap-2 rounded-xl bg-amber-500 px-4 py-2 text-sm font-bold text-white hover:bg-amber-600 transition-all shadow-sm">
                <i class="fa-solid fa-file-invoice"></i>
                Rekap Absen
            </a>
        </div>
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
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
    @foreach($siswas as $index => $s)
        @php($cur = $existing[$s->siswa_id]->status ?? 'H')
        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/50 transition-colors">
            {{-- Kolom No --}}
            <td class="px-6 py-4 text-slate-400 font-medium text-xs">{{ $index + 1 }}</td>
            
            {{-- Kolom NIS & Nama Siswa (DENGAN FOTO) --}}
            <td class="px-6 py-4">
                <div class="flex items-center gap-4">
                    {{-- Foto Siswa --}}
                    <div class="h-10 w-10 shrink-0 overflow-hidden rounded-full border border-slate-100 bg-slate-100 dark:border-slate-800 dark:bg-slate-900 flex items-center justify-center text-slate-400">
                        @if($s->foto)
                            {{-- Jika ada foto, tampilkan (Asumsi foto disimpan di storage/public) --}}
                            <img src="{{ asset('storage/siswa/' . $s->foto) }}" alt="{{ $s->nama_siswa }}" class="h-full w-full object-cover">
                        @else
                            {{-- Jika tidak ada foto, tampilkan Icon Placeholder (fa-solid fa-user) --}}
                            <i class="fa-solid fa-user text-sm"></i>
                        @endif
                    </div>
                    
                    {{-- Identitas Teks --}}
                    <div>
                        <div class="font-bold text-slate-900 dark:text-white leading-tight">
                            {{ $s->nama_siswa }}
                        </div>
                        <div class="text-[11px] font-mono text-slate-400 mt-0.5">
                            NIS: {{ $s->nis }}
                        </div>
                    </div>
                </div>
            </td>
            
            {{-- Kolom Status Kehadiran --}}
            <td class="px-6 py-4">
                <div class="flex items-center justify-center gap-3">
                    @foreach(['H'=>'H','S'=>'S','I'=>'I','A'=>'A'] as $k => $label)
                        <label class="relative flex cursor-pointer items-center justify-center">
                            {{-- Radio Input (Hidden) --}}
                            <input type="radio" 
                                   name="status[{{ $s->siswa_id }}]" 
                                   value="{{ $k }}" 
                                   @checked(old("status.$s->siswa_id", $cur) === $k)
                                   class="peer sr-only">
                            
                            {{-- Lingkaran Huruf --}}
                            <div class="h-9 w-9 rounded-full border-2 border-slate-200 flex items-center justify-center text-xs font-black transition-all
                                peer-checked:border-slate-800 peer-checked:bg-slate-800 peer-checked:text-white
                                dark:border-slate-700 dark:peer-checked:border-slate-300 dark:peer-checked:bg-slate-300 dark:peer-checked:text-slate-950
                                hover:border-slate-400">
                                {{ $label }}
                            </div>
                        </label>
                    @endforeach
                </div>
                @error("status.$s->siswa_id")
                    <div class="mt-1 text-center text-[10px] text-red-600 uppercase font-bold">{{ $message }}</div>
                @enderror
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

