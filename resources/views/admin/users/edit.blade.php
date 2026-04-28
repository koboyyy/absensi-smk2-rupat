@extends('layouts.app', ['title' => 'Edit User'])

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <div class="text-xl font-bold">Edit Akun</div>
        <a href="{{ route('admin.users.index') }}" class="rounded-lg px-3 py-2 text-sm font-semibold hover:bg-slate-100 dark:hover:bg-slate-900">Kembali</a>
    </div>

    <form method="POST" action="{{ route('admin.users.update', $item->id) }}"
          class="max-w-xl space-y-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-950">
        @csrf
        @method('PUT')

        <div>
            <label class="mb-1 block text-sm font-medium">Username</label>
            <input name="username" value="{{ old('username', $item->username) }}"
                   class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 focus:border-blue-500 focus:outline-none dark:border-slate-800 dark:bg-slate-950">
            @error('username')<div class="mt-1 text-sm text-red-600">{{ $message }}</div>@enderror
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium">Password (opsional)</label>
            <input type="password" name="password" placeholder="Kosongkan jika tidak ingin diubah"
                   class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 focus:border-blue-500 focus:outline-none dark:border-slate-800 dark:bg-slate-950">
            @error('password')<div class="mt-1 text-sm text-red-600">{{ $message }}</div>@enderror
        </div>

        {{-- Input Role dengan ID untuk JavaScript --}}
        <div>
            <label class="mb-1 block text-sm font-medium">Role</label>
            <select name="role" id="role-select"
                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 focus:border-blue-500 focus:outline-none dark:border-slate-800 dark:bg-slate-950">
                @foreach($roles as $r)
                    <option value="{{ $r }}" @selected(old('role', $item->role) === $r)>{{ $r }}</option>
                @endforeach
            </select>
            @error('role')<div class="mt-1 text-sm text-red-600">{{ $message }}</div>@enderror
        </div>

        {{-- Dropdown Kelas Binaan (Hidden secara default, disinkronkan via JS) --}}
        <div id="kelas-container" class="hidden">
            <label class="mb-1 block text-sm font-medium">Pilih Kelas Binaannya</label>
            <select name="kelas_id"
                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 focus:border-blue-500 focus:outline-none dark:border-slate-800 dark:bg-slate-950">
                <option value="">-- Pilih Kelas --</option>
                @foreach($kelas as $k)
                    <option value="{{ $k->kelas_id }}" @selected(old('kelas_id', $item->kelas_id) == $k->kelas_id)>
                        {{ $k->nama_kelas }}
                    </option>
                @endforeach
            </select>
            @error('kelas_id')<div class="mt-1 text-sm text-red-600">{{ $message }}</div>@enderror
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium">Status</label>
            <select name="status"
                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 focus:border-blue-500 focus:outline-none dark:border-slate-800 dark:bg-slate-950">
                @foreach(['aktif','nonaktif'] as $s)
                    <option value="{{ $s }}" @selected(old('status', $item->status) === $s)>{{ $s }}</option>
                @endforeach
            </select>
            @error('status')<div class="mt-1 text-sm text-red-600">{{ $message }}</div>@enderror
        </div>

        <button class="w-full rounded-xl bg-blue-600 px-4 py-3 font-semibold text-white hover:bg-blue-700 transition-colors">
            Update Akun
        </button>
    </form>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role-select');
        const kelasContainer = document.getElementById('kelas-container');

        function toggleKelasInput() {
            if (roleSelect.value === 'wali_kelas') {
                kelasContainer.classList.remove('hidden');
            } else {
                kelasContainer.classList.add('hidden');
            }
        }

        // Jalankan saat halaman selesai dimuat (untuk data yang sudah ada di DB)
        toggleKelasInput();

        // Jalankan saat dropdown role diubah oleh user
        roleSelect.addEventListener('change', toggleKelasInput);
    });
</script>
@endsection