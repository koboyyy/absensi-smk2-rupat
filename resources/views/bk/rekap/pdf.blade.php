<!doctype html>

<html lang="id">
<head>
    <meta charset="utf-8" />

    <title>Rekap Absen Siswa - SMKN 2 Rupat</title>

    <style>
        body {
            font-family:
                DejaVu Sans,
                sans-serif;

            font-size: 11px;

            line-height: 1.4;

            color: #333;
        }

        .text-center {
            text-align: center;
        }

        .text-bold {
            font-weight: bold;
        }

        .uppercase {
            text-transform: uppercase;
        }

        .meta {
            margin-bottom: 10px;

            width: 100%;
        }

        table {
            width: 100%;

            border-collapse: collapse;

            margin-top: 10px;
        }

        table.data th,
        table.data td {
            border: 1px solid #000;

            padding: 5px;
        }

        table.data th {
            background: #f2f2f2;
        }

        /* FOOTER */
        .footer-table {
            margin-top: 30px;

            border: none;
        }

        .footer-table td {
            border: none !important;

            width: 50%;

            vertical-align: top;
        }

        .signature-space {
            height: 60px;
        }

        /* HEADER */
        .header-container {
            width: 100%;

            margin-bottom: 20px;

            border-bottom: 2px solid #000;

            padding-bottom: 10px;
        }

        .logo-container {
            float: left;

            width: 130px;
        }

        .logo-container img {
            width: 100%;

            height: auto;
        }

        .header-text {
            text-align: center;

            margin-right: 70px;
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

        .header-container::after {
            content: "";

            clear: both;

            display: table;
        }
    </style>
</head>

<body>
    <!-- KOP -->
    <div class="header-container">
        <div class="logo-container">
            @php

                $path =
                    public_path(
                        'images/logo-smkn-2-rupat.png'
                    );

                $type =
                    pathinfo(
                        $path,
                        PATHINFO_EXTENSION
                    );

                $data =
                    file_get_contents($path);

                $base64 =
                    'data:image/' .
                    $type .
                    ';base64,' .
                    base64_encode($data);

            @endphp

            <img src="{{ $base64 }}" alt="Logo" />
        </div>

        <div class="header-text">
            <h2>PEMERINTAH PROVINSI RIAU</h2>

            <p>DINAS PENDIDIKAN</p>

            <h1>SMK NEGERI 2 RUPAT</h1>

            <p>Alamat: Jl. Pangkalan Pinang, Kec. Rupat, Kab. Bengkalis</p>

            <p>
                <strong> REKAP ABSEN SISWA </strong>
            </p>

            <p>TAHUN PELAJARAN 2025/2026</p>
        </div>
    </div>

    <!-- META -->
    <div class="meta">
        Periode:

        {{ str_pad((string)$bulan, 2, '0', STR_PAD_LEFT) }}/{{ $tahun }}

        @if ($jurusan)
            &bull;

            Jurusan:
            {{ $jurusan }}

        @endif
    </div>

    <!-- TABLE -->
    <table class="data">
        <thead>
            <tr>
                <th rowspan="2" style="width: 30px" class="text-center">NO</th>

                <th rowspan="2">NAMA</th>

                <!-- TAMBAHAN -->
                <th rowspan="2">KELAS</th>

                <!-- TAMBAHAN -->
                <th rowspan="2">JURUSAN</th>

                <th rowspan="2" style="width: 40px" class="text-center">L/P</th>

                <th colspan="3" class="text-center">
                    TOTAL ABSENSI DALAM 1 BULAN
                </th>
            </tr>

            <tr>
                <th style="width: 60px" class="text-center">SAKIT</th>

                <th style="width: 60px" class="text-center">ALFA</th>

                <th style="width: 60px" class="text-center">IZIN</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($perKelas as $index => $r)
                <tr>
                    <!-- NO -->
                    <td class="text-center">{{ $index + 1 }}</td>

                    <!-- NAMA -->
                    <td>{{ $r->nama_siswa }}</td>

                    <!-- KELAS -->
                    <td class="text-center">{{ $r->nama_kelas }}</td>

                    <!-- JURUSAN -->
                    <td class="text-center">{{ $r->jurusan }}</td>

                    <!-- JK -->
                    <td class="text-center">{{ $r->jenis_kelamin }}</td>

                    <!-- SAKIT -->
                    <td class="text-center">{{ $r->sakit ?? 0 }}</td>

                    <!-- ALFA -->
                    <td
                        class="text-center"
                        style="{{ ($r->alfa ?? 0) > 0

                            ? 'background-color: #ffcccc; color: red; font-weight: bold;'

                            : '' }}"
                    >
                        {{ $r->alfa ?? 0 }}
                    </td>

                    <!-- IZIN -->
                    <td class="text-center">{{ $r->izin ?? 0 }}</td>
                </tr>

            @endforeach
        </tbody>

        <!-- FOOTER -->
        <tfoot>
            <tr class="text-bold">
                <td colspan="5" class="text-center">JUMLAH TOTAL</td>

                <td class="text-center">{{ $summary['S'] }}</td>

                <td class="text-center">{{ $summary['A'] }}</td>

                <td class="text-center">{{ $summary['I'] }}</td>
            </tr>
        </tfoot>
    </table>

    <!-- TTD -->
    <table class="footer-table">
        <tr>
            <td class="text-center">
                Mengetahui,
                <br />

                Kepala SMK Negeri 2 Rupat
                <br />

                <div class="signature-space"></div>

                <span class="text-bold" style="text-decoration: underline">
                    Fitria, S.Pd., M.M.
                </span>

                <br />

                NIP. 197809292005012005
            </td>

            <td class="text-center">
                Pangkalan Pinang, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}

                <br />

                Guru Bimbingan Konseling
                <br />

                <div class="signature-space"></div>

                <span class="text-bold" style="text-decoration: underline">
                    Nur Hefni Ebri, S.Pd.
                </span>

                <br />

                NIP. .............................
            </td>
        </tr>
    </table>
</body>
</html>
