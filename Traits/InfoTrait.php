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

namespace Modules\SimpelPengaduan\Traits;

use RuntimeException;

trait InfoTrait
{
    public function loadJson($share = false)
    {
        $path = __DIR__.'/../module.json';

        if (! file_exists($path)) {
            throw new RuntimeException("module.json modul tidak ditemukan: {$path}");
        }

        $moduleJson = json_decode(file_get_contents($path));

        $this->moduleName = $moduleJson->name ?? '';
        $this->moduleNameLower = $moduleJson->namelower ?? '';
        $this->moduleDirectory = $moduleJson->namespace ?? '';
        $this->moduleVersion = $moduleJson->version ?? '';
        $this->moduleRequire = (array) ($moduleJson->require ?? []);

        if (file_exists(__DIR__.'/../rilis.md')) {
            $this->rilis = true;
        }

        if ($share) {
            view()->share('moduleDetail', [
                'moduleName' => $this->moduleName,
                'moduleNameLower' => $this->moduleNameLower,
                'moduleDirectory' => $this->moduleDirectory,
                'moduleVersion' => $this->moduleVersion,
                'moduleRequire' => $this->moduleRequire,
                'rilis' => $this->rilis ?? false,
            ]);
        }
    }
}
