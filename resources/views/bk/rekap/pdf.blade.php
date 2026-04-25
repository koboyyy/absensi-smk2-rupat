<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Rekap BK (S/I/A)</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
        h2 { margin: 0 0 8px 0; }
        .meta { margin: 0 0 10px 0; color: #444; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #ddd; padding: 6px; }
        th { background: #f3f4f6; text-align: left; }
        .cards { width: 100%; margin-top: 8px; }
        .cards td { border: 0; padding: 0; }
        .card { border: 1px solid #ddd; padding: 8px; border-radius: 6px; }
        .card-title { font-size: 10px; color: #555; }
        .card-value { font-size: 16px; font-weight: 700; }
    </style>
</head>
<body>
    <h2>Rekap BK (S/I/A) - SMKN 2 Rupat</h2>
    <div class="meta">
        Periode: {{ str_pad((string)$bulan, 2, '0', STR_PAD_LEFT) }}/{{ $tahun }}
        @if($jurusan) • Jurusan: {{ $jurusan }} @endif
        @if($kelasId) • Kelas ID: {{ $kelasId }} @endif
    </div>

    <table class="cards">
        <tr>
            <td style="width:33%; padding-right:6px;">
                <div class="card">
                    <div class="card-title">Sakit</div>
                    <div class="card-value">{{ $summary['S'] }}</div>
                </div>
            </td>
            <td style="width:33%; padding:0 6px;">
                <div class="card">
                    <div class="card-title">Izin</div>
                    <div class="card-value">{{ $summary['I'] }}</div>
                </div>
            </td>
            <td style="width:33%; padding-left:6px;">
                <div class="card">
                    <div class="card-title">Alfa</div>
                    <div class="card-value">{{ $summary['A'] }}</div>
                </div>
            </td>
        </tr>
    </table>

    <table>
        <thead>
        <tr>
            <th>Jurusan</th>
            <th>Kelas</th>
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
                <td>{{ $r->sakit }}</td>
                <td>{{ $r->izin }}</td>
                <td>{{ $r->alfa }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>

