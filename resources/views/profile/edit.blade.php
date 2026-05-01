@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Pengaturan Akun</h1>
        <p class="text-slate-500 dark:text-slate-400">Kelola kredensial login dan keamanan akun Anda.</p>
    </div>

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
        <!-- Sidebar Info -->
        <div class="space-y-4">
            <div class="p-6 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm text-center">
                <div class="relative mx-auto w-24 h-24 mb-4">
                    <div class="w-full h-full rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center border-4 border-white dark:border-slate-800 shadow-md">
                        <i class="fas fa-user-shield text-3xl text-blue-600 dark:text-blue-400"></i>
                    </div>
                </div>
                <!-- Menampilkan Nama dari relasi Guru jika ada, jika tidak tampilkan Username -->
                <h2 class="font-bold text-slate-800 dark:text-white">
                    {{ $user->guru ? $user->guru->nama : $user->username }}
                </h2>
                <p class="text-[10px] font-bold px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300 inline-block mt-1">
                    {{ Str::replace('_', ' ', strtoupper($user->role)) }}
                </p>
                <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-800">
                    <p class="text-xs text-slate-500 italic">Status Akun: 
                        <span class="text-emerald-600 dark:text-emerald-400 font-semibold">{{ ucfirst($user->status) }}</span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Form Edit -->
        <div class="lg:col-span-2">
            <form action="{{ route('profile.update') }}" method="POST" class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                @csrf
                @method('PATCH')

                <div class="p-6 border-b border-slate-100 dark:border-slate-800">
                    <h3 class="text-lg font-semibold text-slate-800 dark:text-white">Detail Login</h3>
                </div>

                <div class="p-6 space-y-4">
                    <!-- Username -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Username</label>
                        <input type="text" name="username" value="{{ old('username', $user->username) }}" required
                               class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-2.5 text-sm transition focus:border-blue-500 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                        @error('username') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="p-6 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50">
                    <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-4">Keamanan</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Password Baru</label>
                            <input type="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah"
                                   class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-2.5 text-sm transition focus:border-blue-500 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                            @error('password') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation"
                                   class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-2.5 text-sm transition focus:border-blue-500 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/50 flex items-center justify-end">
                    <button type="submit" class="flex items-center gap-2 rounded-xl bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md transition hover:bg-blue-700">
                        <i class="fas fa-check-circle"></i>
                        Update Profil
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection