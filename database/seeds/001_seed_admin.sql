USE library_uas;

INSERT INTO users (name, email, password, role)
VALUES ('Admin Library', 'admin@library.test', '$2y$10$replace_with_bcrypt_hash', 'admin');

