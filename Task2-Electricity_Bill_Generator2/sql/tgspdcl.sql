-- ============================
-- TGSPDCL DATABASE REFACTOR
-- ============================

DROP DATABASE IF EXISTS tgspdcl;
CREATE DATABASE tgspdcl;
USE tgspdcl;

-- ============================
-- USERS TABLE (Admin & Employees)
-- ============================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role ENUM('admin','employee') NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100), -- For Employees
    mobile VARCHAR(15), -- For Employees
    address TEXT, -- For Employees
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Default Admin
INSERT INTO users (role, username, password, name) VALUES
('admin', 'admin', MD5('1234'), 'Super Admin');

-- ============================
-- CONSUMERS TABLE
-- ============================
CREATE TABLE consumers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    service_number VARCHAR(6) UNIQUE NOT NULL, -- 6 Digit Unique Service Number (USCNO)
    type ENUM('household','commercial','industry') NOT NULL,
    name VARCHAR(100) NOT NULL,
    mobile VARCHAR(15) NOT NULL,
    address TEXT NOT NULL,
    pincode VARCHAR(6) NOT NULL,
    license_id VARCHAR(50), 
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================
-- METER READINGS TABLE
-- ============================
CREATE TABLE meter_readings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    service_number VARCHAR(6) NOT NULL,
    prev_unit INT DEFAULT 0,
    curr_unit INT NOT NULL,
    units_used INT NOT NULL,
    month_year VARCHAR(20) NOT NULL,
    reading_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (service_number) REFERENCES consumers(service_number) ON DELETE CASCADE
);

-- ============================
-- BILLS TABLE (Detailed)
-- ============================
CREATE TABLE bills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    service_number VARCHAR(6) NOT NULL,
    units INT NOT NULL,

    energy_charge DECIMAL(10,2) DEFAULT 0,
    fixed_charge DECIMAL(10,2) DEFAULT 0,
    customer_charge DECIMAL(10,2) DEFAULT 0,
    ed DECIMAL(10,2) DEFAULT 0,
    surcharge DECIMAL(10,2) DEFAULT 0,
    interest DECIMAL(10,2) DEFAULT 0,
    fine DECIMAL(10,2) DEFAULT 0,

    total DECIMAL(10,2) NOT NULL,
    due_date DATE NOT NULL,
    status ENUM('PAID','UNPAID') DEFAULT 'UNPAID',
    generated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (service_number) REFERENCES consumers(service_number) ON DELETE CASCADE
);
