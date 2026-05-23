@extends ('layouts.app', ['title' => 'Wali Kelas - Validasi Absensi'])

@section ('content')
    <div
        class="mb-4 flex flex-col gap-2 md:flex-row md:items-end md:justify-between"
    >
        <div>
            <div class="text-xl font-bold">Validasi Absensi</div>
            <div class="text-sm text-slate-500 dark:text-slate-400">
                Validasi absensi siswa di kelas binaan.
            </div>
        </div>
        <form
            method="GET"
            action="{{ route('wali.validasi.index') }}"
            class="flex items-center gap-2"
        >
            <input
                type="date"
                name="tanggal"
                value="{{ $tanggal }}"
                class="rounded-xl border border-slate-200 bg-white px-4 py-2 dark:border-slate-800 dark:bg-slate-950"
            />
            <button
                class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-950 dark:hover:bg-slate-900"
            >
                Tampilkan
            </button>
        </form>
    </div>
    <form
        method="POST"
        action="{{ route('wali.validasi.batch') }}"
        class="space-y-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-950"
    >
        @csrf
        <input type="hidden" name="tanggal" value="{{ $tanggal }}" />

        <div class="flex items-center justify-between">
            <div class="text-sm text-slate-500 dark:text-slate-400">
                Centang data yang ingin divalidasi (set `wali_validasi = true`).
            </div>
            <button
                class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700"
            >
                Validasi Terpilih
            </button>
        </div>

        <div
            class="overflow-hidden rounded-xl border border-slate-200 dark:border-slate-800"
        >
            <table class="w-full text-left text-sm">
                <thead
                    class="bg-slate-50 text-slate-600 dark:bg-slate-900/40 dark:text-slate-300"
                >
                    <tr>
                        <th class="px-4 py-3">Pilih</th>
                        <th class="px-4 py-3">Siswa</th>
                        <th class="px-4 py-3">Mapel</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Validasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                    @forelse ($items as $it)
                        <tr>
                            <td class="px-4 py-3">
                                @if (!$it->wali_validasi)
                                    <input
                                        type="checkbox"
                                        name="absensi_id[]"
                                        value="{{ $it->absensi_id }}"
                                    />
                                @else
                                    <span class="text-xs text-slate-500"
                                        >-</span
                                    >
                                @endif
                            </td>
                            <td class="px-4 py-3 font-medium">
                                {{ $it->siswa?->nama_siswa }}
                            </td>
                            <td class="px-4 py-3">
                                {{ $it->jadwal?->mapel?->nama_mapel }}
                            </td>
                            <td class="px-4 py-3">{{ $it->status }}</td>
                            <td class="px-4 py-3">
                                <span
                                    class="rounded-full px-2 py-1 text-xs font-semibold {{ $it->wali_validasi ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-700' }}"
                                >
                                    {{ $it->wali_validasi ? 'sudah' : 'belum' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td
                                colspan="5"
                                class="px-4 py-6 text-center text-slate-500 dark:text-slate-400"
                            >
                                Tidak ada data.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </form>
@endsection
