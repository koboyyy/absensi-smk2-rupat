@extends('layouts.app', ['title' => 'BK - Rekap S/I/A'])

@section('content')
    <div class="mb-4 flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
        <div>
            <div class="text-xl font-bold">Rekap BK (S/I/A)</div>
            <div class="text-sm text-slate-500 dark:text-slate-400">Rekap siswa khusus status Sakit/Izin/Alfa untuk evaluasi.</div>
        </div>
        <a href="{{ route('bk.rekap.export.pdf', request()->query()) }}"
           class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-950 dark:hover:bg-slate-900">
            Export PDF
        </a>
    </div>

    <form method="GET" action="{{ route('bk.rekap.index') }}"
          class="mb-4 grid grid-cols-1 gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-950 md:grid-cols-5">
        <div>
            <label class="mb-1 block text-xs text-slate-500 dark:text-slate-400">Bulan</label>
            <input type="number" min="1" max="12" name="bulan" value="{{ $bulan }}"
                   class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2 dark:border-slate-800 dark:bg-slate-950">
        </div>
        <div>
            <label class="mb-1 block text-xs text-slate-500 dark:text-slate-400">Tahun</label>
            <input type="number" min="2020" max="2100" name="tahun" value="{{ $tahun }}"
                   class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2 dark:border-slate-800 dark:bg-slate-950">
        </div>
        <div>
            <label class="mb-1 block text-xs text-slate-500 dark:text-slate-400">Jurusan (opsional)</label>
            <select name="jurusan" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2 dark:border-slate-800 dark:bg-slate-950">
                <option value="">Semua</option>
                @foreach($jurusans as $j)
                    <option value="{{ $j }}" @selected($jurusan===$j)>{{ $j }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-1 block text-xs text-slate-500 dark:text-slate-400">Kelas (opsional)</label>
            <select name="kelas_id" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2 dark:border-slate-800 dark:bg-slate-950">
                <option value="">Semua</option>
                @foreach($kelasList as $k)
                    <option value="{{ $k->kelas_id }}" @selected((string)$kelasId === (string)$k->kelas_id)>{{ $k->nama_kelas }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end">
            <button class="w-full rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Tampilkan</button>
        </div>
    </form>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-950">
            <div class="text-sm text-slate-500 dark:text-slate-400">Sakit</div>
            <div class="mt-2 text-3xl font-extrabold text-blue-700 dark:text-blue-400">{{ $summary['S'] }}</div>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-950">
            <div class="text-sm text-slate-500 dark:text-slate-400">Izin</div>
            <div class="mt-2 text-3xl font-extrabold text-blue-700 dark:text-blue-400">{{ $summary['I'] }}</div>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-950">
            <div class="text-sm text-slate-500 dark:text-slate-400">Alfa</div>
            <div class="mt-2 text-3xl font-extrabold text-blue-700 dark:text-blue-400">{{ $summary['A'] }}</div>
        </div>
    </div>

    <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-950">
        <div class="mb-2 text-lg font-bold">Rekap per Kelas</div>
        <div class="overflow-hidden rounded-xl border border-slate-200 dark:border-slate-800">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-600 dark:bg-slate-900/40 dark:text-slate-300">
                <tr>
                    <th class="px-4 py-3">Jurusan</th>
                    <th class="px-4 py-3">Kelas</th>
                    <th class="px-4 py-3">Sakit</th>
                    <th class="px-4 py-3">Izin</th>
                    <th class="px-4 py-3">Alfa</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                @foreach($perKelas as $r)
                    <tr>
                        <td class="px-4 py-3">{{ $r->jurusan }}</td>
                        <td class="px-4 py-3 font-medium">{{ $r->nama_kelas }}</td>
                        <td class="px-4 py-3">{{ $r->sakit }}</td>
                        <td class="px-4 py-3">{{ $r->izin }}</td>
                        <td class="px-4 py-3">{{ $r->alfa }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-950">
        <div class="mb-2 text-lg font-bold">Rekap per Siswa</div>
        <div class="overflow-hidden rounded-xl border border-slate-200 dark:border-slate-800">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-600 dark:bg-slate-900/40 dark:text-slate-300">
                <tr>
                    <th class="px-4 py-3">Siswa</th>
                    <th class="px-4 py-3">Kelas</th>
                    <th class="px-4 py-3">Sakit</th>
                    <th class="px-4 py-3">Izin</th>
                    <th class="px-4 py-3">Alfa</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                @foreach($perSiswa as $row)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $row->siswa?->nama_siswa }}</td>
                        <td class="px-4 py-3">{{ $row->siswa?->kelas?->nama_kelas }}</td>
                        <td class="px-4 py-3">{{ $row->sakit }}</td>
                        <td class="px-4 py-3">{{ $row->izin }}</td>
                        <td class="px-4 py-3">{{ $row->alfa }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $perSiswa->links() }}</div>
    </div>
@endsection

