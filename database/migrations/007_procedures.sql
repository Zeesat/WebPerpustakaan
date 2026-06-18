-- ============================================================
-- Migration 007: Create stored procedures
-- ============================================================

USE library_management;

DELIMITER //

-- -----------------------------------------------------------
-- 1. sp_process_loan_return(loan_id INT)
--    Atomically processes a loan return:
--      1. Calculate fine via fn_calculate_fine
--      2. Set status = 'returned', returned_at = NOW()
--      3. Restore book stock for every loan_detail row
--    All wrapped in a transaction — rolls back on error.
-- -----------------------------------------------------------
DROP PROCEDURE IF EXISTS sp_process_loan_return//

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

    -- (a) Calculate and persist fine
    UPDATE loans
    SET fine        = fn_calculate_fine(p_loan_id)
    WHERE id = p_loan_id;

    -- (b) Mark as returned
    UPDATE loans
    SET status      = 'returned',
        returned_at = NOW()
    WHERE id = p_loan_id
      AND status IN ('approved', 'late');

    -- (c) Restore stock for each book in the loan
    OPEN detail_cursor;
    read_loop: LOOP
        FETCH detail_cursor INTO v_book_id, v_quantity;
        IF v_done THEN LEAVE read_loop; END IF;

        UPDATE books SET stock = stock + v_quantity WHERE id = v_book_id;
    END LOOP;
    CLOSE detail_cursor;

    COMMIT;
END//

-- -----------------------------------------------------------
-- 2. sp_approve_loan(loan_id INT, admin_id INT)
--    Atomically approves a pending loan:
--      1. Validate that every requested book has sufficient stock
--      2. Set status = 'approved', approved_by, approved_at
--      3. Deduct stock for each book
--    All wrapped in a transaction — rolls back on error.
-- -----------------------------------------------------------
DROP PROCEDURE IF EXISTS sp_approve_loan//

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

    -- (a) Validate stock for every book in the loan
    SELECT COUNT(*) INTO insufficient_stock
    FROM loan_details ld
    INNER JOIN books b ON b.id = ld.book_id
    WHERE ld.loan_id = p_loan_id
      AND ld.quantity > b.stock;

    IF insufficient_stock > 0 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Cannot approve: insufficient stock for one or more books.';
    END IF;

    -- (b) Approve the loan
    UPDATE loans
    SET status      = 'approved',
        approved_by = p_admin_id,
        approved_at = NOW()
    WHERE id = p_loan_id
      AND status = 'pending';

    -- (c) Deduct stock
    UPDATE books b
    INNER JOIN loan_details ld ON ld.book_id = b.id
    SET b.stock = b.stock - ld.quantity
    WHERE ld.loan_id = p_loan_id;

    COMMIT;
END//

DELIMITER ;
