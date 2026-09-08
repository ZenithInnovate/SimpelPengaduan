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

/**
 * Entry point seeder modul (dipanggil migrasi akses_modul / setting).
 * Menu admin → ModulSeeder · setting → SettingSeeder · data contoh (hanya mode
 * demo) → Demo\DemoPengaduanSeeder.
 */
class SimpelPengaduanSeeder extends Seeder
{
    public function run(): void
    {
        Model::unguard();

        $this->call(ModulSeeder::class);
        $this->call(SettingSeeder::class);
    }
}
