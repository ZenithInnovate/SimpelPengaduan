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

namespace Modules\SimpelPengaduan\Enums;

enum StatusPengaduanEnum: int
{
    case MENUNGGU = 1;
    case DIPROSES = 2;
    case SELESAI = 3;

    public function label(): string
    {
        return match ($this) {
            self::MENUNGGU => 'Menunggu Diproses',
            self::DIPROSES => 'Sedang Diproses',
            self::SELESAI => 'Selesai Diproses',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::MENUNGGU => 'warning',
            self::DIPROSES => 'info',
            self::SELESAI => 'success',
        };
    }

    public static function options(): array
    {
        return [
            self::MENUNGGU->value => self::MENUNGGU->label(),
            self::DIPROSES->value => self::DIPROSES->label(),
            self::SELESAI->value => self::SELESAI->label(),
        ];
    }

    public static function fromValue(int|string|null $val): ?self
    {
        if ($val === null) {
            return null;
        }

        return self::tryFrom((int) $val);
    }
}
