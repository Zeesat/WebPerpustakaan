# Library Loan Management System

Starter workplace untuk proyek `PHP Native + HTML + CSS + JS` berdasarkan kebutuhan di `SytemDesign.md`.

## Tujuan Struktur

- Langsung siap dipakai untuk implementasi fitur tanpa inisialisasi ulang
- Mudah dipahami untuk pengerjaan individu maupun tim
- Memisahkan concern: `config`, `core`, `controllers`, `models`, `services`, `views`, `routes`, `database`

## Struktur Utama

```text
WebUAS/
|-- app/
|   |-- config/
|   |-- controllers/
|   |   `-- admin/
|   |-- core/
|   |-- helpers/
|   |-- models/
|   |-- services/
|   `-- views/
|       |-- admin/
|       |-- auth/
|       |-- books/
|       |-- layouts/
|       |-- loans/
|       |-- pages/
|       |-- partials/
|       `-- user/
|-- database/
|   |-- migrations/
|   `-- seeds/
|-- docs/
|-- public/
|   `-- assets/
|       |-- css/
|       |-- images/
|       `-- js/
|-- routes/
|-- storage/
|   `-- logs/
`-- SytemDesign.md
```

## Cara Pakai

1. Jadikan folder `public/` sebagai document root server.
2. Sesuaikan koneksi database di `app/config/database.php`.
3. Import schema dari `database/migrations/001_initial_schema.sql`.
4. Opsional: seed admin awal dari `database/seeds/001_seed_admin.sql`.
5. Jalankan dari local server seperti XAMPP/Laragon/Apache.

## Konfigurasi

Project ini belum memakai loader `.env`. Konfigurasi masih berbasis file PHP:

- `app/config/app.php`
  - `name`: nama aplikasi untuk title/layout.
  - `base_url`: prefix URL bila project tidak berjalan di root domain.
- `app/config/database.php`
  - `host`, `port`, `database`, `username`, `password`, `charset`

Pastikan nama database di file config sama dengan database yang dibuat dari migration.

## Authentication Flow

Auth sekarang sudah aktif dengan alur berikut:

- `GET /login` dan `POST /login`
- `GET /register` dan `POST /register`
- `GET /forgot-password`
- `GET /reset-password`
- `POST /logout`

Perilaku utama:

- Password disimpan dengan `password_hash()` dan diverifikasi dengan `password_verify()`.
- Session ID diregenerasi saat login dan logout.
- Halaman user memerlukan session login.
- Halaman admin memerlukan session login dengan role `admin`.
- Form auth dan logout dilindungi CSRF token.
- Reset password masih berupa placeholder page sesuai scope saat ini.

## Seed Admin

File `database/seeds/001_seed_admin.sql` sekarang berisi akun admin awal:

- Email: `admin@library.test`
- Password: `Admin123!`

Gunakan hanya untuk development lokal, lalu ganti password bila lingkungan dipakai bersama.

## Migration Notes

- Tidak ada migration baru yang dibutuhkan untuk fitur auth ini.
- Fitur auth memakai tabel `users` yang sudah ada.
- Placeholder reset password belum menambah kolom token/reset table agar schema tetap minimal dan kompatibel.

## Testing

Test suite native PHP tersedia di folder `tests/`.

- Jalankan: `C:\xampp\php\php.exe tests/run.php`
- Cakupan: unit test model/service, middleware authorization, dan auth flow login/logout

## Catatan

- Routing masih sederhana dan sengaja dibuat ringan agar mudah dikembangkan.
- Halaman yang ada saat ini berupa starter/placeholder dengan alur yang sudah dipetakan.
- File layanan (`services`) disiapkan untuk aturan bisnis seperti approval, return, late, dan expiration.

