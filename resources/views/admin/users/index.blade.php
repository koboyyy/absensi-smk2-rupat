@extends ('layouts.app', ['title' => 'Admin - Users'])

@section ('content')
    <div class="mb-4 flex items-center justify-between gap-3">
        <div>
            <div class="text-xl font-bold">Akun Pengguna</div>
            <div class="text-sm text-slate-500 dark:text-slate-400">
                Kelola akun & role.
            </div>
        </div>

        <div class="flex items-center gap-2">
            <a
                href="{{ route('admin.users.export.pdf') }}"
                class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-semibold hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-950 dark:hover:bg-slate-900"
            >
                Export PDF
            </a>
            <a
                href="{{ route('admin.users.create') }}"
                class="rounded-lg bg-blue-600 px-3 py-2 text-sm font-semibold text-white hover:bg-blue-700"
            >
                Tambah
            </a>
        </div>
    </div>
    <div class="mb-4">
        <form method="GET" action="{{ route('admin.users.index') }}">
            <div class="flex items-center gap-2">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari username, role, atau status..."
                    class="w-full rounded-lg border border-slate-300 px-4 py-2 text-sm focus:border-blue-500 focus:outline-none dark:border-slate-700 dark:bg-slate-900"
                />

                <button
                    type="submit"
                    class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700"
                >
                    Cari
                </button>

                @if (request('search'))
                    <a
                        href="{{ route('admin.users.index') }}"
                        class="rounded-lg border border-slate-300 px-4 py-2 text-sm hover:bg-slate-100 dark:border-slate-700 dark:hover:bg-slate-800"
                    >
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>
    <div
        class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-950"
    >
        <table class="w-full text-left text-sm">
            <thead
                class="bg-slate-50 text-slate-600 dark:bg-slate-900/40 dark:text-slate-300"
            >
                <tr>
                    <th class="px-4 py-3">Username</th>
                    <th class="px-4 py-3">Role</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                @foreach ($items as $item)
                    <tr>
                        <td class="px-4 py-3 font-medium">
                            {{ $item->username }}
                        </td>
                        <td class="px-4 py-3">
                            {{ Str::replace('_', ' ', $item->role) }}
                        </td>
                        <td class="px-4 py-3">
                            <span
                                class="rounded-full px-2 py-1 text-xs font-semibold {{ $item->status === 'aktif' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-700' }}"
                            >
                                {{ $item->status }}
                            </span>

                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-2">
                                <a
                                    href="{{ route('admin.users.edit', $item->id) }}"
                                    class="rounded-lg px-3 py-2 text-xs font-semibold hover:bg-slate-100 dark:hover:bg-slate-900"
                                    >Edit</a
                                >
                                <form
                                    method="POST"
                                    action="{{ route('admin.users.destroy', $item->id) }}"
                                    onsubmit="
                                        return confirm('Hapus akun ini?');
                                    "
                                >
                                    @csrf
                                    @method ('DELETE')
                                    <button
                                        class="rounded-lg bg-red-600 px-3 py-2 text-xs font-semibold text-white hover:bg-red-700"
                                    >
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $items->links() }}</div>
@endsection
