<?php
/**
 * SDC Dental Care Management System
 * Billing, Payments, Balances & Receipts Controller
 */

require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../models/Invoice.php';
require_once __DIR__ . '/../models/Appointment.php';
require_once __DIR__ . '/../models/Treatment.php';
require_once __DIR__ . '/../models/User.php';

class BillingController {
    public function index(): void {
        Session::requireLogin();
        $filters = [];
        if (!empty($_GET['status'])) {
            $filters['payment_status'] = $_GET['status'];
        }
        if (!empty($_GET['branch_id'])) {
            $filters['branch_id'] = (int)$_GET['branch_id'];
        }

        $invoices = Invoice::getAllInvoices($filters);
        $branches = User::getAllBranches();

        require_once __DIR__ . '/../views/billing/index.php';
    }

    public function viewInvoice(): void {
        Session::requireLogin();
        $id = (int)($_GET['id'] ?? 0);
        $invoice = Invoice::findById($id);

        if (!$invoice) {
            Session::flash('error', 'Invoice record not found.');
            header('Location: ' . BASE_URL . '/index.php?page=billing');
            exit;
        }

        $treatments = Treatment::getByAppointment($invoice['appointment_id']);
        $payments = Invoice::getPaymentsByInvoice($id);

        require_once __DIR__ . '/../views/billing/view.php';
    }

    public function recordPayment(): void {
        Session::requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/index.php?page=billing');
            exit;
        }

        $invoiceId = (int)($_POST['invoice_id'] ?? 0);
        $amount = (float)($_POST['amount_paid'] ?? 0);
        $method = $_POST['payment_method'] ?? 'Cash';
        $refNumber = trim($_POST['reference_number'] ?? '');
        $notes = trim($_POST['notes'] ?? '');
        $receivedBy = Session::user()['id'];

        if (!$invoiceId || $amount <= 0) {
            Session::flash('error', 'Please enter a valid payment amount.');
            header('Location: ' . BASE_URL . '/index.php?page=invoice_view&id=' . $invoiceId);
            exit;
        }

        try {
            $paymentId = Invoice::recordPayment($invoiceId, $amount, $method, $refNumber, $receivedBy, $notes);
            Session::flash('success', "Payment of " . APP_CURRENCY . number_format($amount, 2) . " ($method) recorded successfully! Receipt generated.");
            
            // Redirect directly to the printable receipt
            header('Location: ' . BASE_URL . '/index.php?page=receipt&id=' . $paymentId);
            exit;
        } catch (Throwable $e) {
            Session::flash('error', 'Payment recording error: ' . $e->getMessage());
            header('Location: ' . BASE_URL . '/index.php?page=invoice_view&id=' . $invoiceId);
            exit;
        }
    }

    /**
     * Printable Receipt View (Clean layout optimized for 80mm POS Thermal and Standard A4/A5)
     */
    public function receipt(): void {
        Session::requireLogin();
        $paymentId = (int)($_GET['id'] ?? 0);
        $receipt = Invoice::getReceiptDetails($paymentId);

        if (!$receipt) {
            Session::flash('error', 'Payment receipt not found.');
            header('Location: ' . BASE_URL . '/index.php?page=billing');
            exit;
        }

        require_once __DIR__ . '/../views/billing/receipt.php';
    }
}

