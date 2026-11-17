# SDC Dental Care Management System
A comprehensive, multi-branch Dental Clinic Management & ERP System engineered with **PHP 8.2** and **MySQL/MariaDB**.

---

## 🚀 Quick Setup with XAMPP

### 1. Start XAMPP Services
Open **XAMPP Control Panel** and click **Start** on:
- **Apache**
- **MySQL**

### 2. Run the Auto-Installer
Open your web browser and navigate to:
```
http://localhost/SDC-DENTAL-CLINIC/install.php
```
Click **Start System Setup**. This will:
- Create the `sdc_dental_db` database
- Execute all tables and foreign key relationships (`database/schema.sql`)
- Populate initial branches, default treatments, commission rates, supplies, and test accounts (`database/seeders.sql`)
- Create necessary upload directories for patient X-rays and receipts

*(Alternatively, you can run `php install.php` directly from PowerShell/Terminal or import `database/schema.sql` and `database/seeders.sql` via phpMyAdmin).*

### 3. Open the Application
Navigate to:
```
http://localhost/SDC-DENTAL-CLINIC/
```

---

## 🔑 Default Login Credentials

All demo accounts use the default password: **`password123`**

| Role | Username | Description / Permissions |
| :--- | :--- | :--- |
| **Admin / Manager** | `admin` | Full system access, multi-branch consolidated KPIs, commission configuration, dentist payout reports, transparent math inspector. |
| **Dentist** | `dr.maria` | Clinical procedures recording, personal commission share ledger, assigned appointments, patient medical history. |
| **Dentist** | `dr.james` | Secondary attending dentist profile for reassignment and commission tracking. |
| **Front Desk** | `reception` | Scheduling & reassignment, patient registration (with occupation), billing, payments (Cash, Online, Card), receipt printing, expense entry. |
| **Dental Assistant** | `assistant` | Supply usage tracking per patient, inventory catalog, stock adjustments. |

### 🌐 Public Online Booking Portal:
Patients can directly book appointments without logging in at:
```
http://localhost/SDC-DENTAL-CLINIC/index.php?page=portal
```

---

## 📋 Features Implemented (Based on Manager Interview)

### 1. Admin & Financial Management
- **Per-branch financial tracking & reporting**: Switch between all branches or individual clinics in the top navigation bar.
- **Overall KPI Dashboard**: Total income filterable by **Day**, **Week**, **Month**, and **Year** with interactive 12-month Chart.js visual trends.
- **Daily Expense Tracking**: Detailed categorised expenses (utilities, supplies, rent, salaries, maintenance, laboratory, misc) with receipt attachment support.
- **Daily Net Income**: Total income minus operating disbursements computed automatically.
- **Payment Method Breakdown**: Real-time gross, expenses, and net breakdown for **Cash**, **Online** (GCash/Maya/Bank Transfer), and **Card**.
- **Transparent Calculations**: Interactive **"Inspect Math"** modal that reveals the exact arithmetic formula, gross receipts, and itemized deductions behind every metric.

### 2. Commission Management
- **Configurable Commission Splits**: 20%, 30%, 40%, or 50% split options.
- **Fixed Commission Rate per Treatment**: Pre-configured standard split rate for each procedure (e.g. Prophylaxis 30%, Extractions 40%, Root Canals 50%).
- **Basis for Commission**: Attending dentists log performed treatments; commission splits are calculated automatically upon entry.
- **Dentist Earnings View**: View total billed revenue and earned commission share per doctor.
- **Filters**: Filter the commission ledger by dentist, treatment type, branch, and custom date range.

### 3. Front Desk & Scheduling
- **Appointments & Scheduling**: Schedule, update, check-in, and complete appointments.
- **Dentist Selection & Reassignment**: Select the patient's preferred dentist. If unavailable, reassign to an alternate dentist with mandatory audit reasoning.
- **Online Booking Triage**: Incoming portal requests arrive in the front desk queue with status *Pending Confirmation*.

### 4. Payments, Receipts & Balances
- **Multi-Payment Methods**: Flexible support for **Cash**, **Online** (with reference number), and **Card** (with POS approval slip number).
- **Ongoing Treatment Balances**: Real-time remaining balance tracking for multi-session procedures (e.g., Orthodontics/Braces, Root Canal therapy).
- **Printable Receipts**: Clean, responsive receipt template formatted for standard **80mm POS Thermal Printers** and standard **A4/A5 paper**. Displays clinic details, patient info, attending doctor, itemized procedures, amount paid, and **remaining balance due**.

### 5. Patient Records (EMR)
- **Patient Profile & History**: Full demographic profile, past dental concerns, and chronological treatment history with attending dentists.
- **Occupation Field**: Prominently integrated into patient registration and profiles as requested.
- **File & X-Ray Uploads**: Drag-and-drop or select X-rays (Panorex, Periapical, bitewing JPG/PNG/DICOM) and medical documents with inline preview.

### 6. Dental Inventory
- **Supplies Catalog**: Master list of dental consumables, stock on hand, and reorder levels.
- **Per-Patient Supply Usage**: Assistant logs exact consumables used during a patient's appointment (e.g. anesthesia carpules, composite syringes, suction tips), automatically deducting clinic stock.

---

## 🏗️ Project Architecture

```
SDC-DENTAL-CLINIC/
├── config/
│   ├── constants.php            # App settings, commission tiers, paths
│   ├── database.php             # PDO Connection & Singleton query wrapper
│   └── session.php              # Auth helper, CSRF & RBAC middleware
├── controllers/
│   ├── AuthController.php       # Authentication & Role routing
│   ├── AppointmentController.php# Scheduling, Reassignments & Online Portal
│   ├── PatientController.php    # Records, Occupation field & X-ray uploads
│   ├── TreatmentController.php  # Treatment catalog & Commission engine
│   ├── BillingController.php    # Invoicing, Payments & Printable Receipts
│   ├── ExpenseController.php    # Daily expenses & attachments
│   ├── InventoryController.php  # Dental supplies & Assistant usage logs
│   └── ReportController.php     # Financial KPIs & Transparent calculations
├── models/
│   ├── User.php                 # Staff & Dentist profiles
│   ├── Patient.php              # EMR, demographics & attachments
│   ├── Appointment.php          # Booking & dentist assignments
│   ├── Treatment.php            # Procedures & commission formulas
│   ├── Invoice.php              # Invoices, balances & payments
│   ├── Expense.php              # Operating expenses
│   ├── Supply.php               # Inventory & per-patient usage
│   └── Report.php               # Financial aggregations & transparent audit
├── views/
│   ├── layouts/                 # Header, Sidebar, Topbar, Footer & Modals
│   ├── auth/                    # Login interface
│   ├── portal/                  # Public Patient Online Booking Portal
│   ├── dashboard/               # Executive & Dentist portals
│   ├── appointments/            # Scheduling & Clinical encounter
│   ├── patients/                # Directory, EMR & X-rays
│   ├── treatments/              # Catalog & fixed commission rates
│   ├── billing/                 # Invoices, balances & printable receipt
│   ├── expenses/                # Daily disbursements
│   ├── inventory/               # Supply stock & usage logs
│   └── reports/                 # Dentist commission ledger
├── database/
│   ├── schema.sql               # Full DDL schema (InnoDB, Foreign Keys)
│   └── seeders.sql              # Initial branches, accounts & demo data
├── assets/
│   ├── css/style.css            # Styles + @media print thermal receipts
│   └── js/app.js                # Calculation inspector & Chart.js logic
├── install.php                  # Web database installer & seeder
└── index.php                    # Front controller / application router
```

