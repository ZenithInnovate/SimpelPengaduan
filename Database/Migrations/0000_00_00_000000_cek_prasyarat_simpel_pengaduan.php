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

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Gerbang prasyarat modul.
     */
    public function up(): void
    {
        $svc = \Modules\SimpelCore\Services\ModulService::class;

        if (class_exists($svc)) {
            $svc::pastikanPrasyarat(__DIR__.'/../..');
        }
    }

    public function down(): void
    {
        // no-op
    }
};
