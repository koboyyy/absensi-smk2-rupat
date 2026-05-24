@extends ('layouts.app', ['title' => 'Guru - Absensi'])

@section ('content')
    <div class="mb-6 flex flex-col gap-1">
        <div
            class="text-xs font-bold uppercase tracking-widest text-blue-600 dark:text-blue-400"
        >
            Manajemen Kelas
        </div>
        <div class="text-2xl font-extrabold text-slate-800 dark:text-white">
            Jadwal Mengajar
        </div>
        <div class="text-sm text-slate-500 dark:text-slate-400">
            Pilih mata pelajaran di bawah ini untuk memulai pengisian absensi
            hari ini.
        </div>
    </div>
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-2">
        @forelse ($jadwals as $j)
            <a
                href="{{ route('guru.absensi.form', ['jadwal' => $j->jadwal_id, 'tanggal' => now()->toDateString()]) }}"
                class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition-all hover:-translate-y-1 hover:border-blue-500 hover:shadow-xl dark:border-slate-800 dark:bg-slate-950 dark:hover:border-blue-600"
            >
                <div class="flex items-center justify-between gap-4">
                    <div class="flex-1">
                        <!-- Indikator Waktu & Hari -->
                        <div
                            class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-tighter text-slate-400 dark:text-slate-500"
                        >
                            <span class="flex items-center gap-1">
                                <i class="fa-regular fa-calendar"></i>
                                {{ $j->hari }}
                            </span>
                            <span>•</span>
                            <span class="flex items-center gap-1">
                                <i class="fa-regular fa-clock"></i>
                                {{ $j->jam_mulai }} - {{ $j->jam_selesai }}
                            </span>
                        </div>

                        <!-- Mata Pelajaran -->
                        <div
                            class="mt-2 text-xl font-black text-slate-800 group-hover:text-blue-600 dark:text-white dark:group-hover:text-blue-400 transition-colors"
                        >
                            {{ $j->mapel?->nama_mapel }}
                        </div>

                        <!-- Badge Kelas -->
                        <div
                            class="mt-3 inline-flex items-center gap-1.5 rounded-lg bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700 dark:bg-blue-900/30 dark:text-blue-300"
                        >
                            <i class="fa-solid fa-users-rectangle"></i>
                            Kelas {{ $j->kelas?->nama_kelas }}
                        </div>
                    </div>

                    <!-- Tombol Aksi Visual -->
                    <div class="flex flex-col items-center gap-2">
                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-600 text-white shadow-lg shadow-blue-600/30 group-hover:scale-110 transition-transform"
                        >
                            <i class="fa-solid fa-clipboard-user text-xl"></i>
                        </div>
                        <span
                            class="text-[10px] font-black uppercase tracking-widest text-blue-600 dark:text-blue-400"
                            >Mulai</span
                        >
                    </div>
                </div>

                <!-- Efek Dekoratif Samping -->
                <div
                    class="absolute left-0 top-0 h-full w-1 bg-blue-600 opacity-0 group-hover:opacity-100 transition-opacity"
                ></div>
            </a>
        @empty
            <div
                class="col-span-full flex flex-col items-center justify-center rounded-3xl border-2 border-dashed border-slate-200 py-12 dark:border-slate-800"
            >
                <div
                    class="mb-3 rounded-full bg-slate-100 p-4 dark:bg-slate-900"
                >
                    <i
                        class="fa-solid fa-calendar-xmark text-3xl text-slate-300"
                    ></i>
                </div>
                <div class="text-lg font-bold text-slate-400">
                    Tidak ada jadwal hari ini
                </div>
                <p class="text-sm text-slate-500">Anda tidak memiliki jadwal mengajar yang terdaftar untuk hari ini.</p>
            </div>
        @endforelse
    </div>
@endsection
