CREATE DATABASE school_fees_db;
USE school_fees_db;

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(50) NOT NULL,
    email VARCHAR(100),
    role ENUM('admin', 'staff') DEFAULT 'staff'
);

-- Insert default admin user
INSERT INTO users (username, password, email, role) 
VALUES ('admin', 'password', 'admin@school.com', 'admin');

CREATE TABLE students (
    student_id VARCHAR(20) PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    class VARCHAR(10) NOT NULL,
    section VARCHAR(5),
    parent_name VARCHAR(100),
    parent_contact VARCHAR(15),
    parent_email VARCHAR(100),
    address TEXT,
    admission_date DATE,
    status ENUM('Active', 'Inactive') DEFAULT 'Active'
);

CREATE TABLE fee_structure (
    id INT AUTO_INCREMENT PRIMARY KEY,
    class VARCHAR(10) NOT NULL,
    fee_type VARCHAR(50) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    academic_year VARCHAR(9) NOT NULL
);

CREATE TABLE fee_payments (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(20),
    amount DECIMAL(10,2) NOT NULL,
    payment_date DATE NOT NULL,
    payment_mode VARCHAR(20) NOT NULL,
    receipt_no VARCHAR(20) UNIQUE,
    remarks TEXT,
    FOREIGN KEY (student_id) REFERENCES students(student_id)
);