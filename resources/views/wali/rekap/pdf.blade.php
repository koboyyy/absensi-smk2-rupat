<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Rekap Absensi Bulanan - {{ $nama_kelas }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 10mm;
        }
        body {
            font-family: 'Helvetica', Arial, sans-serif;
            font-size: 9px; /* Ukuran lebih kecil agar kolom tanggal muat */
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
        }
        .header h2 { margin: 0; font-size: 14px; text-transform: uppercase; }
        .header p { margin: 2px 0; font-size: 10px; }

        .table-absen {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed; /* Menjaga ukuran kolom tetap konsisten */
        }
        .table-absen th, .table-absen td {
            border: 1px solid #000;
            padding: 2px;
            text-align: center;
            overflow: hidden;
        }
        .col-no { width: 25px; }
        .col-nama { width: 150px; text-align: left !important; padding-left: 5px !important; }
        .col-tgl { width: 18px; font-size: 8px; }
        .col-rekap { width: 25px; background-color: #f2f2f2; font-weight: bold; }

        .footer {
            margin-top: 20px;
            width: 100%;
        }
        .signature {
            float: right;
            width: 200px;
            text-align: center;
        }
        .spacer { height: 50px; }

        .header-container {
        width: 100%;
        margin-bottom: 20px;
        border-bottom: 2px solid #000; /* Garis kop surat */
        padding-bottom: 10px;
    }
    .logo-container {
        float: left;
        width: 110px; /* Sesuaikan ukuran logo */
    }
    .logo-container img {
        width: 100%;
        height: auto;
    }
    .header-text {
        text-align: center;
        margin-right: 70px; /* Untuk menyeimbangkan posisi teks karena ada logo di kiri */
    }
    .header-text h2 { margin: 0; font-size: 14px; text-transform: uppercase; }
    .header-text h1 { margin: 0; font-size: 18px; text-transform: uppercase; }
    .header-text p { margin: 2px 0; font-size: 10px; }
    
    /* Clearfix agar float tidak merusak tabel dibawahnya */
    .header-container::after {
        content: "";
        clear: both;
        display: table;
    }
    </style>
</head>
<body>

    <div class="header-container">
        <div class="logo-container">
            {{-- Pastikan file logo ada di public/img/logo.png --}}
            @php
                $path = public_path('images/logo-smkn-2-rupat.png'); // Ganti sesuai lokasi logo Anda
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            @endphp
            <img src="{{ $base64 }}" alt="Logo">
        </div>
        <div class="header-text">
            <h2>PEMERINTAH PROVINSI RIAU</h2>
            <p>DINAS PENDIDIKAN</p>
            <h1>SMK NEGERI 2 RUPAT</h1>
            <p>Alamat: Jl. Pangkalan Pinang, Kec. Rupat, Kab. Bengkalis</p>
            <p><strong>DAFTAR HADIR SISWA KELAS {{ $nama_kelas }}</strong></p>
            <p>TAHUN PELAJARAN 2025/2026</p>
        </div>
    </div>

    <table class="table-absen">
        {{-- BAGIAN HEADER (HANYA UNTUK JUDUL KOLOM) --}}
<thead>
    <tr>
        <th>NO</th>
        <th>NAMA SISWA</th>
        @for($i=1; $i<=31; $i++)
            <th>{{ $i }}</th>
        @endfor
        <th>H</th>
        <th>S</th>
        <th>I</th>
        <th>A</th>
    </tr>
</thead>

{{-- BAGIAN DATA (DISINI BARU BISA PAKAI $siswa) --}}
<tbody>
    @foreach($siswas as $index => $siswa)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $siswa->nama_siswa }}</td>
        
        {{-- Loop Tanggal --}}
        @for($i=1; $i<=31; $i++)
        <td>
            @php 
                // Pastikan key array ini sesuai dengan yang dikirim dari controller
                $st = $dataAbsen[$siswa->siswa_id][$i] ?? ''; 
            @endphp
            
            @if($st == 'H') H
            @elseif($st == 'S') S 
            @elseif($st == 'I') I 
            @elseif($st == 'A') A 
            @endif
        </td>
        @endfor

        {{-- Total Rekap --}}
        <td>{{ $totalPerSiswa[$siswa->siswa_id]['H'] ?? 0 }}</td>
        <td>{{ $totalPerSiswa[$siswa->siswa_id]['S'] ?? 0 }}</td>
        <td>{{ $totalPerSiswa[$siswa->siswa_id]['I'] ?? 0 }}</td>
        <td>{{ $totalPerSiswa[$siswa->siswa_id]['A'] ?? 0 }}</td>
    </tr>
    @endforeach
</tbody>
    </table>

    <div class="footer">
        <div class="signature">
            <p>Pangkalan Pinang, {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</p>
            <p>WALI KELAS</p>
            <div class="spacer"></div>
            <p><strong>{{ Auth::user()->guru->nama_guru }}</strong></p>
            <p>NIP. {{ Auth::user()->guru->nip ?? '..........................' }}</p>
        </div>
    </div>

</body>
</html>