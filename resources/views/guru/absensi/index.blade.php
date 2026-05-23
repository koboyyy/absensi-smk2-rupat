@extends ('layouts.app', ['title' => 'Guru - Absensi'])

@section ('content')
    <!-- HEADER -->
    <div class="mb-6 flex flex-col gap-1">
        <div
            class="text-xs font-bold uppercase tracking-widest text-blue-600 dark:text-blue-400"
        >
            Manajemen Kelas
        </div>

        <div class="text-2xl font-extrabold text-slate-800 dark:text-white">
            Jadwal Mengajar Hari Ini
        </div>

        <div class="text-sm text-slate-500 dark:text-slate-400">
            Monitoring jadwal realtime dan input absensi siswa.
        </div>
    </div>
    <!-- CARD REALTIME -->
    <div
        class="mb-8 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950"
    >
        <div class="mb-5 flex items-center justify-between">
            <div>
                <div class="text-lg font-black text-slate-800 dark:text-white">
                    Jadwal Realtime Hari Ini
                </div>

                <div class="text-sm text-slate-500">
                    {{ now()->translatedFormat('l, d F Y') }}
                </div>
            </div>

            <div
                class="rounded-2xl bg-blue-600 px-4 py-2 text-sm font-black text-white shadow-lg"
            >
                {{ now()->format('H:i') }}
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            @forelse ($jadwals as $j)
                @php

                $statusConfig = match($j->status_pelajaran) {

                    'belum_mulai' => [
                        'label' => 'Belum Mulai',
                        'color' => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300',
                        'icon' => 'fa-clock'
                    ],

                    'berlangsung' => [
                        'label' => 'Sedang Berlangsung',
                        'color' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300',
                        'icon' => 'fa-play'
                    ],

                    default => [
                        'label' => 'Selesai',
                        'color' => 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-300',
                        'icon' => 'fa-check'
                    ]
                };

            @endphp
                <div
                    class="rounded-2xl border border-slate-200 bg-slate-50 p-5 dark:border-slate-800 dark:bg-slate-900/50"
                >
                    <!-- STATUS -->
                    <div class="mb-4 flex items-center justify-between">
                        <div
                            class="flex items-center gap-2 rounded-xl px-3 py-1.5 text-xs font-black {{ $statusConfig['color'] }}"
                        >
                            <i class="fa-solid {{ $statusConfig['icon'] }}"></i>

                            {{ $statusConfig['label'] }}
                        </div>

                        <div class="text-xs font-bold text-slate-400 uppercase">
                            {{ $j->hari }}
                        </div>
                    </div>

                    <!-- MAPEL -->
                    <div
                        class="text-xl font-black text-slate-800 dark:text-white"
                    >
                        {{ $j->mapel?->nama_mapel }}
                    </div>

                    <!-- KELAS -->
                    <div
                        class="mt-2 flex items-center gap-2 text-sm font-bold text-blue-600 dark:text-blue-400"
                    >
                        <i class="fa-solid fa-users"></i>

                        Kelas {{ $j->kelas?->nama_kelas }}
                    </div>

                    <!-- JAM -->
                    <div
                        class="mt-3 flex items-center gap-2 text-sm text-slate-500"
                    >
                        <i class="fa-regular fa-clock"></i>

                        {{ $j->jam_mulai }} - {{ $j->jam_selesai }}
                    </div>

                    <!-- BUTTON -->
                    <div class="mt-5 flex flex-wrap gap-3">
                        <!-- MULAI ABSENSI -->
                        <a
                            href="{{ route('guru.absensi.form', ['jadwal' => $j->jadwal_id, 'tanggal' => now()->toDateString()]) }}"
                            class="flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2 text-sm font-black text-white hover:bg-blue-700 transition-all"
                        >
                            <i class="fa-solid fa-clipboard-user"></i>

                            Mulai Absensi
                        </a>

                        <!-- SELESAI -->
                        <button
                            type="button"
                            class="flex items-center gap-2 rounded-xl bg-rose-600 px-4 py-2 text-sm font-black text-white hover:bg-rose-700 transition-all"
                        >
                            <i class="fa-solid fa-check-double"></i>

                            Jam Pelajaran Selesai
                        </button>
                    </div>
                </div>

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

                    <p class="text-sm text-slate-500">Anda tidak memiliki jadwal mengajar hari ini.</p>
                </div>

            @endforelse
        </div>
    </div>

@endsection
