@extends('layouts.app', ['title' => 'Dashboard'])

@section('content')
    <div class="mb-6 flex items-start justify-between">
        <div class="flex flex-col gap-1">
            <div class="text-sm text-slate-500 dark:text-slate-400">Halo,</div>
            <div class="text-2xl font-bold">
                {{ $user->username }}
                <span class="text-sm font-semibold text-blue-700 dark:text-blue-400">({{ $user->role }})</span>

                @if ($user->role === 'wali_kelas' && isset($kelas->nama_kelas))
                    <span class="ml-2 text-sm font-medium text-green-700 dark:text-green-400">
                        - Wali Kelas: {{ $kelas->nama_kelas }}
                    </span>
                @endif
            </div>
        </div>

        {{-- Icon Lonceng Khusus Guru --}}
        @if ($user->role === 'guru')
            <div class="relative">
                <a href="{{ route('guru.surat-izin.index') }}" class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-sm transition-all hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-400">
                    <i class="fa-solid fa-bell text-lg"></i>
                </a>
                
                {{-- Tanda Merah (Badge) --}}
                @if ($unreadSuratCount > 0)
                    <span class="absolute -right-1 -top-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-600 text-[10px] font-bold text-white ring-2 ring-white dark:ring-slate-950">
                        {{ $unreadSuratCount > 9 ? '9+' : $unreadSuratCount }}
                    </span>
                    {{-- Animasi Ping --}}
                    <span class="absolute -right-1 -top-1 h-4 w-4 animate-ping rounded-full bg-red-600 opacity-75"></span>
                @endif
            </div>
        @endif
    </div>

    {{-- Sisanya tetap sama seperti kode Anda sebelumnya --}}
    <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
        @foreach (['H' => 'Hadir', 'S' => 'Sakit', 'I' => 'Izin', 'A' => 'Alfa'] as $k => $label)
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                <div class="text-sm text-slate-500 dark:text-slate-400">{{ $label }}</div>
                <div class="mt-2 text-3xl font-extrabold text-blue-700 dark:text-blue-400">{{ (int) ($stats[$k] ?? 0) }}</div>
            </div>
        @endforeach
    </div>

    <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-950">
        <div class="mb-3 flex items-center justify-between">
            <div>
                <div class="text-lg font-bold">Grafik Kehadiran</div>
                <div class="text-sm text-slate-500 dark:text-slate-400">Ringkasan berdasarkan data absensi yang dapat Anda akses.</div>
            </div>
        </div>
        <div class="h-[320px]">
            <canvas id="chartKehadiran"></canvas>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
    const data = @json($stats);

    const ctx = document.getElementById('chartKehadiran');
    new Chart(ctx, {
        type: 'bar', // Mengubah tipe menjadi bar
        data: {
            labels: ['Hadir', 'Sakit', 'Izin', 'Alfa'],
            datasets: [{
                label: 'Jumlah Absensi', // Menambahkan label untuk tooltip
                data: [
                    data.H || 0, 
                    data.S || 0, 
                    data.I || 0, 
                    data.A || 0
                ],
                backgroundColor: [
                    '#2563eb', // Biru
                    '#22c55e', // Hijau
                    '#f59e0b', // Kuning/Oranye
                    '#ef4444'  // Merah
                ],
                borderRadius: 8, // Membuat ujung batang sedikit melengkung agar modern
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false // Sembunyikan legenda karena label sudah ada di sumbu X
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0 // Memastikan angka di sumbu Y bulat
                    },
                    grid: {
                        display: true,
                        drawBorder: false,
                        color: 'rgba(156, 163, 175, 0.1)' // Warna garis tipis
                    }
                },
                x: {
                    grid: {
                        display: false // Hilangkan garis vertikal agar lebih bersih
                    }
                }
            }
        }
    });
</script>
@endsection
