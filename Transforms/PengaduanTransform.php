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

namespace Modules\SimpelPengaduan\Transforms;

use Modules\SimpelPengaduan\Models\PengaduanModel;

class PengaduanTransform
{
    /**
     * Mentransformasi objek pengaduan menjadi representasi detail pelacakan publik.
     *
     * @param  PengaduanModel  $pengaduan  Objek model pengaduan
     * @return array<string, mixed> Data detail pelacakan
     */
    public static function transformDetail(PengaduanModel $pengaduan): array
    {
        return [
            'id' => $pengaduan->id,
            'nomor_tiket' => $pengaduan->nomor_tiket,
            'judul' => $pengaduan->judul,
            'isi' => $pengaduan->isi,
            'pelapor' => $pengaduan->nama,
            'status' => $pengaduan->status_label,
            'status_badge' => $pengaduan->status_badge,
            'foto_url' => $pengaduan->foto_url,
            'tgl_lapor' => $pengaduan->created_at ? $pengaduan->created_at->format('d M Y H:i') : '',
            'balasan' => $pengaduan->child ? self::collectionBalasan($pengaduan->child) : [],
        ];
    }

    /**
     * Mentransformasi objek balasan pengaduan.
     *
     * @param  PengaduanModel  $balasan  Objek balasan
     * @return array<string, mixed> Data baris balasan
     */
    public static function transformBalasan(PengaduanModel $balasan): array
    {
        return [
            'pengirim' => $balasan->nama,
            'isi' => $balasan->isi,
            'foto_url' => $balasan->foto_url,
            'waktu' => $balasan->created_at ? $balasan->created_at->format('d M Y H:i') : '',
        ];
    }

    /**
     * Mentransformasi koleksi balasan menjadi array list.
     *
     * @param  iterable  $items  Koleksi balasan
     * @return array<int, array<string, mixed>>
     */
    public static function collectionBalasan(iterable $items): array
    {
        $result = [];
        foreach ($items as $item) {
            $result[] = self::transformBalasan($item);
        }

        return $result;
    }
}
