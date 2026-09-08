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
use Modules\SimpelCore\Traits\MigratorTrait;

class ModulSeeder extends Seeder
{
    use MigratorTrait;

    public function run(): void
    {
        Model::unguard();

        $this->createModul([
            'modul' => 'Simpel Pengaduan',
            'slug' => 'simpel-pengaduan',
            'url' => 'simpel/pengaduan',
            'ikon' => 'fa-comments',
            'level' => 2,
            'parent' => 0,
        ]);
    }
}
