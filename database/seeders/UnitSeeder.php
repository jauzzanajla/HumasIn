<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unit;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        Unit::create([
            'kode_unit' => 'SETDJPK',
            'nama_unit' => 'Sekretariat Direktorat Jenderal',
            'aktif' => true,
        ]);

        Unit::create([
            'kode_unit' => 'DTU',
            'nama_unit' => 'Direktorat Dana Transfer Umum',
            'aktif' => true,
        ]);

        Unit::create([
            'kode_unit' => 'DTK',
            'nama_unit' => 'Direktorat Dana Transfer Khusus',
            'aktif' => true,
        ]);

        Unit::create([
            'kode_unit' => 'DDIOKK',
            'nama_unit' => 'Direktorat Dana Desa, Insentif, Otonomi Khusus, dan Keistimewaan',
            'aktif' => true,
        ]);

        Unit::create([
            'kode_unit' => 'DPDRD',
            'nama_unit' => 'Direktorat Pajak Daerah dan Retribusi Daerah',
            'aktif' => true,
        ]);

        Unit::create([
            'kode_unit' => 'DP2D',
            'nama_unit' => 'Direktorat Pembiayaan dan Perekonomian Daerah',
            'aktif' => true,
        ]);

        Unit::create([
            'kode_unit' => 'DSPK',
            'nama_unit' => 'Direktorat Sistem Perimbangan Keuangan',
            'aktif' => true,
        ]);
    }
}