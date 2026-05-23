<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <title>Rekap Kehadiran - {{ $tanggal }}</title>
    <style>
        body {
            font-family: "Helvetica", Arial, sans-serif;
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
            border: none;
        }
        .info-table td {
            padding: 2px 0;
            vertical-align: top;
        }

        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .main-table th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
        }
        .main-table th,
        .main-table td {
            border: 1px solid #000;
            padding: 6px 4px;
        }

        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .font-bold {
            font-weight: bold;
        }

        .footer-row {
            background-color: #f9f9f9;
            font-weight: bold;
        }

        .signature-wrapper {
            margin-top: 30px;
            width: 100%;
        }
        .signature-box {
            float: right;
            width: 200px;
            text-align: center;
        }
        .spacer {
            height: 60px;
        }
    </style>
</head>
<body>
    <div class="header-container">
        <div class="logo-container">
            @php
                // Mengambil logo SMKN 2 Rupat dengan Base64
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
            <p>Sistem Informasi Absensi Siswa Terintegrasi</p>
        </div>
    </div>

    <h3
        class="text-center"
        style="text-decoration: underline; margin-bottom: 15px; font-size: 13px"
    >
        LAPORAN REKAP KEHADIRAN SISWA
    </h3>

    <table class="info-table">
        <tr>
            <td width="15%">Tanggal</td>
            <td width="2%">:</td>
            <td width="33%">
                {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}
            </td>
            <td width="15%">Guru Pengampu</td>
            <td width="2%">:</td>
            <td>{{ Auth::user()->guru->nama_guru }}</td>
        </tr>
        <tr>
            <td>Periode</td>
            <td>:</td>
            <td>Harian</td>
            <td>NIP</td>
            <td>:</td>
            <td>{{ Auth::user()->guru->nip ?? '-' }}</td>
        </tr>
    </table>

    <table class="main-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">NIS</th>
                <th>Nama Siswa</th>
                <th width="8%">H</th>
                <th width="8%">S</th>
                <th width="8%">I</th>
                <th width="8%">A</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $grandH = 0; $grandS = 0; $grandI = 0; $grandA = 0; 
            @endphp
            @forelse ($items as $index => $item)
                @php
                    $grandH += $item->total_hadir;
                    $grandS += $item->total_sakit;
                    $grandI += $item->total_izin;
                    $grandA += $item->total_alfa;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center font-mono">{{ $item->nis }}</td>
                    <td>{{ $item->nama_siswa }}</td>
                    <td class="text-center">{{ $item->total_hadir }}</td>
                    <td class="text-center">{{ $item->total_sakit }}</td>
                    <td class="text-center">{{ $item->total_izin }}</td>
                    <td class="text-center">{{ $item->total_alfa }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">
                        Data tidak ditemukan untuk periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="footer-row">
                <td colspan="3" class="text-right">TOTAL SELURUHNYA</td>
                <td class="text-center">{{ $grandH }}</td>
                <td class="text-center">{{ $grandS }}</td>
                <td class="text-center">{{ $grandI }}</td>
                <td class="text-center">{{ $grandA }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="signature-wrapper">
        <div class="signature-box">
            <p>Pangkalan Pinang, {{ now()->translatedFormat('d F Y') }}</p>
            <p>Guru Mata Pelajaran,</p>
            <div class="spacer"></div>
            <p><strong>{{ Auth::user()->guru->nama_guru }}</strong></p>
            <p>NIP. {{ Auth::user()->guru->nip ?? '..........................' }}</p>
        </div>
        <div style="clear: both"></div>
    </div>
</body>
</html>
