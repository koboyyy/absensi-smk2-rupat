@extends('layouts.app', ['title' => 'Guru - Input Absensi'])

@section('content')
    <!-- Header Section -->
    <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-blue-600 dark:text-blue-400">
                <i class="fa-solid fa-calendar-day"></i>
                {{ $jadwal->hari }} • {{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}
            </div>
            <div class="text-2xl font-black text-slate-800 dark:text-white">
                {{ $jadwal->mapel?->nama_mapel }}
                <span class="text-lg font-medium text-slate-400">({{ $jadwal->kelas?->nama_kelas }})</span>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <form method="GET" action="{{ route('guru.absensi.form', $jadwal->jadwal_id) }}" class="flex items-center gap-2 bg-white dark:bg-slate-950 p-1 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
                <input type="date" name="tanggal" value="{{ $tanggal }}"
                       class="bg-transparent border-none text-sm font-bold text-slate-700 dark:text-slate-200 focus:ring-0">
                <button class="rounded-xl bg-slate-100 dark:bg-white/10 px-4 py-1.5 text-xs font-black uppercase hover:bg-blue-600 hover:text-white transition-all">
                    Pilih
                </button>
            </form>

            <a href="{{ route('guru.absensi.rekap-jadwal', $jadwal->jadwal_id) }}" 
               class="flex items-center gap-2 rounded-xl bg-amber-500 px-5 py-2.5 text-sm font-black text-white hover:bg-amber-600 transition-all shadow-lg shadow-amber-500/20">
                <i class="fa-solid fa-file-lines"></i>
                REKAP
            </a>
        </div>
    </div>

    <!-- Main Form -->
    <form method="POST" action="{{ route('guru.absensi.store', $jadwal->jadwal_id) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <input type="hidden" name="tanggal" value="{{ $tanggal }}">

        <!-- Toolbar: Bukti Foto & Legend -->
        <div class="flex flex-col gap-4 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950 md:flex-row md:items-center md:justify-between">
            <div class="space-y-1">
                <label class="text-xs font-black uppercase tracking-wider text-slate-400">Bukti Kegiatan (Foto Kelas)</label>
                <input type="file" name="foto_bukti"
                       class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-white/5 dark:file:text-slate-300">
                @error('foto_bukti')<div class="mt-1 text-xs text-red-500 font-bold">{{ $message }}</div>@enderror
            </div>

            <div class="flex items-center gap-3 rounded-2xl bg-slate-50 dark:bg-white/5 p-3">
                <span class="text-[10px] font-black uppercase text-slate-400 mr-2">Indikator:</span>
                @foreach(['H' => 'Hadir', 'S' => 'Sakit', 'I' => 'Izin', 'A' => 'Alfa'] as $k => $v)
                    <div class="flex items-center gap-1.5">
                        <span class="h-2 w-2 rounded-full status-dot-{{ $k }}"></span>
                        <span class="text-[10px] font-bold text-slate-600 dark:text-slate-400">{{ $v }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Table Container -->
        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-950">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50/50 text-slate-500 dark:bg-slate-900/40 font-black uppercase text-[10px] tracking-widest">
                    <tr>
                        <th class="px-6 py-4 text-center w-16">No</th>
                        <th class="px-6 py-4">Informasi Siswa</th>
                        <th class="px-6 py-4 text-center">Status Kehadiran</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach($siswas as $index => $s)
                        @php($cur = $existing[$s->siswa_id]->status ?? 'H')
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/50 transition-colors">
                            <td class="px-6 py-4 text-center font-bold text-slate-400">{{ $index + 1 }}</td>
                            
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="h-11 w-11 shrink-0 overflow-hidden rounded-2xl border-2 border-slate-100 dark:border-slate-800 shadow-sm">
                                        @if($s->foto)
                                            <img src="{{ asset('storage/siswa/' . $s->foto) }}" class="h-full w-full object-cover">
                                        @else
                                            <div class="flex h-full w-full items-center justify-center bg-slate-100 dark:bg-slate-900 text-slate-400">
                                                <i class="fa-solid fa-user-graduate"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-extrabold text-slate-800 dark:text-white leading-tight uppercase tracking-tight">{{ $s->nama_siswa }}</div>
                                        <div class="text-[11px] font-bold text-blue-600/60 dark:text-blue-400/60 mt-0.5">NIS: {{ $s->nis }}</div>
                                    </div>
                                </div>
                            </td>
                            
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2 md:gap-4">
                                    @foreach([
                                        'H' => ['color' => 'peer-checked:bg-blue-600 peer-checked:border-blue-600', 'text' => 'H'],
                                        'S' => ['color' => 'peer-checked:bg-emerald-500 peer-checked:border-emerald-500', 'text' => 'S'],
                                        'I' => ['color' => 'peer-checked:bg-amber-500 peer-checked:border-amber-500', 'text' => 'I'],
                                        'A' => ['color' => 'peer-checked:bg-rose-600 peer-checked:border-rose-600', 'text' => 'A']
                                    ] as $k => $style)
                                        <label class="group relative cursor-pointer">
                                            <input type="radio" name="status[{{ $s->siswa_id }}]" value="{{ $k }}" 
                                                   @checked(old("status.$s->siswa_id", $cur) === $k) class="peer sr-only">
                                            <div class="flex h-10 w-10 md:h-12 md:w-12 items-center justify-center rounded-full border-2 border-slate-200 dark:border-slate-800 font-black text-slate-400 transition-all group-hover:border-slate-400 {{ $style['color'] }} peer-checked:text-white peer-checked:shadow-lg">
                                                {{ $style['text'] }}
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Footer Actions -->
        <div class="flex flex-col-reverse gap-4 md:flex-row md:items-center md:justify-between pb-10">
            <a href="{{ route('guru.absensi.index') }}" 
               class="flex items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-6 py-3 text-sm font-bold text-slate-600 transition-all hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-400">
                <i class="fa-solid fa-arrow-left"></i>
                Kembali ke Jadwal
            </a>
            <button type="submit" class="flex items-center justify-center gap-2 rounded-2xl bg-blue-600 px-10 py-3 text-sm font-black uppercase tracking-widest text-white shadow-xl shadow-blue-600/20 hover:bg-blue-700 active:scale-95 transition-all">
                Simpan Absensi
                <i class="fa-solid fa-cloud-arrow-up"></i>
            </button>
        </div>
    </form>

    <style>
        .status-dot-H { @apply bg-blue-600; }
        .status-dot-S { @apply bg-emerald-500; }
        .status-dot-I { @apply bg-amber-500; }
        .status-dot-A { @apply bg-rose-600; }
    </style>
@endsection