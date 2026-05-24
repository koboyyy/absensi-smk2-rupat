<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\OrangTua;
use App\Models\User;
use App\Models\WaliKelas;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /**
         * ADMIN
         */
        User::create([
            'username' => 'admin',
            'password' => Hash::make('password'),
            'roles' => ['admin'],
            'status' => 'aktif',
        ]);

        /**
         * GURU + WALI KELAS
         */
        $guruWali = User::create([
            'username' => 'handoko',
            'password' => Hash::make('password'),
            'roles' => ['guru', 'wali_kelas'],
            'status' => 'aktif',
        ]);

        $guru1 = Guru::create([
            'user_id' => $guruWali->id,
            'nama_guru' => 'Budi Santoso',
            'nip' => '1987654321',
            'jenis_kelamin' => 'L',
            'no_hp' => '081234567890',
            'alamat' => 'Pekanbaru',
        ]);

        /**
         * HUBUNGKAN KE WALI KELAS
         * pastikan kelas_id = 1 tersedia
         */
        WaliKelas::create([
            'kelas_id' => 1,
            'guru_id' => $guru1->guru_id,
        ]);

        /**
         * GURU BK
         */
        $guruBk = User::create([
            'username' => 'bk',
            'password' => Hash::make('password'),
            'roles' => ['guru_bk'],
            'status' => 'aktif',
        ]);

        Guru::create([
            'user_id' => $guruBk->id,
            'nama_guru' => 'Guru BK',
            'nip' => '198888888',
            'jenis_kelamin' => 'P',
            'no_hp' => '08111111111',
            'alamat' => 'Pekanbaru',
        ]);

        /**
         * KEPALA SEKOLAH + GURU
         */
        $kepsek = User::create([
            'username' => 'kepsek',
            'password' => Hash::make('password'),
            'roles' => ['kepala_sekolah', 'guru'],
            'status' => 'aktif',
        ]);

        Guru::create([
            'user_id' => $kepsek->id,
            'nama_guru' => 'Kepala Sekolah',
            'nip' => '197777777',
            'jenis_kelamin' => 'L',
            'no_hp' => '08222222222',
            'alamat' => 'Pekanbaru',
        ]);

        /**
         * ORANG TUA
         */
        $ortu = User::create([
            'username' => 'ortu',
            'password' => Hash::make('password'),
            'roles' => ['orang_tua'],
            'status' => 'aktif',
        ]);

        OrangTua::create([
            'user_id' => $ortu->id,
            'nama_ortu' => 'Siti Aminah',
            'no_hp' => '08333333333',
            'alamat' => 'Pekanbaru',
        ]);

        /**
         * MULTI ROLE BESAR
         */
        $multi = User::create([
            'username' => 'multirole',
            'password' => Hash::make('password'),
            'roles' => [
                'guru',
                'wali_kelas',
                'guru_bk'
            ],
            'status' => 'aktif',
        ]);

        $guruMulti = Guru::create([
            'user_id' => $multi->id,
            'nama_guru' => 'Multi Role',
            'nip' => '199999999',
            'jenis_kelamin' => 'L',
            'no_hp' => '08444444444',
            'alamat' => 'Pekanbaru',
        ]);

        WaliKelas::create([
            'kelas_id' => 2,
            'guru_id' => $guruMulti->guru_id,
        ]);
    }
}