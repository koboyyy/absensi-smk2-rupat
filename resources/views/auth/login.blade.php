<!doctype html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Absensi SMKN 2 Rupat</title>
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
<body class="h-full">
    <div class="relative flex min-h-full items-center justify-center bg-slate-900">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-900/80 via-slate-900 to-slate-950"></div>
        <div class="absolute inset-0 opacity-25"
             style="background-image:url('{{ asset('images/school-bg.jpg') }}'); background-size:cover; background-position:center;">
        </div>
        <div class="relative mx-auto w-full max-w-md px-4">
            <div class="rounded-2xl border border-white/10 bg-white/10 p-6 shadow-2xl backdrop-blur">
                <div class="mb-6 flex items-center gap-3">
                    <div class="grid h-12 w-12 place-items-center rounded-xl bg-white text-blue-700">
                        <span class="text-lg font-extrabold">SMK</span>
                    </div>
                    <div>
                        <div class="text-lg font-bold text-white">SMK Negeri 2 Rupat</div>
                        <div class="text-sm text-white/70">Sistem Absensi Siswa</div>
                    </div>
                </div>

                <h1 class="mb-4 text-xl font-semibold text-white">Login</h1>

                <form method="POST" action="{{ route('login.attempt') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="mb-1 block text-sm font-medium text-white/80">Username</label>
                        <input name="username" value="{{ old('username') }}" autocomplete="username" autofocus
                               class="w-full rounded-xl border border-white/10 bg-white/10 px-4 py-3 text-white placeholder:text-white/40 focus:border-blue-400 focus:outline-none"
                               placeholder="Masukkan username">
                        @error('username')
                            <div class="mt-1 text-sm text-red-200">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-white/80">Password</label>
                        <input type="password" name="password" autocomplete="current-password"
                               class="w-full rounded-xl border border-white/10 bg-white/10 px-4 py-3 text-white placeholder:text-white/40 focus:border-blue-400 focus:outline-none"
                               placeholder="Masukkan password">
                        @error('password')
                            <div class="mt-1 text-sm text-red-200">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 text-sm text-white/80">
                            <input type="checkbox" name="remember" class="rounded border-white/20 bg-white/10 text-blue-500">
                            Ingat saya
                        </label>
                        <button type="button" data-theme-toggle class="text-sm font-medium text-white/80 hover:text-white">
                            Toggle Dark
                        </button>
                    </div>

                    <button class="w-full rounded-xl bg-blue-600 px-4 py-3 font-semibold text-white hover:bg-blue-700">
                        Masuk
                    </button>
                </form>

                <div class="mt-6 text-xs text-white/60">
                    Catatan: letakkan gambar sekolah di <code class="text-white/80">public/images/school-bg.jpg</code> dan logo di <code class="text-white/80">public/images/logo-smkn2.png</code> jika tersedia.
                </div>
            </div>
        </div>
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
