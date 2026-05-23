@extends ('layouts.app', ['title' => 'Dashboard'])

@section ('content')
    <!-- Welcome Header -->
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
                <span
                    class="rounded-lg bg-slate-200 dark:bg-white/10 px-2 py-0.5 text-[10px] font-black uppercase text-slate-600 dark:text-slate-400"
                >
                    {{ Str::headline($user->role) }}
                </span>
                @if ($user->role === 'wali_kelas' && isset($kelas->nama_kelas))
                    <span class="h-1 w-1 rounded-full bg-slate-300"></span>
                    <span
                        class="text-xs font-medium text-slate-500 dark:text-slate-400"
                    >
                        Wali Kelas:
                        <span
                            class="font-bold text-slate-700 dark:text-slate-300"
                            >{{ $kelas->nama_kelas }}</span
                        >
                    </span>
                @endif
            </div>
        </div>

        {{-- Icon Lonceng Khusus Guru --}}
        @if ($user->role === 'guru')
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
    <!-- Stats Cards -->
    <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
        @php
            $cardConfigs = [
                'H' => ['label' => 'Hadir', 'color' => 'blue', 'icon' => 'fa-user-check'],
                'S' => ['label' => 'Sakit', 'color' => 'emerald', 'icon' => 'fa-face-frown-open'],
                'I' => ['label' => 'Izin', 'color' => 'amber', 'icon' => 'fa-envelope-open-text'],
                'A' => ['label' => 'Alfa', 'color' => 'rose', 'icon' => 'fa-user-xmark'],
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
                <!-- Garis dekoratif bawah -->
                <div
                    class="absolute bottom-0 left-0 h-1 w-full bg-{{ $conf['color'] }}-500 opacity-20 group-hover:opacity-100 transition-opacity"
                ></div>
            </div>
        @endforeach
    </div>
    <!-- Chart Section -->
    <div
        class="mt-8 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950"
    >
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-slate-800 dark:text-white">
                    Grafik Kehadiran
                </h3>
                <p class="text-sm text-slate-500 dark:text-slate-400">Statistik visual absensi periode ini.</p>
            </div>
            <div
                class="rounded-lg bg-slate-100 dark:bg-white/5 px-3 py-1 text-[10px] font-bold uppercase text-slate-500"
            >
                Live Data
            </div>
        </div>
        <div class="h-[350px]">
            <canvas id="chartKehadiran"></canvas>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <script>
        const data = @json ($stats);
        const ctx = document.getElementById("chartKehadiran");

        // Cek apakah dark mode aktif untuk penyesuaian warna grid
        const isDark = document.documentElement.classList.contains("dark");

        new Chart(ctx, {
            type: "bar",
            data: {
                labels: ["Hadir", "Sakit", "Izin", "Alfa"],
                datasets: [
                    {
                        label: "Siswa",
                        data: [data.H || 0, data.S || 0, data.I || 0, data.A || 0],
                        backgroundColor: [
                            "#2563eb", // blue-600
                            "#10b981", // emerald-500
                            "#f59e0b", // amber-500
                            "#f43f5e", // rose-500
                        ],
                        borderRadius: 12,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: isDark ? "#1e293b" : "#ffffff",
                        titleColor: isDark ? "#ffffff" : "#1e293b",
                        bodyColor: isDark ? "#cbd5e1" : "#64748b",
                        padding: 12,
                        cornerRadius: 12,
                        displayColors: false,
                        borderWidth: 1,
                        borderColor: "rgba(0,0,0,0.1)",
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: isDark
                                ? "rgba(255,255,255,0.05)"
                                : "rgba(0,0,0,0.05)",
                            drawBorder: false,
                        },
                        ticks: {
                            color: "#94a3b8",
                            font: { weight: "bold" },
                            precision: 0,
                        },
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            color: "#94a3b8",
                            font: { weight: "bold" },
                        },
                    },
                },
            },
        });
    </script>
@endsection
