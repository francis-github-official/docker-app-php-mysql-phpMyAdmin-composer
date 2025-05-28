-- database.sql
-- This script will be executed when the MySQL container starts for the first time.

-- Create the database if it doesn't exist
CREATE DATABASE IF NOT EXISTS test_db;

-- Use the newly created database
USE test_db;

-- Create the users table if it doesn't exist
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert some initial data (optional)
INSERT INTO users (name, email) VALUES
('Alice Smith', 'alice.smith@example.com'),
('Bob Johnson', 'bob.johnson@example.com')
ON DUPLICATE KEY UPDATE name=name; -- Prevents re-inserting if email already exists
