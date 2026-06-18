CREATE DATABASE IF NOT EXISTS u169077025_db_libmanage;
USE u169077025_db_libmanage;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user','admin') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(150) NOT NULL,
    category_id INT NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    description TEXT,
    CONSTRAINT fk_books_category
        FOREIGN KEY (category_id)
        REFERENCES categories(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS loans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    loan_date DATE NOT NULL,
    due_date DATE NOT NULL,
    status ENUM(
        'pending',
        'approved',
        'rejected',
        'returned',
        'late'
    ) NOT NULL DEFAULT 'pending',
    approved_by INT NULL,
    approved_at DATETIME NULL,
    returned_at DATETIME NULL,
    fine DECIMAL(10,2) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_loans_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_loans_admin
        FOREIGN KEY (approved_by)
        REFERENCES users(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS loan_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    loan_id INT NOT NULL,
    book_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,

    CONSTRAINT fk_loan_details_loan
        FOREIGN KEY (loan_id)
        REFERENCES loans(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    CONSTRAINT fk_loan_details_book
        FOREIGN KEY (book_id)
        REFERENCES books(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);

CREATE INDEX idx_books_category
ON books(category_id);

CREATE INDEX idx_loans_user
ON loans(user_id);

CREATE INDEX idx_loans_status
ON loans(status);

CREATE INDEX idx_loans_due_date
ON loans(due_date);

CREATE INDEX idx_loan_details_loan
ON loan_details(loan_id);

CREATE INDEX idx_loan_details_book
ON loan_details(book_id);

-- ============================================================
-- VIEWS
-- ============================================================

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

CREATE OR REPLACE VIEW vw_category_summary AS
SELECT
    c.id,
    c.name,
    COUNT(b.id)            AS book_count,
    COALESCE(SUM(b.stock), 0) AS stock_total
FROM categories c
LEFT JOIN books b ON b.category_id = c.id
GROUP BY c.id, c.name;

-- ============================================================
-- STORED FUNCTIONS
-- ============================================================

DELIMITER //

CREATE FUNCTION fn_count_active_loans(p_user_id INT)
RETURNS INT
DETERMINISTIC
READS SQL DATA
BEGIN
    DECLARE loan_count INT DEFAULT 0;
    SELECT COUNT(*) INTO loan_count
    FROM loans
    WHERE user_id = p_user_id
      AND status IN ('pending', 'approved', 'late');
    RETURN loan_count;
END//

CREATE FUNCTION fn_calculate_fine(p_loan_id INT)
RETURNS DECIMAL(10,2)
DETERMINISTIC
READS SQL DATA
BEGIN
    DECLARE fine_amount DECIMAL(10,2) DEFAULT 0;
    DECLARE v_due_date    DATE;
    DECLARE v_status      VARCHAR(20);
    DECLARE v_returned_at DATETIME;
    DECLARE days_late     INT;
    SELECT due_date, status, COALESCE(returned_at, NOW())
    INTO v_due_date, v_status, v_returned_at
    FROM loans WHERE id = p_loan_id;
    IF v_status IN ('returned', 'late') THEN
        SET days_late = DATEDIFF(v_returned_at, v_due_date);
        IF days_late > 0 THEN
            SET fine_amount = days_late * 2000.00;
        END IF;
    END IF;
    RETURN fine_amount;
END//

-- ============================================================
-- STORED PROCEDURES
-- ============================================================

CREATE PROCEDURE sp_process_loan_return(IN p_loan_id INT)
BEGIN
    DECLARE v_book_id  INT;
    DECLARE v_quantity INT;
    DECLARE v_done     BOOLEAN DEFAULT FALSE;
    DECLARE detail_cursor CURSOR FOR
        SELECT book_id, quantity FROM loan_details WHERE loan_id = p_loan_id;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_done = TRUE;
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'sp_process_loan_return: transaction failed, rolled back.';
    END;
    START TRANSACTION;
    UPDATE loans SET fine = fn_calculate_fine(p_loan_id) WHERE id = p_loan_id;
    UPDATE loans SET status = 'returned', returned_at = NOW()
    WHERE id = p_loan_id AND status IN ('approved', 'late');
    OPEN detail_cursor;
    read_loop: LOOP
        FETCH detail_cursor INTO v_book_id, v_quantity;
        IF v_done THEN LEAVE read_loop; END IF;
        UPDATE books SET stock = stock + v_quantity WHERE id = v_book_id;
    END LOOP;
    CLOSE detail_cursor;
    COMMIT;
END//

CREATE PROCEDURE sp_approve_loan(IN p_loan_id INT, IN p_admin_id INT)
BEGIN
    DECLARE insufficient_stock INT DEFAULT 0;
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'sp_approve_loan: transaction failed, rolled back.';
    END;
    START TRANSACTION;
    SELECT COUNT(*) INTO insufficient_stock
    FROM loan_details ld
    INNER JOIN books b ON b.id = ld.book_id
    WHERE ld.loan_id = p_loan_id AND ld.quantity > b.stock;
    IF insufficient_stock > 0 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Cannot approve: insufficient stock for one or more books.';
    END IF;
    UPDATE loans SET status = 'approved', approved_by = p_admin_id, approved_at = NOW()
    WHERE id = p_loan_id AND status = 'pending';
    UPDATE books b
    INNER JOIN loan_details ld ON ld.book_id = b.id
    SET b.stock = b.stock - ld.quantity
    WHERE ld.loan_id = p_loan_id;
    COMMIT;
END//

DELIMITER ;

-- ============================================================
-- TRIGGERS
-- ============================================================

DELIMITER //

CREATE TRIGGER trg_before_loan_delete
BEFORE DELETE ON loans
FOR EACH ROW
BEGIN
    IF OLD.status IN ('pending', 'approved', 'late') THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Cannot delete an active loan. Return or reject it first.';
    END IF;
END//

CREATE TRIGGER trg_before_book_delete
BEFORE DELETE ON books
FOR EACH ROW
BEGIN
    DECLARE active_count INT DEFAULT 0;
    SELECT COUNT(*) INTO active_count
    FROM loan_details ld
    INNER JOIN loans l ON l.id = ld.loan_id
    WHERE ld.book_id = OLD.id
      AND l.status IN ('pending', 'approved', 'late');
    IF active_count > 0 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Cannot delete book: it is currently on active loan(s).';
    END IF;
END//

CREATE TRIGGER trg_before_loan_update
BEFORE UPDATE ON loans
FOR EACH ROW
BEGIN
    IF NEW.status = 'approved' AND OLD.status = 'pending' THEN
        SET NEW.approved_at = NOW();
    END IF;
    IF NEW.status = 'returned' AND OLD.status IN ('approved', 'late') THEN
        SET NEW.returned_at = NOW();
    END IF;
END//

DELIMITER ;