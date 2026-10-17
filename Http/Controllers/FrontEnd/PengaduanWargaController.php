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

use Modules\SimpelCore\Http\Controllers\WebModulController;
use Modules\SimpelCore\Traits\RateLimitTrait;
use Modules\SimpelPengaduan\Http\Requests\KirimPengaduanRequest;
use Modules\SimpelPengaduan\Http\Requests\TanggapiPengaduanRequest;
use Modules\SimpelPengaduan\Models\PengaduanModel;
use Modules\SimpelPengaduan\Services\PengaduanService;
use Modules\SimpelPengaduan\Transforms\PengaduanTransform;

defined('BASEPATH') || exit('No direct script access allowed');

class PengaduanWargaController extends WebModulController
{
    use RateLimitTrait;

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

        $this->ensureMinuteRateLimited('sp_lacak', 10);
        $this->ensureNotRateLimited('sp_lacak', 200);

        $pengaduan = $this->service->cariTiket($kataKunci, trim((string) request('verifikasi')));

        if (! $pengaduan instanceof PengaduanModel) {
            json([
                'success' => false,
                'message' => 'Data pengaduan tidak ditemukan. Jika memakai nomor tiket, sertakan NIK atau nomor WhatsApp pelapor.',
            ], 404);

            return;
        }

        json([
            'success' => true,
            'data' => PengaduanTransform::transformDetail($pengaduan),
        ]);
    }

    /**
     * Warga mengirim balasan lanjutan pada tiket pengaduan
     */
    public function balas(int $id, TanggapiPengaduanRequest $request): void
    {
        $parent = PengaduanModel::utama()->findOrFail($id);

        // IDOR guard: verifikasi NIK/WA terhadap pemilik tiket (nomor tiket saja tidak cukup)
        $this->ensureMinuteRateLimited('sp_balas', 10);
        if (! $parent->isCocokKredensial(request('kata_kunci'))) {
            json([
                'success' => false,
                'message' => 'NIK atau nomor WhatsApp tidak sesuai dengan pengaduan ini.',
            ], 403);

            return;
        }

        $balasan = $this->service->tanggapi(
            $parent,
            $request->validated(),
            $request->file('foto'),
            false
        );

        json([
            'success' => true,
            'message' => 'Balasan berhasil dikirim.',
            'data' => $balasan->toBalasanArray(),
        ]);
    }
}
