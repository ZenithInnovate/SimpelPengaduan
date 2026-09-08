@extends('admin.layouts.index')
@include('simpel-core::components.assets.style')
@include('simpel-core::components.page.header', [
    'title' => 'Detail & Utas Pengaduan',
    'sub' => 'Simpel Pengaduan',
    'breadcrumb' => [
        ['url' => site_url('simpel/pengaduan'), 'label' => 'Pengaduan'],
        'Detail Tiket ' . $pengaduan->nomor_tiket
    ],
])

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="row">
        {{-- Kolom Kiri: Profil Pelapor & Status --}}
        <div class="col-md-4">
            @include('simpel-core::components.card.open', [
                'type' => 'box-primary',
                'title' => 'Informasi Laporan',
                'icon' => 'fa-info-circle',
                'bodyClass' => 'box-profile',
            ])
                    <div class="text-center" style="margin-bottom: 15px;">
                        <span class="badge label-primary" style="font-size: 16px; padding: 6px 12px;">{{ $pengaduan->nomor_tiket }}</span>
                        <div style="margin-top: 10px;">
                            <span class="label label-{{ $pengaduan->status_badge }}" style="font-size: 13px;">
                                {{ $pengaduan->status_label }}
                            </span>
                        </div>
                    </div>

                    <ul class="list-group list-group-unbordered">
                        <li class="list-group-item">
                            <b>Nama Pelapor</b> <span class="pull-right">{{ $pengaduan->nama }}</span>
                        </li>
                        <li class="list-group-item">
                            <b>NIK</b> <span class="pull-right">{{ $pengaduan->nik ?: '-' }}</span>
                        </li>
                        <li class="list-group-item">
                            <b>No. Telepon / WA</b>
                            <span class="pull-right">
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $pengaduan->telepon) }}" target="_blank" class="text-success">
                                    <i class="fa fa-whatsapp"></i> {{ $pengaduan->telepon }}
                                </a>
                            </span>
                        </li>
                        <li class="list-group-item">
                            <b>Email</b> <span class="pull-right">{{ $pengaduan->email ?: '-' }}</span>
                        </li>
                        <li class="list-group-item">
                            <b>Waktu Pengaduan</b> <span class="pull-right">{{ $pengaduan->created_at ? $pengaduan->created_at->format('d/m/Y H:i') : '-' }}</span>
                        </li>
                        <li class="list-group-item">
                            <b>Alamat IP</b> <span class="pull-right text-muted">{{ $pengaduan->ip_address ?: '-' }}</span>
                        </li>
                    </ul>

                    {{-- Form Cepat Perbarui Status --}}
                    <form action="{{ site_url('simpel/pengaduan/status/' . $pengaduan->id) }}" method="post">
                        @include('simpel-core::components.form.csrf')
                        <div class="form-group" style="margin-top: 15px;">
                            <label class="control-label">Perbarui Status Penanganan:</label>
                            <div class="input-group">
                                <select name="status" class="form-control input-sm">
                                    @foreach (\Modules\SimpelPengaduan\Enums\StatusPengaduanEnum::cases() as $st)
                                        <option value="{{ $st->value }}" @selected($pengaduan->status == $st->value)>{{ $st->label() }}</option>
                                    @endforeach
                                </select>
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Ubah</button>
                                </span>
                            </div>
                        </div>
                    </form>

                    <div style="margin-top: 15px;">
                        @include('simpel-core::components.buttons.kembali', [
                            'url' => site_url('simpel/pengaduan'),
                            'label' => 'Daftar Pengaduan',
                        ])
                    </div>
            @include('simpel-core::components.card.close')
        </div>

        {{-- Kolom Kanan: Timeline Utas & Form Balas --}}
        <div class="col-md-8">
            @include('simpel-core::components.card.open', [
                'type' => 'box-solid',
                'title' => $pengaduan->judul,
                'icon' => 'fa-bullhorn text-primary',
            ])
                    {{-- Timeline Utas Percakapan --}}
                    <ul class="timeline">
                        {{-- Pengaduan Awal (Root) --}}
                        <li class="time-label">
                            <span class="bg-blue">
                                {{ $pengaduan->created_at ? $pengaduan->created_at->format('d M Y') : 'Hari ini' }}
                            </span>
                        </li>
                        <li>
                            <i class="fa fa-user bg-aqua"></i>
                            <div class="timeline-item">
                                <span class="time"><i class="fa fa-clock-o"></i> {{ $pengaduan->created_at ? $pengaduan->created_at->format('H:i') : '' }}</span>
                                <h3 class="timeline-header"><strong>{{ $pengaduan->nama }}</strong> menyampaikan laporan:</h3>
                                <div class="timeline-body">
                                    {!! nl2br(e($pengaduan->isi)) !!}

                                    @if ($pengaduan->foto_url)
                                        <div style="margin-top: 15px;">
                                            <p class="text-muted" style="margin-bottom: 5px;"><i class="fa fa-paperclip"></i> <strong>Lampiran Foto:</strong></p>
                                            <a href="{{ $pengaduan->foto_url }}" target="_blank">
                                                <img src="{{ $pengaduan->foto_url }}" alt="Lampiran" class="img-responsive img-thumbnail" style="max-height: 250px;">
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </li>

                        {{-- Tanggapan-Tanggapan Berutas --}}
                        @foreach ($pengaduan->child as $balasan)
                            <li>
                                @if (empty($balasan->nik))
                                    <i class="fa fa-institution bg-green"></i>
                                    <div class="timeline-item" style="border-left: 3px solid #00a65a;">
                                @else
                                    <i class="fa fa-comment bg-yellow"></i>
                                    <div class="timeline-item">
                                @endif
                                    <span class="time"><i class="fa fa-clock-o"></i> {{ $balasan->created_at ? $balasan->created_at->format('d M Y H:i') : '' }}</span>
                                    <h3 class="timeline-header">
                                        <strong>{{ $balasan->nama }}</strong>
                                        @if (empty($balasan->nik))
                                            <span class="label label-success" style="font-size: 10px; margin-left: 5px;">Pemerintah Desa</span>
                                        @else
                                            <span class="label label-warning" style="font-size: 10px; margin-left: 5px;">Pelapor</span>
                                        @endif
                                    </h3>
                                    <div class="timeline-body">
                                        {!! nl2br(e($balasan->isi)) !!}

                                        @if ($balasan->foto_url)
                                            <div style="margin-top: 10px;">
                                                <a href="{{ $balasan->foto_url }}" target="_blank">
                                                    <img src="{{ $balasan->foto_url }}" alt="Lampiran Balasan" class="img-responsive img-thumbnail" style="max-height: 200px;">
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </li>
                        @endforeach

                        <li>
                            <i class="fa fa-clock-o bg-gray"></i>
                        </li>
                    </ul>
            @include('simpel-core::components.card.close')

            {{-- Form Tanggapan Baru Admin --}}
            @include('simpel-core::components.card.open', [
                'type' => 'box-info',
                'title' => 'Berikan Tanggapan / Tindak Lanjut',
                'icon' => 'fa-reply',
                'noBody' => true,
            ])
                <form action="{{ site_url('simpel/pengaduan/tanggapi/' . $pengaduan->id) }}" method="post" enctype="multipart/form-data">
                    @include('simpel-core::components.form.csrf')
                    <div class="box-body">
                        @include('simpel-core::components.form.field', [
                            'type'        => 'textarea',
                            'name'        => 'isi',
                            'label'       => 'Isi Tanggapan / Tindak Lanjut',
                            'required'    => true,
                            'rows'        => 4,
                            'placeholder' => 'Tuliskan keterangan tanggapan atau solusi penanganan...',
                            'horizontal'  => false,
                        ])

                        <div class="row">
                            <div class="col-md-6">
                                @include('simpel-core::components.form.field', [
                                    'type'        => 'select',
                                    'name'        => 'status',
                                    'label'       => 'Perbarui Status Sekaligus:',
                                    'horizontal'  => false,
                                    'emptyOption' => '— Biarkan Status Saat Ini (' . $pengaduan->status_label . ') —',
                                    'options'     => [
                                        \Modules\SimpelPengaduan\Enums\StatusPengaduanEnum::DIPROSES->value => 'Set ke: ' . \Modules\SimpelPengaduan\Enums\StatusPengaduanEnum::DIPROSES->label(),
                                        \Modules\SimpelPengaduan\Enums\StatusPengaduanEnum::SELESAI->value => 'Set ke: ' . \Modules\SimpelPengaduan\Enums\StatusPengaduanEnum::SELESAI->label(),
                                    ],
                                ])
                            </div>
                            <div class="col-md-6">
                                @include('simpel-core::components.form.field', [
                                    'type'        => 'file',
                                    'name'        => 'foto',
                                    'label'       => 'Lampiran Foto Pendukung (Opsional):',
                                    'horizontal'  => false,
                                    'attr'        => 'accept="image/*"',
                                    'hint'        => 'Maksimal 3 MB (JPG/PNG/WEBP).',
                                ])
                            </div>
                        </div>
                    </div>
                    <div class="box-footer text-right">
                        @include('simpel-core::components.buttons.simpan', [
                            'label' => 'Kirim Tanggapan',
                            'icon' => 'fa-paper-plane',
                            'color' => 'btn-success',
                        ])
                    </div>
                </form>
            @include('simpel-core::components.card.close', ['noBody' => true])
        </div>
    </div>
@endsection
