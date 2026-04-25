<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Data Jadwal</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; }
        h2 { margin: 0 0 10px 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 5px; }
        th { background: #f3f4f6; text-align: left; }
    </style>
</head>
<body>
    <h2>Data Jadwal - SMKN 2 Rupat</h2>
    <table>
        <thead>
        <tr>
            <th>Hari</th>
            <th>Jam</th>
            <th>Kelas</th>
            <th>Mapel</th>
            <th>Guru</th>
        </tr>
        </thead>
        <tbody>
        @foreach($items as $item)
            <tr>
                <td>{{ $item->hari }}</td>
                <td>{{ $item->jam_mulai }} - {{ $item->jam_selesai }}</td>
                <td>{{ $item->kelas?->nama_kelas }}</td>
                <td>{{ $item->mapel?->nama_mapel }}</td>
                <td>{{ $item->guru?->nama_guru }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>

