@extends('simpel-pengaduan::frontend.layouts.app')

@section('title', 'Layanan Pengaduan & Aspirasi Warga')

@section('content')
<div class="space-y-8">
    {{-- Hero Title --}}
    <div class="text-center space-y-2">
        <h1 class="text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
            Pusat Pengaduan & Aspirasi Warga
        </h1>
        <p class="text-slate-600 text-sm sm:text-base max-w-2xl mx-auto">
            Sampaikan keluhan, masukan, atau laporan pelayanan publik desa secara cepat. Pantau langsung tahapan tindak lanjutnya melalui nomor tiket.
        </p>
    </div>

    {{-- Navigasi Tab --}}
    <div class="flex justify-center">
        <div class="inline-flex p-1 bg-slate-200/80 rounded-xl">
            <button type="button" id="tab-btn-lapor" onclick="switchTab('lapor')"
                class="px-5 py-2.5 rounded-lg text-xs sm:text-sm font-bold transition shadow-sm bg-white text-brand-700">
                <i class="fa fa-pencil-square-o mr-1.5"></i> Sampaikan Pengaduan
            </button>
            <button type="button" id="tab-btn-lacak" onclick="switchTab('lacak')"
                class="px-5 py-2.5 rounded-lg text-xs sm:text-sm font-bold transition text-slate-600 hover:text-slate-900">
                <i class="fa fa-search mr-1.5"></i> Lacak Progres Tiket
            </button>
        </div>
    </div>

    {{-- TAB 1: FORM PENGADUAN --}}
    <div id="tab-content-lapor" class="block">
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 sm:p-10 space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <h2 class="text-lg font-bold text-slate-900">Formulir Laporan Pengaduan</h2>
                <p class="text-xs text-slate-500">Isi data identitas pelapor dan keterangan laporan dengan jelas.</p>
            </div>

            <form id="form-pengaduan" enctype="multipart/form-data" class="space-y-5">
                <div class="grid sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Nama Lengkap <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="nama" required maxlength="100" placeholder="Contoh: Budi Santoso"
                            class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            NIK (Nomor Induk Kependudukan)
                        </label>
                        <input type="text" name="nik" maxlength="20" placeholder="16 digit NIK (opsional)"
                            class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition">
                    </div>
                </div>

                <div class="grid sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Nomor WhatsApp / Telepon <span class="text-rose-500">*</span>
                        </label>
                        <input type="tel" name="telepon" required maxlength="20" placeholder="08xxxxxxxxxx (untuk konfirmasi & notifikasi)"
                            class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Alamat Email
                        </label>
                        <input type="email" name="email" maxlength="100" placeholder="nama@email.com (opsional)"
                            class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Judul Laporan / Pengaduan <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="judul" required maxlength="255" placeholder="Ringkasan inti laporan Anda"
                        class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Rincian Pengaduan / Keluhan <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="isi" rows="5" required placeholder="Jelaskan secara detail masalah, lokasi kejadian, dan informasi penting lainnya..."
                        class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Lampiran Foto Bukti / Kondisi Lapangan (Opsional)
                    </label>
                    <input type="file" name="foto" accept="image/*"
                        class="w-full border border-slate-300 rounded-xl px-4 py-2 text-sm file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100">
                    <p class="text-xs text-slate-500 mt-1">Format: JPG, PNG, WEBP. Maksimal 3 MB.</p>
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-end">
                    <button type="submit" id="btn-submit-lapor"
                        class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-bold text-white bg-brand-600 hover:bg-brand-700 shadow-md hover:shadow-lg transition">
                        <i class="fa fa-paper-plane"></i> Kirim Laporan Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- TAB 2: LACAK PROGRES TIKET --}}
    <div id="tab-content-lacak" class="hidden space-y-6">
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 sm:p-8">
            <h2 class="text-lg font-bold text-slate-900 mb-2">Lacak Status & Riwayat Tanggapan</h2>
            <p class="text-xs text-slate-500 mb-4">Masukkan nomor tiket pengaduan (misal: LPR-202609-0001) atau nomor WhatsApp yang Anda daftarkan saat melapor.</p>

            <form id="form-lacak" class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <i class="fa fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" id="input-kata-kunci" required placeholder="Nomor Tiket / Nomor WhatsApp / NIK..."
                        class="w-full border border-slate-300 rounded-xl pl-11 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition">
                </div>
                <button type="submit" id="btn-submit-lacak"
                    class="px-6 py-2.5 rounded-xl font-bold text-white bg-slate-800 hover:bg-slate-900 transition flex items-center justify-center gap-2">
                    <i class="fa fa-search"></i> Lacak Tiket
                </button>
            </form>
        </div>

        {{-- Wadah Hasil Pencarian Tiket --}}
        <div id="wadah-hasil-lacak" class="hidden space-y-6">
            {{-- Kartu Info Laporan --}}
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 sm:p-8 space-y-4">
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 pb-4">
                    <div>
                        <span id="hasil-tiket" class="px-3 py-1 rounded-lg text-xs font-bold bg-brand-100 text-brand-800 tracking-wider"></span>
                        <h3 id="hasil-judul" class="text-lg font-extrabold text-slate-900 mt-2"></h3>
                        <p class="text-xs text-slate-500">
                            Pelapor: <strong id="hasil-pelapor"></strong> &bull; Waktu: <span id="hasil-waktu"></span>
                        </p>
                    </div>
                    <div>
                        <span id="hasil-status" class="px-3 py-1.5 rounded-full text-xs font-bold"></span>
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="text-sm text-slate-700 leading-relaxed whitespace-pre-line" id="hasil-isi"></div>
                    <div id="hasil-lampiran" class="hidden pt-2">
                        <p class="text-xs font-bold text-slate-500 mb-1"><i class="fa fa-paperclip"></i> Lampiran Bukti:</p>
                        <a id="hasil-foto-link" href="" target="_blank">
                            <img id="hasil-foto-img" src="" alt="Bukti Foto" class="max-h-60 rounded-xl border border-slate-200 object-cover shadow-sm">
                        </a>
                    </div>
                </div>
            </div>

            {{-- Riwayat Tanggapan (Thread) --}}
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 sm:p-8 space-y-4">
                <h4 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3">
                    <i class="fa fa-comments text-brand-600 mr-1.5"></i> Riwayat Utas Tanggapan & Tindak Lanjut
                </h4>

                <div id="wadah-utas-balasan" class="space-y-4">
                    {{-- Diisi secara dinamis via JS --}}
                </div>

                {{-- Form Balas dari Warga --}}
                <div class="border-t border-slate-100 pt-5 mt-6">
                    <h5 class="text-sm font-bold text-slate-800 mb-2">Kirim Balasan / Pertanyaan Lanjutan:</h5>
                    <form id="form-balas-warga" class="space-y-3">
                        <input type="hidden" id="balas-pengaduan-id" value="">
                        <textarea id="balas-isi" rows="3" required placeholder="Tulis tanggapan atau pertanyaan lanjutan di sini..."
                            class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 transition"></textarea>
                        <div class="flex justify-end">
                            <button type="submit" id="btn-submit-balas"
                                class="px-5 py-2 rounded-xl text-xs font-bold text-white bg-brand-600 hover:bg-brand-700 transition flex items-center gap-1.5">
                                <i class="fa fa-reply"></i> Kirim Balasan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function switchTab(tab) {
        if (tab === 'lapor') {
            $('#tab-content-lapor').removeClass('hidden');
            $('#tab-content-lacak').addClass('hidden');
            $('#tab-btn-lapor').addClass('bg-white text-brand-700 shadow-sm').removeClass('text-slate-600');
            $('#tab-btn-lacak').removeClass('bg-white text-brand-700 shadow-sm').addClass('text-slate-600');
        } else {
            $('#tab-content-lapor').addClass('hidden');
            $('#tab-content-lacak').removeClass('hidden');
            $('#tab-btn-lacak').addClass('bg-white text-brand-700 shadow-sm').removeClass('text-slate-600');
            $('#tab-btn-lapor').removeClass('bg-white text-brand-700 shadow-sm').addClass('text-slate-600');
        }
    }

    $(document).ready(function () {
        // Kirim Pengaduan Baru
        $('#form-pengaduan').on('submit', function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            var $btn = $('#btn-submit-lapor');

            $btn.prop('disabled', true); // disable tombol, tanpa spinner

            // Debounced loading: alert menunggu hanya tampil bila proses >= 1 detik
            var loadingTimer = setTimeout(function () {
                Swal.fire({
                    title: 'Mengirim Laporan',
                    text: 'Mohon tunggu, laporan Anda sedang diproses...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: function () { Swal.showLoading(); }
                });
            }, 1000);

            $.ajax({
                url: '{{ site_url("layanan-pengaduan/kirim") }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function (res) {
                    clearTimeout(loadingTimer);
                    Swal.fire({
                        icon: 'success',
                        title: 'Laporan Berhasil Terkirim!',
                        html: '<p>' + res.message + '</p><div class="mt-4 p-3 bg-slate-100 rounded-xl font-mono text-base font-bold text-slate-800">' + res.nomor_tiket + '</div>',
                        confirmButtonText: 'Lacak Tiket Ini',
                        showCancelButton: true,
                        cancelButtonText: 'Tutup'
                    }).then(function (result) {
                        $('#form-pengaduan')[0].reset();
                        if (result.isConfirmed) {
                            switchTab('lacak');
                            $('#input-kata-kunci').val(res.nomor_tiket);
                            $('#form-lacak').submit();
                        }
                    });
                },
                error: function (xhr) {
                    clearTimeout(loadingTimer);
                    var pesan = 'Terjadi kesalahan saat mengirim pengaduan.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        pesan = xhr.responseJSON.message;
                    }
                    Swal.fire('Gagal', pesan, 'error');
                },
                complete: function () {
                    $btn.prop('disabled', false);
                }
            });
        });

        // Lacak Pengaduan
        $('#form-lacak').on('submit', function (e) {
            e.preventDefault();
            var kataKunci = $('#input-kata-kunci').val().trim();
            if (!kataKunci) return;

            var $btn = $('#btn-submit-lacak');
            $btn.prop('disabled', true); // disable tombol, tanpa spinner

            var loadingTimer = setTimeout(function () {
                Swal.fire({
                    title: 'Mencari Tiket',
                    text: 'Mohon tunggu, data pengaduan sedang dicari...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: function () { Swal.showLoading(); }
                });
            }, 1000);

            $.ajax({
                url: '{{ site_url("layanan-pengaduan/lacak") }}',
                type: 'GET',
                data: { kata_kunci: kataKunci },
                success: function (res) {
                    clearTimeout(loadingTimer);
                    if (Swal.isVisible() && Swal.isLoading()) { Swal.close(); }
                    if (res.success && res.data) {
                        var d = res.data;
                        $('#balas-pengaduan-id').val(d.id);
                        $('#hasil-tiket').text(d.nomor_tiket);
                        $('#hasil-judul').text(d.judul);
                        $('#hasil-pelapor').text(d.pelapor);
                        $('#hasil-waktu').text(d.tgl_lapor);
                        $('#hasil-isi').text(d.isi);

                        var badgeClass = 'bg-amber-100 text-amber-800';
                        if (d.status_badge === 'info') badgeClass = 'bg-blue-100 text-blue-800';
                        if (d.status_badge === 'success') badgeClass = 'bg-emerald-100 text-emerald-800';
                        $('#hasil-status').attr('class', 'px-3 py-1.5 rounded-full text-xs font-bold ' + badgeClass).text(d.status);

                        if (d.foto_url) {
                            $('#hasil-foto-link').attr('href', d.foto_url);
                            $('#hasil-foto-img').attr('src', d.foto_url);
                            $('#hasil-lampiran').removeClass('hidden');
                        } else {
                            $('#hasil-lampiran').addClass('hidden');
                        }

                        renderUtas(d.balasan);
                        $('#wadah-hasil-lacak').removeClass('hidden');
                    }
                },
                error: function (xhr) {
                    clearTimeout(loadingTimer);
                    var pesan = 'Data pengaduan tidak ditemukan.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        pesan = xhr.responseJSON.message;
                    }
                    Swal.fire('Informasi', pesan, 'info');
                    $('#wadah-hasil-lacak').addClass('hidden');
                },
                complete: function () {
                    $btn.prop('disabled', false);
                }
            });
        });

        function renderUtas(balasanList) {
            var $box = $('#wadah-utas-balasan').empty();
            if (!balasanList || balasanList.length === 0) {
                $box.html('<p class="text-xs text-slate-400 italic py-2">Belum ada balasan dari petugas atau pemerintah desa.</p>');
                return;
            }

            balasanList.forEach(function (b) {
                var html = '<div class="p-4 rounded-xl border border-slate-200 bg-slate-50 space-y-1.5">' +
                    '<div class="flex items-center justify-between text-xs">' +
                        '<strong class="text-slate-900">' + b.pengirim + '</strong>' +
                        '<span class="text-slate-400">' + b.waktu + '</span>' +
                    '</div>' +
                    '<p class="text-xs text-slate-700 whitespace-pre-line">' + b.isi + '</p>' +
                '</div>';
                $box.append(html);
            });
        }

        // Balas Pengaduan dari Warga
        $('#form-balas-warga').on('submit', function (e) {
            e.preventDefault();
            var id = $('#balas-pengaduan-id').val();
            var isi = $('#balas-isi').val().trim();
            var kataKunciBalas = $('#input-kata-kunci').val().trim();
            if (!id || !isi) return;

            var $btn = $('#btn-submit-balas');
            $btn.prop('disabled', true); // disable tombol, tanpa spinner

            var loadingTimer = setTimeout(function () {
                Swal.fire({
                    title: 'Mengirim Balasan',
                    text: 'Mohon tunggu...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: function () { Swal.showLoading(); }
                });
            }, 1000);

            $.ajax({
                url: '{{ site_url("layanan-pengaduan/tanggapi") }}/' + id,
                type: 'POST',
                data: { isi: isi, kata_kunci: kataKunciBalas },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function (res) {
                    clearTimeout(loadingTimer);
                    $('#balas-isi').val('');
                    if (res.data) {
                        var html = '<div class="p-4 rounded-xl border border-slate-200 bg-slate-50 space-y-1.5">' +
                            '<div class="flex items-center justify-between text-xs">' +
                                '<strong class="text-slate-900">' + res.data.pengirim + '</strong>' +
                                '<span class="text-slate-400">' + res.data.waktu + '</span>' +
                            '</div>' +
                            '<p class="text-xs text-slate-700 whitespace-pre-line">' + res.data.isi + '</p>' +
                        '</div>';
                        $('#wadah-utas-balasan').append(html);
                    }
                    Swal.fire('Terkirim', 'Balasan Anda berhasil dikirim ke petugas desa.', 'success');
                },
                error: function (xhr) {
                    clearTimeout(loadingTimer);
                    var pesan = 'Gagal mengirim balasan.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        pesan = xhr.responseJSON.message;
                    }
                    Swal.fire('Gagal', pesan, 'error');
                },
                complete: function () {
                    $btn.prop('disabled', false);
                }
            });
        });
    });
</script>
@endpush
