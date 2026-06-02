CREATE DATABASE IF NOT EXISTS library_management;
USE library_management;

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