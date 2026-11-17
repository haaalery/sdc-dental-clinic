<?php
/**
 * SDC Dental Care Management System
 * Front Controller & Main Application Router
 */

require_once __DIR__ . '/config/constants.php';
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/config/database.php';

// Controllers
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/AppointmentController.php';
require_once __DIR__ . '/controllers/PatientController.php';
require_once __DIR__ . '/controllers/TreatmentController.php';
require_once __DIR__ . '/controllers/BillingController.php';
require_once __DIR__ . '/controllers/ExpenseController.php';
require_once __DIR__ . '/controllers/InventoryController.php';
require_once __DIR__ . '/controllers/ReportController.php';

// Auto-check Database Connection
try {
    Database::getConnection();
} catch (Throwable $e) {
    // If DB is missing, direct user to install page
    if (basename($_SERVER['PHP_SELF']) !== 'install.php') {
        header('Location: ' . BASE_URL . '/install.php');
        exit;
    }
}

$page = $_GET['page'] ?? (Session::isLoggedIn() ? 'dashboard' : 'landing');

switch ($page) {
    // Authentication
    case 'login':
        (new AuthController())->showLogin();
        break;
    case 'do_login':
        (new AuthController())->login();
        break;
    case 'logout':
        (new AuthController())->logout();
        break;

    // Dashboards
    case 'dashboard':
        (new ReportController())->dashboard();
        break;
    case 'dentist_dashboard':
        (new ReportController())->dentistDashboard();
        break;

    // Appointments & Scheduling
    case 'appointments':
        (new AppointmentController())->index();
        break;
    case 'appointment_create':
        (new AppointmentController())->create();
        break;
    case 'appointment_store':
        (new AppointmentController())->store();
        break;
    case 'appointment_view':
        (new AppointmentController())->view();
        break;
    case 'appointment_reassign':
        (new AppointmentController())->reassign();
        break;
    case 'appointment_status':
        (new AppointmentController())->updateStatus();
        break;

    // Patients EMR
    case 'patients':
        (new PatientController())->index();
        break;
    case 'patient_create':
        (new PatientController())->create();
        break;
    case 'patient_store':
        (new PatientController())->store();
        break;
    case 'patient_view':
        (new PatientController())->view();
        break;
    case 'patient_upload_file':
        (new PatientController())->uploadFile();
        break;
    case 'patient_delete_file':
        (new PatientController())->deleteFile();
        break;

    // Treatments & Performed Procedures
    case 'treatments':
        (new TreatmentController())->index();
        break;
    case 'treatment_store':
        (new TreatmentController())->store();
        break;
    case 'treatment_update':
        (new TreatmentController())->update();
        break;
    case 'treatment_record_performed':
        (new TreatmentController())->recordPerformed();
        break;
    case 'treatment_delete_performed':
        (new TreatmentController())->deletePerformed();
        break;

    // Billing, Invoices, Payments, Receipts
    case 'billing':
        (new BillingController())->index();
        break;
    case 'invoice_view':
        (new BillingController())->viewInvoice();
        break;
    case 'record_payment':
        (new BillingController())->recordPayment();
        break;
    case 'receipt':
        (new BillingController())->receipt();
        break;

    // Daily Expenses
    case 'expenses':
        (new ExpenseController())->index();
        break;
    case 'expense_store':
        (new ExpenseController())->store();
        break;
    case 'expense_delete':
        (new ExpenseController())->delete();
        break;

    // Inventory & Assistant Patient Usage
    case 'inventory':
        (new InventoryController())->index();
        break;
    case 'inventory_store':
        (new InventoryController())->storeItem();
        break;
    case 'inventory_adjust':
        (new InventoryController())->adjustStock();
        break;
    case 'inventory_record_usage':
        (new InventoryController())->recordUsage();
        break;

    // Reports & Commissions
    case 'commissions':
        (new ReportController())->commissions();
        break;
    case 'api_calc_audit':
        (new ReportController())->getCalculationAudit();
        break;

    // Public Patient Online Portal & Landing Page
    case 'landing':
    case 'portal':
        (new AppointmentController())->showPortal();
        break;
    case 'portal_submit':
        (new AppointmentController())->submitPortalBooking();
        break;

    default:
        header('Location: ' . BASE_URL . (Session::isLoggedIn() ? '/index.php?page=dashboard' : '/index.php'));
        exit;
}

