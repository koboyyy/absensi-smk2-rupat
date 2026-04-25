<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Data Kelas</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h2 { margin: 0 0 10px 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px; }
        th { background: #f3f4f6; text-align: left; }
    </style>
</head>
<body>
    <h2>Data Kelas - SMKN 2 Rupat</h2>
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

