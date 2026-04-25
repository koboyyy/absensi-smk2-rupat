<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Kehadiran (Kepsek)</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; }
        h2 { margin: 0 0 8px 0; }
        .meta { margin: 0 0 10px 0; color: #444; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #ddd; padding: 5px; }
        th { background: #f3f4f6; text-align: left; }
        .cards { width: 100%; margin-top: 8px; }
        .cards td { border: 0; padding: 0; }
        .card { border: 1px solid #ddd; padding: 8px; border-radius: 6px; }
        .card-title { font-size: 10px; color: #555; }
        .card-value { font-size: 14px; font-weight: 700; }
    </style>
</head>
<body>
    <h2>Laporan Kehadiran (Global) - SMKN 2 Rupat</h2>
    <div class="meta">Periode: {{ str_pad((string)$bulan, 2, '0', STR_PAD_LEFT) }}/{{ $tahun }}</div>

    <table class="cards">
        <tr>
            <td style="width:25%; padding-right:6px;">
                <div class="card"><div class="card-title">Hadir</div><div class="card-value">{{ $summary['H'] }}</div></div>
            </td>
            <td style="width:25%; padding:0 6px;">
                <div class="card"><div class="card-title">Sakit</div><div class="card-value">{{ $summary['S'] }}</div></div>
            </td>
            <td style="width:25%; padding:0 6px;">
                <div class="card"><div class="card-title">Izin</div><div class="card-value">{{ $summary['I'] }}</div></div>
            </td>
            <td style="width:25%; padding-left:6px;">
                <div class="card"><div class="card-title">Alfa</div><div class="card-value">{{ $summary['A'] }}</div></div>
            </td>
        </tr>
    </table>

    <h3 style="margin:14px 0 6px 0;">Rekap per Jurusan</h3>
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
        @foreach($perJurusan as $r)
            <tr>
                <td>{{ $r->jurusan }}</td>
                <td>{{ $r->hadir }}</td>
                <td>{{ $r->sakit }}</td>
                <td>{{ $r->izin }}</td>
                <td>{{ $r->alfa }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <h3 style="margin:14px 0 6px 0;">Rekap per Kelas</h3>
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
        @foreach($perKelas as $r)
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
</body>
</html>

