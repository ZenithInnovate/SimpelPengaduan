# Panduan Penggunaan Modul Simpel Pengaduan

Modul **Simpel Pengaduan** adalah solusi pengelolaan dan penanganan aspirasi serta pengaduan warga desa secara transparan, akuntabel, dan responsif. Modul ini terintegrasi langsung dengan tabel inti `pengaduan` OpenSID, didukung fitur nomor tiket otomatis (`LPR-YYYYMM-XXXX`), percakapan berutas (*threaded replies*), serta notifikasi WhatsApp otomatis melalui modul `SimpelWhatsApp`.

---

## 1. Alur Pengaduan Warga (Portal Publik)

Warga desa dapat menyampaikan laporan pengaduan secara mandiri melalui tautan publik:
`https://domain-desa.id/layanan-pengaduan`

1. **Menyampaikan Laporan Baru**:
   - Pilih tab **Sampaikan Pengaduan**.
   - Isi formulir identitas: Nama Lengkap, NIK (opsional), Nomor WhatsApp (wajib, untuk notifikasi), dan Email.
   - Isi Judul Laporan serta Rincian Pengaduan secara jelas dan informatif.
   - Lampirkan foto bukti atau dokumentasi lapangan jika ada (format JPG/PNG/WEBP, maksimal 3 MB).
   - Klik **Kirim Laporan Sekarang**.
   - Sistem akan menerbitkan **Nomor Tiket** resmi dan mengirimkan konfirmasi via WhatsApp ke pelapor.

2. **Melacak Progres Laporan**:
   - Pilih tab **Lacak Progres Tiket**.
   - Masukkan nomor tiket pengaduan (contoh: `LPR-202609-0001`) atau nomor WhatsApp / NIK pelapor.
   - Klik **Lacak Tiket**.
   - Sistem akan menampilkan status laporan (*Menunggu Diproses*, *Sedang Diproses*, atau *Selesai Diproses*), riwayat tanggapan dari pemerintah desa, dan form balasan lanjutan bagi warga.

---

## 2. Pengelolaan Pengaduan oleh Operator / Admin Desa

Admin desa mengelola pengaduan warga melalui menu backend OpenSID:
**Simpel Pengaduan > Daftar Pengaduan** (URL: `simpel/pengaduan`).

1. **Dashboard Metrik**:
   - Menampilkan 4 kartu statistik: Total Pengaduan, Menunggu Diproses, Sedang Diproses, dan Selesai Ditangani.
   - Filter cepat berdasarkan status penanganan.

2. **Daftar Laporan Pengaduan**:
   - Tabel memuat No. Tiket, Data Pelapor, Ringkasan Laporan, Status, dan Waktu Masuk.
   - Kolom aksi baris:
     - **Detail / Utas Percakapan** (ikon komentar): Membuka halaman detail utas dan form tanggapan.
     - **Hapus** (ikon tempat sampah): Menghapus laporan dan seluruh utas tanggapannya.

3. **Menindaklanjuti & Menanggapi Pengaduan**:
   - Klik tombol **Detail** pada baris laporan.
   - Periksa rincian laporan, foto lampiran, serta identitas pelapor.
   - Di bagian kanan terdapat **Timeline Utas Percakapan** yang menampilkan seluruh riwayat pesan secara kronologis.
   - Untuk memberikan respons resmi:
     - Isi teks tanggapan pada kolom **Isi Tanggapan / Tindak Lanjut**.
     - Pilih status baru (misal: *Sedang Diproses* atau *Selesai Diproses*).
     - Lampirkan foto tindak lanjut lapangan bila diperlukan.
     - Klik **Kirim Tanggapan**.
   - Warga akan otomatis mendapatkan notifikasi WhatsApp terkait tanggapan dan pembaruan status tersebut.

---

## 3. Konfigurasi Modul & Notifikasi WhatsApp

Pengaturan modul dapat disesuaikan pada menu **Pengaturan Aplikasi** OpenSID (kategori *Simpel Pengaduan*):

- **Notifikasi WhatsApp Pengaduan (`sp_wa_notif_aktif`)**:
  - Aktifkan (`1`) untuk mengirim pesan WhatsApp otomatis ke pelapor saat tiket dibuat, ditanggapi, atau diubah statusnya.
  - Memerlukan modul `SimpelWhatsApp` yang sudah terpasang dan terhubung dengan gateway/perangkat aktif.
- **Nomor WhatsApp Admin Pengaduan (`sp_wa_admin_nomor`)**:
  - Isi dengan nomor WhatsApp petugas desa yang ditugaskan menangani pengaduan (format `08xxxxxxxxxx`).
  - Setiap ada laporan baru atau balasan dari warga, nomor ini akan menerima pemberitahuan otomatis.
