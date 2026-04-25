@extends('layouts.app', ['title' => 'Buat Surat Izin'])

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <div class="text-xl font-bold">Buat Surat Izin / Sakit</div>
        <a href="{{ route('ortu.surat-izin.index') }}" class="rounded-lg px-3 py-2 text-sm font-semibold hover:bg-slate-100 dark:hover:bg-slate-900">Kembali</a>
    </div>

    <form method="POST" action="{{ route('ortu.surat-izin.store') }}" enctype="multipart/form-data"
          class="max-w-2xl space-y-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-950">
        @csrf

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label class="mb-1 block text-sm font-medium">Siswa</label>
                <select name="siswa_id" class="w-full rounded-xl border border-slate-200 px-4 py-3 dark:border-slate-800 dark:bg-slate-950">
                    @foreach($siswas as $s)
                        <option value="{{ $s->siswa_id }}" @selected(old('siswa_id')==$s->siswa_id)>
                            {{ $s->nama_siswa }} ({{ $s->kelas?->nama_kelas }})
                        </option>
                    @endforeach
                </select>
                @error('siswa_id')<div class="mt-1 text-sm text-red-600">{{ $message }}</div>@enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium">Tanggal</label>
                <input type="date" name="tanggal" value="{{ old('tanggal', now()->toDateString()) }}"
                       class="w-full rounded-xl border border-slate-200 px-4 py-3 dark:border-slate-800 dark:bg-slate-950">
                @error('tanggal')<div class="mt-1 text-sm text-red-600">{{ $message }}</div>@enderror
            </div>
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium">Jadwal / Mapel</label>
            <select name="jadwal_id" class="w-full rounded-xl border border-slate-200 px-4 py-3 dark:border-slate-800 dark:bg-slate-950">
                @foreach($jadwals as $j)
                    <option value="{{ $j->jadwal_id }}" @selected(old('jadwal_id')==$j->jadwal_id)>
                        {{ $j->kelas?->nama_kelas }} - {{ $j->hari }} ({{ $j->jam_mulai }}-{{ $j->jam_selesai }}) - {{ $j->mapel?->nama_mapel }} - {{ $j->guru?->nama_guru }}
                    </option>
                @endforeach
            </select>
            @error('jadwal_id')<div class="mt-1 text-sm text-red-600">{{ $message }}</div>@enderror
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium">Keterangan</label>
            <textarea name="keterangan" rows="3"
                      class="w-full rounded-xl border border-slate-200 px-4 py-3 dark:border-slate-800 dark:bg-slate-950">{{ old('keterangan') }}</textarea>
            @error('keterangan')<div class="mt-1 text-sm text-red-600">{{ $message }}</div>@enderror
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium">File Bukti (opsional)</label>
            <input type="file" name="file_bukti"
                   class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 dark:border-slate-800 dark:bg-slate-950">
            @error('file_bukti')<div class="mt-1 text-sm text-red-600">{{ $message }}</div>@enderror
        </div>

        <button class="rounded-xl bg-blue-600 px-4 py-3 font-semibold text-white hover:bg-blue-700">Kirim</button>
    </form>
@endsection

