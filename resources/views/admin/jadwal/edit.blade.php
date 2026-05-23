@extends ('layouts.app', ['title' => 'Edit Jadwal'])

@section ('content')
    {{-- Tambahkan Flatpickr agar sama dengan form Create --}}
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css"
    />
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <div class="mb-4 flex items-center justify-between">
        <div class="text-xl font-bold">Edit Jadwal</div>
        <a
            href="{{ route('admin.jadwal.index') }}"
            class="rounded-lg px-3 py-2 text-sm font-semibold hover:bg-slate-100 dark:hover:bg-slate-900"
            >Kembali</a
        >
    </div>
    <form
        method="POST"
        action="{{ route('admin.jadwal.update', $item->jadwal_id) }}"
        class="max-w-2xl space-y-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-950"
    >
        @csrf
        @method ('PUT')

        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <div>
                <label class="mb-1 block text-sm font-medium">Hari</label>
                {{-- Diubah ke select agar konsisten dengan Create --}}
                <select
                    name="hari"
                    class="w-full rounded-xl border border-slate-200 px-4 py-3 dark:border-slate-800 dark:bg-slate-950"
                >
                    @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $hari)
                        <option
                            value="{{ $hari }}"
                            @selected (old('hari', $item->hari) == $hari)
                            >{{ $hari }}
                        </option>
                    @endforeach
                </select>
                @error ('hari')
                    <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium">Jam Mulai</label>
                {{-- Gunakan id="jam_mulai" dan class="timepicker" untuk Flatpickr --}}
                <input
                    type="text"
                    name="jam_mulai"
                    id="jam_mulai"
                    value="{{ old('jam_mulai', \Carbon\Carbon::parse($item->jam_mulai)->format('H:i')) }}"
                    class="timepicker w-full rounded-xl border border-slate-200 px-4 py-3 dark:border-slate-800 dark:bg-slate-950"
                />
                @error ('jam_mulai')
                    <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium"
                    >Jam Selesai</label
                >
                <input
                    type="text"
                    name="jam_selesai"
                    id="jam_selesai"
                    value="{{ old('jam_selesai', \Carbon\Carbon::parse($item->jam_selesai)->format('H:i')) }}"
                    class="timepicker w-full rounded-xl border border-slate-200 px-4 py-3 dark:border-slate-800 dark:bg-slate-950"
                />
                @error ('jam_selesai')
                    <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <div>
                <label class="mb-1 block text-sm font-medium">Kelas</label>
                <select
                    name="kelas_id"
                    class="w-full rounded-xl border border-slate-200 px-4 py-3 dark:border-slate-800 dark:bg-slate-950"
                >
                    @foreach ($kelas as $k)
                        <option
                            value="{{ $k->kelas_id }}"
                            @selected (old('kelas_id', $item->kelas_id) == $k->kelas_id)
                            >{{ $k->nama_kelas }}
                        </option>
                    @endforeach
                </select>
                @error ('kelas_id')
                    <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium">Mapel</label>
                <select
                    name="mapel_id"
                    class="w-full rounded-xl border border-slate-200 px-4 py-3 dark:border-slate-800 dark:bg-slate-950"
                >
                    @foreach ($mapel as $m)
                        <option
                            value="{{ $m->mapel_id }}"
                            @selected (old('mapel_id', $item->mapel_id) == $m->mapel_id)
                            >{{ $m->kode_mapel }} - {{ $m->nama_mapel }}
                        </option>
                    @endforeach
                </select>
                @error ('mapel_id')
                    <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium">Guru</label>
                <select
                    name="guru_id"
                    class="w-full rounded-xl border border-slate-200 px-4 py-3 dark:border-slate-800 dark:bg-slate-950"
                >
                    @foreach ($guru as $g)
                        <option
                            value="{{ $g->guru_id }}"
                            @selected (old('guru_id', $item->guru_id) == $g->guru_id)
                            >{{ $g->nama_guru }}
                        </option>
                    @endforeach
                </select>
                @error ('guru_id')
                    <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <button
            class="rounded-xl bg-blue-600 px-4 py-3 font-semibold text-white hover:bg-blue-700"
        >
            Simpan Perubahan
        </button>
    </form>
    {{-- Script Flatpickr yang sama dengan Create --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            flatpickr(".timepicker", {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                time_24hr: true,
                minuteIncrement: 5,
            });
        });
    </script>
@endsection
