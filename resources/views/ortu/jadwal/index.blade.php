@extends ('layouts.app', ['title' => 'Jadwal Pelajaran Anak'])

@section ('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">
            Monitoring Kehadiran Anak
        </h1>
        <p class="text-sm text-slate-500 dark:text-slate-400">Pilih mata pelajaran untuk melihat detail kehadiran.</p>
    </div>
    @if ($showSelectAnak)
        <div class="mb-8">
            <div class="mb-5">
                <h2 class="text-xl font-black text-slate-800 dark:text-white">
                    Pilih Anak
                </h2>

                <p class="text-sm text-slate-500 dark:text-slate-400">Silakan pilih anak untuk melihat jadwal dan monitoring kehadiran.</p>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($siswas as $anak)
                    <a
                        href="{{ route('ortu.jadwal.index', ['siswa_id' => $anak->siswa_id]) }}"
                        class="group overflow-hidden rounded-3xl border border-slate-200 bg-white p-5 shadow-sm transition-all hover:-translate-y-1 hover:border-blue-500 hover:shadow-xl dark:border-slate-800 dark:bg-slate-950"
                    >
                        <!-- FOTO -->
                        <div class="mb-5 flex items-center gap-4">
                            <div
                                class="h-16 w-16 overflow-hidden rounded-2xl border-2 border-slate-100 dark:border-slate-800"
                            >
                                @if ($anak->foto)
                                    <img
                                        src="{{ asset('storage/siswa/' . $anak->foto) }}"
                                        class="h-full w-full object-cover"
                                    />

                                @else
                                    <div
                                        class="flex h-full w-full items-center justify-center bg-slate-100 dark:bg-slate-900"
                                    >
                                        <i
                                            class="fa-solid fa-user-graduate text-2xl text-slate-400"
                                        ></i>
                                    </div>

                                @endif
                            </div>

                            <div>
                                <!-- NAMA -->
                                <div
                                    class="text-lg font-black text-slate-800 dark:text-white group-hover:text-blue-600 transition-colors"
                                >
                                    {{ $anak->nama_siswa }}
                                </div>

                                <!-- KELAS -->
                                <div
                                    class="mt-1 text-sm font-bold text-blue-600 dark:text-blue-400"
                                >
                                    Kelas {{ $anak->kelas?->nama_kelas }}
                                </div>
                            </div>
                        </div>

                        <!-- JURUSAN -->
                        <div
                            class="rounded-2xl bg-slate-50 dark:bg-slate-900/50 px-4 py-3"
                        >
                            <div
                                class="text-[10px] font-black uppercase tracking-widest text-slate-400"
                            >
                                Jurusan
                            </div>

                            <div
                                class="mt-1 text-sm font-bold text-slate-700 dark:text-slate-300"
                            >
                                {{ $anak->kelas?->jurusan ?? '-' }}
                            </div>
                        </div>

                        <!-- BUTTON -->
                        <div class="mt-5 flex items-center justify-between">
                            <div
                                class="text-xs font-bold uppercase tracking-wider text-slate-400"
                            >
                                Monitoring Anak
                            </div>

                            <div
                                class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-600 text-white shadow-lg shadow-blue-600/20"
                            >
                                <i class="fa-solid fa-arrow-right"></i>
                            </div>
                        </div>
                    </a>

                @endforeach
            </div>
        </div>

    @endif
    @if (!$showSelectAnak)
        {{-- Jadwal Card Grid --}}
        <div
            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-x-8 gap-y-10"
        >
            @forelse ($items as $item)
                <div
                    class="flex flex-col rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-950 transition-all hover:shadow-md"
                >
                    {{-- Header --}}
                    <div class="flex justify-between items-start mb-4">
                        <div class="max-w-[70%]">
                            <h3
                                class="text-base font-bold text-slate-900 dark:text-white leading-tight uppercase"
                            >
                                {{ $item->mapel?->nama_mapel }}
                            </h3>
                            <div
                                class="flex items-center gap-2 mt-1 text-xs text-slate-500 font-medium"
                            >
                                {{-- Icon Kelas (Font Awesome) --}}
                                <i
                                    class="fa-solid fa-users-rectangle text-blue-500"
                                ></i>
                                {{ $siswa->kelas->nama_kelas }}
                            </div>
                        </div>
                        <span
                            class="text-[10px] font-bold text-slate-400 bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded"
                        >
                            2026/2027
                        </span>
                    </div>

                    {{-- Waktu --}}
                    <div
                        class="space-y-2.5 mb-4 border-t border-slate-100 dark:border-slate-800 pt-4"
                    >
                        <div
                            class="flex items-center gap-2.5 text-xs text-slate-600 dark:text-slate-400 font-semibold"
                        >
                            {{-- Icon Jam (Font Awesome) --}}
                            <i
                                class="fa-regular fa-clock text-blue-500 text-sm"
                            ></i>
                            {{ $item->hari }}, {{ $item->jam_mulai }} - {{ $item->jam_selesai }} WIB
                        </div>
                        <div
                            class="flex items-center gap-2.5 text-xs text-slate-500 dark:text-slate-500"
                        >
                            {{-- Icon Kehadiran (Font Awesome) --}}
                            <i
                                class="fa-solid fa-clipboard-user text-slate-400 text-sm"
                            ></i>
                            Kehadiran Anak
                        </div>
                    </div>

                    {{-- Statistik Berjejer --}}
                    <div
                        class="flex items-center justify-between gap-2 border-y border-slate-100 dark:border-slate-800 py-3 mb-5 bg-slate-50/30 dark:bg-slate-900/30 rounded-lg"
                    >
                        <div class="flex-1 text-center">
                            <div
                                class="text-[9px] text-slate-400 uppercase font-black tracking-tighter"
                            >
                                Hadir
                            </div>
                            <div
                                class="text-base font-black text-slate-900 dark:text-white"
                            >
                                {{ $item->hadir_count ?? 0 }}
                            </div>
                        </div>
                        <div
                            class="flex-1 text-center border-l border-slate-200 dark:border-slate-700"
                        >
                            <div
                                class="text-[9px] text-slate-400 uppercase font-black tracking-tighter"
                            >
                                Sakit
                            </div>
                            <div
                                class="text-base font-black text-slate-900 dark:text-white"
                            >
                                {{ $item->sakit_count ?? 0 }}
                            </div>
                        </div>
                        <div
                            class="flex-1 text-center border-l border-slate-200 dark:border-slate-700"
                        >
                            <div
                                class="text-[9px] text-slate-400 uppercase font-black tracking-tighter"
                            >
                                Izin
                            </div>
                            <div
                                class="text-base font-black text-slate-900 dark:text-white"
                            >
                                {{ $item->izin_count ?? 0 }}
                            </div>
                        </div>
                        <div
                            class="flex-1 text-center border-l border-slate-200 dark:border-slate-700"
                        >
                            <div
                                class="text-[9px] text-slate-400 uppercase font-black tracking-tighter"
                            >
                                Alfa
                            </div>
                            <div class="text-base font-black text-red-500">
                                {{ $item->alfa_count ?? 0 }}
                            </div>
                        </div>
                    </div>

                    {{-- Tombol --}}
                    <a
                        href="{{ route('ortu.jadwal.show', [
    'jadwal' => $item->jadwal_id,
    'siswa_id' => $siswa->siswa_id
]) }}"
                        class="mt-auto block w-fit px-4 ml-auto text-center py-2.5 rounded-xl bg-blue-600 text-[10px] font-black text-white hover:bg-blue-700 transition-all shadow-sm hover:shadow-md tracking-widest uppercase"
                    >
                        <i class="fa-solid fa-eye mr-1"></i> Lihat Detail
                        Kehadiran
                    </a>
                </div>
            @empty
                <div class="col-span-full py-20 text-center">
                    <i
                        class="fa-solid fa-calendar-xmark text-4xl text-slate-200 mb-3"
                    ></i>
                    <p class="text-slate-500 font-medium">Belum ada jadwal pelajaran tersedia.</p>
                </div>
            @endforelse
        </div>
        {{-- Pagination --}}
        @if ($items->hasPages())
            <div class="mt-8">{{ $items->links() }}</div>
        @endif
    @endif
@endsection
