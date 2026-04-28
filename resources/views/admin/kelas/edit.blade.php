@extends('layouts.app', ['title' => 'Edit Kelas'])

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <div class="text-xl font-bold">Edit Kelas</div>
        <a href="{{ route('admin.kelas.index') }}" class="rounded-lg px-3 py-2 text-sm font-semibold hover:bg-slate-100 dark:hover:bg-slate-900">Kembali</a>
    </div>

    <form method="POST" action="{{ route('admin.kelas.update', $item->kelas_id) }}"
          class="max-w-xl space-y-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-950">
        @csrf
        @method('PUT')

        <div>
            <label class="mb-1 block text-sm font-medium">Nama Kelas</label>
            <input name="nama_kelas" value="{{ old('nama_kelas', $item->nama_kelas) }}"
                   class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 focus:border-blue-500 focus:outline-none dark:border-slate-800 dark:bg-slate-950">
            @error('nama_kelas')<div class="mt-1 text-sm text-red-600">{{ $message }}</div>@enderror
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium">Tingkat</label>
            <select name="tingkat"
                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 focus:border-blue-500 focus:outline-none dark:border-slate-800 dark:bg-slate-950">
                @foreach(['10','11','12'] as $t)
                    <option value="{{ $t }}" @selected(old('tingkat', $item->tingkat) == $t)>{{ $t }}</option>
                @endforeach
            </select>
            @error('tingkat')<div class="mt-1 text-sm text-red-600">{{ $message }}</div>@enderror
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium">Jurusan</label>
            <select name="jurusan"
                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 focus:border-blue-500 focus:outline-none dark:border-slate-800 dark:bg-slate-950">
                @php
                    $daftar_jurusan = [
                        'AKN' => 'Akuntansi (AKN)',
                        'TKJ' => 'Teknik Komputer dan Jaringan (TKJ)',
                        'TSM' => 'Teknik Sepeda Motor (TSM)'
                    ];
                @endphp
                @foreach($daftar_jurusan as $kode => $nama)
                    <option value="{{ $kode }}" @selected(old('jurusan', $item->jurusan) == $kode)>{{ $nama }}</option>
                @endforeach
            </select>
            @error('jurusan')<div class="mt-1 text-sm text-red-600">{{ $message }}</div>@enderror
        </div>

        <button class="w-full rounded-xl bg-blue-600 px-4 py-3 font-semibold text-white hover:bg-blue-700 transition-colors">
            Perbarui Kelas
        </button>
    </form>
@endsection