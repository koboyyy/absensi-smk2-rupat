<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Data Mapel</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h2 { margin: 0 0 10px 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px; }
        th { background: #f3f4f6; text-align: left; }
    </style>
</head>
<body>
    <h2>Data Mata Pelajaran - SMKN 2 Rupat</h2>
    <table>
        <thead>
        <tr>
            <th>Kode</th>
            <th>Nama</th>
        </tr>
        </thead>
        <tbody>
        @foreach($items as $item)
            <tr>
                <td>{{ $item->kode_mapel }}</td>
                <td>{{ $item->nama_mapel }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>

