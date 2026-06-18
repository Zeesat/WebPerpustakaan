# Deployment Guide — Database Objects (Migrations 005–008)

## Ringkasan Perubahan

| Migration | Objek | Jumlah | File |
|-----------|-------|--------|------|
| 005 | Views | 3 | `database/migrations/005_views.sql` |
| 006 | Stored Functions | 2 | `database/migrations/006_functions.sql` |
| 007 | Stored Procedures | 2 | `database/migrations/007_procedures.sql` |
| 008 | Triggers | 3 | `database/migrations/008_triggers.sql` |

---

## Prasyarat di Server Produksi

Sebelum menjalankan migration, pastikan:

1. **MySQL user memiliki privileges:**
   ```sql
   -- Cek privileges user produksi
   SHOW GRANTS FOR 'u169077025_ale'@'%';   -- sesuaikan host
   ```
   User minimal butuh:
   - `CREATE VIEW`
   - `CREATE ROUTINE` (untuk FUNCTION & PROCEDURE)
   - `TRIGGER` (untuk TRIGGER)
   - `EXECUTE` (untuk menjalankan PROCEDURE)

   Jika kurang, minta hosting provider untuk menambahkannya, atau jalankan migration sebagai root/admin.

2. **Backup database sebelum menjalankan:**
   ```bash
   mysqldump -u u169077025_ale -p u169077025_db_libmanage > backup_before_migration_$(date +%Y%m%d).sql
   ```

---

## Langkah Deployment

### Step 1: Upload file migration ke server

Upload 4 file migration ke server:
```
database/migrations/005_views.sql
database/migrations/006_functions.sql
database/migrations/007_procedures.sql
database/migrations/008_triggers.sql
```

### Step 2: Jalankan migration secara berurutan

Semua file migration sudah menggunakan `USE u169077025_db_libmanage;` — tidak perlu edit manual.

```bash
mysql -u u169077025_ale -pPerpustakaan1239 -h <host_produksi> < database/migrations/005_views.sql
mysql -u u169077025_ale -pPerpustakaan1239 -h <host_produksi> < database/migrations/006_functions.sql
mysql -u u169077025_ale -pPerpustakaan1239 -h <host_produksi> < database/migrations/007_procedures.sql
mysql -u u169077025_ale -pPerpustakaan1239 -h <host_produksi> < database/migrations/008_triggers.sql
```

### Step 3: Verifikasi

Jalankan query berikut untuk memastikan semua objek terbuat:

```sql
USE u169077025_db_libmanage;

-- Cek views (harus 3: vw_book_catalog, vw_active_loans, vw_category_summary)
SHOW FULL TABLES WHERE TABLE_TYPE = 'VIEW';

-- Cek functions (harus 2: fn_count_active_loans, fn_calculate_fine)
SHOW FUNCTION STATUS WHERE Db = 'u169077025_db_libmanage';

-- Cek procedures (harus 2: sp_approve_loan, sp_process_loan_return)
SHOW PROCEDURE STATUS WHERE Db = 'u169077025_db_libmanage';

-- Cek triggers (harus 3)
SHOW TRIGGERS;
```

### Step 5: Deploy kode PHP yang sudah diupdate

Upload file-file PHP yang sudah diubah:
- `app/models/Book.php` — menggunakan `vw_book_catalog`
- `app/models/Category.php` — menggunakan `vw_category_summary`
- `app/models/Loan.php` — menggunakan `vw_active_loans` + stored function + stored procedure wrapper
- `docs/database.sql` — (opsional) referensi schema terbaru

---

## Perubahan Perilaku yang Perlu Diketahui

### 1. Trigger `trg_before_loan_delete`
- **Sebelum:** Loan bisa dihapus kapan saja lewat PHP (meski masih aktif)
- **Sesudah:** Loan dengan status `pending`/`approved`/`late` TIDAK bisa dihapus. Harus di-return atau di-reject dulu.
- **Dampak:** Jika ada fitur admin "hapus loan", pastikan logikanya sudah menangani error MySQL `1644`.

### 2. Trigger `trg_before_book_delete`
- **Sebelum:** Buku bisa dihapus meski sedang dipinjam
- **Sesudah:** Buku yang sedang dalam pinjaman aktif TIDAK bisa dihapus
- **Dampak:** Method `Book::delete()` di PHP sudah punya catch block — error akan muncul sebagai flash message.

### 3. Trigger `trg_before_loan_update`
- **Sebelum:** `approved_at` dan `returned_at` harus di-set manual di PHP
- **Sesudah:** Otomatis di-set oleh trigger saat status berubah
- **Dampak:** Kode PHP tidak perlu lagi mengisi timestamp ini secara manual.

### 4. View `vw_book_catalog` menggunakan `LEFT JOIN`
- **Sebelum:** `getCatalogBooks()` pakai `INNER JOIN` → buku tanpa kategori (category_id=0) tidak muncul
- **Sesudah:** View pakai `LEFT JOIN` → semua buku muncul, termasuk yang `Uncategorized`
- **Dampak:** Buku dengan `category_id=0` sekarang muncul di katalog.

### 5. Model `Loan` punya fallback
- Jika stored function/procedure belum ada di database, model akan fallback ke query SQL inline.
- App tetap berjalan normal meski migration belum dijalankan.

---

## Rollback Plan

Jika terjadi masalah setelah deployment:

```sql
USE u169077025_db_libmanage;

-- Hapus triggers
DROP TRIGGER IF EXISTS trg_before_loan_update;
DROP TRIGGER IF EXISTS trg_before_book_delete;
DROP TRIGGER IF EXISTS trg_before_loan_delete;

-- Hapus procedures
DROP PROCEDURE IF EXISTS sp_approve_loan;
DROP PROCEDURE IF EXISTS sp_process_loan_return;

-- Hapus functions
DROP FUNCTION IF EXISTS fn_calculate_fine;
DROP FUNCTION IF EXISTS fn_count_active_loans;

-- Hapus views
DROP VIEW IF EXISTS vw_category_summary;
DROP VIEW IF EXISTS vw_active_loans;
DROP VIEW IF EXISTS vw_book_catalog;
```

Kemudian deploy ulang kode PHP versi sebelumnya (sebelum perubahan model).

---

## Catatan Tambahan

- **Hosting shared (cPanel/niagahoster/etc.):** Jika tidak bisa connect via SSH, gunakan **phpMyAdmin** — buka tab SQL, copy-paste isi file migration, dan jalankan.
- **DELIMITER:** phpMyAdmin tidak butuh `DELIMITER //` — hapus baris tersebut dan ganti `//` dengan `;` jika menggunakan phpMyAdmin.
- **MariaDB vs MySQL:** Migration sudah di-test di MariaDB (XAMPP). Jika server produksi pakai MySQL 5.7+, seharusnya kompatibel. Jika ada error sintaks, cek versi MySQL.
- **Denda (Rp 2.000/hari):** Rate denda di `fn_calculate_fine` saat ini Rp 2.000/hari. Sesuaikan nilai di fungsi jika ada kebijakan berbeda.
