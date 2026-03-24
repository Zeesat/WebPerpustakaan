# Workplace Blueprint

## Arsitektur

Pola kerja menggunakan pendekatan sederhana mirip MVC:

- `public/` sebagai entry point aplikasi
- `routes/web.php` untuk definisi route
- `app/core/` untuk router, database, dan base controller
- `app/controllers/` untuk alur request
- `app/models/` untuk representasi data
- `app/services/` untuk aturan bisnis
- `app/views/` untuk tampilan

## Modul yang Sudah Dipetakan

- Auth: register, login, logout
- Public books: daftar buku, detail buku
- User: dashboard, request loan, riwayat/status pinjaman
- Admin: dashboard, buku, kategori, user, verifikasi pinjaman

## Pengembangan Lanjutan yang Disarankan

1. Lengkapi model untuk seluruh tabel.
2. Implement middleware auth dan role.
3. Tambahkan validasi form.
4. Sambungkan controller ke database.
5. Pisahkan CSS per halaman jika UI mulai membesar.

