<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Absensi - {{ $jadwal->mapel->nama_mapel }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #444;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            text-transform: uppercase;
            font-size: 18px;
        }
        .header p {
            margin: 5px 0;
            font-size: 14px;
            color: #666;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 3px 0;
        }
        .label {
            font-weight: bold;
            width: 120px;
        }
        .content-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .content-table th {
            background-color: #f2f2f2;
            border: 1px solid #ddd;
            padding: 10px 5px;
            text-align: center;
            text-transform: uppercase;
            font-size: 11px;
        }
        .content-table td {
            border: 1px solid #ddd;
            padding: 8px 5px;
        }
        .text-center {
            text-align: center;
        }
        .font-bold {
            font-weight: bold;
        }
        .footer {
            margin-top: 50px;
            width: 100%;
        }
        .signature {
            float: right;
            width: 200px;
            text-align: center;
        }
        .signature-space {
            height: 70px;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Rekap Kehadiran Siswa</h1>
        <p>SMKN 2 RUPAT</p>
    </div>

    <table class="info-table">
        <tr>
            <td class="label">Mata Pelajaran</td>
            <td>: {{ $jadwal->mapel->nama_mapel }}</td>
            <td class="label">Periode</td>
            <td>: {{ \Carbon\Carbon::create()->month((int)$bulan)->translatedFormat('F') }} {{ $tahun }}</td>
        </tr>
        <tr>
            <td class="label">Kelas</td>
            <td>: {{ $jadwal->kelas->nama_kelas }}</td>
            <td class="label">Guru Pengampu</td>
            <td>: {{ Auth::user()->name }}</td>
        </tr>
    </table>

    <table class="content-table">
        <thead>
            <tr>
                <th width="30">No</th>
                <th width="80">NIS</th>
                <th>Nama Siswa</th>
                <th width="50">H</th>
                <th width="50">S</th>
                <th width="50">I</th>
                <th width="50">A</th>
            </tr>
        </thead>
        <tbody>
            @foreach($siswas as $index => $s)
                @php($data = $rekap[$s->siswa_id] ?? null)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $s->nis }}</td>
                    <td>{{ $s->nama_siswa }}</td>
                    <td class="text-center">{{ $data->total_hadir ?? 0 }}</td>
                    <td class="text-center">{{ $data->total_sakit ?? 0 }}</td>
                    <td class="text-center">{{ $data->total_izin ?? 0 }}</td>
                    <td class="text-center">{{ $data->total_alfa ?? 0 }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #f9f9f9;">
                <td colspan="3" class="text-center font-bold">TOTAL AKUMULASI</td>
                <td class="text-center font-bold">{{ $rekap->sum('total_hadir') }}</td>
                <td class="text-center font-bold">{{ $rekap->sum('total_sakit') }}</td>
                <td class="text-center font-bold">{{ $rekap->sum('total_izin') }}</td>
                <td class="text-center font-bold">{{ $rekap->sum('total_alfa') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <div class="signature">
            <p>Bengkalis, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <p>Guru Mata Pelajaran,</p>
            <div class="signature-space"></div>
            <p class="font-bold underline">{{ Auth::user()->name }}</p>
            <p>NIP. ...........................</p>
        </div>
    </div>

</body>
</html>