<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <title>Laporan Kehadiran (Kepsek)</title>
    <style>
        body {
            font-family:
                DejaVu Sans,
                sans-serif;
            font-size: 10px;
            color: #333;
        }

        /* Container Kop Surat */
        .header-container {
            width: 100%;
            margin-bottom: 20px;
            border-bottom: 3px double #000; /* Garis khas kop surat */
            padding-bottom: 10px;
        }
        .logo-container {
            float: left;
            width: 110px;
        }
        .logo-container img {
            width: 100%;
            height: auto;
        }
        .header-text {
            text-align: center;
            margin-right: 70px; /* Menyeimbangkan posisi karena float logo */
        }
        .header-text h2 {
            margin: 0;
            font-size: 14px;
            text-transform: uppercase;
        }
        .header-text h1 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }
        .header-text p {
            margin: 2px 0;
            font-size: 10px;
        }

        /* Clearfix */
        .header-container::after {
            content: "";
            clear: both;
            display: table;
        }

        h3 {
            margin: 14px 0 6px 0;
            font-size: 12px;
            border-left: 3px solid #2563eb;
            padding-left: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        th,
        td {
            border: 1px solid #ddd;
            padding: 6px;
        }
        th {
            background: #f3f4f6;
            text-align: left;
            font-weight: bold;
        }

        /* Stats Cards */
        .cards {
            width: 100%;
            margin-top: 10px;
            border: none;
        }
        .cards td {
            border: 0;
            padding: 0;
        }
        .card {
            border: 1px solid #e5e7eb;
            padding: 10px;
            border-radius: 8px;
            background: #f9fafb;
        }
        .card-title {
            font-size: 10px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .card-value {
            font-size: 16px;
            font-weight: 700;
            color: #111827;
        }
    </style>
</head>
<body>
    <div class="header-container">
        <div class="logo-container">
            @php
                // Mengambil logo SMKN 2 Rupat
                $path = public_path('images/logo-smkn-2-rupat.png'); 
                $base64 = '';
                if (file_exists($path)) {
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $data = file_get_contents($path);
                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                }
            @endphp
            @if ($base64)
                <img src="{{ $base64 }}" alt="Logo" />
            @endif
        </div>
        <div class="header-text">
            <h2>PEMERINTAH PROVINSI RIAU</h2>
            <p>DINAS PENDIDIKAN</p>
            <h1>SMK NEGERI 2 RUPAT</h1>
            <p>Jl. Pangkalan Pinang, Kec. Rupat, Kab. Bengkalis, Riau</p>
            <p><strong>LAPORAN KEHADIRAN SISWA (GLOBAL)</strong></p>
            <p>Periode: {{ str_pad((string)$bulan, 2, '0', STR_PAD_LEFT) }} / {{ $tahun }}</p>
        </div>
    </div>

    <table class="cards">
        <tr>
            <td style="width: 25%; padding-right: 8px">
                <div class="card">
                    <div class="card-title">Total Hadir</div>
                    <div class="card-value">{{ $summary['H'] }}</div>
                </div>
            </td>
            <td style="width: 25%; padding: 0 4px">
                <div class="card">
                    <div class="card-title">Total Sakit</div>
                    <div class="card-value" style="color: #ca8a04">
                        {{ $summary['S'] }}
                    </div>
                </div>
            </td>
            <td style="width: 25%; padding: 0 4px">
                <div class="card">
                    <div class="card-title">Total Izin</div>
                    <div class="card-value" style="color: #2563eb">
                        {{ $summary['I'] }}
                    </div>
                </div>
            </td>
            <td style="width: 25%; padding-left: 8px">
                <div class="card">
                    <div class="card-title">Total Alfa</div>
                    <div class="card-value" style="color: #dc2626">
                        {{ $summary['A'] }}
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <h3>Rekap per Jurusan</h3>
    <table>
        <thead>
            <tr>
                <th>Jurusan</th>
                <th>Hadir</th>
                <th>Sakit</th>
                <th>Izin</th>
                <th>Alfa</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($perJurusan as $r)
                <tr>
                    <td style="font-weight: 500">{{ $r->jurusan }}</td>
                    <td>{{ $r->hadir }}</td>
                    <td>{{ $r->sakit }}</td>
                    <td>{{ $r->izin }}</td>
                    <td>{{ $r->alfa }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Rekap per Kelas</h3>
    <table>
        <thead>
            <tr>
                <th>Jurusan</th>
                <th>Kelas</th>
                <th>Hadir</th>
                <th>Sakit</th>
                <th>Izin</th>
                <th>Alfa</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($perKelas as $r)
                <tr>
                    <td>{{ $r->jurusan }}</td>
                    <td>{{ $r->nama_kelas }}</td>
                    <td>{{ $r->hadir }}</td>
                    <td>{{ $r->sakit }}</td>
                    <td>{{ $r->izin }}</td>
                    <td>{{ $r->alfa }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 30px; text-align: right; font-size: 10px">
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }}</p>
    </div>
</body>
</html>
