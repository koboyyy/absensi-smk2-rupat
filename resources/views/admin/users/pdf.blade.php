<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Data Users</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h2 { margin: 0 0 10px 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px; }
        th { background: #f3f4f6; text-align: left; }
    </style>
</head>
<body>
    <h2>Data Akun Pengguna - SMKN 2 Rupat</h2>
    <table>
        <thead>
        <tr>
            <th>Username</th>
            <th>Role</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
        @foreach($items as $item)
            <tr>
                <td>{{ $item->username }}</td>
                <td>{{ $item->role }}</td>
                <td>{{ $item->status }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>

