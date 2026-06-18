-- ============================================================
-- Migration 006: Create stored functions
-- ============================================================

USE library_management;

DELIMITER //

-- -----------------------------------------------------------
-- 1. fn_count_active_loans(user_id INT) → INT
--    Returns how many active (pending|approved|late) loans
--    a given user currently has.
--    Replaces Loan::countActiveByUser().
-- -----------------------------------------------------------
DROP FUNCTION IF EXISTS fn_count_active_loans//

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

-- -----------------------------------------------------------
-- 2. fn_calculate_fine(loan_id INT) → DECIMAL(10,2)
--    Calculates the late fine for a loan based on the
--    difference between returned_at (or NOW) and due_date.
--    Rate: IDR 2 000 per day late.
-- -----------------------------------------------------------
DROP FUNCTION IF EXISTS fn_calculate_fine//

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
    FROM loans
    WHERE id = p_loan_id;

    -- Only calculate fine when status is 'returned' or 'late'
    IF v_status IN ('returned', 'late') THEN
        SET days_late = DATEDIFF(v_returned_at, v_due_date);
        IF days_late > 0 THEN
            SET fine_amount = days_late * 2000.00;
        END IF;
    END IF;

    RETURN fine_amount;
END//

DELIMITER ;
