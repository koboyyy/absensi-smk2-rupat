@extends('layouts.app', ['title' => 'Tambah User'])

@section('content')
    <div class="mb-6 flex flex-col gap-1">
        <div class="text-xs font-bold uppercase tracking-widest text-blue-600 dark:text-blue-400">Manajemen Akun</div>
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-black text-slate-800 dark:text-white uppercase tracking-tight">Tambah Akun Baru</h1>
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-bold text-slate-600 transition-all hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-400">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                Kembali
            </a>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.users.store') }}"
          class="max-w-2xl space-y-5 rounded-3xl border border-slate-200 bg-white p-8 shadow-sm dark:border-slate-800 dark:bg-slate-950">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <!-- Username -->
            <div>
                <label class="mb-1.5 block text-sm font-bold text-slate-700 dark:text-white/80">Username</label>
                <input name="username" value="{{ old('username') }}" placeholder="Masukkan username"
                       class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none dark:border-slate-800 dark:bg-slate-900/50 dark:text-white">
                @error('username')<div class="mt-1 text-xs font-bold text-red-600 uppercase">{{ $message }}</div>@enderror
            </div>

            <!-- Password -->
            <div>
                <label class="mb-1.5 block text-sm font-bold text-slate-700 dark:text-white/80">Password</label>
                <input type="password" name="password" placeholder="••••••••"
                       class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none dark:border-slate-800 dark:bg-slate-900/50 dark:text-white">
                @error('password')<div class="mt-1 text-xs font-bold text-red-600 uppercase">{{ $message }}</div>@enderror
            </div>
        </div>

        <!-- Role Selection -->
        <div>
            <label class="mb-1.5 block text-sm font-bold text-slate-700 dark:text-white/80">Role / Jabatan</label>
            <select name="role" id="role-select"
                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 font-semibold focus:border-blue-500 focus:outline-none dark:border-slate-800 dark:bg-slate-900/50 dark:text-white">
                <option value="">-- Pilih Role --</option>
                @foreach($roles as $r)
                    <option value="{{ $r }}" @selected(old('role')===$r)>{{ Str::headline($r) }}</option>
                @endforeach
            </select>
            @error('role')<div class="mt-1 text-xs font-bold text-red-600 uppercase">{{ $message }}</div>@enderror
        </div>

        <!-- INPUT BARU: NIP (Muncul untuk Wali Kelas, Kepsek, BK) -->
        <div id="nip-container" class="hidden animate-fade-in">
            <label class="mb-1.5 block text-sm font-bold text-slate-700 dark:text-white/80">NIP (Nomor Induk Pegawai)</label>
            <input type="number" name="nip" value="{{ old('nip') }}" placeholder="Contoh: 1980xxxxxxxxxxxxxx"
                   class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 focus:border-blue-500 focus:outline-none dark:border-slate-800 dark:bg-slate-900/50 dark:text-white font-mono tracking-widest">
            <p class="mt-1 text-[10px] text-slate-400 font-medium italic">*Wajib diisi untuk pejabat sekolah.</p>
            @error('nip')<div class="mt-1 text-xs font-bold text-red-600 uppercase">{{ $message }}</div>@enderror
        </div>

        <!-- INPUT: Kelas (Hanya untuk Wali Kelas) -->
        <div id="kelas-container" class="hidden animate-fade-in">
            <label class="mb-1.5 block text-sm font-bold text-slate-700 dark:text-white/80">Kelas Binaan</label>
            <select name="kelas_id"
                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 focus:border-blue-500 focus:outline-none dark:border-slate-800 dark:bg-slate-900/50 dark:text-white">
                <option value="">-- Pilih Kelas --</option>
                @foreach($kelas as $k)
                    <option value="{{ $k->kelas_id }}" @selected(old('kelas_id') == $k->kelas_id)>
                        {{ $k->nama_kelas }}
                    </option>
                @endforeach
            </select>
            @error('kelas_id')<div class="mt-1 text-xs font-bold text-red-600 uppercase">{{ $message }}</div>@enderror
        </div>

        <!-- Status -->
        <div>
            <label class="mb-1.5 block text-sm font-bold text-slate-700 dark:text-white/80">Status Akun</label>
            <select name="status"
                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 focus:border-blue-500 focus:outline-none dark:border-slate-800 dark:bg-slate-900/50 dark:text-white">
                @foreach(['aktif','nonaktif'] as $s)
                    <option value="{{ $s }}" @selected(old('status', 'aktif')===$s)>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            @error('status')<div class="mt-1 text-xs font-bold text-red-600 uppercase">{{ $message }}</div>@enderror
        </div>

        <div class="pt-4 text-right">
            <button class="w-full md:w-auto rounded-xl bg-blue-600 px-10 py-3 font-black uppercase tracking-widest text-white shadow-lg shadow-blue-600/20 transition-all hover:bg-blue-700 active:scale-95">
                Simpan Akun
            </button>
        </div>
    </form>

    <script>
    const roleSelect = document.getElementById('role-select');
    const kelasContainer = document.getElementById('kelas-container');
    const nipContainer = document.getElementById('nip-container');

    function toggleExtraInputs() {
        const val = roleSelect.value;
        
        // Logika NIP: Tampil jika role adalah wali_kelas, kepala_sekolah, atau guru_bk
        const rolesWithNip = ['wali_kelas', 'kepala_sekolah', 'guru_bk'];
        if (rolesWithNip.includes(val)) {
            nipContainer.classList.remove('hidden');
        } else {
            nipContainer.classList.add('hidden');
        }

        // Logika Kelas: Hanya jika wali_kelas
        if (val === 'wali_kelas') {
            kelasContainer.classList.remove('hidden');
        } else {
            kelasContainer.classList.add('hidden');
        }
    }

    // Jalankan saat halaman dimuat (untuk menangani old value setelah error validation)
    toggleExtraInputs();

    // Jalankan saat dropdown role berubah
    roleSelect.addEventListener('change', toggleExtraInputs);
</script>
@endsection