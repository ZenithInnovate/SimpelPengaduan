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

namespace Modules\SimpelPengaduan\Http\Controllers\FrontEnd;

use Modules\SimpelPengaduan\Http\Requests\KirimPengaduanRequest;
use Modules\SimpelPengaduan\Http\Requests\TanggapiPengaduanRequest;
use Modules\SimpelPengaduan\Models\PengaduanModel;
use Modules\SimpelPengaduan\Services\PengaduanService;
use Web_Controller;

defined('BASEPATH') || exit('No direct script access allowed');

class PengaduanWargaController extends Web_Controller
{
    protected PengaduanService $service;

    public function __construct(?PengaduanService $service = null)
    {
        parent::__construct();
        $this->service = $service ?? new PengaduanService;
    }

    /**
     * Tampilan form pengaduan & pelacakan publik
     */
    public function index()
    {
        return view('simpel-pengaduan::frontend.form');
    }

    /**
     * Simpan pengaduan baru dari warga
     */
    public function store(KirimPengaduanRequest $request): void
    {
        $pengaduan = $this->service->simpanPengaduan(
            $request->validated(),
            $request->file('foto'),
            request()->ip()
        );

        json([
            'success' => true,
            'nomor_tiket' => $pengaduan->nomor_tiket,
            'message' => 'Laporan pengaduan Anda berhasil dikirim dengan nomor tiket '.$pengaduan->nomor_tiket.'. Simpan nomor tiket ini untuk melacak perkembangan penanganan.',
        ]);
    }

    /**
     * Lacak status tiket pengaduan
     */
    public function lacak(): void
    {
        $kataKunci = trim((string) request('kata_kunci'));

        if (empty($kataKunci)) {
            json([
                'success' => false,
                'message' => 'Silakan masukkan nomor tiket, NIK, atau nomor WhatsApp.',
            ], 422);

            return;
        }

        $pengaduan = $this->service->cariTiket($kataKunci);

        if (! $pengaduan) {
            json([
                'success' => false,
                'message' => 'Data pengaduan tidak ditemukan. Pastikan nomor tiket atau kontak sesuai.',
            ], 404);

            return;
        }

        $balasan = $pengaduan->child->map(function ($item) {
            return [
                'pengirim' => $item->nama,
                'isi' => $item->isi,
                'foto_url' => $item->foto_url,
                'waktu' => $item->created_at ? $item->created_at->format('d M Y H:i') : '',
            ];
        });

        json([
            'success' => true,
            'data' => [
                'id' => $pengaduan->id,
                'nomor_tiket' => $pengaduan->nomor_tiket,
                'judul' => $pengaduan->judul,
                'isi' => $pengaduan->isi,
                'pelapor' => $pengaduan->nama,
                'status' => $pengaduan->status_label,
                'status_badge' => $pengaduan->status_badge,
                'foto_url' => $pengaduan->foto_url,
                'tgl_lapor' => $pengaduan->created_at ? $pengaduan->created_at->format('d M Y H:i') : '',
                'balasan' => $balasan,
            ],
        ]);
    }

    /**
     * Warga mengirim balasan lanjutan pada tiket pengaduan
     */
    public function balas(int $id, TanggapiPengaduanRequest $request): void
    {
        $parent = PengaduanModel::utama()->findOrFail($id);

        $balasan = $this->service->tanggapi(
            $parent,
            $request->validated(),
            $request->file('foto'),
            false
        );

        json([
            'success' => true,
            'message' => 'Balasan berhasil dikirim.',
            'data' => [
                'pengirim' => $balasan->nama,
                'isi' => $balasan->isi,
                'foto_url' => $balasan->foto_url,
                'waktu' => $balasan->created_at ? $balasan->created_at->format('d M Y H:i') : '',
            ],
        ]);
    }
}
