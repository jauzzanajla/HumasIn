<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Peran;

class PeranSeeder extends Seeder
{
    public function run(): void
    {
        Peran::create([
            'kode_peran' => 'SUPER_ADMIN',
            'nama_peran' => 'Super Admin',
            'deskripsi' => 'Pengelola utama sistem dari Humas DJPK.',
            'aktif' => true,
        ]);

        Peran::create([
            'kode_peran' => 'ADMIN',
            'nama_peran' => 'Admin',
            'deskripsi' => 'Petugas Humas DJPK yang menangani layanan dan permintaan.',
            'aktif' => true,
        ]);

        Peran::create([
            'kode_peran' => 'TU_ESELON_II',
            'nama_peran' => 'TU Eselon II',
            'deskripsi' => 'Tata Usaha dari masing-masing Eselon II DJPK.',
            'aktif' => true,
        ]);

        Peran::create([
            'kode_peran' => 'KARYAWAN',
            'nama_peran' => 'Karyawan',
            'deskripsi' => 'Karyawan DJPK selain Humas dan TU Eselon II.',
            'aktif' => true,
        ]);
    }
}