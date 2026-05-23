## Sistem Absensi Siswa SMK Negeri 2 Rupat (Laravel)

### Fitur yang sudah discaffold

- **Database**: migration + model + relasi untuk `users`, `gurus`, `orang_tuas`, `kelas`, `mapels`, `jadwals`, `siswas`, `absensis`, `surat_izins`, `wali_kelas`, `rekap_kelas`
- **Auth**: login/logout session berbasis **username** + status akun (aktif/nonaktif)
- **RBAC**: middleware `role` untuk membatasi akses per-aktor
- **Admin**: CRUD + Export PDF (dompdf) untuk `Users`, `Guru`, `Kelas`, `Mapel`, `Jadwal`, `Siswa`
- **Dashboard**: Chart.js (doughnut) ringkas H/S/I/A, **tema biru-putih** + **dark mode**
- **Orang Tua**: kirim **Surat Izin/Sakit** dengan upload bukti (tersimpan di `storage/app/public/surat_izin`)

### Cara Menjalankan

1. Install dependency:

```bash
composer install
cp .env.example .env
php artisan key:generate
```

2. Jalankan migrasi + seeder (membuat akun admin):

```bash
php artisan migrate:fresh --seed
php artisan storage:link
```

3. Jalankan server:

```bash
php artisan serve
```

### Akun Default

- **username**: `admin`
- **password**: `admin123`

### Asset Login (opsional)

Letakkan file:

- `public/images/school-bg.jpg` (background halaman login)
- `public/images/logo-smkn2.png` (logo sekolah)
