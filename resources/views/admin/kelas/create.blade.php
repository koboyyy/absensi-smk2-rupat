@extends('layouts.app', ['title' => 'Tambah Kelas'])

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <div class="text-xl font-bold">Tambah Kelas</div>
        <a href="{{ route('admin.kelas.index') }}" class="rounded-lg px-3 py-2 text-sm font-semibold hover:bg-slate-100 dark:hover:bg-slate-900">Kembali</a>
    </div>

    <form method="POST" action="{{ route('admin.kelas.store') }}"
          class="max-w-xl space-y-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-950">
        @csrf

        <div>
            <label class="mb-1 block text-sm font-medium">Nama Kelas</label>
            <input name="nama_kelas" value="{{ old('nama_kelas') }}"
                   class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 focus:border-blue-500 focus:outline-none dark:border-slate-800 dark:bg-slate-950">
            @error('nama_kelas')<div class="mt-1 text-sm text-red-600">{{ $message }}</div>@enderror
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium">Tingkat</label>
            <select name="tingkat"
                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 focus:border-blue-500 focus:outline-none dark:border-slate-800 dark:bg-slate-950">
                @foreach(['10','11','12'] as $t)
                    <option value="{{ $t }}" @selected(old('tingkat')===$t)>{{ $t }}</option>
                @endforeach
            </select>
            @error('tingkat')<div class="mt-1 text-sm text-red-600">{{ $message }}</div>@enderror
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium">Jurusan</label>
            <input name="jurusan" value="{{ old('jurusan') }}"
                   class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 focus:border-blue-500 focus:outline-none dark:border-slate-800 dark:bg-slate-950">
            @error('jurusan')<div class="mt-1 text-sm text-red-600">{{ $message }}</div>@enderror
        </div>

        <button class="rounded-xl bg-blue-600 px-4 py-3 font-semibold text-white hover:bg-blue-700">Simpan</button>
    </form>
@endsection

