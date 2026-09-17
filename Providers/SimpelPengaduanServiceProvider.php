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

namespace Modules\SimpelPengaduan\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\SimpelCore\Traits\ProviderTrait;
use Modules\SimpelPengaduan\Traits\InfoTrait;

class SimpelPengaduanServiceProvider extends ServiceProvider
{
    use InfoTrait;
    use ProviderTrait;

    public function boot(): void
    {
        $this->loadJson();
        $this->bootModule();
    }

    public function register(): void {}
}
