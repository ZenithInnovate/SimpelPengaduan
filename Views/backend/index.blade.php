@extends('admin.layouts.index')
@include('simpel-core::components.assets.style')
@include('simpel-core::components.page.header', [
    'title' => 'Pengaduan Warga',
    'sub' => 'Simpel Pengaduan',
    'breadcrumb' => ['Pengaduan Warga'],
])

@include('admin.layouts.components.asset_datatables')

@section('content')
    @include('admin.layouts.components.notifikasi')

    {{-- Widget Ringkasan Statistik --}}
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3>{{ number_format($metrics['total'] ?? 0) }}</h3>
                    <p>Total Pengaduan</p>
                </div>
                <div class="icon">
                    <i class="fa fa-comments"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3>{{ number_format($metrics['menunggu'] ?? 0) }}</h3>
                    <p>Menunggu Diproses</p>
                </div>
                <div class="icon">
                    <i class="fa fa-clock-o"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-blue">
                <div class="inner">
                    <h3>{{ number_format($metrics['diproses'] ?? 0) }}</h3>
                    <p>Sedang Diproses</p>
                </div>
                <div class="icon">
                    <i class="fa fa-cogs"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>{{ number_format($metrics['selesai'] ?? 0) }}</h3>
                    <p>Selesai Ditangani</p>
                </div>
                <div class="icon">
                    <i class="fa fa-check-circle"></i>
                </div>
            </div>
        </div>
    </div>

    @include('simpel-core::components.card.open', [
        'type' => 'box-primary',
        'row' => true,
    ])
        <div class="row mepet">
            <div class="col-md-3">
                <label class="control-label">Filter Status</label>
                <select id="filter-status" class="form-control input-sm">
                    <option value="">— Semua Status —</option>
                    <option value="1">Menunggu Diproses</option>
                    <option value="2">Sedang Diproses</option>
                    <option value="3">Selesai Diproses</option>
                </select>
            </div>
        </div>
        <hr class="batas">

        <div class="table-responsive">
            @include('simpel-core::components.table.datatable', [
                'columns' => [
                    ['label' => 'No', 'class' => 'padat'],
                    ['label' => 'Aksi', 'class' => 'aksi'],
                    ['label' => 'No. Tiket', 'class' => 'padat'],
                    'Pelapor / Kontak',
                    'Laporan / Pengaduan',
                    ['label' => 'Status', 'class' => 'padat'],
                    ['label' => 'Waktu Masuk', 'class' => 'padat'],
                ],
            ])
        </div>
    @include('simpel-core::components.card.close', ['row' => true])
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        @include('simpel-core::components.js.datatable', [
            'var' => 'TablePengaduan',
            'url' => site_url('simpel/pengaduan/datatables'),
            'ajaxData' => 'd.status = $("#filter-status").val();',
            'columns' => [
                ['data' => 'DT_RowIndex', 'class' => 'padat', 'orderable' => false, 'searchable' => false],
                ['data' => 'aksi', 'class' => 'aksi', 'orderable' => false, 'searchable' => false],
                ['data' => 'tiket', 'name' => 'id', 'class' => 'padat'],
                ['data' => 'pelapor', 'name' => 'nama'],
                ['data' => 'laporan', 'name' => 'judul'],
                ['data' => 'status_badge', 'name' => 'status', 'class' => 'padat'],
                ['data' => 'tgl_lapor', 'name' => 'created_at', 'class' => 'padat'],
            ],
            'order' => [[6, 'desc']],
            'after' => '$("#filter-status").on("change", function () { TablePengaduan.draw(); });',
        ])

        @include('simpel-core::components.js.konfirmasi', [
            'table' => 'TablePengaduan',
            'massal' => false,
        ])
    });
</script>
@endpush
