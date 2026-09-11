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

if (! function_exists('simpel_pengaduan_nomor_tiket')) {
    /**
     * Format virtual nomor tiket pengaduan
     * Contoh: LPR-202609-0012
     */
    function simpel_pengaduan_nomor_tiket(int|string $id, $createdAt = null): string
    {
        $timestamp = $createdAt ? strtotime((string) $createdAt) : time();
        $ym = date('Ym', $timestamp ?: time());

        return 'LPR-'.$ym.'-'.str_pad((string) $id, 4, '0', STR_PAD_LEFT);
    }
}

if (! function_exists('simpel_pengaduan_id_from_tiket')) {
    /**
     * Ekstrak id numerik dari nomor tiket
     */
    function simpel_pengaduan_id_from_tiket(string $tiket): ?int
    {
        $tiket = trim($tiket);
        if (preg_match('/^LPR-\d{6}-(\d+)$/i', $tiket, $matches)) {
            return (int) $matches[1];
        }

        if (ctype_digit($tiket)) {
            return (int) $tiket;
        }

        return null;
    }
}

if (! function_exists('simpel_pengaduan_foto_url')) {
    /**
     * Dapatkan URL foto lampiran pengaduan
     */
    function simpel_pengaduan_foto_url(?string $foto): ?string
    {
        if (empty($foto)) {
            return null;
        }

        $path = defined('LOKASI_PENGADUAN') ? LOKASI_PENGADUAN : 'desa/upload/pengaduan/';
        $fullFile = FCPATH.$path.$foto;

        if (file_exists($fullFile)) {
            return base_url($path.$foto);
        }

        return null;
    }
}
