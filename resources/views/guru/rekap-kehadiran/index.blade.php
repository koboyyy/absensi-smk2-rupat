@extends ('layouts.app', ['title' => 'Guru - Rekap Kehadiran'])

@section ('content')
    <div
        class="mb-6 flex flex-col gap-3 md:flex-row md:items-end md:justify-between"
    >
        <div>
            <div class="text-2xl font-bold text-slate-800 dark:text-white">
                Rekap Kehadiran
            </div>
            <div class="text-sm text-slate-500 dark:text-slate-400">
                Menampilkan data tanggal:
                <strong
                    >{{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }}</strong
                >
            </div>
        </div>

        <form
            action="{{ route('guru.rekap-kehadiran.index') }}"
            method="GET"
            class="flex items-center gap-2"
        >
            <div class="relative">
                <input
                    type="date"
                    name="tanggal"
                    value="{{ $tanggal }}"
                    class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium focus:border-blue-500 focus:ring-blue-500 dark:border-slate-800 dark:bg-slate-950 dark:text-white"
                />
            </div>
            <button
                type="submit"
                class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 transition-colors"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                Tampilkan
            </button>
        </form>
    </div>
    <div
        class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-950"
    >
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead
                    class="bg-slate-50 text-slate-600 dark:bg-slate-900/40 dark:text-slate-300"
                >
                    <tr class="border-b border-slate-200 dark:border-slate-800">
                        <th class="px-4 py-4 font-bold text-center w-12">NO</th>
                        <th class="px-4 py-4 font-bold">NIS</th>
                        <th class="px-4 py-4 font-bold">Nama</th>
                        <th class="px-4 py-4 font-bold text-center">
                            Hadir (H)
                        </th>
                        <th class="px-4 py-4 font-bold text-center">
                            Sakit (S)
                        </th>
                        <th class="px-4 py-4 font-bold text-center">
                            Izin (I)
                        </th>
                        <th class="px-4 py-4 font-bold text-center">
                            Alfa (A)
                        </th>
                    </tr>
                </thead>
                @foreach ($items as $index => $item)
                    <tr
                        class="hover:bg-slate-50/50 dark:hover:bg-slate-900/50 transition-colors"
                    >
                        <td class="px-4 py-3 text-center">
                            {{ $items->firstItem() + $index }}
                        </td>

                        <td
                            class="px-4 py-3 font-medium text-slate-700 dark:text-slate-200"
                        >
                            {{ $item->nis }}
                        </td>

                        <td
                            class="px-4 py-3 text-slate-700 dark:text-slate-200"
                        >
                            {{ $item->nama_siswa }}
                        </td>

                        <td
                            class="px-4 py-3 text-center font-semibold text-green-600"
                        >
                            {{ $item->total_hadir ?? 0 }}
                        </td>
                        <td
                            class="px-4 py-3 text-center font-semibold text-yellow-600"
                        >
                            {{ $item->total_sakit ?? 0 }}
                        </td>
                        <td
                            class="px-4 py-3 text-center font-semibold text-blue-600"
                        >
                            {{ $item->total_izin ?? 0 }}
                        </td>
                        <td
                            class="px-4 py-3 text-center font-semibold text-red-600"
                        >
                            {{ $item->total_alfa ?? 0 }}
                        </td>
                    </tr>
                @endforeach
                <tfoot class="bg-slate-50 font-bold dark:bg-slate-900/40">
                    <tr>
                        <td
                            colspan="3"
                            class="px-4 py-3 text-right uppercase tracking-wider"
                        >
                            Total
                        </td>
                        <td class="px-4 py-3 text-center text-green-600">
                            {{ $items->sum('total_hadir') }}
                        </td>
                        <td class="px-4 py-3 text-center text-yellow-600">
                            {{ $items->sum('total_sakit') }}
                        </td>
                        <td class="px-4 py-3 text-center text-blue-600">
                            {{ $items->sum('total_izin') }}
                        </td>
                        <td class="px-4 py-3 text-center text-red-600">
                            {{ $items->sum('total_alfa') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="mt-6 flex justify-end">
        <a
            href="{{ route('guru.rekap-kehadiran.pdf', ['tanggal' => $tanggal]) }}"
            target="_blank"
            class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-sm font-bold text-white shadow-md hover:bg-emerald-700 transition-all w-fit"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>

            <span>Cetak PDF</span>
        </a>
    </div>
@endsection
