@extends ('layouts.app')

@section ('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">
                Pengaturan Akun
            </h1>
            <p class="text-slate-500 dark:text-slate-400">Kelola kredensial login dan keamanan akun Anda.</p>
        </div>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
            <!-- Sidebar Info -->
            <!-- Sidebar Info -->
            <div class="space-y-4">
                <!-- CARD PROFILE -->
                <div
                    class="p-6 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm"
                >
                    <!-- FOTO / ICON -->
                    <div class="text-center">
                        <div class="relative mx-auto w-24 h-24 mb-4">
                            <div
                                class="w-full h-full rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center border-4 border-white dark:border-slate-800 shadow-md"
                            >
                                <i
                                    class="fas fa-user text-3xl text-blue-600 dark:text-blue-400"
                                ></i>
                            </div>
                        </div>

                        <!-- NAMA -->
                        <h2
                            class="font-bold text-lg text-slate-800 dark:text-white"
                        >
                            {{ $user->guru?->nama_guru 
                    ?? $user->orangTua?->nama_ortu 
                    ?? $user->username }}
                        </h2>

                        <!-- ROLE -->
                        <p class="text-[10px] font-bold px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300 inline-block mt-2">
                            {{ Str::replace('_', ' ', strtoupper($user->role)) }}
                        </p>

                        <!-- STATUS -->
                        <div class="mt-3">
                            <span
                                class="rounded-full px-3 py-1 text-xs font-semibold
                    {{ $user->status === 'aktif'
                        ? 'bg-emerald-100 text-emerald-700'
                        : 'bg-red-100 text-red-700' }}"
                            >
                                {{ ucfirst($user->status) }}
                            </span>
                        </div>
                    </div>

                    <!-- DETAIL BIODATA -->
                    <div
                        class="mt-6 space-y-3 border-t border-slate-100 dark:border-slate-800 pt-4"
                    >
                        <!-- USERNAME -->
                        <div>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Username</p>

                            <p class="text-sm font-semibold text-slate-800 dark:text-white">
                                {{ $user->username }}
                            </p>
                        </div>

                        <!-- NIP -->
                        @if ($user->guru?->nip)
                            <div>
                                <p class="text-xs text-slate-500 dark:text-slate-400">NIP</p>

                                <p class="text-sm font-semibold text-slate-800 dark:text-white">
                                    {{ $user->guru->nip }}
                                </p>
                            </div>
                        @endif

                        <!-- JENIS KELAMIN -->
                        @if ($user->guru?->jenis_kelamin)
                            <div>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Jenis Kelamin</p>

                                <p class="text-sm font-semibold text-slate-800 dark:text-white">
                                    {{ $user->guru->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                </p>
                            </div>
                        @endif

                        <!-- NO HP -->
                        @if ($user->guru?->no_hp || $user->orangTua?->no_hp)
                            <div>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Nomor HP</p>

                                <p class="text-sm font-semibold text-slate-800 dark:text-white">
                                    {{ $user->guru?->no_hp ?? $user->orangTua?->no_hp }}
                                </p>
                            </div>
                        @endif

                        <!-- ALAMAT -->
                        @if ($user->guru?->alamat || $user->orangTua?->alamat)
                            <div>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Alamat</p>

                                <p class="text-sm font-semibold text-slate-800 dark:text-white">
                                    {{ $user->guru?->alamat ?? $user->orangTua?->alamat }}
                                </p>
                            </div>
                        @endif

                        <!-- WALI KELAS -->
                        @if ($user->guru && $user->guru->waliKelas)
                            <div>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Wali Kelas</p>

                                <p class="text-sm font-semibold text-slate-800 dark:text-white">
                                    {{ $user->guru->waliKelas->kelas?->nama_kelas }}
                                </p>
                            </div>
                        @endif

                        <!-- CREATED -->
                        <div>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Bergabung Sejak</p>

                            <p class="text-sm font-semibold text-slate-800 dark:text-white">
                                {{ $user->created_at->translatedFormat('d F Y') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Edit -->
            <div class="lg:col-span-2">
                <form
                    action="{{ route('profile.update') }}"
                    method="POST"
                    class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden"
                >
                    @csrf
                    @method ('PATCH')

                    <div
                        class="p-6 border-b border-slate-100 dark:border-slate-800"
                    >
                        <h3
                            class="text-lg font-semibold text-slate-800 dark:text-white"
                        >
                            Detail Login
                        </h3>
                    </div>

                    <div class="p-6 space-y-4">
                        <!-- Username -->
                        <div>
                            <label
                                class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1"
                                >Username</label
                            >
                            <input
                                type="text"
                                name="username"
                                value="{{ old('username', $user->username) }}"
                                required
                                class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-2.5 text-sm transition focus:border-blue-500 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                            />
                            @error ('username')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div
                        class="p-6 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50"
                    >
                        <h3
                            class="text-lg font-semibold text-slate-800 dark:text-white mb-4"
                        >
                            Keamanan
                        </h3>

                        <div class="space-y-4">
                            <div>
                                <label
                                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1"
                                    >Password Baru</label
                                >
                                <input
                                    type="password"
                                    name="password"
                                    placeholder="Kosongkan jika tidak ingin mengubah"
                                    class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-2.5 text-sm transition focus:border-blue-500 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                                />
                                @error ('password')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1"
                                    >Konfirmasi Password Baru</label
                                >
                                <input
                                    type="password"
                                    name="password_confirmation"
                                    class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-2.5 text-sm transition focus:border-blue-500 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                                />
                            </div>
                        </div>
                    </div>

                    <div
                        class="px-6 py-4 bg-slate-50 dark:bg-slate-800/50 flex items-center justify-end"
                    >
                        <button
                            type="submit"
                            class="flex items-center gap-2 rounded-xl bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md transition hover:bg-blue-700"
                        >
                            <i class="fas fa-check-circle"></i>
                            Update Profil
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
