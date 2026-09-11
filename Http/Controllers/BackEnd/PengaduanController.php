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

namespace Modules\SimpelPengaduan\Http\Controllers\BackEnd;

use Illuminate\Support\Facades\View;
use Illuminate\View\View as ViewResponse;
use Modules\SimpelCore\Http\Controllers\AdminModulController;
use Modules\SimpelPengaduan\Http\Requests\TanggapiPengaduanRequest;
use Modules\SimpelPengaduan\Models\PengaduanModel;
use Modules\SimpelPengaduan\Services\PengaduanService;

defined('BASEPATH') || exit('No direct script access allowed');

class PengaduanController extends AdminModulController
{
    protected bool $shareModuleDetail = true;

    public $modul_ini = 'simpel-pengaduan';

    public $sub_modul_ini = 'simpel-pengaduan';

    protected PengaduanService $service;

    public function __construct(?PengaduanService $service = null)
    {
        parent::__construct();
        $this->service = $service ?? new PengaduanService;
        isCan('b');
    }

    /**
     * Tampilan utama daftar pengaduan warga
     */
    public function index(): ViewResponse
    {
        $metrics = $this->service->getMetrics();

        return view('simpel-pengaduan::backend.index', [
            'metrics' => $metrics,
        ]);
    }

    /**
     * Sumber data server-side DataTables
     */
    public function datatables(): JsonResponse
    {
        $status = request()->filled('status') ? (int) request('status') : null;
        $search = request('search.value');

        $query = $this->service->queryPengaduan($status, $search);

        return datatables()->of($query)
            ->addIndexColumn()
            ->addColumn('tiket', static function (PengaduanModel $row): string {
                return '<span class="badge label-primary">'.$row->nomor_tiket.'</span>';
            })
            ->addColumn('pelapor', static function (PengaduanModel $row): string {
                $kontak = e($row->telepon);
                $nama = e($row->nama);
                $nik = $row->nik ? '<br><small class="text-muted">NIK: '.e($row->nik).'</small>' : '';

                return '<strong>'.$nama.'</strong>'.$nik.'<br><small><i class="fa fa-whatsapp text-success"></i> '.$kontak.'</small>';
            })
            ->addColumn('laporan', static function (PengaduanModel $row): string {
                $judul = '<strong>'.e($row->judul).'</strong>';
                $ringkasan = '<p class="text-muted mb-0" style="font-size: 12px;">'.e(str_limit($row->isi, 80)).'</p>';
                $badgeLampiran = $row->foto ? ' <span class="badge label-default"><i class="fa fa-paperclip"></i> Foto</span>' : '';

                return $judul.$badgeLampiran.$ringkasan;
            })
            ->addColumn('status_badge', static function (PengaduanModel $row): string {
                return '<span class="label label-'.$row->status_badge.'">'.$row->status_label.'</span>';
            })
            ->addColumn('tgl_lapor', static function (PengaduanModel $row): string {
                return $row->created_at ? $row->created_at->format('d/m/Y H:i') : '-';
            })
            ->addColumn('aksi', static function (PengaduanModel $row): string {
                $btnDetail = View::make('simpel-core::components.buttons.actions.detail', [
                    'url' => site_url('simpel/pengaduan/detail/'.$row->id),
                    'icon' => 'fa-comments',
                    'title' => 'Utas & Tanggapi',
                ])->render();
                $btnDelete = View::make('simpel-core::components.buttons.actions.delete', [
                    'id' => $row->id,
                    'url' => site_url('simpel/pengaduan/delete'),
                ])->render();

                return '<div class="btn-group">'.$btnDetail.' '.$btnDelete.'</div>';
            })
            ->rawColumns(['tiket', 'pelapor', 'laporan', 'status_badge', 'aksi'])
            ->make();
    }

    /**
     * Halaman detail utas percakapan & tanggapi
     */
    public function detail(int $id): ViewResponse
    {
        $pengaduan = PengaduanModel::utama()->with(['child'])->findOrFail($id);

        return view('simpel-pengaduan::backend.detail', [
            'pengaduan' => $pengaduan,
        ]);
    }

    /**
     * Proses kirim balasan dari admin desa
     */
    public function tanggapi(int $id, TanggapiPengaduanRequest $request)
    {
        isCanJson('u');

        $parent = PengaduanModel::utama()->findOrFail($id);
        $this->service->tanggapi($parent, $request->validated(), $request->file('foto'), true);

        return json([
            'status' => 'success',
            'message' => 'Tanggapan berhasil dikirim ke pelapor.',
            'redirect_url' => site_url('simpel/pengaduan/detail/'.$id),
        ]);
    }

    /**
     * Ubah status penanganan pengaduan secara cepat
     */
    public function ubahStatus(int $id)
    {
        isCanJson('u');

        $status = (int) request('status');
        $pengaduan = PengaduanModel::utama()->findOrFail($id);

        $this->service->ubahStatus($pengaduan, $status, true);

        return json([
            'status' => 'success',
            'message' => 'Status pengaduan berhasil diperbarui.',
        ]);
    }

    /**
     * Hapus pengaduan
     */
    public function delete()
    {
        isCanJson('h');

        $ids = (array) (request('ids') ?: request('id'));
        $id = (int) ($ids[0] ?? 0);
        $pengaduan = PengaduanModel::utama()->findOrFail($id);

        $this->service->hapus($pengaduan);

        return json([
            'status' => 'success',
            'message' => 'Pengaduan berhasil dihapus.',
        ]);
    }
}
