##### 🔧 **CATATAN RILIS v1.0.0**
- **Urutan Pengaturan Versi**: Menyetel nomor urut pengaturan `sp_version` ke `999999` di `SettingSeeder` agar konsisten berada di posisi paling akhir daftar pengaturan aplikasi (2026-09-11).
- Perbaiki 1 potensi masalah kompatibilitas I10 (Umum v2507+) pada SimpelPengaduan
- **Standarisasi Respon JSON Murni & Eliminasi Fallback Redirect (2026-09-10)**: Menyelaraskan method `tanggapi()`, `ubahStatus()`, dan `delete()` pada `PengaduanController` ke respon JSON murni dengan otorisasi `isCanJson('u'/'h')`, menghapus percabangan dual redirect dan `redirect()->with()` yang tidak kompatibel di runtime hybrid CI3 + Illuminate 10, memigrasikan formulir tanggapan admin di `Views/backend/detail.blade.php` ke komponen bersama `simpel-core::components.assets.form_request` (`#form_validasi`), serta menerapkan handler AJAX debounced loading alert (ambang batas 1 detik) tanpa spinner tombol pada pembaruan status pengaduan per CODING-STANDARDS §12.
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
  - Standarisasi Komponen Form: Mengadopsi simpel-core::components.form.field pada formulir tanggapan admin dan menyelaraskan pilihan status penanganan menggunakan StatusPengaduanEnum pada halaman detail dan filter index.
  - Standarisasi AJAX Delete & Ubah Status: Mengubah method `delete()` dan `ubahStatus()` pada `PengaduanController` agar mengembalikan respon JSON terstandarisasi `{ status: 'success'|'error', message: '...' }` dengan otorisasi `isCanJson('h')` / `isCanJson('u')` untuk mendukung anti-reload DataTables via `js.konfirmasi`.
  - Standarisasi BaseModel SimpelBuilder: Mengalihkan inheritance model `Pengaduan` ke `Modules\SimpelCore\Models\BaseModel` guna mendukung query chaining Laravel standar (`findOrFail()`, `firstOrFail()`) di runtime I10 dan L13 melalui `SimpelBuilder`.
  - Standarisasi Debounced Loading & Peniadaan Spinner Tombol (2026-09-09): Menghapus spinner `fa-spinner` pada 3 tombol submit form publik (`Views/frontend/form.blade.php`: lapor, lacak, balas) — kini hanya di-disable tanpa perubahan teks/ikon — dan menerapkan debounced loading alert 1 detik sehingga `Swal.showLoading()` hanya muncul bila proses berlangsung >= 1000ms, sesuai `CODING-STANDARDS.md` aturan Debounced Loading.
  - Kompatibel penuh dengan target runtime I10 (PHP 8.1 / Illuminate 10) & L13 (PHP 8.4 / Laravel 13).

---

##### 🚀 **FITUR STANDAR**

- Integrasi penuh dengan tabel `pengaduan` bawaan OpenSID.
- Dukungan pelacakan pengaduan berbasis nomor tiket dan NIK/No. Telepon.
- Threaded conversation (percakapan bersarang antara pelapor dan pemerintah desa).
- Notifikasi WhatsApp otomatis ke pelapor saat ada tanggapan atau pembaruan status.
- Dashboard metrik pengaduan masuk, diproses, dan selesai.

