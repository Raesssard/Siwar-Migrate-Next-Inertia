<?php

namespace Database\Seeders;

use App\Models\Kartu_keluarga;
use App\Models\Rukun_tetangga;
use App\Models\Rw;
use App\Models\User;
use App\Models\Warga;
use App\Models\Kategori_golongan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        // Buat roles dulu (jika belum ada)
        $roles = ['admin', 'rw', 'rt', 'warga'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // ambil id kategori kampung
        $kampung = Kategori_golongan::where('jenis', 'kampung')->first();

        // 1. Buat RW
        $rw = Rw::create([
            'nik' => '1234567890123452',
            'nomor_rw' => '01',
            'nama_ketua_rw' => 'Pak RW',
            'mulai_menjabat' => now(),
            'akhir_jabatan' => now()->addYears(3),
        ]);

        /*
        ==============================
        KK 1 → Ketua RT sekaligus Warga
        ==============================
        */
        $kk_rt = Kartu_keluarga::create([
            'no_kk' => '1111111111111111',
            'no_registrasi' => '3404.0000001',
            'alamat' => 'Jalan Melati',
            'id_rw' => $rw->id,
            'kelurahan' => 'Kelurahan Mawar',
            'kecamatan' => 'Kecamatan Indah',
            'kabupaten' => 'Kabupaten Damai',
            'provinsi' => 'Provinsi Sejahtera',
            'kode_pos' => '12345',
            'tgl_terbit' => now(),
            'kategori_iuran' => $kampung->id,
            'instansi_penerbit' => 'Dinas Dukcapil',
            'kabupaten_kota_penerbit' => 'Kota Bandung',
            'nama_kepala_dukcapil' => 'Budi Santoso S.Kom',
            'nip_kepala_dukcapil' => '123456789012345678',
        ]);

        // RT
        $rt = Rukun_tetangga::create([
            'no_kk' => $kk_rt->no_kk,
            'nik' => '0000000000000002',
            'rt' => '01',
            'nama' => 'Andi Kurniawan',
            'mulai_menjabat' => now(),
            'akhir_jabatan' => now()->addYears(3),
            'jabatan' => 'ketua',
            'id_rw' => $rw->id,
        ]);

        $kk_rt->update(['id_rt' => $rt->id]);

        // Warga Kepala Keluarga (yang juga RT)
        $warga_rt = Warga::create([
            'no_kk' => $kk_rt->no_kk,
            'nik' => '0000000000000002',
            'nama' => 'Andi Kurniawan',
            'jenis_kelamin' => 'laki-laki',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2000-01-01',
            'agama' => 'Islam',
            'pendidikan' => 'S1',
            'pekerjaan' => 'PNS',
            'status_perkawinan' => 'menikah',
            'status_hubungan_dalam_keluarga' => 'kepala keluarga',
            'golongan_darah' => 'A',
            'kewarganegaraan' => 'WNI',
            'nama_ayah' => 'Budi Hartono',
            'nama_ibu' => 'Siti Kurniawan',
            'status_warga' => 'penduduk',
        ]);

        /*
        ==============================
        KK 2 → Warga biasa
        ==============================
        */
        $kk_warga = Kartu_keluarga::create([
            'no_kk' => '2222222222222222',
            'no_registrasi' => '3404.0000002',
            'alamat' => 'Jalan Mawar',
            'id_rt' => $rt->id,
            'id_rw' => $rw->id,
            'kelurahan' => 'Kelurahan Mawar',
            'kecamatan' => 'Kecamatan Indah',
            'kabupaten' => 'Kabupaten Damai',
            'provinsi' => 'Provinsi Sejahtera',
            'kode_pos' => '12345',
            'tgl_terbit' => now(),
            'kategori_iuran' => $kampung->id,
            'instansi_penerbit' => 'Dinas Dukcapil',
            'kabupaten_kota_penerbit' => 'Kota Bandung',
            'nama_kepala_dukcapil' => 'Budi Santoso S.Kom',
            'nip_kepala_dukcapil' => '123456789012345678',
        ]);

        $warga_biasa = Warga::create([
            'no_kk' => $kk_warga->no_kk,
            'nik' => '3333333333333333',
            'nama' => 'Joko Santoso',
            'jenis_kelamin' => 'laki-laki',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '1995-05-05',
            'agama' => 'Islam',
            'pendidikan' => 'SMA',
            'pekerjaan' => 'Karyawan',
            'status_perkawinan' => 'menikah',
            'status_hubungan_dalam_keluarga' => 'kepala keluarga',
            'golongan_darah' => 'B',
            'kewarganegaraan' => 'WNI',
            'nama_ayah' => 'Slamet',
            'nama_ibu' => 'Sri',
            'status_warga' => 'penduduk',
        ]);

        /*
        ==============================
        Users + Role Assignment
        ==============================
        */
        $admin = User::create([
            'nik' => '0000000000000001',
            'nama' => 'Admin',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('admin');

        $pakRw = User::create([
            'nik' => '1234567890123452',
            'nama' => 'Pak RW',
            'password' => Hash::make('password'),
            'id_rw' => $rw->id,
        ]);
        $pakRw->assignRole('rw');

        $pakRt = User::create([
            'nik' => $rt->nik,
            'nama' => $rt->nama,
            'password' => Hash::make('password'),
            'id_rt' => $rt->id,
            'id_rw' => $rw->id,
        ]);
        $pakRt->assignRole(['rt', 'warga']); // multi role

        $joko = User::create([
            'nik' => $warga_biasa->nik,
            'nama' => $warga_biasa->nama,
            'password' => Hash::make('password'),
            'id_rt' => $rt->id,
            'id_rw' => $rw->id,
        ]);
        $joko->assignRole('warga');
    }
}
