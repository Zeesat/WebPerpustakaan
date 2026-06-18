-- ============================================================
-- Migration 005: Create database views
-- Views simplify repeated JOIN queries used across models
-- ============================================================

USE library_management;

-- -----------------------------------------------------------
-- 1. vw_book_catalog
--    Books with category name (replaces repeated LEFT JOIN
--    in Book::getAll, getCatalogBooks, findById, findByIds,
--    findRelatedByCategory).
-- -----------------------------------------------------------
CREATE OR REPLACE VIEW vw_book_catalog AS
SELECT
    b.id,
    b.title,
    b.author,
    b.stock,
    b.cover,
    COALESCE(b.description, '') AS description,
    b.category_id,
    COALESCE(c.name, 'Uncategorized') AS category_name
FROM books b
LEFT JOIN categories c ON c.id = b.category_id;

-- -----------------------------------------------------------
-- 2. vw_active_loans
--    Loans that are still active (pending, approved, late)
--    with user and book details already joined.
--    Replaces the triple JOIN in Loan::getLoanItemsByUser
--    and Loan::getPendingLoans.
-- -----------------------------------------------------------
CREATE OR REPLACE VIEW vw_active_loans AS
SELECT
    l.id          AS loan_id,
    l.user_id,
    u.name        AS user_name,
    u.email       AS user_email,
    l.loan_date,
    l.due_date,
    l.status,
    l.fine,
    l.approved_by,
    l.approved_at,
    l.returned_at,
    l.created_at,
    ld.id         AS loan_detail_id,
    ld.quantity,
    b.id          AS book_id,
    b.title       AS book_title,
    b.author      AS book_author,
    b.cover       AS book_cover
FROM loans l
INNER JOIN users u        ON u.id = l.user_id
INNER JOIN loan_details ld ON ld.loan_id = l.id
INNER JOIN books b        ON b.id = ld.book_id
WHERE l.status IN ('pending', 'approved', 'late');

-- -----------------------------------------------------------
-- 3. vw_category_summary
--    Category list with book count and total stock.
--    Replaces Category::getAllWithBookCounts.
-- -----------------------------------------------------------
CREATE OR REPLACE VIEW vw_category_summary AS
SELECT
    c.id,
    c.name,
    COUNT(b.id)            AS book_count,
    COALESCE(SUM(b.stock), 0) AS stock_total
FROM categories c
LEFT JOIN books b ON b.category_id = c.id
GROUP BY c.id, c.name;
