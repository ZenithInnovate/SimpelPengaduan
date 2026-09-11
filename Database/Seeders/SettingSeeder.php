<?php

/*
 * -----------------------------------------------------------------------------
 * Module Simpel Pengaduan
 * -----------------------------------------------------------------------------
 * @package   Simpel
 * @author    AkarDev.com
 * @copyright Hak Cipta 2026 AkarDev.com
 * @link      https://akar-dev.com
 * -----------------------------------------------------------------------------
 */

namespace Modules\SimpelPengaduan\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\SimpelCore\Enums\StatusBooleanEnum;
use Modules\SimpelCore\Traits\MigratorTrait;

class SettingSeeder extends Seeder
{
    use MigratorTrait;

    public function run(): void
    {
        Model::unguard();
        $this->createSettings([
            [
                'judul' => 'Notifikasi WhatsApp Pengaduan',
                'key' => 'sp_wa_notif_aktif',
                'value' => '1',
                'keterangan' => 'Kirim notifikasi WhatsApp ke warga saat pengaduan dibuat atau ditanggapi (1 = Ya, 0 = Tidak)',
                'kategori' => 'Simpel Pengaduan',
                'jenis' => 'select-array',
                'option' => json_encode(StatusBooleanEnum::all()),
                'attribute' => json_encode([
                    'class' => 'required',
                ]),
                'urut' => 1,
            ],
            [
                'judul' => 'Nomor WhatsApp Admin Pengaduan',
                'key' => 'sp_wa_admin_nomor',
                'value' => '',
                'keterangan' => 'Nomor WhatsApp petugas/admin desa penerima laporan baru warga (kosongkan jika tidak digunakan)',
                'kategori' => 'Simpel Pengaduan',
                'jenis' => 'input-text',
                'attribute' => json_encode([
                    'placeholder' => '08xxxxxxxxxx',
                ]),
                'urut' => 2,
            ],
        ]);

        $this->deleteSetting(['key' => 'sp_version']);

        cache()->flush();
    }
}
