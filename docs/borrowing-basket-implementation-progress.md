# Borrowing Basket Implementation Progress

Date: 2026-06-13

## Objective

Implement a browser-based borrowing basket for the library platform using:

- PHP Native
- HTML
- CSS
- Vanilla JavaScript

The basket should collect selected books locally first, then submit one loan request with multiple loan details only when the user confirms.

## Delivered

### Backend

- Added `POST /loans/request` as the basket submission endpoint.
- Added `BorrowBasketService` to centralize:
  - basket page config
  - request rules
  - active-loan blocking
  - max-item validation
  - stock revalidation at submit time
  - transactional creation of:
    - one `loans` row
    - many `loan_details` rows
- Added JSON-aware auth middleware handling so AJAX basket submission receives a proper `401` JSON response instead of an HTML redirect.
- Added model helpers for:
  - fetching multiple books by id
  - creating pending loans
  - creating loan detail rows

### Frontend

- Added global basket navigation control with:
  - live badge count
  - empty/filled/warning visual states
  - mobile sticky review bar
- Added localStorage-backed basket behavior with:
  - duplicate prevention
  - max-limit enforcement
  - TTL expiration
  - account-scoped basket safety
  - cross-tab synchronization
- Added add-to-basket interactions on:
  - catalog cards
  - book detail page
  - related books section
- Added toast feedback and basket-control pulse microinteractions.

### Basket Page

- Replaced the placeholder request page with a full basket experience:
  - summary highlights
  - rule chips
  - selected book list
  - remove action
  - clear basket action
  - submit summary panel
  - empty state
  - success state
  - inline validation feedback

## Key Rules Implemented

- Maximum request size defaults to `3` books.
- Quantity is fixed to `1` per title in V1.
- Existing active or pending loan blocks new submission.
- Titles with `stock <= 0` cannot be submitted.
- Basket is cleared after successful submission.
- Basket expires after a configurable local inactivity window.

## Main Files Touched

- `app/core/App.php`
- `app/controllers/LoanController.php`
- `app/helpers/functions.php`
- `app/middleware/AuthMiddleware.php`
- `app/models/Book.php`
- `app/models/Loan.php`
- `app/models/LoanDetail.php`
- `app/services/BookService.php`
- `app/services/BorrowBasketService.php`
- `app/views/books/index.php`
- `app/views/books/show.php`
- `app/views/layouts/main.php`
- `app/views/loans/request.php`
- `app/views/partials/navbar-landing.php`
- `public/assets/css/app.css`
- `public/assets/js/app.js`

## Verification Performed

- PHP syntax checks on updated backend and view files
- JavaScript syntax check with `node --check`

## Suggested Next Iterations

- Add automated tests for basket submission validation and persistence edge cases.
- Add admin-side loan verification UI that reads and approves real submitted requests.
- Add optional server-side basket preview endpoint if live revalidation before submit becomes necessary.
