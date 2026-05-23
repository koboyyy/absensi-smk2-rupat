@extends ('layouts.app', ['title' => 'Rekap Kehadiran'])

@section ('content')
    <div
        class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between"
    >
        <div>
            <h1 class="text-2xl font-bold">Rekap Kehadiran Siswa</h1>
            <p class="text-sm text-slate-500 uppercase font-bold">{{ $jadwal->mapel->nama_mapel }} - {{ $jadwal->kelas->nama_kelas }}</p>
        </div>

        {{-- Form Filter Bulan & Tahun --}}
        <form
            method="GET"
            action="{{ route('guru.absensi.rekap-jadwal', $jadwal->jadwal_id) }}"
            class="flex flex-wrap items-center gap-2"
        >
            <select
                name="bulan"
                class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm dark:border-slate-800 dark:bg-slate-950"
            >
                @foreach (range(1, 12) as $m)
                    <option
                        value="{{ sprintf('%02d', $m) }}"
                        @selected ($bulan == sprintf('%02d', $m))
                    >
                        {{-- Tambahkan (int) di depan $m --}}
                        {{ \Carbon\Carbon::create()->month((int)$m)->translatedFormat('F') }}
                    </option>
                @endforeach
            </select>

            <select
                name="tahun"
                class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm dark:border-slate-800 dark:bg-slate-950"
            >
                @foreach (range(date('Y') - 2, date('Y') + 1) as $y)
                    <option value="{{ $y }}" @selected ($tahun == $y)
                        >{{ $y }}
                    </option>
                @endforeach
            </select>

            <button
                type="submit"
                class="rounded-xl bg-slate-800 px-4 py-2 text-sm font-bold text-white hover:bg-slate-700 transition-all"
            >
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>

            <a
                href="{{ route('guru.absensi.form', $jadwal->jadwal_id) }}"
                class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-bold text-slate-600 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-400"
            >
                <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
            </a>
        </form>
    </div>
    {{-- Alert Periode Aktif --}}
    <div
        class="mb-4 inline-flex items-center gap-2 rounded-lg bg-blue-50 px-3 py-2 text-xs font-bold text-blue-700 dark:bg-blue-900/30 dark:text-blue-400"
    >
        <i class="fa-solid fa-calendar-check"></i>
        {{-- Konversi $bulan ke (int) --}}
        PERIODE: {{ \Carbon\Carbon::create()->month((int)$bulan)->translatedFormat('F') }} {{ $tahun }}
    </div>
    <div
        class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-950"
    >
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-slate-500 dark:bg-slate-900/40">
                <tr class="uppercase text-[10px] tracking-widest font-black">
                    <th class="px-6 py-4">No</th>
                    <th class="px-6 py-4">NIS</th>
                    <th class="px-6 py-4">Nama Siswa</th>
                    <th class="px-6 py-4 text-center">Hadir (H)</th>
                    <th class="px-6 py-4 text-center">Sakit (S)</th>
                    <th class="px-6 py-4 text-center">Izin (I)</th>
                    <th class="px-6 py-4 text-center">Alfa (A)</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse ($siswas as $index => $s)
                    @php ($data = $rekap[$s->siswa_id] ?? null)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4 text-slate-400">
                            {{ $index + 1 }}
                        </td>
                        <td class="px-6 py-4 font-mono text-xs">
                            {{ $s->nis }}
                        </td>
                        <td
                            class="px-6 py-4 font-bold text-slate-900 dark:text-white"
                        >
                            {{ $s->nama_siswa }}
                        </td>
                        <td
                            class="px-6 py-4 text-center font-bold text-green-600"
                        >
                            {{ $data->total_hadir ?? 0 }}
                        </td>
                        <td
                            class="px-6 py-4 text-center font-bold text-yellow-600"
                        >
                            {{ $data->total_sakit ?? 0 }}
                        </td>
                        <td
                            class="px-6 py-4 text-center font-bold text-blue-600"
                        >
                            {{ $data->total_izin ?? 0 }}
                        </td>
                        <td
                            class="px-6 py-4 text-center font-bold text-red-600"
                        >
                            {{ $data->total_alfa ?? 0 }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td
                            colspan="7"
                            class="px-6 py-10 text-center text-slate-500 italic"
                        >
                            Data siswa tidak ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot class="bg-slate-50 font-bold dark:bg-slate-900/40">
                <tr>
                    <td
                        colspan="3"
                        class="px-6 py-4 text-right uppercase tracking-widest text-[10px]"
                    >
                        Total Akumulasi Periode
                    </td>
                    <td class="px-6 py-4 text-center">
                        {{ $rekap->sum('total_hadir') }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        {{ $rekap->sum('total_sakit') }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        {{ $rekap->sum('total_izin') }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        {{ $rekap->sum('total_alfa') }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
    {{-- Ganti tombol cetak sebelumnya dengan ini --}}
    <div class="mt-4 flex justify-end">
        <a
            href="{{ route('guru.absensi.rekap-pdf', [$jadwal->jadwal_id, 'bulan' => $bulan, 'tahun' => $tahun]) }}"
            class="rounded-xl bg-red-600 px-6 py-2.5 text-xs font-black text-white hover:bg-red-700 uppercase tracking-widest transition-all shadow-md"
        >
            <i class="fa-solid fa-file-pdf mr-2"></i> Ekspor PDF
        </a>
    </div>
@endsection
