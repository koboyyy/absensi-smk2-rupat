@extends('layouts.app', ['title' => 'Surat Izin'])

@section('content')
    <div class="mb-6 flex items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Riwayat Surat Izin</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Daftar surat izin/sakit yang telah diajukan kepada guru.</p>
        </div>
        <a href="{{ route('ortu.surat-izin.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition-all hover:bg-blue-700 hover:shadow-md">
            <i class="fa-solid fa-plus text-xs"></i>
            Buat Surat
        </a>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-950">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-[11px] uppercase tracking-wider text-slate-500 dark:bg-slate-900/40 dark:text-slate-400">
                    <tr>
                        <th class="px-6 py-4 font-bold">Tanggal & Siswa</th>
                        <th class="px-6 py-4 font-bold">Mata Pelajaran</th>
                        <th class="px-6 py-4 font-bold">Status</th>
                        <th class="px-6 py-4 font-bold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach($items as $item)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900 dark:text-white">
                                    {{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') : '-' }}
                                </div>
                                <div class="text-xs text-slate-500 flex items-center gap-1 mt-0.5">
                                    <i class="fa-solid fa-circle-user text-[10px]"></i>
                                    {{ $item->siswa?->nama_siswa }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-700 dark:text-slate-300">
                                    {{ $item->jadwal?->mapel?->nama_mapel ?? 'Semua Mapel' }}
                                </div>
                                <div class="text-[11px] text-slate-400 mt-0.5">
                                    <i class="fa-regular fa-clock mr-1"></i>
                                    {{ $item->jadwal?->jam_mulai }} - {{ $item->jadwal?->jam_selesai }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColor = match($item->status) {
                                        'disetujui' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                                        'ditolak'   => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                        default     => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                    };
                                @endphp
                                <span class="{{ $statusColor }} inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider">
                                    {{ $item->status ?? 'pending' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('ortu.surat-izin.show', $item->surat_id) }}" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 transition-all hover:bg-slate-50 hover:text-blue-600 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-400 dark:hover:bg-slate-800">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </a>
                                    
                                    {{-- Tombol Hapus hanya muncul jika status masih pending --}}
                                    {{-- @if($item->status == 'pending')
                                    <form action="{{ route('ortu.surat-izin.destroy', $item->surat_id) }}" method="POST" onsubmit="return confirm('Batalkan pengajuan surat izin ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-400 transition-all hover:bg-red-50 hover:text-red-600 dark:border-slate-800 dark:bg-slate-900 dark:hover:bg-red-900/20">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </form>
                                    @endif --}}
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    @if($items->isEmpty())
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-500">
                                <i class="fa-solid fa-envelope-open text-4xl text-slate-200 mb-3 block"></i>
                                Belum ada riwayat surat izin yang diajukan.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6 flex justify-center">
        {{ $items->links() }}
    </div>
@endsection