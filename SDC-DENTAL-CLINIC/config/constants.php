<?php
/**
 * SDC Dental Care Management System
 * System Constants & Global Configurations
 */

// Application Details
define('APP_NAME', 'SDC Dental Care');
define('APP_VERSION', '1.0.0');
define('APP_CURRENCY', '₱');
define('APP_TIMEZONE', 'Asia/Manila');

// Set Timezone
date_default_timezone_set(APP_TIMEZONE);

// Base URLs and Paths
define('BASE_PATH', dirname(__DIR__));
// Dynamically resolve base URL
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
$baseUrl = ($scriptDir === '/' || $scriptDir === '\\') ? '' : $scriptDir;
// If called from a subfolder, resolve back to root
if (basename($baseUrl) === 'views' || basename($baseUrl) === 'controllers') {
    $baseUrl = dirname($baseUrl);
}
define('BASE_URL', rtrim($baseUrl, '/'));

// Upload Directories
define('UPLOAD_DIR', BASE_PATH . '/uploads');
define('PATIENT_UPLOAD_DIR', UPLOAD_DIR . '/patients');
define('EXPENSE_UPLOAD_DIR', UPLOAD_DIR . '/expenses');

// Commission Rate Presets (%)
define('COMMISSION_RATES', [20.00, 30.00, 40.00, 50.00]);

// Payment Methods
define('PAYMENT_METHODS', ['Cash', 'Online', 'Card']);

// Expense Categories
define('EXPENSE_CATEGORIES', [
    'utilities' => 'Utilities (Electric, Water, Internet)',
    'supplies' => 'Dental Supplies & Consumables',
    'rent' => 'Clinic Space Rent',
    'salaries' => 'Staff Salaries & Allowances',
    'maintenance' => 'Equipment Maintenance & Repairs',
    'laboratory' => 'Dental Laboratory Fees',
    'miscellaneous' => 'Miscellaneous / Operational'
]);

// User Roles
define('ROLE_ADMIN', 'admin');
define('ROLE_DENTIST', 'dentist');
define('ROLE_FRONTDESK', 'front_desk');
define('ROLE_ASSISTANT', 'assistant');

// Appointment Statuses
define('STATUS_PENDING', 'pending_confirmation');
define('STATUS_CONFIRMED', 'confirmed');
define('STATUS_IN_PROGRESS', 'in_progress');
define('STATUS_COMPLETED', 'completed');
define('STATUS_CANCELLED', 'cancelled');
define('STATUS_NO_SHOW', 'no_show');

