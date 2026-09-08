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

namespace Modules\SimpelPengaduan\Database\Seeders\Demo;

use App\Models\SettingAplikasi;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Modules\SimpelPengaduan\Enums\StatusPengaduanEnum;
use Modules\SimpelPengaduan\Models\Pengaduan;

/**
 * Data contoh (dummy) untuk demonstrasi.
 * HANYA berjalan jika `demo_mode` bernilai true (config_item('demo_mode') === true)
 * dan belum pernah di-seed sebelumnya (idempoten via setting `sp_demo_seeded`).
 */
class DemoPengaduanSeeder extends Seeder
{
    private const PENANDA = 'sp_demo_seeded';

    public function run(): void
    {
        if (! config_item('demo_mode')) {
            return;
        }

        if (! Schema::hasTable('simpel_pengaduan')) {
            return;
        }

        Model::unguard();

        $configId = identitas('id');

        if (SettingAplikasi::where('key', self::PENANDA)->where('config_id', $configId)->value('value') === '1') {
            return;
        }

        $now = Carbon::now();

        // 1. Pengaduan Selesai Diproses (PJU Padam)
        $lapor1 = Pengaduan::create([
            'id_pengaduan' => null,
            'nama' => 'Ahmad Fauzi',
            'nik' => '3201012304850001',
            'telepon' => '081234567890',
            'email' => 'ahmad.fauzi@contohdesa.id',
            'judul' => 'Lampu Penerangan Jalan Umum (PJU) Padam di Jalan Melati RT 03',
            'isi' => 'Selamat pagi admin desa, lampu PJU di pertigaan RT 03 RW 02 dekat pos ronda sudah padam selama 3 malam berturut-turut. Mohon bantuan petugas untuk memeriksa dan mengganti bohlam karena jalanan menjadi sangat gelap saat malam hari.',
            'status' => StatusPengaduanEnum::SELESAI->value,
            'ip_address' => '127.0.0.1',
            'created_at' => $now->copy()->subDays(5)->setTime(8, 30),
            'updated_at' => $now->copy()->subDays(4)->setTime(16, 0),
        ]);

        // Tanggapan 1 dari Admin
        Pengaduan::create([
            'id_pengaduan' => $lapor1->id,
            'nama' => 'Pemerintah Desa',
            'nik' => null,
            'telepon' => null,
            'email' => null,
            'judul' => 'Re: '.$lapor1->judul,
            'isi' => 'Terima kasih atas laporannya Bapak Ahmad Fauzi. Laporan Anda telah kami terima dan kami teruskan ke petugas teknis sarana prasarana desa.',
            'status' => StatusPengaduanEnum::DIPROSES->value,
            'ip_address' => '127.0.0.1',
            'created_at' => $now->copy()->subDays(5)->setTime(10, 15),
            'updated_at' => $now->copy()->subDays(5)->setTime(10, 15),
        ]);

        // Tanggapan 2 penyelesaian dari Admin
        Pengaduan::create([
            'id_pengaduan' => $lapor1->id,
            'nama' => 'Pemerintah Desa',
            'nik' => null,
            'telepon' => null,
            'email' => null,
            'judul' => 'Re: '.$lapor1->judul,
            'isi' => 'Petugas teknis telah mengganti bohlam LED PJU pada pukul 14.30 WIB tadi. Lampu penerangan jalan sudah menyala normal kembali. Laporan ini kami nyatakan selesai. Terima kasih atas partisipasi aktif Anda dalam menjaga lingkungan desa.',
            'status' => StatusPengaduanEnum::SELESAI->value,
            'ip_address' => '127.0.0.1',
            'created_at' => $now->copy()->subDays(4)->setTime(16, 0),
            'updated_at' => $now->copy()->subDays(4)->setTime(16, 0),
        ]);

        // 2. Pengaduan Sedang Diproses (Saluran Drainase Tersumbat)
        $lapor2 = Pengaduan::create([
            'id_pengaduan' => null,
            'nama' => 'Siti Rahmawati',
            'nik' => '3201015607920002',
            'telepon' => '085678901234',
            'email' => 'siti.rahma@contohdesa.id',
            'judul' => 'Saluran Air Drainase Tersumbat Sampah di Gang Mawar RW 01',
            'isi' => 'Mohon perhatian pihak desa, gorong-gorong di depan Gang Mawar tersumbat tumpukan ranting pohon dan sampah plastik. Setiap kali turun hujan lebat air selalu meluap menggenangi halaman rumah warga.',
            'status' => StatusPengaduanEnum::DIPROSES->value,
            'ip_address' => '127.0.0.1',
            'created_at' => $now->copy()->subDays(2)->setTime(13, 20),
            'updated_at' => $now->copy()->subDays(1)->setTime(9, 0),
        ]);

        // Balasan Admin
        Pengaduan::create([
            'id_pengaduan' => $lapor2->id,
            'nama' => 'Pemerintah Desa',
            'nik' => null,
            'telepon' => null,
            'email' => null,
            'judul' => 'Re: '.$lapor2->judul,
            'isi' => 'Halo Ibu Siti Rahmawati, laporan telah kami koordinasikan dengan Ketua RW 01 dan tim kebersihan desa. Kerja bakti pembersihan saluran air dijadwalkan besok pagi mulai pukul 08.00 WIB.',
            'status' => StatusPengaduanEnum::DIPROSES->value,
            'ip_address' => '127.0.0.1',
            'created_at' => $now->copy()->subDays(1)->setTime(9, 0),
            'updated_at' => $now->copy()->subDays(1)->setTime(9, 0),
        ]);

        // 3. Pengaduan Menunggu Diproses (Pohon Rawan Tumbang)
        Pengaduan::create([
            'id_pengaduan' => null,
            'nama' => 'Bambang Sudarsono',
            'nik' => '3201011211780003',
            'telepon' => '087812345678',
            'email' => 'bambang.sudarsono@contohdesa.id',
            'judul' => 'Permohonan Pemangkasan Dahan Pohon Tua Rawan Tumbang',
            'isi' => 'Dahan pohon beringin tua di tepi jalan poros Dusun II terlihat sudah sangat rapuh dan menjulur ke dekat kabel jaringan listrik PLN. Khawatir dahan patah saat angin kencang atau hujan deras dan membahayakan pengendara yang melintas.',
            'status' => StatusPengaduanEnum::MENUNGGU->value,
            'ip_address' => '127.0.0.1',
            'created_at' => $now->copy()->subHours(4),
            'updated_at' => $now->copy()->subHours(4),
        ]);

        // Tandai bahwa data demo telah berhasil di-seed (withoutEvents agar aman di instalasi tanpa tabel log_activity)
        SettingAplikasi::withoutEvents(static function () use ($configId): void {
            SettingAplikasi::updateOrCreate(
                ['key' => self::PENANDA, 'config_id' => $configId],
                [
                    'value' => '1',
                    'keterangan' => 'Penanda data demo modul Simpel Pengaduan telah di-seed',
                    'kategori' => 'Simpel Pengaduan',
                ]
            );
        });

        cache()->flush();
    }
}
