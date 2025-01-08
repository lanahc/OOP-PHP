-- Create the database
CREATE DATABASE oop;

-- Use the created database
USE oop;

-- Create the users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email_address VARCHAR(100) NOT NULL UNIQUE,
    phone_number VARCHAR(15) NOT NULL,
    address TEXT NOT NULL,
    gender ENUM('Male', 'Female', 'Other') NOT NULL
);
