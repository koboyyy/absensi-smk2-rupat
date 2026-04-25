@extends('layouts.app', ['title' => 'Dashboard'])

@section('content')
    <div class="mb-6 flex flex-col gap-1">
        <div class="text-sm text-slate-500 dark:text-slate-400">Halo,</div>
        <div class="text-2xl font-bold">
            {{ $user->username }}
            <span class="text-sm font-semibold text-blue-700 dark:text-blue-400">({{ $user->role }})</span>
        </div>
    </div>

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
    <script>
        const data = @json($stats);

        const ctx = document.getElementById('chartKehadiran');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Hadir', 'Sakit', 'Izin', 'Alfa'],
                datasets: [{
                    data: [data.H, data.S, data.I, data.A],
                    backgroundColor: ['#2563eb', '#22c55e', '#f59e0b', '#ef4444'],
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    </script>
@endsection
