SYSTEM: Library Loan Management System

OVERVIEW:
A web-based library system that manages book borrowing with a verification workflow. The system supports two roles: USER and ADMIN. Borrowing is not instantly approved; instead, it requires validation to ensure realistic constraints such as stock availability and user eligibility.

HTML, CSS, JS, PHP Native. No framework

ROLES:

1. USER:

   * Register and login
   * Browse books
   * Request book loans
   * View loan status and history

2. ADMIN:

   * Manage books and categories (CRUD)
   * View and verify loan requests
   * Approve or reject loans
   * Process book returns

DATABASE SCHEMA:

TABLE users:

* id (PK)
* name
* email
* password
* role ENUM('user','admin')
* created_at

TABLE categories:

* id (PK)
* name

TABLE books:

* id (PK)
* title
* author
* category_id (FK -> categories.id)
* stock
* description

TABLE loans:

* id (PK)
* user_id (FK -> users.id)
* loan_date
* due_date
* status ENUM('pending','approved','rejected','returned','late')
* approved_by (FK -> users.id, nullable)
* approved_at (datetime, nullable)

TABLE loan_details:

* id (PK)
* loan_id (FK -> loans.id)
* book_id (FK -> books.id)
* quantity

BUSINESS RULES:

1. Loan Request:

* A user can request a loan by selecting a book.
* The system creates a loan record with status = 'pending'.
* The system creates loan_details linked to the loan.
* A user cannot request a loan if:

  * They already have an active loan (status = 'pending' or 'approved')
  * They exceeded the maximum allowed books (e.g., 3 books)

2. Loan Approval:

* Admin reviews pending loans.
* If approved:

  * status → 'approved'
  * approved_by and approved_at are filled
  * book stock is reduced
* If rejected:

  * status → 'rejected'

3. Loan Return:

* Admin marks loan as returned.
* status → 'returned'
* book stock is increased

4. Late Handling:

* If current_date > due_date AND status = 'approved':

  * status → 'late'
* Optional fine:

  * fine = (days_late × 1000)

5. Stock Validation:

* Stock must be checked at approval time (not only at request time).
* If stock is insufficient at approval:

  * loan is rejected

6. Expiration:

* Pending loans older than 2 days are automatically rejected.

SYSTEM FLOW:

USER FLOW:

1. User logs in
2. User browses books
3. User clicks "Request Loan"
4. System creates loan (status = pending)
5. User waits for admin verification
6. User sees status updates (pending / approved / rejected)

ADMIN FLOW:

1. Admin logs in
2. Admin views list of pending loans
3. Admin approves or rejects
4. Admin handles returns

API ENDPOINTS (example):

POST   /auth/login
POST   /auth/register
GET    /books
GET    /books/{id}
POST   /loans
GET    /loans/my
GET    /admin/loans/pending
PUT    /admin/loans/{id}/approve
PUT    /admin/loans/{id}/reject
PUT    /admin/loans/{id}/return

FRONTEND PAGES:

PUBLIC:

* Home
* Book List
* Book Detail
* Login/Register

USER:

* Dashboard
* My Loans
* Request Loan

ADMIN:

* Admin Dashboard
* Manage Books
* Manage Categories
* Manage Users
* Loan Verification Panel

NOTES:

* The system emphasizes realistic workflows instead of instant transactions.
* Verification ensures control over inventory and user behavior.
* Designed to simulate real-world library operations.

END SYSTEM
