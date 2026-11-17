-- SDC Dental Care Management System
-- Complete Database Schema DDL
-- Compatible with MySQL 5.7+ / MariaDB 10.4+ / InnoDB

CREATE DATABASE IF NOT EXISTS `sdc_dental_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `sdc_dental_db`;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `patient_supply_usage`;
DROP TABLE IF EXISTS `supplies`;
DROP TABLE IF EXISTS `expenses`;
DROP TABLE IF EXISTS `payments`;
DROP TABLE IF EXISTS `billing_invoices`;
DROP TABLE IF EXISTS `appointment_treatments`;
DROP TABLE IF EXISTS `appointments`;
DROP TABLE IF EXISTS `patient_files`;
DROP TABLE IF EXISTS `patients`;
DROP TABLE IF EXISTS `treatments`;
DROP TABLE IF EXISTS `dentists`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `branches`;
SET FOREIGN_KEY_CHECKS = 1;

-- 1. Branches Table
CREATE TABLE `branches` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `branch_name` VARCHAR(100) NOT NULL,
    `branch_code` VARCHAR(20) UNIQUE NOT NULL,
    `address` TEXT,
    `contact_number` VARCHAR(30),
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Users Table
CREATE TABLE `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `branch_id` INT NULL,
    `role` ENUM('admin', 'dentist', 'front_desk', 'assistant') NOT NULL,
    `username` VARCHAR(50) UNIQUE NOT NULL,
    `password_hash` VARCHAR(255) NOT NULL,
    `full_name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) UNIQUE,
    `phone` VARCHAR(30),
    `status` ENUM('active', 'inactive') DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`branch_id`) REFERENCES `branches`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Dentists Profile & Commission Defaults
CREATE TABLE `dentists` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL UNIQUE,
    `license_no` VARCHAR(50),
    `specialization` VARCHAR(100) DEFAULT 'General Dentistry',
    `default_commission_rate` DECIMAL(5,2) DEFAULT 30.00,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Patients Table (Includes occupation as requested)
CREATE TABLE `patients` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `patient_code` VARCHAR(30) UNIQUE NOT NULL,
    `first_name` VARCHAR(50) NOT NULL,
    `last_name` VARCHAR(50) NOT NULL,
    `gender` ENUM('male', 'female', 'other') DEFAULT 'other',
    `date_of_birth` DATE NULL,
    `phone` VARCHAR(30) NOT NULL,
    `email` VARCHAR(100) NULL,
    `address` TEXT NULL,
    `occupation` VARCHAR(100) NULL,
    `medical_history` TEXT NULL,
    `allergies` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Patient Files & X-rays
CREATE TABLE `patient_files` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `patient_id` INT NOT NULL,
    `file_title` VARCHAR(150) NOT NULL,
    `file_path` VARCHAR(255) NOT NULL,
    `file_type` VARCHAR(50) NOT NULL,
    `file_size_bytes` INT NOT NULL,
    `uploaded_by` INT NOT NULL,
    `uploaded_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`patient_id`) REFERENCES `patients`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`uploaded_by`) REFERENCES `users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Treatments Master Catalog with Fixed Commission Rates
CREATE TABLE `treatments` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `treatment_code` VARCHAR(30) UNIQUE NOT NULL,
    `treatment_name` VARCHAR(100) NOT NULL,
    `category` VARCHAR(50) NOT NULL DEFAULT 'General',
    `standard_price` DECIMAL(10,2) NOT NULL,
    `fixed_commission_rate` DECIMAL(5,2) NOT NULL DEFAULT 30.00,
    `description` TEXT,
    `is_active` TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Appointments & Online Booking
CREATE TABLE `appointments` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `appointment_code` VARCHAR(30) UNIQUE NOT NULL,
    `branch_id` INT NOT NULL,
    `patient_id` INT NULL,
    `guest_name` VARCHAR(100) NULL,
    `guest_phone` VARCHAR(30) NULL,
    `preferred_dentist_id` INT NULL,
    `assigned_dentist_id` INT NULL,
    `reassignment_reason` TEXT NULL,
    `appointment_date` DATE NOT NULL,
    `appointment_time` TIME NOT NULL,
    `dental_concern` TEXT NOT NULL,
    `source` ENUM('online_portal', 'walk_in', 'phone') DEFAULT 'walk_in',
    `status` ENUM('pending_confirmation', 'confirmed', 'in_progress', 'completed', 'cancelled', 'no_show') DEFAULT 'confirmed',
    `notes` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`branch_id`) REFERENCES `branches`(`id`),
    FOREIGN KEY (`patient_id`) REFERENCES `patients`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`preferred_dentist_id`) REFERENCES `dentists`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`assigned_dentist_id`) REFERENCES `dentists`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. Treatments Performed in Appointment (Commission Basis)
CREATE TABLE `appointment_treatments` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `appointment_id` INT NOT NULL,
    `treatment_id` INT NOT NULL,
    `dentist_id` INT NOT NULL,
    `actual_price` DECIMAL(10,2) NOT NULL,
    `commission_rate` DECIMAL(5,2) NOT NULL,
    `dentist_commission_amount` DECIMAL(10,2) NOT NULL,
    `clinic_share_amount` DECIMAL(10,2) NOT NULL,
    `notes` TEXT NULL,
    `performed_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`appointment_id`) REFERENCES `appointments`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`treatment_id`) REFERENCES `treatments`(`id`),
    FOREIGN KEY (`dentist_id`) REFERENCES `dentists`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. Billing Invoices & Balances for Ongoing Treatments
CREATE TABLE `billing_invoices` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `invoice_number` VARCHAR(30) UNIQUE NOT NULL,
    `appointment_id` INT NOT NULL,
    `patient_id` INT NOT NULL,
    `branch_id` INT NOT NULL,
    `subtotal` DECIMAL(10,2) NOT NULL,
    `discount_amount` DECIMAL(10,2) DEFAULT 0.00,
    `total_amount` DECIMAL(10,2) NOT NULL,
    `paid_amount` DECIMAL(10,2) DEFAULT 0.00,
    `balance_amount` DECIMAL(10,2) NOT NULL,
    `payment_status` ENUM('unpaid', 'partially_paid', 'paid') DEFAULT 'unpaid',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`appointment_id`) REFERENCES `appointments`(`id`),
    FOREIGN KEY (`patient_id`) REFERENCES `patients`(`id`),
    FOREIGN KEY (`branch_id`) REFERENCES `branches`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. Payment Transactions (Cash, Online, Card)
CREATE TABLE `payments` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `receipt_number` VARCHAR(30) UNIQUE NOT NULL,
    `invoice_id` INT NOT NULL,
    `patient_id` INT NOT NULL,
    `branch_id` INT NOT NULL,
    `amount_paid` DECIMAL(10,2) NOT NULL,
    `payment_method` ENUM('Cash', 'Online', 'Card') NOT NULL,
    `reference_number` VARCHAR(100) NULL,
    `received_by` INT NOT NULL,
    `payment_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `notes` TEXT,
    FOREIGN KEY (`invoice_id`) REFERENCES `billing_invoices`(`id`),
    FOREIGN KEY (`patient_id`) REFERENCES `patients`(`id`),
    FOREIGN KEY (`branch_id`) REFERENCES `branches`(`id`),
    FOREIGN KEY (`received_by`) REFERENCES `users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 11. Daily Expenses Table
CREATE TABLE `expenses` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `branch_id` INT NOT NULL,
    `category` ENUM('utilities', 'supplies', 'rent', 'salaries', 'maintenance', 'laboratory', 'miscellaneous') NOT NULL,
    `title` VARCHAR(150) NOT NULL,
    `amount` DECIMAL(10,2) NOT NULL,
    `payment_method` ENUM('Cash', 'Online', 'Card') DEFAULT 'Cash',
    `expense_date` DATE NOT NULL,
    `receipt_attachment` VARCHAR(255) NULL,
    `recorded_by` INT NOT NULL,
    `notes` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`branch_id`) REFERENCES `branches`(`id`),
    FOREIGN KEY (`recorded_by`) REFERENCES `users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 12. Dental Supplies Catalog & Inventory
CREATE TABLE `supplies` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `item_code` VARCHAR(30) UNIQUE NOT NULL,
    `item_name` VARCHAR(100) NOT NULL,
    `category` VARCHAR(50) DEFAULT 'General Dental',
    `unit_of_measure` VARCHAR(30) NOT NULL,
    `current_stock` INT NOT NULL DEFAULT 0,
    `reorder_level` INT NOT NULL DEFAULT 10,
    `unit_cost` DECIMAL(10,2) DEFAULT 0.00,
    `is_active` TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 13. Per-Patient Supply Usage
CREATE TABLE `patient_supply_usage` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `appointment_id` INT NOT NULL,
    `patient_id` INT NOT NULL,
    `supply_id` INT NOT NULL,
    `quantity_used` INT NOT NULL,
    `recorded_by` INT NOT NULL,
    `recorded_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `notes` VARCHAR(255) NULL,
    FOREIGN KEY (`appointment_id`) REFERENCES `appointments`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`patient_id`) REFERENCES `patients`(`id`),
    FOREIGN KEY (`supply_id`) REFERENCES `supplies`(`id`),
    FOREIGN KEY (`recorded_by`) REFERENCES `users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

