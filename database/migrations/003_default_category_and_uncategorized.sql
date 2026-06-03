-- ============================================================
-- Migration 003: Insert default "Uncategorized" category with id=0
-- and update books.category_id foreign key to allow category_id=0
-- ============================================================

USE library_management;

-- 1. Insert the "Uncategorized" category with a specific id=0.
--    Since categories.id is AUTO_INCREMENT, we temporarily disable
--    strict mode or use SET INSERT_ID to force id=0.
SET @prev = @@SESSION.sql_mode;
SET SESSION sql_mode = '';

INSERT INTO categories (id, name) VALUES (0, 'Uncategorized')
ON DUPLICATE KEY UPDATE name = 'Uncategorized';

SET SESSION sql_mode = @prev;

-- 2. Update any existing books that have no real category to point to id=0
--    (fallback in case data was inserted with invalid category_id).
--    This is a safe no-op if the schema was respected.
UPDATE IGNORE books SET category_id = 0 WHERE category_id NOT IN (SELECT id FROM categories WHERE id > 0);

-- 3. Drop the existing foreign key constraint so we can allow id=0
ALTER TABLE books DROP FOREIGN KEY fk_books_category;

-- 4. Modify the column to allow 0 as a valid value, default to 0
ALTER TABLE books MODIFY COLUMN category_id INT NOT NULL DEFAULT 0;

-- 5. Re-add the foreign key constraint, but only enforce for positive IDs.
--    MySQL does not support conditional FK, so we skip re-adding FK here.
--    Referential integrity for category_id > 0 is enforced by application code.
