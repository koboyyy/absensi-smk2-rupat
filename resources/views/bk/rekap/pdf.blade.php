<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Rekap Absen Siswa - SMKN 2 Rupat</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; line-height: 1.4; color: #333; }
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }
        
        h2 { margin: 0; padding: 0; text-align: center; font-size: 14px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 5px; }
        
        .meta { margin-bottom: 10px; width: 100%; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.data th, table.data td { border: 1px solid #000; padding: 5px; }
        table.data th { background: #f2f2f2; }
        
        /* Footer Tanda Tangan */
        .footer-table { margin-top: 30px; border: none; }
        .footer-table td { border: none !important; width: 50%; vertical-align: top; }
        .signature-space { height: 60px; }
    </style>
</head>
<body>

    <div class="header">
        <h2 class="uppercase">REKAP ABSEN SISWA KELAS {{ $namaKelas ?? 'XI C' }}</h2>
        <h2 class="uppercase">SMKN 2 RUPAT</h2>
    </div>

    <div class="meta">
        Periode: {{ str_pad((string)$bulan, 2, '0', STR_PAD_LEFT) }}/{{ $tahun }}
        @if($jurusan) • Jurusan: {{ $jurusan }} @endif
    </div>

    <table class="data">
        <thead>
            <tr>
                <th rowspan="2" style="width: 30px;" class="text-center">NO</th>
                <th rowspan="2">NAMA</th>
                <th rowspan="2" style="width: 40px;" class="text-center">L/P</th>
                <th colspan="3" class="text-center">TOTAL ABSENSI DALAM 1 BULAN</th>
            </tr>
            <tr>
                <th style="width: 60px;" class="text-center">SAKIT</th>
                <th style="width: 60px;" class="text-center">ALFA</th>
                <th style="width: 60px;" class="text-center">IZIN</th>
            </tr>
        </thead>
        <tbody>
            @foreach($perKelas as $index => $r)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $r->nama_siswa }}</td>
                    <td class="text-center">{{ $r->jenis_kelamin }}</td>
                    <td class="text-center">{{ $r->sakit ?? 0 }}</td>
                    <td class="text-center" style="{{ ($r->alfa ?? 0) > 0 ? 'background-color: #ffcccc; color: red; font-weight: bold;' : '' }}">
                        {{ $r->alfa ?? 0 }}
                    </td>
                    <td class="text-center">{{ $r->izin ?? 0 }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="text-bold">
                <td colspan="3" class="text-center">JUMLAH TOTAL</td>
                <td class="text-center">{{ $summary['S'] }}</td>
                <td class="text-center">{{ $summary['A'] }}</td>
                <td class="text-center">{{ $summary['I'] }}</td>
            </tr>
        </tfoot>
    </table>

    {{-- Bagian Tanda Tangan sesuai Gambar --}}
    <table class="footer-table">
        <tr>
            <td class="text-center">
                Mengetahui,<br>
                Kepala SMK Negeri 2 Rupat<br>
                <div class="signature-space"></div>
                <span class="text-bold" style="text-decoration: underline;">Fitria, S.Pd., M.M.</span><br>
                NIP. 197809292005012005
            </td>
            <td class="text-center">
                Rupat, {{ now()->format('d F Y') }}<br>
                Guru Bimbingan Konseling<br>
                <div class="signature-space"></div>
                <span class="text-bold" style="text-decoration: underline;">Nur Hefni Ebri, S.Pd.</span><br>
                NIP. .............................
            </td>
        </tr>
    </table>

</body>
</html>