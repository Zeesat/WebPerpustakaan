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
4. Jalankan dari local server seperti XAMPP/Laragon/Apache.

## Catatan

- Routing masih sederhana dan sengaja dibuat ringan agar mudah dikembangkan.
- Halaman yang ada saat ini berupa starter/placeholder dengan alur yang sudah dipetakan.
- File layanan (`services`) disiapkan untuk aturan bisnis seperti approval, return, late, dan expiration.

