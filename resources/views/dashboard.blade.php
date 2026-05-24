@extends ('layouts.app', ['title' => 'Dashboard'])

@section ('content')
    <!-- HEADER -->
    <div class="mb-8 flex items-center justify-between">
        <div class="flex flex-col gap-1">
            <div
                class="text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400"
            >
                Ringkasan Sistem
            </div>

            <div class="text-2xl font-extrabold text-slate-800 dark:text-white">
                Selamat Datang, {{ $user->username }}
            </div>

            <div class="flex items-center gap-2">
                <!-- ROLE AKTIF -->
                <span
                    class="rounded-lg bg-slate-200 dark:bg-white/10 px-2 py-0.5 text-[10px] font-black uppercase text-slate-600 dark:text-slate-400"
                >
                    {{ Str::headline($activeRole) }}
                </span>

                <!-- WALI KELAS -->
                @if (
                    $activeRole === 'guru' &&
                    isset($kelas->nama_kelas)
                )
                    <span class="h-1 w-1 rounded-full bg-slate-300"></span>
                    <span
                        class="text-xs font-medium text-slate-500 dark:text-slate-400"
                    >
                        Wali Kelas:

                        <span
                            class="font-bold text-slate-700 dark:text-slate-300"
                        >
                            {{ $kelas->nama_kelas }}
                        </span>
                    </span>

                @endif
            </div>
        </div>

        <!-- ICON LONCENG -->
        @if ($activeRole === 'guru')
            <div class="relative">
                <a
                    href="{{ route('guru.surat-izin.index') }}"
                    class="flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-600 shadow-sm transition-all hover:scale-110 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-400"
                >
                    <i class="fa-solid fa-bell text-xl"></i>
                </a>

                @if ($unreadSuratCount > 0)
                    <span
                        class="absolute -right-1 -top-1 flex h-5 w-5 items-center justify-center rounded-full bg-red-600 text-[10px] font-black text-white ring-4 ring-slate-100 dark:ring-slate-900"
                    >
                        {{ $unreadSuratCount > 9 ? '9+' : $unreadSuratCount }}
                    </span>
                    <span
                        class="absolute -right-1 -top-1 h-5 w-5 animate-ping rounded-full bg-red-600 opacity-75"
                    ></span>

                @endif
            </div>

        @endif
    </div>
    <!-- STATS -->
    <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
        @php

            $cardConfigs = [

                'H' => [
                    'label' => 'Hadir',
                    'color' => 'blue',
                    'icon' => 'fa-user-check'
                ],

                'S' => [
                    'label' => 'Sakit',
                    'color' => 'emerald',
                    'icon' => 'fa-face-frown-open'
                ],

                'I' => [
                    'label' => 'Izin',
                    'color' => 'amber',
                    'icon' => 'fa-envelope-open-text'
                ],

                'A' => [
                    'label' => 'Alfa',
                    'color' => 'rose',
                    'icon' => 'fa-user-xmark'
                ],

            ];

        @endphp

        @foreach ($cardConfigs as $k => $conf)
            <div
                class="group relative overflow-hidden rounded-3xl border border-slate-200 bg-white p-5 shadow-sm transition-all hover:-translate-y-1 hover:shadow-xl dark:border-slate-800 dark:bg-slate-950"
            >
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <div
                            class="text-xs font-bold uppercase tracking-tight text-slate-500 dark:text-slate-400"
                        >
                            {{ $conf['label'] }}
                        </div>

                        <div
                            class="mt-1 text-3xl font-black text-slate-800 dark:text-white"
                        >
                            {{ (int) ($stats[$k] ?? 0) }}
                        </div>
                    </div>

                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-xl bg-{{ $conf['color'] }}-100 dark:bg-{{ $conf['color'] }}-900/30 text-{{ $conf['color'] }}-600 dark:text-{{ $conf['color'] }}-400 transition-colors group-hover:bg-{{ $conf['color'] }}-600 group-hover:text-white"
                    >
                        <i class="fa-solid {{ $conf['icon'] }}"></i>
                    </div>
                </div>

                <!-- DECORATION -->
                <div
                    class="absolute bottom-0 left-0 h-1 w-full bg-{{ $conf['color'] }}-500 opacity-20 group-hover:opacity-100 transition-opacity"
                ></div>
            </div>

        @endforeach
    </div>
    <!-- CHART -->
    <div
        class="mt-8 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950"
    >
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-slate-800 dark:text-white">
                    Grafik Kehadiran
                </h3>

                <p
                    class="text-sm text-slate-500 dark:text-slate-400"
                >Statistik visual absensi periode ini.</p>
            </div>

            <div
                class="rounded-lg bg-slate-100 px-3 py-1 text-[10px] font-bold uppercase text-slate-500 dark:bg-white/5"
            >
                Live Data
            </div>
        </div>

        <!-- DEBUG -->
        {{-- <pre>{{ print_r($stats, true) }}</pre> --}}

        <div class="h-[350px]">
            <canvas id="chartKehadiran"></canvas>
        </div>
    </div>
    <!-- JADWAL HARI INI -->
    @if ($activeRole === 'guru')
        <!-- HEADER -->
        <div class="mb-6 mt-6 flex flex-col gap-1">
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
                    <div
                        class="text-lg font-black text-slate-800 dark:text-white"
                    >
                        Jadwal Realtime Hari Ini
                    </div>

                    <div class="text-sm text-slate-500">
                        {{ now()->translatedFormat('l, d F Y') }}
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                @forelse ($jadwals as $j)
                    @php

                    /**
                     * STATUS CONFIG
                     */
                    $statusConfig = match(
                        $j->status_pelajaran
                    ) {

                        'belum_mulai' => [

                            'label' =>
                                'Belum Mulai',

                            'color' =>
                                'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300',

                            'icon' =>
                                'fa-clock'

                        ],

                        'berlangsung' => [

                            'label' =>
                                'Sedang Berlangsung',

                            'color' =>
                                'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300',

                            'icon' =>
                                'fa-play'

                        ],

                        default => [

                            'label' =>
                                'Selesai',

                            'color' =>
                                'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-300',

                            'icon' =>
                                'fa-check'

                        ]

                    };

                    /**
                     * BUTTON AKTIF
                     */
                    $isBerlangsung =
                        $j->status_pelajaran ===
                        'berlangsung';

                @endphp
                    <div
                        class="rounded-2xl border border-slate-200 bg-slate-50 p-5 dark:border-slate-800 dark:bg-slate-900/50"
                    >
                        <!-- STATUS -->
                        <div class="mb-4 flex items-center justify-between">
                            <div
                                class="flex items-center gap-2 rounded-xl px-3 py-1.5 text-xs font-black {{ $statusConfig['color'] }}"
                            >
                                <i
                                    class="fa-solid {{ $statusConfig['icon'] }}"
                                ></i>

                                {{ $statusConfig['label'] }}
                            </div>

                            <div
                                class="text-xs font-bold uppercase text-slate-400"
                            >
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
                                href="{{ route(
            'guru.absensi.form',
            [
                'jadwal' =>
                    $j->jadwal_id,

                'tanggal' =>
                    now()->toDateString()
            ]
        ) }}"
                                class="{{ $j->is_berlangsung

            ? 'bg-blue-600 hover:bg-blue-700'

            : 'cursor-not-allowed bg-slate-300 dark:bg-slate-700'

        }}

        flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-black text-white transition-all"
                                {{ !$j->is_berlangsung
            ? 'onclick=return false;'
            : '' }}
                            >
                                <i class="fa-solid fa-clipboard-user"></i>

                                Mulai Absensi
                            </a>

                            <!-- JIKA BELUM SELESAI -->
                            @if (!$j->is_selesai)
                                <!-- SELESAI -->
                                <form
                                    action="{{ route(
                'guru.jadwal.selesai',
                $j->jadwal_id
            ) }}"
                                    method="POST"
                                >
                                    @csrf

                                    @method ('PUT')

                                    <button
                                        type="submit"
                                        onclick="
                                            return confirm(
                                                'Selesaikan jam pelajaran ini?',
                                            );
                                        "
                                        class="{{ $j->is_berlangsung

                    ? 'bg-rose-600 hover:bg-rose-700'

                    : 'cursor-not-allowed bg-slate-300 dark:bg-slate-700'

                }}

                flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-black text-white transition-all"
                                        {{ !$j->is_berlangsung
                    ? 'disabled'
                    : '' }}
                                    >
                                        <i class="fa-solid fa-check-double"></i>

                                        Jam Pelajaran Selesai
                                    </button>
                                </form>

                            @else
                                <!-- STATUS -->
                                <div
                                    class="flex items-center gap-2 rounded-xl bg-emerald-100 px-4 py-2 text-sm font-black text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300"
                                >
                                    <i class="fa-solid fa-circle-check"></i>

                                    Pelajaran Sudah Diselesaikan
                                </div>

                            @endif
                        </div>

                        <!-- INFO -->
                        @if (!$isBerlangsung)
                            <div
                                class="mt-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-xs font-semibold text-amber-700 dark:border-amber-900/40 dark:bg-amber-950/30 dark:text-amber-300"
                            >
                                <i class="fa-solid fa-circle-info mr-1"></i>

                                Tombol aktif saat jam pelajaran berlangsung.
                            </div>

                        @endif
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

                        <p
                            class="text-sm text-slate-500"
                        >Anda tidak memiliki jadwal mengajar hari ini.</p>
                    </div>

                @endforelse
            </div>
        </div>

    @endif
    @php

    $chartData = $stats ?? [

        'H' => 0,
        'S' => 0,
        'I' => 0,
        'A' => 0

    ];

    @endphp
    <!-- CHART JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <script>
        /**
         * DATA DARI CONTROLLER
         */
        const data = @json ($chartData);

        console.log(data);

        /**
         * ELEMENT CANVAS
         */
        const ctx = document.getElementById("chartKehadiran");

        /**
         * CEK ELEMENT
         */
        if (ctx) {
            /**
             * DARK MODE
             */
            const isDark = document.documentElement.classList.contains("dark");

            /**
             * CHART
             */
            new Chart(ctx, {
                type: "bar",

                data: {
                    labels: ["Hadir", "Sakit", "Izin", "Alfa"],

                    datasets: [
                        {
                            label: "Jumlah",

                            data: [data.H || 0, data.S || 0, data.I || 0, data.A || 0],

                            backgroundColor: [
                                "#2563eb",
                                "#10b981",
                                "#f59e0b",
                                "#f43f5e",
                            ],

                            borderRadius: 12,

                            borderSkipped: false,
                        },
                    ],
                },

                options: {
                    responsive: true,

                    maintainAspectRatio: false,

                    plugins: {
                        legend: {
                            display: false,
                        },

                        tooltip: {
                            backgroundColor: isDark ? "#1e293b" : "#ffffff",

                            titleColor: isDark ? "#ffffff" : "#1e293b",

                            bodyColor: isDark ? "#cbd5e1" : "#64748b",

                            borderWidth: 1,

                            borderColor: "rgba(0,0,0,0.1)",

                            padding: 12,

                            cornerRadius: 12,

                            displayColors: false,
                        },
                    },

                    scales: {
                        y: {
                            beginAtZero: true,

                            ticks: {
                                precision: 0,

                                color: "#94a3b8",

                                font: {
                                    weight: "bold",
                                },
                            },

                            grid: {
                                color: isDark
                                    ? "rgba(255,255,255,0.05)"
                                    : "rgba(0,0,0,0.05)",

                                drawBorder: false,
                            },
                        },

                        x: {
                            ticks: {
                                color: "#94a3b8",

                                font: {
                                    weight: "bold",
                                },
                            },

                            grid: {
                                display: false,
                            },
                        },
                    },
                },
            });
        } else {
            console.error("Canvas chartKehadiran tidak ditemukan");
        }
    </script>

@endsection
