-- SDC Dental Care Management System
-- Initial Seed Data

USE `sdc_dental_db`;

-- 1. Branches
INSERT INTO `branches` (`id`, `branch_name`, `branch_code`, `address`, `contact_number`, `is_active`) VALUES
(1, 'SDC Dental Care - Main Branch', 'SDC-MAIN', '123 Health Ave, Medical City', '+63 917 111 2233', 1),
(2, 'SDC Dental Care - Uptown Branch', 'SDC-UPTOWN', '456 Business Blvd, Suite 2B', '+63 918 444 5566', 1);

-- 2. Users (Password for all is 'password123', hashed with BCRYPT)
INSERT INTO `users` (`id`, `branch_id`, `role`, `username`, `password_hash`, `full_name`, `email`, `phone`, `status`) VALUES
(1, 1, 'admin', 'admin', '$2y$10$haQktrY94Kx7cAEoFDawIu8mz5mLHEStNndGctiuvbulevp1C7K5q', 'Admin Manager', 'admin@sdcdental.com', '+63 917 000 0001', 'active'),
(2, 1, 'dentist', 'dr.maria', '$2y$10$haQktrY94Kx7cAEoFDawIu8mz5mLHEStNndGctiuvbulevp1C7K5q', 'Dr. Maria Santos, DMD', 'maria.santos@sdcdental.com', '+63 917 000 0002', 'active'),
(3, 1, 'dentist', 'dr.james', '$2y$10$haQktrY94Kx7cAEoFDawIu8mz5mLHEStNndGctiuvbulevp1C7K5q', 'Dr. James Cruz, DMD', 'james.cruz@sdcdental.com', '+63 917 000 0003', 'active'),
(4, 1, 'front_desk', 'reception', '$2y$10$haQktrY94Kx7cAEoFDawIu8mz5mLHEStNndGctiuvbulevp1C7K5q', 'Sarah Jenkins', 'reception@sdcdental.com', '+63 917 000 0004', 'active'),
(5, 1, 'assistant', 'assistant', '$2y$10$haQktrY94Kx7cAEoFDawIu8mz5mLHEStNndGctiuvbulevp1C7K5q', 'Mark Lee', 'mark.lee@sdcdental.com', '+63 917 000 0005', 'active');

-- 3. Dentists Profile
INSERT INTO `dentists` (`id`, `user_id`, `license_no`, `specialization`, `default_commission_rate`) VALUES
(1, 2, 'PRC-0089211', 'Orthodontics & General Dentistry', 40.00),
(2, 3, 'PRC-0091442', 'Endodontics & Oral Surgery', 50.00);

-- 4. Treatments Catalog
INSERT INTO `treatments` (`id`, `treatment_code`, `treatment_name`, `category`, `standard_price`, `fixed_commission_rate`, `description`, `is_active`) VALUES
(1, 'TRT-001', 'Comprehensive Oral Examination & Consultation', 'Preventive', 500.00, 30.00, 'Full mouth checkup with treatment planning', 1),
(2, 'TRT-002', 'Dental Prophylaxis (Cleaning) - Mild', 'Preventive', 1200.00, 30.00, 'Routine ultrasonic scaling and polishing', 1),
(3, 'TRT-003', 'Dental Prophylaxis (Cleaning) - Deep Scaling', 'Periodontics', 2000.00, 40.00, 'Deep periodontal scaling and root planing', 1),
(4, 'TRT-004', 'Composite Resin Restoration (Tooth Filling)', 'Restorative', 1500.00, 30.00, 'Light-cured tooth-colored filling per surface', 1),
(5, 'TRT-005', 'Simple Tooth Extraction', 'Oral Surgery', 1200.00, 40.00, 'Extraction of erupted non-surgical tooth', 1),
(6, 'TRT-006', 'Surgical Extraction / Odontectomy (Impacted Molar)', 'Oral Surgery', 7500.00, 50.00, 'Surgical removal of impacted third molar with suturing', 1),
(7, 'TRT-007', 'Root Canal Therapy (Anterior)', 'Endodontics', 8000.00, 50.00, 'Complete endodontic treatment for single canal anterior tooth', 1),
(8, 'TRT-008', 'Orthodontic Braces (Package - Metal)', 'Orthodontics', 45000.00, 40.00, 'Upper and lower metal brackets with monthly adjustments', 1),
(9, 'TRT-009', 'Laser Teeth Whitening', 'Cosmetic', 6500.00, 30.00, 'In-office professional dental bleaching system', 1);

-- 5. Dental Supplies Catalog
INSERT INTO `supplies` (`id`, `item_code`, `item_name`, `category`, `unit_of_measure`, `current_stock`, `reorder_level`, `unit_cost`, `is_active`) VALUES
(1, 'SUP-001', 'Lidocaine HCl 2% with Epinephrine Carpules', 'Anesthesia', 'carpule', 120, 30, 45.00, 1),
(2, 'SUP-002', 'Composite Hybrid Resin Syringe (Shade A2)', 'Restorative', 'syringe', 18, 5, 850.00, 1),
(3, 'SUP-003', 'Etchant Gel 37% Phosphoric Acid', 'Restorative', 'syringe', 12, 4, 320.00, 1),
(4, 'SUP-004', 'Dental Bonding Adhesive (Single Bottle)', 'Restorative', 'bottle', 8, 3, 1400.00, 1),
(5, 'SUP-005', 'Nitrile Examination Gloves (Medium)', 'PPE', 'box (100pcs)', 25, 10, 280.00, 1),
(6, 'SUP-006', 'Disposable Saliva Ejector Suction Tips', 'Disposables', 'pack (100pcs)', 15, 5, 160.00, 1),
(7, 'SUP-007', 'Prophy Paste Mint Flavor', 'Preventive', 'jar', 6, 2, 450.00, 1),
(8, 'SUP-008', 'Dental Needles 27G Short', 'Anesthesia', 'box (100pcs)', 10, 3, 350.00, 1);

-- 6. Sample Patients (with occupation)
INSERT INTO `patients` (`id`, `patient_code`, `first_name`, `last_name`, `gender`, `date_of_birth`, `phone`, `email`, `address`, `occupation`, `medical_history`, `allergies`) VALUES
(1, 'PAT-2026-001', 'Juan', 'Dela Cruz', 'male', '1992-05-14', '+63 917 555 1234', 'juan.delacruz@email.com', 'Blk 12 Lot 4, Sunrise Village, Makati', 'Software Engineer', 'No known systemic illness', 'Penicillin'),
(2, 'PAT-2026-002', 'Maria Clara', 'Reyes', 'female', '1988-11-20', '+63 920 888 5678', 'maria.reyes@email.com', 'Unit 504 Tower 1, Cypress St, Pasig', 'High School Teacher', 'Mild hypertension (controlled)', 'None'),
(3, 'PAT-2026-003', 'Carlos', 'Tan', 'male', '1999-03-08', '+63 928 333 7788', 'carlos.tan@email.com', '78 Mabini St, San Juan', 'Bank Financial Analyst', 'Asthma (seasonal)', 'Aspirin');

-- 7. Sample Appointments
INSERT INTO `appointments` (`id`, `appointment_code`, `branch_id`, `patient_id`, `guest_name`, `guest_phone`, `preferred_dentist_id`, `assigned_dentist_id`, `reassignment_reason`, `appointment_date`, `appointment_time`, `dental_concern`, `source`, `status`, `notes`) VALUES
(1, 'APT-2026-001', 1, 1, NULL, NULL, 1, 1, NULL, CURDATE(), '09:00:00', 'Toothache on lower right molar and severe sensitivity', 'walk_in', 'completed', 'Patient arrived on time, completed treatment.'),
(2, 'APT-2026-002', 1, 2, NULL, NULL, 1, 2, 'Preferred dentist Dr. Santos was in emergency ortho surgery; reassigned with patient verbal consent.', CURDATE(), '10:30:00', 'Annual routine oral cleaning and prophylaxis', 'phone', 'completed', 'Cleaned successfully.'),
(3, 'APT-2026-003', 1, 3, NULL, NULL, 1, 1, NULL, CURDATE(), '14:00:00', 'Braces adjustment and wire replacement', 'online_portal', 'in_progress', 'Ongoing appointment.'),
(4, 'APT-2026-004', 1, NULL, 'Roberto Gomez', '+63 919 777 9999', 2, 2, NULL, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '11:00:00', 'Impacted wisdom tooth pain and swelling', 'online_portal', 'pending_confirmation', 'Online booking awaiting front desk confirmation.');

-- 8. Sample Performed Treatments & Commission Records
INSERT INTO `appointment_treatments` (`id`, `appointment_id`, `treatment_id`, `dentist_id`, `actual_price`, `commission_rate`, `dentist_commission_amount`, `clinic_share_amount`, `notes`) VALUES
-- Appointment 1: Composite filling (1500 @ 30%) and Tooth Extraction (1200 @ 40%) by Dr. Maria Santos
(1, 1, 4, 1, 1500.00, 30.00, 450.00, 1050.00, 'Restored tooth #46 occlusal surface.'),
(2, 1, 5, 1, 1200.00, 40.00, 480.00, 720.00, 'Extracted tooth #48 uncomplicated.'),
-- Appointment 2: Prophylaxis Deep Scaling (2000 @ 40%) by Dr. James Cruz
(3, 2, 3, 2, 2000.00, 40.00, 800.00, 1200.00, 'Deep scaling all 4 quadrants.');

-- 9. Billing Invoices (One fully paid, one partial for ongoing treatment)
INSERT INTO `billing_invoices` (`id`, `invoice_number`, `appointment_id`, `patient_id`, `branch_id`, `subtotal`, `discount_amount`, `total_amount`, `paid_amount`, `balance_amount`, `payment_status`) VALUES
(1, 'INV-2026-0001', 1, 1, 1, 2700.00, 200.00, 2500.00, 2500.00, 0.00, 'paid'),
(2, 'INV-2026-0002', 2, 2, 1, 2000.00, 0.00, 2000.00, 1000.00, 1000.00, 'partially_paid');

-- 10. Payments Recorded (Cash, Online, Card)
INSERT INTO `payments` (`id`, `receipt_number`, `invoice_id`, `patient_id`, `branch_id`, `amount_paid`, `payment_method`, `reference_number`, `received_by`, `notes`) VALUES
(1, 'RCT-2026-0001', 1, 1, 1, 1500.00, 'Cash', NULL, 4, 'Initial cash down payment upon arrival'),
(2, 'RCT-2026-0002', 1, 1, 1, 1000.00, 'Online', 'GCASH-TXN-9988221', 4, 'Settled remaining balance via GCash'),
(3, 'RCT-2026-0003', 2, 2, 1, 1000.00, 'Card', 'VISA-APPROVAL-55410', 4, 'Partial payment via BDO Visa Debit');

-- 11. Sample Daily Expenses
INSERT INTO `expenses` (`id`, `branch_id`, `category`, `title`, `amount`, `payment_method`, `expense_date`, `recorded_by`, `notes`) VALUES
(1, 1, 'utilities', 'Meralco Electric Bill (Clinic A/C & Compressors)', 3200.00, 'Online', CURDATE(), 4, 'Paid via clinic bank account'),
(2, 1, 'supplies', 'Purified Distilled Water for Autoclave', 180.00, 'Cash', CURDATE(), 4, 'Cash on delivery for 6 bottles'),
(3, 1, 'miscellaneous', 'Clinic Reception Fresh Flowers & Sanitizer', 350.00, 'Cash', CURDATE(), 4, 'Weekly lobby refreshments and amenities');

-- 12. Sample Per-Patient Supply Usage
INSERT INTO `patient_supply_usage` (`id`, `appointment_id`, `patient_id`, `supply_id`, `quantity_used`, `recorded_by`, `notes`) VALUES
(1, 1, 1, 1, 2, 5, '2 carpules Lidocaine used for tooth #46 and #48'),
(2, 1, 1, 2, 1, 5, 'Composite resin tip shade A2'),
(3, 1, 1, 6, 2, 5, 'Suction tips consumed'),
(4, 2, 2, 7, 1, 5, 'Prophy paste applied');

