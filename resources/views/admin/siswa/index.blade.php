@extends('layouts.app', ['title' => 'Admin - Siswa'])

@section('content')
    <div class="mb-4 flex items-center justify-between gap-3">
        <div>
            <div class="text-xl font-bold">Data Siswa</div>
            <div class="text-sm text-slate-500 dark:text-slate-400">Kelola data siswa.</div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.siswa.export.pdf') }}" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-semibold hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-950 dark:hover:bg-slate-900">
                Export PDF
            </a>
            <a href="{{ route('admin.siswa.create') }}" class="rounded-lg bg-blue-600 px-3 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                Tambah
            </a>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-950">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-600 dark:bg-slate-900/40 dark:text-slate-300">
                <tr>
                    <th class="px-4 py-3">No</th>
                    <th class="px-4 py-3">Nis</th>
                    <th class="px-4 py-3">Nama Lengkap</th>
                    <th class="px-4 py-3">JK</th>
                    <th class="px-4 py-3">Kelas</th>
                    <th class="px-4 py-3">Jurusan</th>
                    <th class="px-4 py-3">Nama Ortu</th>
                    <th class="px-4 py-3">Alamat</th>
                    <th class="px-4 py-3">Foto</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                @foreach($items as $index => $item)
                    <tr>
                        <td class="px-4 py-3">{{ $items->firstItem() + $index }}</td>
                        <td class="px-4 py-3 font-mono">{{ $item->nis }}</td>
                        <td class="px-4 py-3 font-medium">{{ $item->nama_siswa }}</td>
                        <td class="px-4 py-3">{{ $item->jenis_kelamin }}</td> {{-- Sesuaikan field JK --}}
                        <td class="px-4 py-3">{{ $item->kelas?->nama_kelas }}</td>
                        <td class="px-4 py-3">
    {{ $item->kelas?->jurusan ?? '-' }}
</td> {{-- Sesuaikan relasi jurusan --}}
                        <td class="px-4 py-3">{{ $item->orangTua?->nama_ortu }}</td>
                        <td class="px-4 py-3 truncate max-w-[150px]">{{ $item->alamat }}</td> {{-- Sesuaikan field alamat --}}
                        <td class="px-4 py-3">
                            @if($item->foto)
                                <img src="{{ asset('storage/siswa/' . $item->foto) }}" class="h-10 w-10 rounded-full object-cover" alt="Foto">
                            @else
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 dark:bg-slate-800">
                                    <span class="text-[10px] text-slate-400">No Pic</span>
                                </div>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.siswa.edit', $item->siswa_id) }}" class="rounded-lg px-2 py-1 text-xs font-semibold hover:bg-slate-100 dark:hover:bg-slate-900" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('admin.siswa.destroy', $item->siswa_id) }}" onsubmit="return confirm('Hapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="rounded-lg px-2 py-1 text-xs font-semibold text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20" title="Hapus">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m4-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $items->links() }}</div>
@endsection