<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Pilih Role</title>

    @vite ('resources/css/app.css')
</head>

<body class="min-h-screen bg-slate-100 flex items-center justify-center p-6">
    <div class="w-full max-w-md rounded-3xl bg-white p-8 shadow-2xl">
        <!-- HEADER -->
        <div class="text-center">
            <div
                class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-3xl bg-blue-100 text-blue-600"
            >
                <i class="fa-solid fa-user-shield text-3xl"></i>
            </div>

            <h1 class="text-3xl font-black text-slate-800">Pilih Role</h1>

            <p
                class="mt-2 text-sm text-slate-500"
            >Pilih role yang ingin digunakan</p>
        </div>

        <!-- USER -->
        <div class="mt-6 rounded-2xl bg-slate-50 p-4 text-center">
            <div
                class="text-xs uppercase tracking-widest text-slate-400 font-bold"
            >
                Login Sebagai
            </div>

            <div class="mt-1 text-lg font-black text-slate-700">
                {{ $user->username }}
            </div>
        </div>

        <!-- ROLE LIST -->
        <div class="mt-6 space-y-3">
            @foreach ($user->roles as $role)
                <form method="POST" action="{{ route('role.set') }}">
                    @csrf

                    <input type="hidden" name="role" value="{{ $role }}" />

                    <button
                        class="group flex w-full items-center justify-between rounded-2xl border border-slate-200 bg-white px-5 py-4 font-bold text-slate-700 transition-all hover:border-blue-500 hover:bg-blue-50 hover:text-blue-700"
                    >
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 text-slate-600 group-hover:bg-blue-100 group-hover:text-blue-600"
                            >
                                @if ($role === 'admin')
                                    <i class="fa-solid fa-user-shield"></i>
                                @elseif ($role === 'guru')
                                    <i class="fa-solid fa-chalkboard-user"></i>
                                @elseif ($role === 'wali_kelas')
                                    <i class="fa-solid fa-school"></i>
                                @elseif ($role === 'guru_bk')
                                    <i class="fa-solid fa-user-doctor"></i>
                                @elseif ($role === 'kepala_sekolah')
                                    <i class="fa-solid fa-building-user"></i>
                                @elseif ($role === 'orang_tua')
                                    <i class="fa-solid fa-people-roof"></i>
                                @else
                                    <i class="fa-solid fa-user"></i>
                                @endif
                            </div>

                            <div class="text-left">
                                <div
                                    class="text-xs uppercase tracking-widest text-slate-400"
                                >
                                    Masuk Sebagai
                                </div>

                                <div class="text-base font-black">
                                    {{ Str::headline($role) }}
                                </div>
                            </div>
                        </div>

                        <i
                            class="fa-solid fa-chevron-right text-slate-400 group-hover:text-blue-600"
                        ></i>
                    </button>
                </form>

            @endforeach
        </div>

        <!-- LOGOUT -->
        <form method="POST" action="{{ route('logout') }}" class="mt-6">
            @csrf

            <button
                class="w-full rounded-2xl bg-red-50 py-3 text-sm font-bold text-red-600 transition-all hover:bg-red-100"
            >
                Logout
            </button>
        </form>
    </div>
</body>
</html>
