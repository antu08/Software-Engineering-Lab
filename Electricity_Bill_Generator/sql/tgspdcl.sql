-- ============================
-- TGSPDCL DATABASE
-- ============================

CREATE DATABASE IF NOT EXISTS tgspdcl;
USE tgspdcl;

-- ============================
-- USERS TABLE
-- ============================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role ENUM('admin','employee') NOT NULL,
    username VARCHAR(50) UNIQUE,
    password VARCHAR(255) NOT NULL
);

INSERT INTO users (role, username, password) VALUES
('admin', 'admin', MD5('1234')),
('employee', 'employee1', MD5('1234')),
('employee', 'employee2', MD5('1234'));

-- ============================
-- CONSUMERS TABLE
-- ============================
CREATE TABLE consumers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    meter_id VARCHAR(10) UNIQUE NOT NULL,
    type ENUM('household','commercial','industry') NOT NULL,
    name VARCHAR(100) NOT NULL,
    mobile VARCHAR(10) NOT NULL,
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
    meter_id VARCHAR(10) NOT NULL,
    prev_unit INT DEFAULT 0,
    curr_unit INT NOT NULL,
    units_used INT NOT NULL,
    month_year VARCHAR(20) NOT NULL
);

-- ============================
-- BILLS TABLE (DETAILED)
-- ============================
CREATE TABLE bills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    meter_id VARCHAR(10) NOT NULL,
    units INT NOT NULL,

    energy_charge DECIMAL(10,2) DEFAULT 0,
    fixed_charge DECIMAL(10,2) DEFAULT 12,
    customer_charge DECIMAL(10,2) DEFAULT 75,
    ed DECIMAL(10,2) DEFAULT 0,
    surcharge DECIMAL(10,2) DEFAULT 0,
    interest DECIMAL(10,2) DEFAULT 0,
    fine DECIMAL(10,2) DEFAULT 0,

    total DECIMAL(10,2) NOT NULL,
    due_date DATE NOT NULL,
    status ENUM('PAID','UNPAID') DEFAULT 'UNPAID'
);
