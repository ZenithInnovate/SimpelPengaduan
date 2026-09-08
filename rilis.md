# 📦 CATATAN RILIS

## v1.0.0 (2026-09-07)

- **Inisialisasi Modul Simpel Pengaduan**:
  - Menggunakan langsung tabel inti `pengaduan` OpenSID (Umum & Premium) tanpa menduplikasi data atau merusak riwayat yang sudah ada.
  - Penomoran tiket otomatis virtual berformat `LPR-YYYYMM-XXXX` memudahkan warga melacak progres laporan.
  - Manajemen backend modern menggunakan komponen standar `simpel-core::components.*` (Ringkasan statistik metrik, DataTables server-side, filter status responsif).
  - Halaman detail percakapan berutas (*threaded conversation*) dengan respons langsung dari admin/operator desa.
  - Perubahan status pengaduan (Menunggu, Diproses, Selesai) secara instan.
  - Integrasi notifikasi WhatsApp otomatis ke pelapor saat tiket dibuat atau ditanggapi via `simpel_whatsapp_kirim()`.
  - Formulir publik modern & portal pelacakan tiket pengaduan publik tanpa membebani server inti.
  - Data contoh (Demo Seeder) otomatis tersedia saat dipasang dengan mode demo (`config_item('demo_mode') === true`) mencakup skenario pengaduan selesai, diproses, dan menunggu beserta utas tanggapannya.
  - Standarisasi Komponen SimpelCore: Memperbarui halaman detail pengaduan (3 panel box) agar konsisten menggunakan komponen bersama `simpel-core::components.card.open/close` menggantikan markup `div.box` mentah.
  - Penambahan entry point seeder modul `SimpelPengaduanSeeder` (memanggil `ModulSeeder`, `SettingSeeder`, dan `DemoPengaduanSeeder` per BLUEPRINT §1), penyesuaian migrasi setting, dan penambahan dokumen analisis teknis `Docs/ANALISIS.md`.
  - Eliminasi hazard kompatibilitas CI3/I10: Mengganti pemanggilan `response()->json()` dengan helper global `json()` pada controller backend dan frontend.
  - Kompatibilitas Form Pengaturan Aplikasi Lintas-Versi: Menyesuaikan tipe setting `select-boolean` menjadi `select-array` dengan opsi `StatusBooleanEnum::all()` pada `SettingSeeder` untuk menjamin kompatibilitas penuh dengan rilis OpenSID Premium v2609+.
  - Migrasi konfirmasi hapus modal klasik ke komponen bersama: Mengganti include modal klasik inti OpenSID (`konfirmasi_hapus`) pada view backend index dengan komponen bersama `simpel-core::components.js.konfirmasi` agar tombol aksi hapus (`buttons.actions.delete`) terhubung dengan dialog SweetAlert konfirmasi standar.
  - Kompatibel penuh dengan target runtime I10 (PHP 8.1 / Illuminate 10) & L13 (PHP 8.4 / Laravel 13).

---

## 🚀 FITUR STANDAR

- Integrasi penuh dengan tabel `pengaduan` bawaan OpenSID.
- Dukungan pelacakan pengaduan berbasis nomor tiket dan NIK/No. Telepon.
- Threaded conversation (percakapan bersarang antara pelapor dan pemerintah desa).
- Notifikasi WhatsApp otomatis ke pelapor saat ada tanggapan atau pembaruan status.
- Dashboard metrik pengaduan masuk, diproses, dan selesai.
