<!doctype html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Absensi SMKN 2 Rupat</title>
    @vite('resources/css/app.css')

    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up { animation: fadeInUp 0.8s ease-out forwards; }
        .delay-200 { animation-delay: 0.2s; opacity: 0; animation-fill-mode: forwards; }
        
        /* Smooth transition untuk switch theme */
        .theme-transition { transition: background-color 0.5s, color 0.5s, border-color 0.5s; }
    </style>

    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
</head>
<body class="h-full theme-transition">
    <div class="relative flex min-h-full flex-col items-center justify-center bg-slate-100 dark:bg-slate-900 overflow-hidden transition-colors duration-500">
        
        <!-- Background Layer -->
        <div class="absolute inset-0 bg-gradient-to-br from-blue-500/20 via-slate-100 to-white dark:from-blue-900/80 dark:via-slate-900 dark:to-slate-950"></div>
        <div class="absolute inset-0 opacity-10 dark:opacity-25 transition-opacity duration-500"
             style="background-image:url('{{ asset('images/pt-smkn-2-rupat.jpg') }}'); background-size:cover; background-position:center;">
        </div>

        <div class="flex flex-col lg:flex-row justify-evenly items-center w-full z-10 gap-10 px-6">
            <!-- Logo Section -->
            <div class="relative w-full max-w-2xl flex justify-center animate-fade-in-up">
                <img class="w-full h-auto drop-shadow-2xl" src="{{ asset('images/logo-smkn-2-rupat.png') }}" alt="logo-smkn-2-rupat">
            </div>
    
            <!-- Login Card Section -->
            <div class="relative w-full max-w-md animate-fade-in-up delay-200">
                <!-- Theme Switcher Floating -->
                <div class="absolute -top-12 right-0">
                    <button id="themeToggle" class="flex items-center gap-2 px-3 py-2 rounded-full bg-white/50 dark:bg-white/10 border border-slate-200 dark:border-white/10 backdrop-blur shadow-sm hover:scale-105 transition-all">
                        <span id="themeLabel" class="text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-white/80">Mode</span>
                        <div class="relative w-10 h-5 bg-slate-300 dark:bg-blue-600 rounded-full transition-colors">
                            <div id="toggleDot" class="absolute top-1 left-1 dark:left-6 w-3 h-3 bg-white rounded-full transition-all duration-300 shadow-sm"></div>
                        </div>
                    </button>
                </div>

                <div class="rounded-2xl border border-white/20 dark:border-white/10 bg-white/70 dark:bg-white/10 p-8 shadow-2xl backdrop-blur-xl transition-all">
                    <!-- Header Card -->
                    <div class="mb-8 flex items-center gap-4">
                        <div class="grid h-14 w-14 place-items-center">
                            <img class="w-full h-auto drop-shadow-2xl" src="{{ asset('images/logo-smkn-2-rupat.png') }}" alt="logo-smkn-2-rupat">
                        </div>
                        <div>
                            <div class="text-xl font-bold text-slate-800 dark:text-white leading-tight">SMK Negeri 2 Rupat</div>
                            <div class="text-sm text-slate-500 dark:text-white/60 font-medium">Sistem Absensi Siswa</div>
                        </div>
                    </div>
    
                    <h1 class="mb-6 text-2xl font-bold text-slate-800 dark:text-white">Login</h1>
    
                    <form method="POST" action="{{ route('login.attempt') }}" class="space-y-5">
                        @csrf
    
                        <!-- Username -->
                        <div>
                            <label class="mb-1.5 block text-sm font-semibold text-slate-700 dark:text-white/80">Username</label>
                            <input name="username" value="{{ old('username') }}" autocomplete="username" autofocus
                                   class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-white dark:bg-white/5 px-4 py-3 text-slate-900 dark:text-white placeholder:text-slate-400 dark:placeholder:text-white/20 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-all"
                                   placeholder="Masukkan username">
                            @error('username')
                                <div class="mt-1 text-sm text-red-500 dark:text-red-400 font-medium">{{ $message }}</div>
                            @enderror
                        </div>
    
                        <!-- Password with Toggle -->
                        <div>
                            <label class="mb-1.5 block text-sm font-semibold text-slate-700 dark:text-white/80">Password</label>
                            <div class="relative group">
                                <input type="password" name="password" id="password" autocomplete="current-password"
                                       class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-white dark:bg-white/5 px-4 py-3 pr-12 text-slate-900 dark:text-white placeholder:text-slate-400 dark:placeholder:text-white/20 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-all"
                                       placeholder="Masukkan password">
                                
                                <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 dark:text-white/30 hover:text-blue-500 dark:hover:text-white focus:outline-none transition-colors">
                                    <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5">
                                        <path id="eyePath" stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <div class="mt-1 text-sm text-red-500 dark:text-red-400 font-medium">{{ $message }}</div>
                            @enderror
                        </div>
    
                        <div class="flex items-center justify-between">
                            <label class="flex items-center gap-2 text-sm text-slate-600 dark:text-white/70 cursor-pointer group">
                                <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 dark:border-white/20 bg-white/50 dark:bg-white/10 text-blue-600 focus:ring-blue-500 transition-all">
                                <span class="group-hover:text-slate-900 dark:group-hover:text-white transition-colors">Ingat saya</span>
                            </label>
                        </div>
    
                        <button class="w-full rounded-xl bg-blue-600 px-4 py-3.5 font-bold text-white shadow-lg shadow-blue-600/30 hover:bg-blue-700 hover:-translate-y-0.5 active:scale-95 transition-all duration-200">
                            Masuk Ke Sistem
                        </button>
                    </form>
    
                    <!-- Footer Card -->
                    <div class="mt-10 pt-6 border-t border-slate-200 dark:border-white/10 text-center">
                        <p class="text-xs text-slate-400 dark:text-white/30 leading-relaxed font-medium">
                            &copy; 2026 SMK Negeri 2 Rupat. <br> Dikembangkan untuk Sistem Absensi Digital.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        const themeToggleBtn = document.getElementById('themeToggle');
        const themeLabel = document.getElementById('themeLabel');

        function updateThemeUI() {
            if (document.documentElement.classList.contains('dark')) {
                themeLabel.innerText = 'Gelap';
            } else {
                themeLabel.innerText = 'Terang';
            }
        }

        themeToggleBtn.addEventListener('click', function() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.theme = 'light';
            } else {
                document.documentElement.classList.add('dark');
                localStorage.theme = 'dark';
            }
            updateThemeUI();
        });

        // Initialize UI
        updateThemeUI();

        // Logika Toggle Password Visibility
        const togglePassword = document.querySelector('#togglePassword');
        const passwordInput = document.querySelector('#password');
        const eyeIcon = document.querySelector('#eyeIcon');

        togglePassword.addEventListener('click', function () {
            const isPassword = passwordInput.getAttribute('type') === 'password';
            passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
            
            if (isPassword) {
                // Ikon Mata Coret (Show)
                eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />`;
            } else {
                // Ikon Mata Biasa (Hide)
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                `;
            }
        });
    </script>
</body>
</html>