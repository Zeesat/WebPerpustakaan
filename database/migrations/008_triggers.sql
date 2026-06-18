-- ============================================================
-- Migration 008: Create triggers
-- ============================================================

USE library_management;

DELIMITER //

-- -----------------------------------------------------------
-- 1. trg_before_loan_delete
--    Prevents deletion of a loan that is still active
--    (pending, approved, or late). Only 'returned' and
--    'rejected' loans may be deleted.
-- -----------------------------------------------------------
DROP TRIGGER IF EXISTS trg_before_loan_delete//

CREATE TRIGGER trg_before_loan_delete
BEFORE DELETE ON loans
FOR EACH ROW
BEGIN
    IF OLD.status IN ('pending', 'approved', 'late') THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Cannot delete an active loan. Return or reject it first.';
    END IF;
END//

-- -----------------------------------------------------------
-- 2. trg_before_book_delete
--    Prevents deletion of a book that is currently part of
--    any active loan (pending, approved, or late).
-- -----------------------------------------------------------
DROP TRIGGER IF EXISTS trg_before_book_delete//

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

-- -----------------------------------------------------------
-- 3. trg_before_loan_update
--    Auto-sets timestamp columns when the status changes:
--      pending → approved  →  approved_at = NOW()
--      approved/late → returned  →  returned_at = NOW()
-- -----------------------------------------------------------
DROP TRIGGER IF EXISTS trg_before_loan_update//

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
