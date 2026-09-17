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
        $query = $this->service->queryPengaduan($status, request('search.value'));

        return datatables()->of($query)
            ->addIndexColumn()
            ->addColumn('tiket', static fn (PengaduanModel $row): string => '<span class="badge label-primary">'.$row->nomor_tiket.'</span>')
            ->addColumn('pelapor', static function (PengaduanModel $row): string {
                $nik = $row->nik ? '<br><small class="text-muted">NIK: '.e($row->nik).'</small>' : '';

                return '<strong>'.e($row->nama).'</strong>'.$nik.'<br><small><i class="fa fa-whatsapp text-success"></i> '.e($row->telepon).'</small>';
            })
            ->addColumn('laporan', static function (PengaduanModel $row): string {
                $badge = $row->foto ? ' <span class="badge label-default"><i class="fa fa-paperclip"></i> Foto</span>' : '';

                return '<strong>'.e($row->judul).'</strong>'.$badge.'<p class="text-muted mb-0" style="font-size: 12px;">'.e(str_limit($row->isi, 80)).'</p>';
            })
            ->addColumn('status_badge', static fn (PengaduanModel $row): string => '<span class="label label-'.$row->status_badge.'">'.$row->status_label.'</span>')
            ->addColumn('tgl_lapor', static fn (PengaduanModel $row): string => $row->created_at ? $row->created_at->format('d/m/Y H:i') : '-')
            ->addColumn('aksi', static fn (PengaduanModel $row): string => self::renderAksi($row))
            ->rawColumns(['tiket', 'pelapor', 'laporan', 'status_badge', 'aksi'])
            ->make();
    }

    /**
     * Render tombol aksi pada baris tabel pengaduan.
     */
    private static function renderAksi(PengaduanModel $row): string
    {
        $btnDetail = View::make('simpel-core::components.buttons.actions.detail', [
            'url' => simpel_url('simpel/pengaduan/detail/'.$row->id),
            'icon' => 'fa-comments',
            'title' => 'Utas & Tanggapi',
        ])->render();
        $btnDelete = View::make('simpel-core::components.buttons.actions.delete', [
            'id' => $row->id,
            'url' => simpel_url('simpel/pengaduan/delete'),
        ])->render();

        return '<div class="btn-group">'.$btnDetail.' '.$btnDelete.'</div>';
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
            'message' => simpel_message('simpel-pengaduan::messages.response_success'),
            'redirect_url' => simpel_url('simpel/pengaduan/detail/'.$id),
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
            'message' => simpel_message('simpel-pengaduan::messages.update_status_success'),
        ]);
    }

    /**
     * Hapus pengaduan
     */
    public function destroy()
    {
        isCanJson('h');

        $ids = (array) (request('ids') ?: request('id'));
        $id = (int) ($ids[0] ?? 0);
        $pengaduan = PengaduanModel::utama()->findOrFail($id);

        $this->service->hapus($pengaduan);

        return json([
            'status' => 'success',
            'message' => simpel_message('simpel-core::messages.delete_success'),
        ]);
    }
}
