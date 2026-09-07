# Simpel Pengaduan

Modul Pengaduan Warga OpenSID terintegrasi tabel pengaduan inti, tracking tiket, respons percakapan berutas, dan notifikasi WhatsApp

Modul add-on untuk **[OpenSID](https://github.com/OpenSID/OpenSID)**, bagian dari
ekosistem modul `Simpel*`. Berjalan **di dalam** OpenSID pada runtime hibrida
CodeIgniter 3 + Illuminate/Laravel.

## Ringkasan

Pengaduan & aspirasi warga desa di OpenSID: tabel inti `pengaduan`, kode tiket virtual otomatis `LPR-YYYYMM-XXXX`, dashboard metrik, respon/utas percakapan admin & warga, integrasi notifikasi WhatsApp pelapor, formulir publik Tailwind responsif.
## Kompatibilitas

Modul **wajib** jalan di **semua** rilis OpenSID Umum & Premium yang didukung, pada
**dua target runtime**:

| Target | Rilis OpenSID | Framework | PHP |
|---|---|---|---|
| **I10** | **Umum** semua rilis + **Premium** `v2601`–`v2605` | CodeIgniter 3 + Illuminate 10 (komponen individual, tanpa `foundation`/`routing`) | **8.1** – 8.3 |
| **L13** | **Premium** `v2606`+ | CodeIgniter 3 + Laravel 13 penuh | **8.4** |

- Rentang penuh yang didukung: **PHP 8.1 – 8.4**, Illuminate 10 – Laravel 13.
- Daftar rilis lengkap + matriks kontrak inti lintas-versi: `Docs/KOMPATIBILITAS.md`.
- Jebakan runtime hibrida yang dihindari di kode modul (`now()`/`today()`,
  `route()`, `abort()`, `csrf_token()`, `response()->json()`, `session()`, `url()`):
  `Docs/HAZARD-CI3-LARAVEL.md`. Modul memakai `Carbon::now()`, `site_url('literal')`,
  helper `json()`, `show_404()`.
- Multi-desa: seluruh tabel modul memakai kolom `config_id`.

## Metadata

| | |
|---|---|
| **Versi** | `1.0.0` |
| **Membutuhkan** | **Simpel Core** `>=1.10.0` |
| **Namespace** | `Modules\SimpelPengaduan` |

## Instalasi

1. Pastikan **Simpel Core** terpasang (engine modul).
2. Salin folder ini ke `Modules/SimpelPengaduan` pada instalasi OpenSID.
3. Pasang lewat **Pengaturan → Modul** di panel admin — migrasi, seeder menu, dan
   pengaturan bawaan berjalan otomatis. Jalur CLI modul OpenSID juga didukung.

## Struktur

```
Config/      Konfigurasi modul
Database/    Migrations & Seeders (prefix tabel = slug modul; migrasi 9999 = registrasi modul)
Enums/       Backed enum (nilai statis)
Helpers/     Helper global modul
Http/        Controllers (BackEnd / FrontEnd / Api) & Requests (FormRequest)
Models/      Eloquent models
Providers/   Service provider modul
Routes/      web.php — route admin + publik (tanpa ->name())
Services/    Logika bisnis (controller ≤ 250 baris)
Traits/      Trait pendukung
Views/       backend/* (wajib komponen simpel-core), frontend/* (Tailwind + AJAX)
Panduan/     Panduan pengguna akhir (Markdown)
```

## Dokumentasi

- Catatan rilis: [`rilis.md`](rilis.md)
- Panduan per-fitur: [`Panduan/`](Panduan/)
- Standar & kompatibilitas ekosistem: `AGENTS.md`, `CODING-STANDARDS.md`, `Docs/KOMPATIBILITAS.md`, `Docs/HAZARD-CI3-LARAVEL.md` (di monorepo `Ekosistem-Modul`)

## Lisensi

GPL-3.0-or-later, mengikuti lisensi OpenSID.

---
© 2026 AkarDev.com · <https://akar-dev.com>
