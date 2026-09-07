# Analisis Modul — SimpelPengaduan

*Per 2026-09-07. Bagian dari [../../Docs/ANALISIS-GLOBAL.md](../../Docs/ANALISIS-GLOBAL.md).*

## Dokumentasi modul ini

| Berkas | Isi | Sifat |
|---|---|---|
| `README.md` | Ikhtisar + kompatibilitas + arsitektur | Umum |
| `rilis.md` | v1.0.0 — rilis awal | Per-modul |
| `Panduan/panduan.md` | Panduan pengguna (admin desa & publik) | Per-modul (pengguna akhir) |
| `Docs/ANALISIS.md` | Analisis teknis, audit hazard, status kepatuhan | Per-modul (dev) |

## Fungsi & struktur

**Layanan Pengaduan Warga & Pelacakan Tiket** — modul add-on OpenSID untuk mengelola aspirasi, laporan, dan aduan masyarakat desa secara terintegrasi langsung dengan tabel inti `pengaduan` OpenSID (Umum & Premium). Modul ini menambahkan:
1. Penomoran tiket virtual otomatis berformat `LPR-YYYYMM-XXXX`.
2. Halaman publik modern untuk pelaporan dan pelacakan perkembangan tiket.
3. Panel admin modern berbasis komponen SimpelCore (`card.open/close`, `table.datatable`, `js.datatable`, filter status responsif).
4. Utas percakapan dua arah (*threaded conversation*) antara pelapor dan pemerintah desa.
5. Notifikasi WhatsApp otomatis ke pelapor via `simpel_whatsapp_kirim()` saat tiket dibuat atau ditanggapi.
6. Demo seeder idempoten dengan status berbeda (`DemoPengaduanSeeder`).

Struktur berkas:
- **Models**: `Pengaduan` (menggunakan tabel bawaan `pengaduan` dengan relasi hierarkis `parent`/`child`, scope `utama()`, helper tiket).
- **Controllers**:
  - `BackEnd/PengaduanController` (183 baris ≤ 250) — dashboard metrik, DataTables, detail percakapan, ubah status, tanggapi, hapus.
  - `FrontEnd/PengaduanWargaController` (134 baris ≤ 250) — formulir publik, kirim laporan, lacak tiket, balas utas.
- **Requests**: `KirimPengaduanRequest`, `TanggapiPengaduanRequest`.
- **Services**: `PengaduanService`, `BuildService`.
- **Enums**: `StatusPengaduanEnum` (backed enum).
- **Seeders**: `SimpelPengaduanSeeder` (entry point) → `ModulSeeder` + `SettingSeeder` + `DemoPengaduanSeeder`.

## Temuan & Audit Standar

### ✅ Bebas Hazard CI3/Laravel (I10 & L13)
- `now()` / `today()` bare: **0** (semua menggunakan `\Carbon\Carbon::now()`).
- `response()->json()`: **0** (seluruh respons JSON menggunakan helper global `json()`).
- `route()` / `csrf_token()` / `@csrf` / `abort()` / `session()` / `url()`: **0**.
- `->name()` pada Route: **0** (menghindari duplikasi named route per `ROUTING-MODUL.md`).

### ✅ Controller Ramping (STANDAR-CONTROLLER)
- `PengaduanController`: 183 baris (≤ 250).
- `PengaduanWargaController`: 134 baris (≤ 250).

### ✅ View Backend Seragam Komponen SimpelCore
- `index.blade.php`: Menggunakan `card.open/close`, `table.datatable`, `js.datatable`, `page.header`, `assets.style`.
- `detail.blade.php`: Menggunakan `card.open/close` (3 panel box), `form.csrf`, `buttons.kembali`, `buttons.simpan`, `page.header`.

### ✅ Seeder & Migrasi Idempoten
- Seeder entry point `SimpelPengaduanSeeder` memanggil `ModulSeeder`, `SettingSeeder`, dan `DemoPengaduanSeeder` (hanya aktif saat `config_item('demo_mode') === true`).
- Migrasi register modul `9999_99_99_999999_register_modul_simpel_pengaduan_table.php` mendaftarkan modul ke `simple_core`.
