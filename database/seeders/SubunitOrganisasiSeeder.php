<?php

namespace Database\Seeders;

use App\Models\Unit;
use App\Models\SubunitOrganisasi;
use Illuminate\Database\Seeder;

class SubunitOrganisasiSeeder extends Seeder
{
    public function run(): void
    {
        $struktur = [
            'SETDJPK' => [
                'jenis' => 'BAGIAN',
                'items' => [
                    ['BAG-PRK', 'Bagian Perencanaan dan Keuangan'],
                    ['BAG-OKI', 'Bagian Organisasi dan Kepatuhan Internal'],
                    ['BAG-SDM', 'Bagian Sumber Daya Manusia'],
                    ['BAG-UKLI', 'Bagian Umum, Komunikasi, dan Layanan Informasi'],
                    ['BAG-HKAKAL', 'Bagian Harmonisasi Kebijakan, Advokasi, dan Kerja Sama Antar Lembaga'],
                ],
            ],

            'DTU' => ['jenis' => 'SUBDIT', 'items' => [
                ['DTU-SPMP', 'Subdit Program dan Manajemen Pengetahuan'],
                ['DTU-KJF', 'Kelompok Jabatan Fungsional'],
            ]],

            'DTK' => ['jenis' => 'SUBDIT', 'items' => [
                ['DTK-SPMP', 'Subdit Program dan Manajemen Pengetahuan'],
                ['DTK-KJF', 'Kelompok Jabatan Fungsional'],
            ]],

            'DDIOKK' => ['jenis' => 'SUBDIT', 'items' => [
                ['DDIOKK-SPMP', 'Subdit Program dan Manajemen Pengetahuan'],
                ['DDIOKK-KJF', 'Kelompok Jabatan Fungsional'],
            ]],

            'DPDRD' => ['jenis' => 'SUBDIT', 'items' => [
                ['DPDRD-SPMP', 'Subdit Program dan Manajemen Pengetahuan'],
                ['DPDRD-KJF', 'Kelompok Jabatan Fungsional'],
            ]],

            'DP2D' => ['jenis' => 'SUBDIT', 'items' => [
                ['DP2D-SPMP', 'Subdit Program dan Manajemen Pengetahuan'],
                ['DP2D-KJF', 'Kelompok Jabatan Fungsional'],
            ]],

            'DSPK' => ['jenis' => 'SUBDIT', 'items' => [
                ['DSPK-SPMP', 'Subdit Program dan Manajemen Pengetahuan'],
                ['DSPK-KJF', 'Kelompok Jabatan Fungsional'],
            ]],
        ];

        foreach ($struktur as $kodeUnit => $data) {
            $unit = Unit::where('kode_unit', $kodeUnit)->first();

            if (!$unit) {
                $this->command->warn("Unit {$kodeUnit} tidak ditemukan, dilewati.");
                continue;
            }

            foreach ($data['items'] as [$kode, $nama]) {
                SubunitOrganisasi::updateOrCreate(
                    ['kode_subunit' => $kode],
                    [
                        'unit_id' => $unit->id,
                        'nama_subunit' => $nama,
                        'jenis' => $data['jenis'],
                        'aktif' => true,
                    ]
                );
            }
        }
    }
}
