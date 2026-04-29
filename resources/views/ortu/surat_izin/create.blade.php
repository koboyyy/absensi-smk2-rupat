@extends('layouts.app', ['title' => 'Buat Surat Izin'])

@section('content')
    {{-- Tambahkan CDN Select2 di bagian atas atau di layout utama --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Penyesuaian agar Select2 cocok dengan gaya Tailwind Anda */
        .select2-container--default .select2-selection--multiple {
            border-radius: 0.75rem;
            border-color: #e2e8f0;
            padding: 5px;
        }
        .dark .select2-container--default .select2-selection--multiple {
            background-color: #020617;
            border-color: #1e293b;
        }
    </style>

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
            <label class="mb-1 block text-sm font-medium">Jadwal / Mapel (Bisa pilih banyak)</label>
            {{-- Perhatikan penambahan '[]' pada name dan atribut 'multiple' --}}
            <select name="jadwal_id[]" id="select-jadwal" multiple="multiple" class="w-full select2 rounded-xl">
                @foreach($jadwals as $j)
                    <option value="{{ $j->jadwal_id }}" {{ (is_array(old('jadwal_id')) && in_array($j->jadwal_id, old('jadwal_id'))) ? 'selected' : '' }}>
                        [{{ $j->hari }}] {{ $j->mapel?->nama_mapel }} ({{ $j->jam_mulai }}-{{ $j->jam_selesai }})
                    </option>
                @endforeach
            </select>
            @error('jadwal_id')<div class="mt-1 text-sm text-red-600">{{ $message }}</div>@enderror
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium">Keterangan</label>
            <textarea name="keterangan" rows="3" placeholder="Contoh: Sakit demam, Izin ada keperluan keluarga"
                      class="w-full rounded-xl border border-slate-200 px-4 py-3 dark:border-slate-800 dark:bg-slate-950">{{ old('keterangan') }}</textarea>
            @error('keterangan')<div class="mt-1 text-sm text-red-600">{{ $message }}</div>@enderror
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium">File Bukti (opsional)</label>
            <input type="file" name="file_bukti"
                   class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 dark:border-slate-800 dark:bg-slate-950">
            @error('file_bukti')<div class="mt-1 text-sm text-red-600">{{ $message }}</div>@enderror
        </div>

        <button type="submit" class="w-full rounded-xl bg-blue-600 px-4 py-3 font-semibold text-white hover:bg-blue-700 transition-colors uppercase tracking-widest text-xs">
            <i class="fa-solid fa-paper-plane mr-2"></i> Kirim Surat Izin
        </button>
    </form>

    {{-- Script Select2 --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#select-jadwal').select2({
                placeholder: " Pilih satu atau lebih mata pelajaran",
                allowClear: true
            });
        });
    </script>
@endsection