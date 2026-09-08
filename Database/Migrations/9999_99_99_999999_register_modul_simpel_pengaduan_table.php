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

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\File;
use Modules\SimpelCore\Models\ModulModel;
use Modules\SimpelCore\Services\ModulService;
use Modules\SimpelCore\Traits\MigratorTrait;
use Modules\SimpelPengaduan\Database\Seeders\Demo\DemoPengaduanSeeder;
use Modules\SimpelPengaduan\Services\BuildService;

return new class extends Migration
{
    use MigratorTrait;

    public function up(): void
    {
        $path = base_path('Modules/SimpelPengaduan');

        if (! File::isDirectory($path)) {
            return;
        }

        Model::unguarded(static function () use ($path): void {
            ModulService::handle($path);
        });

        // Isi data demo \u2014 hanya jika demo_mode aktif, idempoten (ada penanda)
        (new DemoPengaduanSeeder)->run();
    }

    public function down(): void
    {
        Model::unguarded(function (): void {
            $this->deleteModul(['slug' => 'simpel-pengaduan']);
        });

        $uuid = BuildService::get();

        if ($uuid) {
            ModulModel::withoutConfigId()->where('modul_uuid', $uuid)->delete();
        }

        cache()->flush();
    }
};
