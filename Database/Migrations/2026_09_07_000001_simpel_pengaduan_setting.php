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
use Modules\SimpelPengaduan\Database\Seeders\SimpelPengaduanSeeder;

return new class extends Migration
{
    public function up(): void
    {
        (new SimpelPengaduanSeeder)->run();
    }

    public function down(): void {}
};
