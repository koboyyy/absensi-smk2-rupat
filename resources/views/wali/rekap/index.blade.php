@extends ('layouts.app', ['title' => 'Wali Kelas - Rekap Bulanan'])

@section ('content')
    <!-- Header Section (Sama seperti sebelumnya) -->
    <div
        class="mb-8 flex flex-col gap-6 md:flex-row md:items-center md:justify-between"
    >
        <div class="flex flex-col gap-1">
            <div
                class="flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-blue-600 dark:text-blue-400 mb-1"
            >
                <i class="fa-solid fa-chart-line"></i>
                Laporan Akademik
            </div>
            <div
                class="text-3xl font-black text-slate-800 dark:text-white uppercase tracking-tight"
            >
                Rekap Bulanan
            </div>
            <div class="flex items-center gap-2">
                <div
                    class="inline-flex items-center gap-1.5 rounded-lg bg-blue-50 px-2.5 py-1 text-[10px] font-black uppercase text-blue-700 dark:bg-blue-900/30 dark:text-blue-300"
                >
                    <i class="fa-solid fa-users-rectangle"></i>
                    Kelas: {{ $kelas->nama_kelas ?? 'Tidak Terdeteksi' }}
                </div>
                <span class="text-sm text-slate-500 dark:text-slate-400 italic"
                    >Periode: {{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }} {{ $tahun }}</span
                >
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <form
                method="GET"
                action="{{ route('wali.rekap.index') }}"
                class="flex items-end gap-2 bg-white dark:bg-slate-950 p-3 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm"
            >
                <div class="flex gap-2">
                    <div class="flex flex-col">
                        <label
                            class="mb-1 text-[10px] font-black uppercase text-slate-400 ml-1"
                            >Bulan</label
                        >
                        <select
                            name="bulan"
                            class="w-28 rounded-xl border-none bg-slate-100 px-3 py-2 text-xs font-bold text-slate-700 focus:ring-2 focus:ring-blue-500 dark:bg-white/5 dark:text-slate-200"
                        >
                            @foreach (range(1, 12) as $m)
                                <option
                                    value="{{ $m }}"
                                    {{ $bulan == $m ? 'selected' : '' }}
                                >
                                    {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex flex-col">
                        <label
                            class="mb-1 text-[10px] font-black uppercase text-slate-400 ml-1"
                            >Tahun</label
                        >
                        <input
                            type="number"
                            min="2020"
                            max="2100"
                            name="tahun"
                            value="{{ $tahun }}"
                            class="w-24 rounded-xl border-none bg-slate-100 px-3 py-2 text-xs font-bold text-slate-700 focus:ring-2 focus:ring-blue-500 dark:bg-white/5 dark:text-slate-200"
                        />
                    </div>
                </div>
                <button
                    class="h-9 w-9 flex items-center justify-center rounded-xl bg-slate-800 text-white hover:bg-blue-600 transition-all shadow-lg active:scale-95"
                >
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </button>
            </form>

            <a
                href="{{ route('wali.rekap.pdf', ['bulan' => $bulan, 'tahun' => $tahun]) }}"
                target="_blank"
                class="group flex items-center gap-2 rounded-2xl bg-emerald-600 px-6 py-3 text-sm font-black uppercase tracking-widest text-white shadow-lg shadow-emerald-600/20 transition-all hover:bg-emerald-700 hover:-translate-y-1 active:scale-95"
            >
                <i
                    class="fa-solid fa-file-pdf transition-transform group-hover:scale-110"
                ></i>
                Cetak PDF
            </a>
        </div>
    </div>
    <!-- Stats Cards (Sama seperti sebelumnya) -->
    <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
        <div
            class="group relative overflow-hidden rounded-3xl border border-slate-200 bg-white p-5 shadow-sm transition-all hover:border-blue-500 dark:border-slate-800 dark:bg-slate-950"
        >
            <div class="relative z-10">
                <div
                    class="text-[10px] font-black uppercase tracking-widest text-slate-400"
                >
                    Total Hadir
                </div>
                <div
                    class="mt-2 text-4xl font-black text-blue-600 dark:text-blue-400 leading-none"
                >
                    {{ $rekap['hadir'] }}
                </div>
            </div>
            <div
                class="absolute -right-2 -bottom-2 opacity-5 transition-transform group-hover:scale-110 group-hover:opacity-10"
            >
                <i class="fa-solid fa-user-check text-6xl text-blue-600"></i>
            </div>
        </div>

        <div
            class="group relative overflow-hidden rounded-3xl border border-slate-200 bg-white p-5 shadow-sm transition-all hover:border-emerald-500 dark:border-slate-800 dark:bg-slate-950"
        >
            <div class="relative z-10">
                <div
                    class="text-[10px] font-black uppercase tracking-widest text-slate-400"
                >
                    Total Sakit
                </div>
                <div
                    class="mt-2 text-4xl font-black text-emerald-600 dark:text-emerald-400 leading-none"
                >
                    {{ $rekap['sakit'] }}
                </div>
            </div>
            <div
                class="absolute -right-2 -bottom-2 opacity-5 transition-transform group-hover:scale-110 group-hover:opacity-10"
            >
                <i
                    class="fa-solid fa-face-frown-open text-6xl text-emerald-600"
                ></i>
            </div>
        </div>

        <div
            class="group relative overflow-hidden rounded-3xl border border-slate-200 bg-white p-5 shadow-sm transition-all hover:border-amber-500 dark:border-slate-800 dark:bg-slate-950"
        >
            <div class="relative z-10">
                <div
                    class="text-[10px] font-black uppercase tracking-widest text-slate-400"
                >
                    Total Izin
                </div>
                <div
                    class="mt-2 text-4xl font-black text-amber-500 dark:text-amber-400 leading-none"
                >
                    {{ $rekap['izin'] }}
                </div>
            </div>
            <div
                class="absolute -right-2 -bottom-2 opacity-5 transition-transform group-hover:scale-110 group-hover:opacity-10"
            >
                <i
                    class="fa-solid fa-envelope-open-text text-6xl text-amber-500"
                ></i>
            </div>
        </div>

        <div
            class="group relative overflow-hidden rounded-3xl border border-slate-200 bg-white p-5 shadow-sm transition-all hover:border-rose-500 dark:border-slate-800 dark:bg-slate-950"
        >
            <div class="relative z-10">
                <div
                    class="text-[10px] font-black uppercase tracking-widest text-slate-400"
                >
                    Total Alfa
                </div>
                <div
                    class="mt-2 text-4xl font-black text-rose-600 dark:text-rose-400 leading-none"
                >
                    {{ $rekap['alfa'] }}
                </div>
            </div>
            <div
                class="absolute -right-2 -bottom-2 opacity-5 transition-transform group-hover:scale-110 group-hover:opacity-10"
            >
                <i class="fa-solid fa-user-xmark text-6xl text-rose-600"></i>
            </div>
        </div>
    </div>
    <!-- BAGIAN: Daftar Siswa Binaan & Rekap Individu -->
    <div class="mt-8 flex flex-col gap-4">
        <div class="flex items-center justify-between px-2">
            <h3
                class="text-lg font-black text-slate-800 dark:text-white uppercase tracking-tight"
            >
                Rekapan Siswa Individu
            </h3>
            <span
                class="text-xs font-bold text-slate-400 uppercase tracking-widest"
                >Periode: {{ $bulan }}/{{ $tahun }}</span
            >
        </div>

        <div
            class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-950"
        >
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead
                        class="bg-slate-50/50 text-slate-400 dark:bg-slate-900/40 uppercase text-[10px] tracking-[0.15em] font-black"
                    >
                        <tr>
                            <th class="px-8 py-5">Identitas Siswa</th>
                            <th class="px-4 py-5 text-center">Hadir</th>
                            <th class="px-4 py-5 text-center">Sakit</th>
                            <th class="px-4 py-5 text-center">Izin</th>
                            <th class="px-4 py-5 text-center">Alfa</th>
                            <th class="px-8 py-5 text-right font-black">
                                Persentase
                            </th>
                        </tr>
                    </thead>
                    <tbody
                        class="divide-y divide-slate-100 dark:divide-slate-800"
                    >
                        @forelse ($kelas->siswas as $siswa)
                            @php
                                // Ambil data dari variabel totalPerSiswa yang dikirim controller
                                $s_hadir = $totalPerSiswa[$siswa->siswa_id]['H'] ?? 0;
                                $s_sakit = $totalPerSiswa[$siswa->siswa_id]['S'] ?? 0;
                                $s_izin  = $totalPerSiswa[$siswa->siswa_id]['I'] ?? 0;
                                $s_alfa  = $totalPerSiswa[$siswa->siswa_id]['A'] ?? 0;
                                
                                $total_hari = $s_hadir + $s_sakit + $s_izin + $s_alfa;
                                $persen = $total_hari > 0 ? round(($s_hadir / $total_hari) * 100) : 0;
                            @endphp
                            <tr
                                class="hover:bg-slate-50/50 dark:hover:bg-slate-900/50 transition-colors group"
                            >
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="h-10 w-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-black text-xs shadow-lg shadow-blue-600/20"
                                        >
                                            {{ substr($siswa->nama_siswa, 0, 1) }}
                                        </div>
                                        <div>
                                            <div
                                                class="font-extrabold text-slate-800 dark:text-white leading-tight uppercase tracking-tight"
                                            >
                                                {{ $siswa->nama_siswa }}
                                            </div>
                                            <div
                                                class="text-[10px] font-bold text-slate-400 mt-0.5 uppercase"
                                            >
                                                NIS: {{ $siswa->nis }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Statistik Individu -->
                                <td
                                    class="px-4 py-5 text-center font-black text-blue-600 dark:text-blue-400"
                                >
                                    {{ $s_hadir }}
                                </td>
                                <td
                                    class="px-4 py-5 text-center font-black text-emerald-500"
                                >
                                    {{ $s_sakit }}
                                </td>
                                <td
                                    class="px-4 py-5 text-center font-black text-amber-500"
                                >
                                    {{ $s_izin }}
                                </td>
                                <td
                                    class="px-4 py-5 text-center font-black text-rose-500"
                                >
                                    {{ $s_alfa }}
                                </td>

                                <!-- Progress Kehadiran -->
                                <td class="px-8 py-5 text-right">
                                    <div class="flex flex-col items-end gap-1">
                                        <span
                                            class="text-xs font-black {{ $persen < 75 ? 'text-rose-500' : 'text-slate-700 dark:text-slate-300' }}"
                                        >
                                            {{ $persen }}%
                                        </span>
                                        <div
                                            class="w-20 h-1.5 bg-slate-100 dark:bg-white/5 rounded-full overflow-hidden"
                                        >
                                            <div
                                                class="h-full {{ $persen < 75 ? 'bg-rose-500' : 'bg-blue-600' }}"
                                                style="width: {{ $persen }}%"
                                            ></div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-8 py-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <i
                                            class="fa-solid fa-users-slash text-4xl text-slate-200 dark:text-slate-800 mb-3"
                                        ></i>
                                        <span
                                            class="text-sm font-bold text-slate-400 uppercase tracking-widest"
                                            >Tidak ada data siswa</span
                                        >
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Info Footer -->
    <div
        class="mt-8 flex items-center gap-4 rounded-3xl border border-slate-200 bg-slate-50 p-6 dark:border-slate-800 dark:bg-white/5"
    >
        <div
            class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-white text-blue-600 shadow-sm dark:bg-slate-900"
        >
            <i class="fa-solid fa-circle-info text-xl"></i>
        </div>
        <div class="text-sm leading-relaxed text-slate-500 dark:text-slate-400">
            Data rekapitulasi di atas dihitung secara otomatis berdasarkan
            akumulasi absensi harian kelas
            <span
                class="font-bold text-slate-800 dark:text-slate-200"
                >{{ $kelas->nama_kelas ?? 'Binaan' }}</span
            >
            pada periode yang dipilih. Rekap ini secara berkala disimpan ke
            dalam tabel
            <code
                class="rounded bg-blue-100 px-1 py-0.5 font-mono text-blue-700 dark:bg-blue-900/40 dark:text-blue-300 text-xs"
                >rekap_kelas</code
            >.
        </div>
    </div>
@endsection
