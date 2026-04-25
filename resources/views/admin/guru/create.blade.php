@extends('layouts.app', ['title' => 'Tambah Guru'])

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <div class="text-xl font-bold">Tambah Guru</div>
        <a href="{{ route('admin.guru.index') }}" class="rounded-lg px-3 py-2 text-sm font-semibold hover:bg-slate-100 dark:hover:bg-slate-900">Kembali</a>
    </div>

    <form method="POST" action="{{ route('admin.guru.store') }}"
          class="max-w-2xl space-y-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-950">
        @csrf

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label class="mb-1 block text-sm font-medium">Username</label>
                <input name="username" value="{{ old('username') }}" class="w-full rounded-xl border border-slate-200 px-4 py-3 dark:border-slate-800 dark:bg-slate-950">
                @error('username')<div class="mt-1 text-sm text-red-600">{{ $message }}</div>@enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium">Password</label>
                <input type="password" name="password" class="w-full rounded-xl border border-slate-200 px-4 py-3 dark:border-slate-800 dark:bg-slate-950">
                @error('password')<div class="mt-1 text-sm text-red-600">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label class="mb-1 block text-sm font-medium">Status Akun</label>
                <select name="status" class="w-full rounded-xl border border-slate-200 px-4 py-3 dark:border-slate-800 dark:bg-slate-950">
                    @foreach(['aktif','nonaktif'] as $s)
                        <option value="{{ $s }}" @selected(old('status','aktif')===$s)>{{ $s }}</option>
                    @endforeach
                </select>
                @error('status')<div class="mt-1 text-sm text-red-600">{{ $message }}</div>@enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium">Jenis Kelamin</label>
                <select name="jenis_kelamin" class="w-full rounded-xl border border-slate-200 px-4 py-3 dark:border-slate-800 dark:bg-slate-950">
                    <option value="L" @selected(old('jenis_kelamin')==='L')>L</option>
                    <option value="P" @selected(old('jenis_kelamin')==='P')>P</option>
                </select>
                @error('jenis_kelamin')<div class="mt-1 text-sm text-red-600">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label class="mb-1 block text-sm font-medium">Nama Guru</label>
                <input name="nama_guru" value="{{ old('nama_guru') }}" class="w-full rounded-xl border border-slate-200 px-4 py-3 dark:border-slate-800 dark:bg-slate-950">
                @error('nama_guru')<div class="mt-1 text-sm text-red-600">{{ $message }}</div>@enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium">NIP</label>
                <input name="nip" value="{{ old('nip') }}" class="w-full rounded-xl border border-slate-200 px-4 py-3 dark:border-slate-800 dark:bg-slate-950">
                @error('nip')<div class="mt-1 text-sm text-red-600">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label class="mb-1 block text-sm font-medium">No HP</label>
                <input name="no_hp" value="{{ old('no_hp') }}" class="w-full rounded-xl border border-slate-200 px-4 py-3 dark:border-slate-800 dark:bg-slate-950">
                @error('no_hp')<div class="mt-1 text-sm text-red-600">{{ $message }}</div>@enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium">Alamat</label>
                <input name="alamat" value="{{ old('alamat') }}" class="w-full rounded-xl border border-slate-200 px-4 py-3 dark:border-slate-800 dark:bg-slate-950">
                @error('alamat')<div class="mt-1 text-sm text-red-600">{{ $message }}</div>@enderror
            </div>
        </div>

        <button class="rounded-xl bg-blue-600 px-4 py-3 font-semibold text-white hover:bg-blue-700">Simpan</button>
    </form>
@endsection

