<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Data Kelas - SMKN 2 Rupat</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; line-height: 1.4; color: #333; }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 6px; }
        th { background: #f3f4f6; text-align: left; }

        /* === KOP SURAT === */
        .header-container {
            width: 100%;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header-inner {
            display: table;
            width: 100%;
        }
        .logo-cell {
            display: table-cell;
            width: 130px;
            vertical-align: middle;
            text-align: center;
        }
        .logo-cell img {
            width: 100px;
            height: auto;
        }
        .text-cell {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
        }
        .text-cell h2 { margin: 0; font-size: 14px; text-transform: uppercase; }
        .text-cell h1 { margin: 0; font-size: 18px; text-transform: uppercase; }
        .text-cell p  { margin: 2px 0; font-size: 10px; }
        .spacer-cell {
            display: table-cell;
            width: 110px;
        }
    </style>
</head>
<body>

    {{-- KOP SURAT --}}
    <div class="header-container">
        <div class="header-inner">
            <div class="logo-cell">
                @php
                    $path = public_path('images/logo-smkn-2-rupat.png');
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $data = file_get_contents($path);
                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                @endphp
                <img src="{{ $base64 }}" alt="Logo">
            </div>
            <div class="text-cell">
                <h2>PEMERINTAH PROVINSI RIAU</h2>
                <p>DINAS PENDIDIKAN</p>
                <h1>SMK NEGERI 2 RUPAT</h1>
                <p>Alamat: Jl. Pangkalan Pinang, Kec. Rupat, Kab. Bengkalis</p>
                <p><strong>DATA KELAS</strong></p>
                <p>TAHUN PELAJARAN 2025/2026</p>
            </div>
            <div class="spacer-cell"></div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Tingkat</th>
                <th>Jurusan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
                <tr>
                    <td>{{ $item->nama_kelas }}</td>
                    <td>{{ $item->tingkat }}</td>
                    <td>{{ $item->jurusan }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>