<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Data Siswa</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; }
        h2 { margin: 0 0 10px 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 5px; }
        th { background: #f3f4f6; text-align: left; }
    </style>
</head>
<body>
    <h2>Data Siswa - SMKN 2 Rupat</h2>
    <table>
        <thead>
        <tr>
            <th>NIS</th>
            <th>Nama</th>
            <th>JK</th>
            <th>Kelas</th>
            <th>Ortu</th>
        </tr>
        </thead>
        <tbody>
        @foreach($items as $item)
            <tr>
                <td>{{ $item->nis }}</td>
                <td>{{ $item->nama_siswa }}</td>
                <td>{{ $item->jenis_kelamin }}</td>
                <td>{{ $item->kelas?->nama_kelas }}</td>
                <td>{{ $item->orangTua?->nama_ortu }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>

