<!doctype html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8" />

    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>{{ $title ?? 'Sistem Absensi - SMKN 2 Rupat' }}</title>

    <link href="{{ asset('resources/css/all.min.css') }}" rel="stylesheet" />

    @vite ('resources/css/app.css')

    <script>
        if (
            localStorage.theme === "dark" ||
            (!("theme" in localStorage) &&
                window.matchMedia("(prefers-color-scheme: dark)").matches)
        ) {
            document.documentElement.classList.add("dark");
        } else {
            document.documentElement.classList.remove("dark");
        }
    </script>
</head>

<body
    class="h-full bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100"
>
    <div class="min-h-full flex flex-col md:flex-row">
        <!-- SIDEBAR -->
        <header
            class="flex flex-col border-b md:border-b-0 md:border-r border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900 md:w-72 md:fixed md:inset-y-0"
        >
            <!-- LOGO -->
            <div class="flex items-center gap-3 px-6 py-8">
                <div class="rounded-xl bg-blue-50 p-2 dark:bg-blue-900/20">
                    <img
                        class="h-10 w-10 object-contain"
                        src="{{ asset('images/logo-smkn-2-rupat.png') }}"
                        alt="Logo"
                    />
                </div>

                <div class="leading-tight">
                    <div
                        class="font-bold tracking-tight text-slate-800 dark:text-white"
                    >
                        SMKN 2 RUPAT
                    </div>

                    <div
                        class="text-[10px] font-medium uppercase tracking-widest text-blue-600 dark:text-blue-400"
                    >
                        Sistem Absensi
                    </div>
                </div>
            </div>

            <!-- NAVIGATION -->
            <nav class="flex-1 overflow-y-auto px-4 space-y-1">
                @auth
                    @php

                        $active =
                            "bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400";

                        $default =
                            "text-slate-600 hover:bg-slate-50 dark:text-slate-400 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-slate-200";

                        $activeRole = session(
                            'active_role',
                            auth()->user()->roles[0] ?? null
                        );

                    @endphp
                    <!-- LABEL -->
                    <div
                        class="mb-2 px-2 text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500"
                    >
                        Menu Navigasi
                    </div>
                    <!-- ========================= -->
                    <!-- ADMIN -->
                    <!-- ========================= -->
                    @if ($activeRole === 'admin')
                        <a
                            href="{{ route('admin.users.index') }}"
                            class="group flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-semibold transition-all {{ request()->routeIs('admin.users.*') ? $active : $default }}"
                        >
                            <i class="fas fa-user-gear w-5"></i>

                            <span>Users</span>
                        </a>
                        <a
                            href="{{ route('admin.guru.index') }}"
                            class="group flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-semibold transition-all {{ request()->routeIs('admin.guru.*') ? $active : $default }}"
                        >
                            <i class="fas fa-chalkboard-user w-5"></i>

                            <span>Guru</span>
                        </a>
                        <a
                            href="{{ route('admin.kelas.index') }}"
                            class="group flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-semibold transition-all {{ request()->routeIs('admin.kelas.*') ? $active : $default }}"
                        >
                            <i class="fas fa-school w-5"></i>

                            <span>Kelas</span>
                        </a>
                        <a
                            href="{{ route('admin.mapel.index') }}"
                            class="group flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-semibold transition-all {{ request()->routeIs('admin.mapel.*') ? $active : $default }}"
                        >
                            <i class="fas fa-book w-5"></i>

                            <span>Mapel</span>
                        </a>
                        <a
                            href="{{ route('admin.jadwal.index') }}"
                            class="group flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-semibold transition-all {{ request()->routeIs('admin.jadwal.*') ? $active : $default }}"
                        >
                            <i class="fas fa-calendar-alt w-5"></i>

                            <span>Jadwal</span>
                        </a>
                        <a
                            href="{{ route('admin.siswa.index') }}"
                            class="group flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-semibold transition-all {{ request()->routeIs('admin.siswa.*') ? $active : $default }}"
                        >
                            <i class="fas fa-user-graduate w-5"></i>

                            <span>Siswa</span>
                        </a>

                    @endif
                    <!-- ========================= -->
                    <!-- GURU -->
                    <!-- ========================= -->
                    @if ($activeRole === 'guru')
                        <!-- DASHBOARD -->
                        <a
                            href="{{ route('dashboard') }}"
                            class="group flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-semibold transition-all {{ request()->routeIs('dashboard') ? $active : $default }}"
                        >
                            <i class="fas fa-chart-line w-5"></i>

                            <span>Dashboard</span>
                        </a>
                        <a
                            href="{{ route('guru.absensi.index') }}"
                            class="group flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-semibold transition-all {{ request()->routeIs('guru.absensi.*') ? $active : $default }}"
                        >
                            <i class="fas fa-tasks w-5"></i>

                            <span>Absensi Siswa</span>
                        </a>
                        <a
                            href="{{ route('guru.surat-izin.index') }}"
                            class="group flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-semibold transition-all {{ request()->routeIs('guru.surat-izin.*') ? $active : $default }}"
                        >
                            <i class="fas fa-inbox w-5"></i>

                            <span>Surat Masuk</span>
                        </a>

                    @endif
                    <!-- ========================= -->
                    <!-- WALI KELAS -->
                    <!-- ========================= -->
                    @if ($activeRole === 'wali_kelas')
                        <a
                            href="{{ route('wali.rekap.index') }}"
                            class="group flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-semibold transition-all {{ request()->routeIs('wali.rekap.*') ? $active : $default }}"
                        >
                            <i class="fas fa-file-invoice w-5"></i>

                            <span>Rekap Wali</span>
                        </a>

                    @endif
                    <!-- ========================= -->
                    <!-- BK -->
                    <!-- ========================= -->
                    @if ($activeRole === 'guru_bk')
                        <a
                            href="{{ route('bk.rekap.index') }}"
                            class="group flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-semibold transition-all {{ request()->routeIs('bk.rekap.*') ? $active : $default }}"
                        >
                            <i class="fas fa-file-medical w-5"></i>

                            <span>Rekap BK</span>
                        </a>

                    @endif
                    <!-- ========================= -->
                    <!-- KEPALA SEKOLAH -->
                    <!-- ========================= -->
                    @if ($activeRole === 'kepala_sekolah')
                        <a
                            href="{{ route('kepsek.laporan.index') }}"
                            class="group flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-semibold transition-all {{ request()->routeIs('kepsek.laporan.*') ? $active : $default }}"
                        >
                            <i class="fas fa-print w-5"></i>

                            <span>Laporan</span>
                        </a>

                    @endif
                    <!-- ========================= -->
                    <!-- ORANG TUA -->
                    <!-- ========================= -->
                    @if ($activeRole === 'orang_tua')
                        <a
                            href="{{ route('ortu.jadwal.index') }}"
                            class="group flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-semibold transition-all {{ request()->routeIs('ortu.jadwal.*') ? $active : $default }}"
                        >
                            <i class="fas fa-desktop w-5"></i>

                            <span>Monitoring</span>
                        </a>
                        <a
                            href="{{ route('ortu.surat-izin.index') }}"
                            class="group flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-semibold transition-all {{ request()->routeIs('ortu.surat-izin.*') ? $active : $default }}"
                        >
                            <i class="fas fa-envelope-open-text w-5"></i>

                            <span>Surat Izin</span>
                        </a>

                    @endif

                @endauth
            </nav>

            <!-- FOOTER -->
            <div class="border-t border-slate-100 p-4 dark:border-slate-800">
                @auth
                    <!-- THEME -->
                    <button
                        type="button"
                        data-theme-toggle
                        class="mb-3 flex w-full items-center justify-between rounded-xl border border-slate-200 px-4 py-2 text-[10px] font-bold text-slate-600 transition-all hover:bg-slate-50 dark:border-slate-800 dark:text-slate-400 dark:hover:bg-slate-800"
                    >
                        <span class="flex items-center gap-2">
                            <i class="fas fa-moon"></i>

                            TEMA MODE
                        </span>
                    </button>
                    <!-- USER CARD -->
                    <div
                        class="rounded-2xl bg-slate-50 p-3 dark:bg-slate-800/50"
                    >
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-10 w-10 flex-shrink-0 items-center justify-center overflow-hidden rounded-xl border border-blue-200 bg-blue-100 text-blue-600 dark:border-blue-800 dark:bg-blue-900/30 dark:text-blue-400"
                            >
                                <i class="fas fa-user-circle text-xl"></i>
                            </div>

                            <div class="flex-1 overflow-hidden">
                                <div
                                    class="truncate text-xs font-bold text-slate-800 dark:text-white"
                                >
                                    {{ auth()->user()->username }}
                                </div>

                                <div
                                    class="text-[10px] font-medium text-slate-500 dark:text-slate-400"
                                >
                                    {{ Str::headline($activeRole) }}
                                </div>
                            </div>

                            <a
                                href="{{ route('profile.edit') }}"
                                class="text-slate-400 transition-colors hover:text-blue-600"
                            >
                                <i class="fas fa-cog"></i>
                            </a>
                        </div>

                        <!-- BADGE -->
                        <div
                            class="mt-2 inline-flex items-center gap-1 rounded-lg bg-blue-100 px-2 py-1 text-[10px] font-black uppercase text-blue-700 dark:bg-blue-900/30 dark:text-blue-400"
                        >
                            <span
                                class="h-1.5 w-1.5 rounded-full bg-blue-600"
                            ></span>

                            Role Aktif
                        </div>
                    </div>
                    <!-- GANTI ROLE -->
                    @if (count(auth()->user()->roles ?? []) > 1)
                        <a
                            href="{{ route('role.select') }}"
                            class="mt-3 flex w-full items-center justify-between rounded-2xl border border-blue-200 bg-blue-50 px-4 py-3 text-xs font-black uppercase tracking-wider text-blue-700 transition-all hover:bg-blue-100 dark:border-blue-900/30 dark:bg-blue-950/20 dark:text-blue-400"
                        >
                            <span class="flex items-center gap-2">
                                <i class="fas fa-repeat"></i>

                                Ganti Role
                            </span>

                            <i class="fas fa-chevron-right text-[10px]"></i>
                        </a>

                    @endif
                    <!-- LOGOUT -->
                    <form
                        method="POST"
                        action="{{ route('logout') }}"
                        class="mt-2"
                    >
                        @csrf

                        <button
                            class="flex w-full items-center gap-3 rounded-xl px-3 py-2 text-xs font-bold text-red-500 transition-all hover:bg-red-50 dark:hover:bg-red-950/20"
                        >
                            <i class="fas fa-sign-out-alt"></i>

                            <span>Logout</span>
                        </button>
                    </form>

                @endauth
            </div>
        </header>

        <!-- CONTENT -->
        <div class="flex-1 md:ml-72">
            <main class="px-4 py-8 sm:px-6 lg:px-8">
                @if (session('success'))
                    <div
                        class="mb-6 flex items-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-900/40 dark:bg-emerald-950/40 dark:text-emerald-200"
                    >
                        <i class="fas fa-check-circle"></i>

                        {{ session('success') }}
                    </div>

                @endif

                @yield ('content')
            </main>
        </div>
    </div>

    <!-- DARK MODE -->
    <script>
        const themeToggleBtn = document.querySelector("[data-theme-toggle]");

        themeToggleBtn.addEventListener("click", function () {
            if (document.documentElement.classList.contains("dark")) {
                document.documentElement.classList.remove("dark");

                localStorage.theme = "light";
            } else {
                document.documentElement.classList.add("dark");

                localStorage.theme = "dark";
            }
        });
    </script>
</body>
</html>
