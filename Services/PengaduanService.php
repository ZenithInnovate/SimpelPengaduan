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

namespace Modules\SimpelPengaduan\Services;

use App\Models\SettingAplikasi;
use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Modules\SimpelPengaduan\Enums\StatusPengaduanEnum;
use Modules\SimpelPengaduan\Models\PengaduanModel;

class PengaduanService
{
    /**
     * Hitung metrik statistik pengaduan
     */
    public function getMetrics(): array
    {
        return [
            'total' => PengaduanModel::utama()->count(),
            'menunggu' => PengaduanModel::utama()->where('status', StatusPengaduanEnum::MENUNGGU->value)->count(),
            'diproses' => PengaduanModel::utama()->where('status', StatusPengaduanEnum::DIPROSES->value)->count(),
            'selesai' => PengaduanModel::utama()->where('status', StatusPengaduanEnum::SELESAI->value)->count(),
        ];
    }

    /**
     * Dapatkan query data pengaduan utama
     */
    public function queryPengaduan(?int $status = null, ?string $search = null)
    {
        return PengaduanModel::utama()
            ->with(['child'])
            ->filterStatus($status)
            ->cari($search)
            ->orderBy('created_at', 'desc');
    }

    /**
     * Simpan pengaduan baru dari warga
     */
    public function simpanPengaduan(array $data, $fileFoto = null, ?string $ipAddress = null): PengaduanModel
    {
        $namaFoto = null;
        if ($fileFoto && $fileFoto->isValid()) {
            $namaFoto = $this->uploadFoto($fileFoto);
        }

        $pengaduan = PengaduanModel::create([
            'id_pengaduan' => null,
            'nama' => trim($data['nama']),
            'nik' => ! empty($data['nik']) ? trim($data['nik']) : null,
            'telepon' => trim($data['telepon']),
            'email' => ! empty($data['email']) ? trim($data['email']) : null,
            'judul' => trim($data['judul']),
            'isi' => trim($data['isi']),
            'status' => StatusPengaduanEnum::MENUNGGU->value,
            'foto' => $namaFoto,
            'ip_address' => $ipAddress ?: request()->ip(),
        ]);

        $this->notifikasiPengaduanBaru($pengaduan);

        return $pengaduan;
    }

    /**
     * Berikan tanggapan / balasan pada tiket pengaduan
     */
    public function tanggapi(PengaduanModel $parent, array $data, $fileFoto = null, bool $isAdmin = true): PengaduanModel
    {
        $namaFoto = null;
        if ($fileFoto && $fileFoto->isValid()) {
            $namaFoto = $this->uploadFoto($fileFoto);
        }

        $namaPengirim = $isAdmin ? (auth()->user()->nama ?? 'Pemerintah Desa') : $parent->nama;

        $balasan = PengaduanModel::create([
            'id_pengaduan' => $parent->id,
            'nama' => $namaPengirim,
            'nik' => $isAdmin ? null : $parent->nik,
            'telepon' => $isAdmin ? null : $parent->telepon,
            'email' => $isAdmin ? null : $parent->email,
            'judul' => 'Re: '.$parent->judul,
            'isi' => trim($data['isi']),
            'status' => $parent->status,
            'foto' => $namaFoto,
            'ip_address' => request()->ip(),
        ]);

        // Perbarui status induk jika diminta oleh admin
        if ($isAdmin && ! empty($data['status'])) {
            $this->ubahStatus($parent, (int) $data['status'], false);
        }

        $this->notifikasiTanggapan($parent, $balasan, $isAdmin);

        return $balasan;
    }

    /**
     * Perbarui status tiket pengaduan
     */
    public function ubahStatus(PengaduanModel $pengaduan, int $status, bool $kirimNotif = true): bool
    {
        $updated = $pengaduan->update(['status' => $status]);

        if ($updated && $kirimNotif) {
            $statusEnum = StatusPengaduanEnum::fromValue($status);
            $namaStatus = $statusEnum ? $statusEnum->label() : 'Diperbarui';
            $pesan = "Halo {$pengaduan->nama},\n\nStatus pengaduan Anda dengan nomor tiket *{$pengaduan->nomor_tiket}* telah diperbarui menjadi: *{$namaStatus}*.\n\nJudul: {$pengaduan->judul}\n\nTerima kasih atas partisipasi Anda.";
            $this->kirimWhatsApp($pengaduan->telepon, $pesan);
        }

        return $updated;
    }

    /**
     * Hapus pengaduan beserta seluruh utas tanggapannya
     */
    public function hapus(PengaduanModel $pengaduan): bool
    {
        // Hapus balasan anak terlebih dahulu
        $pengaduan->child()->each(function ($anak) {
            $anak->delete();
        });

        return (bool) $pengaduan->delete();
    }

    /**
     * Cari pengaduan berdasarkan nomor tiket atau kata kunci
     */
    public function cariTiket(string $kataKunci): ?PengaduanModel
    {
        $id = simpel_pengaduan_id_from_tiket($kataKunci);

        if ($id) {
            $item = PengaduanModel::utama()->find($id);
            if ($item) {
                return $item;
            }
        }

        return PengaduanModel::utama()
            ->where(function ($q) use ($kataKunci) {
                $q->where('telepon', $kataKunci)
                    ->orWhere('nik', $kataKunci);
            })
            ->latest()
            ->first();
    }

    /**
     * Unggah berkas foto ke folder pengaduan
     */
    private function uploadFoto($file): string
    {
        $lokasiRelatif = defined('LOKASI_PENGADUAN') ? LOKASI_PENGADUAN : 'desa/upload/pengaduan/';
        $targetDir = FCPATH.$lokasiRelatif;

        if (! File::isDirectory($targetDir)) {
            File::makeDirectory($targetDir, 0755, true, true);
        }

        $ekstensi = $file->getClientOriginalExtension();
        $namaFile = 'pengaduan_'.date('YmdHis').'_'.Str::random(8).'.'.$ekstensi;
        $file->move($targetDir, $namaFile);

        return $namaFile;
    }

    /**
     * Notifikasi laporan pengaduan baru
     */
    private function notifikasiPengaduanBaru(PengaduanModel $pengaduan): void
    {
        // Notifikasi ke warga
        $pesanWarga = "Halo {$pengaduan->nama},\n\nTerima kasih telah menyampaikan laporan/pengaduan ke Pemerintah Desa.\n\n"
            ."Nomor Tiket: *{$pengaduan->nomor_tiket}*\n"
            ."Judul: {$pengaduan->judul}\n"
            ."Status: *Menunggu Diproses*\n\n"
            .'Simpan nomor tiket ini untuk melacak perkembangan penanganan pengaduan Anda.';
        $this->kirimWhatsApp($pengaduan->telepon, $pesanWarga);

        // Notifikasi ke petugas/admin jika dikonfigurasi
        $noAdmin = SettingAplikasi::where('key', 'sp_wa_admin_nomor')->value('value');
        if (! empty($noAdmin)) {
            $pesanAdmin = "🔔 *PENGADUAN WARGA BARU*\n\n"
                ."Nomor Tiket: *{$pengaduan->nomor_tiket}*\n"
                ."Pelapor: {$pengaduan->nama} ({$pengaduan->telepon})\n"
                ."Judul: {$pengaduan->judul}\n\n"
                .'Silakan periksa dashboard admin untuk menindaklanjuti.';
            $this->kirimWhatsApp($noAdmin, $pesanAdmin);
        }
    }

    /**
     * Notifikasi saat ada tanggapan baru
     */
    private function notifikasiTanggapan(PengaduanModel $parent, PengaduanModel $balasan, bool $isAdmin): void
    {
        if ($isAdmin) {
            $pesan = "Halo {$parent->nama},\n\nPengaduan Anda (*{$parent->nomor_tiket}*) mendapat tanggapan dari Pemerintah Desa:\n\n"
                ."\"{$balasan->isi}\"\n\n"
                ."Status saat ini: *{$parent->status_label}*.";
            $this->kirimWhatsApp($parent->telepon, $pesan);
        } else {
            $noAdmin = SettingAplikasi::where('key', 'sp_wa_admin_nomor')->value('value');
            if (! empty($noAdmin)) {
                $pesan = "🔔 *BALASAN WARGA PADA TIKET {$parent->nomor_tiket}*\n\n"
                    ."Pelapor: {$parent->nama}\n"
                    ."Pesan: \"{$balasan->isi}\"";
                $this->kirimWhatsApp($noAdmin, $pesan);
            }
        }
    }

    /**
     * Kirim pesan WhatsApp melalui modul SimpelWhatsApp jika aktif
     */
    private function kirimWhatsApp(?string $target, string $pesan): void
    {
        if (empty($target)) {
            return;
        }

        $aktif = SettingAplikasi::where('key', 'sp_wa_notif_aktif')->value('value');
        if ($aktif === '0') {
            return;
        }

        try {
            if (class_exists(\Modules\SimpelWhatsApp\Services\KirimPesanService::class)) {
                $kirimService = new \Modules\SimpelWhatsApp\Services\KirimPesanService;
                $token = $kirimService->tokenAktif();
                if (! empty($token)) {
                    \Modules\SimpelWhatsApp\Services\PesanService::singleSend($token, $target, $pesan);
                }
            }
        } catch (Exception) {
            // Pengiriman WA tidak boleh menggagalkan alur transaksi pengaduan
        }
    }
}
