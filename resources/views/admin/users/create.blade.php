@extends ('layouts.app', ['title' => 'Tambah User'])

@section ('content')
    <div class="mb-6 flex flex-col gap-1">
        <div
            class="text-xs font-bold uppercase tracking-widest text-blue-600 dark:text-blue-400"
        >
            Manajemen Akun
        </div>

        <div class="flex items-center justify-between">
            <h1
                class="text-2xl font-black text-slate-800 dark:text-white uppercase tracking-tight"
            >
                Tambah Akun Baru
            </h1>

            <a
                href="{{ route('admin.users.index') }}"
                class="flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-bold text-slate-600 transition-all hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-400"
            >
                <i class="fa-solid fa-arrow-left text-xs"></i>
                Kembali
            </a>
        </div>
    </div>
    <form
        method="POST"
        action="{{ route('admin.users.store') }}"
        class="max-w-2xl space-y-5 rounded-3xl border border-slate-200 bg-white p-8 shadow-sm dark:border-slate-800 dark:bg-slate-950"
    >
        @csrf

        <!-- USERNAME + PASSWORD -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <!-- Username -->
            <div>
                <label
                    class="mb-1.5 block text-sm font-bold text-slate-700 dark:text-white/80"
                >
                    Username
                </label>

                <input
                    name="username"
                    value="{{ old('username') }}"
                    placeholder="Masukkan username"
                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none dark:border-slate-800 dark:bg-slate-900/50 dark:text-white"
                />

                @error ('username')
                    <div class="mt-1 text-xs font-bold text-red-600 uppercase">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label
                    class="mb-1.5 block text-sm font-bold text-slate-700 dark:text-white/80"
                >
                    Password
                </label>

                <input
                    type="password"
                    name="password"
                    placeholder="••••••••"
                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none dark:border-slate-800 dark:bg-slate-900/50 dark:text-white"
                />

                @error ('password')
                    <div class="mt-1 text-xs font-bold text-red-600 uppercase">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <!-- ROLE -->
        <div>
            <label
                class="mb-1.5 block text-sm font-bold text-slate-700 dark:text-white/80"
            >
                Role / Jabatan
            </label>

            <select
                name="roles[]"
                id="role-select"
                multiple
                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 font-semibold focus:border-blue-500 focus:outline-none dark:border-slate-800 dark:bg-slate-900/50 dark:text-white min-h-[220px]"
            >
                @foreach ($roles as $r)
                    <option
                        value="{{ $r }}"
                        @selected (in_array($r, old('roles', [])))
                    >
                        {{ Str::headline($r) }}
                    </option>
                @endforeach
            </select>

            <p class="mt-2 text-xs text-slate-500">Tekan CTRL + Klik untuk memilih lebih dari satu role.</p>

            @error ('roles')
                <div class="mt-1 text-xs font-bold text-red-600 uppercase">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- NAMA -->
        <div>
            <label
                class="mb-1.5 block text-sm font-bold text-slate-700 dark:text-white/80"
            >
                Nama Lengkap
            </label>

            <input
                type="text"
                name="nama"
                value="{{ old('nama') }}"
                placeholder="Masukkan nama lengkap"
                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 focus:border-blue-500 focus:outline-none dark:border-slate-800 dark:bg-slate-900/50 dark:text-white"
            />

            @error ('nama')
                <div class="mt-1 text-xs font-bold text-red-600 uppercase">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- NIP -->
        <div id="nip-container" class="hidden animate-fade-in">
            <label
                class="mb-1.5 block text-sm font-bold text-slate-700 dark:text-white/80"
            >
                NIP (Nomor Induk Pegawai)
            </label>

            <input
                type="text"
                name="nip"
                value="{{ old('nip') }}"
                placeholder="Contoh: 1980xxxxxxxx"
                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 focus:border-blue-500 focus:outline-none dark:border-slate-800 dark:bg-slate-900/50 dark:text-white font-mono tracking-widest"
            />

            <p class="mt-1 text-[10px] text-slate-400 italic">*Wajib untuk Guru, Wali Kelas, BK, dan Kepala Sekolah</p>

            @error ('nip')
                <div class="mt-1 text-xs font-bold text-red-600 uppercase">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- KELAS -->
        <div id="kelas-container" class="hidden animate-fade-in">
            <label
                class="mb-1.5 block text-sm font-bold text-slate-700 dark:text-white/80"
            >
                Kelas Binaan
            </label>

            <select
                name="kelas_id"
                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 focus:border-blue-500 focus:outline-none dark:border-slate-800 dark:bg-slate-900/50 dark:text-white"
            >
                <option value="">-- Pilih Kelas --</option>

                @foreach ($kelas as $k)
                    <option
                        value="{{ $k->kelas_id }}"
                        @selected (old('kelas_id') == $k->kelas_id)
                    >
                        {{ $k->nama_kelas }}
                    </option>
                @endforeach
            </select>

            @error ('kelas_id')
                <div class="mt-1 text-xs font-bold text-red-600 uppercase">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- NO HP -->
        <div>
            <label
                class="mb-1.5 block text-sm font-bold text-slate-700 dark:text-white/80"
            >
                Nomor HP
            </label>

            <input
                type="text"
                name="no_hp"
                value="{{ old('no_hp') }}"
                placeholder="08xxxxxxxxxx"
                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 focus:border-blue-500 focus:outline-none dark:border-slate-800 dark:bg-slate-900/50 dark:text-white"
            />

            @error ('no_hp')
                <div class="mt-1 text-xs font-bold text-red-600 uppercase">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- ALAMAT -->
        <div>
            <label
                class="mb-1.5 block text-sm font-bold text-slate-700 dark:text-white/80"
            >
                Alamat
            </label>

            <textarea
                name="alamat"
                rows="3"
                placeholder="Masukkan alamat"
                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 focus:border-blue-500 focus:outline-none dark:border-slate-800 dark:bg-slate-900/50 dark:text-white"
                >{{ old('alamat') }}</textarea
            >

            @error ('alamat')
                <div class="mt-1 text-xs font-bold text-red-600 uppercase">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- STATUS -->
        <div>
            <label
                class="mb-1.5 block text-sm font-bold text-slate-700 dark:text-white/80"
            >
                Status Akun
            </label>

            <select
                name="status"
                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 focus:border-blue-500 focus:outline-none dark:border-slate-800 dark:bg-slate-900/50 dark:text-white"
            >
                @foreach (['aktif','nonaktif'] as $s)
                    <option
                        value="{{ $s }}"
                        @selected (old('status', 'aktif') === $s)
                    >
                        {{ ucfirst($s) }}
                    </option>
                @endforeach
            </select>

            @error ('status')
                <div class="mt-1 text-xs font-bold text-red-600 uppercase">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- BUTTON -->
        <div class="pt-4 text-right">
            <button
                class="w-full md:w-auto rounded-xl bg-blue-600 px-10 py-3 font-black uppercase tracking-widest text-white shadow-lg shadow-blue-600/20 transition-all hover:bg-blue-700 active:scale-95"
            >
                Simpan Akun
            </button>
        </div>
    </form>
    <script>
        const roleSelect = document.getElementById("role-select");
        const kelasContainer = document.getElementById("kelas-container");
        const nipContainer = document.getElementById("nip-container");

        function toggleExtraInputs() {
            const selectedRoles = Array.from(roleSelect.selectedOptions).map(
                (option) => option.value,
            );

            // ROLE YANG WAJIB NIP
            const rolesWithNip = [
                "guru",
                "wali_kelas",
                "kepala_sekolah",
                "guru_bk",
            ];

            // TAMPILKAN NIP
            const showNip = selectedRoles.some((role) =>
                rolesWithNip.includes(role),
            );

            if (showNip) {
                nipContainer.classList.remove("hidden");
            } else {
                nipContainer.classList.add("hidden");
            }

            // TAMPILKAN KELAS
            if (selectedRoles.includes("wali_kelas")) {
                kelasContainer.classList.remove("hidden");
            } else {
                kelasContainer.classList.add("hidden");
            }
        }

        // Saat halaman dibuka
        toggleExtraInputs();

        // Saat role berubah
        roleSelect.addEventListener("change", toggleExtraInputs);
    </script>
@endsection
