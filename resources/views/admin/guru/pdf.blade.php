<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Data Guru</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
        h2 { margin: 0 0 10px 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px; }
        th { background: #f3f4f6; text-align: left; }
    </style>
</head>
<body>
    <h2>Data Guru - SMKN 2 Rupat</h2>
    <table>
        <thead>
        <tr>
            <th>Nama</th>
            <th>NIP</th>
            <th>JK</th>
            <th>No HP</th>
            <th>Username</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
        @foreach($items as $item)
            <tr>
                <td>{{ $item->nama_guru }}</td>
                <td>{{ $item->nip }}</td>
                <td>{{ $item->jenis_kelamin }}</td>
                <td>{{ $item->no_hp }}</td>
                <td>{{ $item->user?->username }}</td>
                <td>{{ $item->user?->status }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>

