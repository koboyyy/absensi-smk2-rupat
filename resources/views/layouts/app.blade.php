<!doctype html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Sistem Absensi - SMKN 2 Rupat' }}</title>
    <script>
        tailwind = { config: { darkMode: 'class' } };
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        (function () {
            const key = 'theme';
            const stored = localStorage.getItem(key);
            const preferred = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            const theme = (stored === 'dark' || stored === 'light') ? stored : preferred;
            if (theme === 'dark') document.documentElement.classList.add('dark');
        })();
    </script>
</head>
<body class="h-full bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100">
    <div class="min-h-full">
        <header class="border-b border-slate-200 bg-white/80 backdrop-blur dark:border-slate-800 dark:bg-slate-950/70">
            <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-3 sm:px-6 lg:px-8">
                <div class="flex items-center gap-3">
                    <div class="grid h-9 w-9 place-items-center rounded-lg bg-blue-600 text-white">
                        <span class="text-sm font-bold">A</span>
                    </div>
                    <div class="leading-tight">
                        <div class="font-semibold text-blue-700 dark:text-blue-400">Absensi SMKN 2 Rupat</div>
                        <div class="text-xs text-slate-500 dark:text-slate-400">Berbasis Website</div>
                    </div>
                </div>

                <nav class="flex items-center gap-2">
                    <a href="{{ route('dashboard') }}"
                       class="rounded-lg px-3 py-2 text-sm font-medium hover:bg-slate-100 dark:hover:bg-slate-900">
                        Dashboard
                    </a>

                    @auth
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.users.index') }}" class="rounded-lg px-3 py-2 text-sm font-medium hover:bg-slate-100 dark:hover:bg-slate-900">Users</a>
                            <a href="{{ route('admin.guru.index') }}" class="rounded-lg px-3 py-2 text-sm font-medium hover:bg-slate-100 dark:hover:bg-slate-900">Guru</a>
                            <a href="{{ route('admin.kelas.index') }}" class="rounded-lg px-3 py-2 text-sm font-medium hover:bg-slate-100 dark:hover:bg-slate-900">Kelas</a>
                            <a href="{{ route('admin.mapel.index') }}" class="rounded-lg px-3 py-2 text-sm font-medium hover:bg-slate-100 dark:hover:bg-slate-900">Mapel</a>
                            <a href="{{ route('admin.jadwal.index') }}" class="rounded-lg px-3 py-2 text-sm font-medium hover:bg-slate-100 dark:hover:bg-slate-900">Jadwal</a>
                            <a href="{{ route('admin.siswa.index') }}" class="rounded-lg px-3 py-2 text-sm font-medium hover:bg-slate-100 dark:hover:bg-slate-900">Siswa</a>
                        @endif

                        @if(auth()->user()->role === 'orang_tua')
                            <a href="{{ route('ortu.surat-izin.index') }}" class="rounded-lg px-3 py-2 text-sm font-medium hover:bg-slate-100 dark:hover:bg-slate-900">Surat Izin</a>
                        @endif

                        @if(auth()->user()->role === 'guru')
                            <a href="{{ route('guru.absensi.index') }}" class="rounded-lg px-3 py-2 text-sm font-medium hover:bg-slate-100 dark:hover:bg-slate-900">Absensi</a>
                            <a href="{{ route('guru.surat-izin.index') }}" class="rounded-lg px-3 py-2 text-sm font-medium hover:bg-slate-100 dark:hover:bg-slate-900">Surat Izin</a>
                        @endif

                        @if(auth()->user()->role === 'wali_kelas')
                            <a href="{{ route('wali.validasi.index') }}" class="rounded-lg px-3 py-2 text-sm font-medium hover:bg-slate-100 dark:hover:bg-slate-900">Validasi</a>
                            <a href="{{ route('wali.rekap.index') }}" class="rounded-lg px-3 py-2 text-sm font-medium hover:bg-slate-100 dark:hover:bg-slate-900">Rekap</a>
                        @endif

                        @if(auth()->user()->role === 'guru_bk')
                            <a href="{{ route('bk.rekap.index') }}" class="rounded-lg px-3 py-2 text-sm font-medium hover:bg-slate-100 dark:hover:bg-slate-900">Rekap BK</a>
                        @endif

                        @if(auth()->user()->role === 'kepala_sekolah')
                            <a href="{{ route('kepsek.laporan.index') }}" class="rounded-lg px-3 py-2 text-sm font-medium hover:bg-slate-100 dark:hover:bg-slate-900">Laporan</a>
                        @endif

                        <button type="button" data-theme-toggle
                                class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-medium hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-950 dark:hover:bg-slate-900">
                            Dark Mode
                        </button>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="rounded-lg bg-blue-600 px-3 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                                Logout
                            </button>
                        </form>
                    @endauth
                </nav>
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800 dark:border-emerald-900/40 dark:bg-emerald-950/40 dark:text-emerald-200">
                    {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const btn = document.querySelector('[data-theme-toggle]');
            if (!btn) return;
            btn.addEventListener('click', () => {
                const root = document.documentElement;
                const next = root.classList.contains('dark') ? 'light' : 'dark';
                localStorage.setItem('theme', next);
                root.classList.toggle('dark', next === 'dark');
            });
        });
    </script>
</body>
</html>
