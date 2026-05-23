@extends ('layouts.app', ['title' => 'Kepala Sekolah - Laporan'])

@section ('content')
    <div
        class="mb-4 flex flex-col gap-3 md:flex-row md:items-end md:justify-between"
    >
        <div>
            <div class="text-xl font-bold">Laporan Kehadiran (Global)</div>
            <div class="text-sm text-slate-500 dark:text-slate-400">
                Pantau rekap keseluruhan per jurusan & kelas.
            </div>
        </div>
        <a
            href="{{ route('kepsek.laporan.export.pdf', request()->query()) }}"
            class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-950 dark:hover:bg-slate-900"
        >
            Export PDF
        </a>
    </div>
    <form
        method="GET"
        action="{{ route('kepsek.laporan.index') }}"
        class="mb-4 grid grid-cols-1 gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-950 md:grid-cols-3"
    >
        <div>
            <label class="mb-1 block text-xs text-slate-500 dark:text-slate-400"
                >Bulan</label
            >
            <input
                type="number"
                min="1"
                max="12"
                name="bulan"
                value="{{ $bulan }}"
                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2 dark:border-slate-800 dark:bg-slate-950"
            />
        </div>
        <div>
            <label class="mb-1 block text-xs text-slate-500 dark:text-slate-400"
                >Tahun</label
            >
            <input
                type="number"
                min="2020"
                max="2100"
                name="tahun"
                value="{{ $tahun }}"
                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2 dark:border-slate-800 dark:bg-slate-950"
            />
        </div>
        <div class="flex items-end">
            <button
                class="w-full rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700"
            >
                Tampilkan
            </button>
        </div>
    </form>
    <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
        @foreach (['H' => 'Hadir', 'S' => 'Sakit', 'I' => 'Izin', 'A' => 'Alfa'] as $k => $label)
            <div
                class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-950"
            >
                <div class="text-sm text-slate-500 dark:text-slate-400">
                    {{ $label }}
                </div>
                <div
                    class="mt-2 text-3xl font-extrabold text-blue-700 dark:text-blue-400"
                >
                    {{ $summary[$k] }}
                </div>
            </div>
        @endforeach
    </div>
    <div
        class="mt-6 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-950"
    >
        <div class="mb-2 text-lg font-bold">Grafik per Jurusan</div>
        <div class="h-[340px]">
            <canvas id="chartJurusan"></canvas>
        </div>
    </div>
    <div
        class="mt-6 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-950"
    >
        <div class="mb-2 text-lg font-bold">Tabel per Jurusan</div>
        <div
            class="overflow-hidden rounded-xl border border-slate-200 dark:border-slate-800"
        >
            <table class="w-full text-left text-sm">
                <thead
                    class="bg-slate-50 text-slate-600 dark:bg-slate-900/40 dark:text-slate-300"
                >
                    <tr>
                        <th class="px-4 py-3">Jurusan</th>
                        <th class="px-4 py-3">Hadir</th>
                        <th class="px-4 py-3">Sakit</th>
                        <th class="px-4 py-3">Izin</th>
                        <th class="px-4 py-3">Alfa</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                    @foreach ($perJurusan as $r)
                        <tr>
                            <td class="px-4 py-3 font-medium">
                                {{ $r->jurusan }}
                            </td>
                            <td class="px-4 py-3">{{ $r->hadir }}</td>
                            <td class="px-4 py-3">{{ $r->sakit }}</td>
                            <td class="px-4 py-3">{{ $r->izin }}</td>
                            <td class="px-4 py-3">{{ $r->alfa }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div
        class="mt-6 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-950"
    >
        <div class="mb-2 text-lg font-bold">Tabel per Kelas</div>
        <div
            class="overflow-hidden rounded-xl border border-slate-200 dark:border-slate-800"
        >
            <table class="w-full text-left text-sm">
                <thead
                    class="bg-slate-50 text-slate-600 dark:bg-slate-900/40 dark:text-slate-300"
                >
                    <tr>
                        <th class="px-4 py-3">Jurusan</th>
                        <th class="px-4 py-3">Kelas</th>
                        <th class="px-4 py-3">Hadir</th>
                        <th class="px-4 py-3">Sakit</th>
                        <th class="px-4 py-3">Izin</th>
                        <th class="px-4 py-3">Alfa</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                    @foreach ($perKelas as $r)
                        <tr>
                            <td class="px-4 py-3">{{ $r->jurusan }}</td>
                            <td class="px-4 py-3 font-medium">
                                {{ $r->nama_kelas }}
                            </td>
                            <td class="px-4 py-3">{{ $r->hadir }}</td>
                            <td class="px-4 py-3">{{ $r->sakit }}</td>
                            <td class="px-4 py-3">{{ $r->izin }}</td>
                            <td class="px-4 py-3">{{ $r->alfa }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <script>
        const perJurusan = @json ($perJurusan);
        const labels = perJurusan.map((r) => r.jurusan);
        const hadir = perJurusan.map((r) => Number(r.hadir || 0));
        const sakit = perJurusan.map((r) => Number(r.sakit || 0));
        const izin = perJurusan.map((r) => Number(r.izin || 0));
        const alfa = perJurusan.map((r) => Number(r.alfa || 0));

        new Chart(document.getElementById("chartJurusan"), {
            type: "bar",
            data: {
                labels,
                datasets: [
                    { label: "Hadir", data: hadir, backgroundColor: "#2563eb" },
                    { label: "Sakit", data: sakit, backgroundColor: "#22c55e" },
                    { label: "Izin", data: izin, backgroundColor: "#f59e0b" },
                    { label: "Alfa", data: alfa, backgroundColor: "#ef4444" },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: "bottom" } },
                scales: { x: { stacked: false }, y: { beginAtZero: true } },
            },
        });
    </script>
@endsection
