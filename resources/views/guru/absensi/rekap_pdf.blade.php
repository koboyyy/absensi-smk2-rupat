<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Rekap Absensi - {{ $jadwal->mapel->nama_mapel }}</title>
    <style>
        body {
            font-family: "Helvetica", "Arial", sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
        }

        /* Container Kop Surat */
        .header-container {
            width: 100%;
            margin-bottom: 20px;
            border-bottom: 3px double #000; /* Garis ganda khas kop surat */
            padding-bottom: 10px;
        }
        .logo-container {
            float: left;
            width: 110px;
        }
        .logo-container img {
            width: 100%;
            height: auto;
        }
        .header-text {
            text-align: center;
            margin-right: 70px; /* Menyeimbangkan posisi teks karena ada logo di kiri */
        }
        .header-text h2 {
            margin: 0;
            font-size: 14px;
            text-transform: uppercase;
        }
        .header-text h1 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }
        .header-text p {
            margin: 2px 0;
            font-size: 10px;
        }

        /* Clearfix agar float tidak merusak elemen di bawahnya */
        .header-container::after {
            content: "";
            clear: both;
            display: table;
        }

        .info-table {
            width: 100%;
            margin-bottom: 15px;
        }
        .info-table td {
            padding: 2px 0;
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
            border: 1px solid #000;
            padding: 8px 5px;
            text-align: center;
            text-transform: uppercase;
            font-size: 10px;
        }
        .content-table td {
            border: 1px solid #000;
            padding: 6px 5px;
        }
        .text-center {
            text-align: center;
        }
        .font-bold {
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            width: 100%;
        }
        .signature {
            float: right;
            width: 220px;
            text-align: center;
        }
        .signature-space {
            height: 60px;
        }
    </style>
</head>
<body>
    <div class="header-container">
        <div class="logo-container">
            @php
                // Mengambil logo SMKN 2 Rupat dengan Base64 agar aman di PDF
                $path = public_path('images/logo-smkn-2-rupat.png'); 
                $base64 = '';
                if (file_exists($path)) {
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $data = file_get_contents($path);
                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                }
            @endphp
            @if ($base64)
                <img src="{{ $base64 }}" alt="Logo SMKN 2 Rupat" />
            @endif
        </div>
        <div class="header-text">
            <h2>PEMERINTAH PROVINSI RIAU</h2>
            <p>DINAS PENDIDIKAN</p>
            <h1>SMK NEGERI 2 RUPAT</h1>
            <p>Jl. Pangkalan Pinang, Kec. Rupat, Kab. Bengkalis, Riau</p>
            <p><strong>REKAP KEHADIRAN SISWA PER MATA PELAJARAN</strong></p>
        </div>
    </div>

    <table class="info-table">
        <tr>
            <td class="label">Mata Pelajaran</td>
            <td>: {{ $jadwal->mapel->nama_mapel }}</td>
            <td class="label">Periode</td>
            <td>
                : {{ \Carbon\Carbon::create()->month((int)$bulan)->translatedFormat('F') }} {{ $tahun }}
            </td>
        </tr>
        <tr>
            <td class="label">Kelas</td>
            <td>: {{ $jadwal->kelas->nama_kelas }}</td>
            <td class="label">Guru Pengampu</td>
            <td>: {{ Auth::user()->guru->nama_guru }}</td>
        </tr>
    </table>

    <table class="content-table">
        <thead>
            <tr>
                <th width="30">No</th>
                <th width="80">NIS</th>
                <th>Nama Siswa</th>
                <th width="40">H</th>
                <th width="40">S</th>
                <th width="40">I</th>
                <th width="40">A</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($siswas as $index => $s)
                @php ($data = $rekap[$s->siswa_id] ?? null)
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
            <tr style="background-color: #f9f9f9; font-weight: bold">
                <td colspan="3" class="text-center">TOTAL AKUMULASI</td>
                <td class="text-center">{{ $rekap->sum('total_hadir') }}</td>
                <td class="text-center">{{ $rekap->sum('total_sakit') }}</td>
                <td class="text-center">{{ $rekap->sum('total_izin') }}</td>
                <td class="text-center">{{ $rekap->sum('total_alfa') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <div class="signature">
            <p>Pangkalan Pinang, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <p>Guru Mata Pelajaran,</p>
            <div class="signature-space"></div>
            <p class="font-bold"><u>{{ Auth::user()->guru->nama_guru }}</u></p>
            <p>NIP. {{ Auth::user()->guru->nip ?? '...........................' }}</p>
        </div>
        <div style="clear: both"></div>
    </div>
</body>
</html>
