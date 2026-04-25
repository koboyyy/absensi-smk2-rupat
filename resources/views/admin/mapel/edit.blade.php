@extends('layouts.app', ['title' => 'Edit Mapel'])

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <div class="text-xl font-bold">Edit Mapel</div>
        <a href="{{ route('admin.mapel.index') }}" class="rounded-lg px-3 py-2 text-sm font-semibold hover:bg-slate-100 dark:hover:bg-slate-900">Kembali</a>
    </div>

    <form method="POST" action="{{ route('admin.mapel.update', $item->mapel_id) }}"
          class="max-w-xl space-y-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-950">
        @csrf
        @method('PUT')

        <div>
            <label class="mb-1 block text-sm font-medium">Kode Mapel</label>
            <input name="kode_mapel" value="{{ old('kode_mapel', $item->kode_mapel) }}"
                   class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 focus:border-blue-500 focus:outline-none dark:border-slate-800 dark:bg-slate-950">
            @error('kode_mapel')<div class="mt-1 text-sm text-red-600">{{ $message }}</div>@enderror
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium">Nama Mapel</label>
            <input name="nama_mapel" value="{{ old('nama_mapel', $item->nama_mapel) }}"
                   class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 focus:border-blue-500 focus:outline-none dark:border-slate-800 dark:bg-slate-950">
            @error('nama_mapel')<div class="mt-1 text-sm text-red-600">{{ $message }}</div>@enderror
        </div>

        <button class="rounded-xl bg-blue-600 px-4 py-3 font-semibold text-white hover:bg-blue-700">Update</button>
    </form>
@endsection

