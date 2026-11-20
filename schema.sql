-- Personal Health Journal schema (for InfinityFree: no CREATE DATABASE / USE).
-- Select your database in phpMyAdmin first, then import this file.
-- Table names are prefixed hj_ so they do not clash with the old tables.

CREATE TABLE IF NOT EXISTS hj_users (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    name          VARCHAR(100) NOT NULL,
    email         VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS hj_entries (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    user_id    INT NOT NULL,
    entry_date DATE NOT NULL,
    category   VARCHAR(30) NOT NULL,
    details    TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES hj_users(id) ON DELETE CASCADE
) ENGINE=InnoDB;
