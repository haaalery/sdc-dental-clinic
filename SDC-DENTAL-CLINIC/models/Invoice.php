<?php
/**
 * SDC Dental Care Management System
 * Invoice & Payment Model (Balances, Cash/Online/Card, Printable Receipts)
 */

require_once __DIR__ . '/../config/database.php';

class Invoice {
    public static function generateInvoiceNumber(): string {
        $year = date('Y');
        $count = (int) Database::fetchValue("SELECT COUNT(*) FROM billing_invoices WHERE invoice_number LIKE ?", ["INV-$year-%"]);
        return sprintf("INV-%s-%04d", $year, $count + 1);
    }

    public static function generateReceiptNumber(): string {
        $year = date('Y');
        $count = (int) Database::fetchValue("SELECT COUNT(*) FROM payments WHERE receipt_number LIKE ?", ["RCT-$year-%"]);
        return sprintf("RCT-%s-%04d", $year, $count + 1);
    }

    public static function findById(int $id): ?array {
        $sql = "SELECT i.*, 
                       p.patient_code, p.first_name, p.last_name, p.phone as patient_phone, p.address as patient_address, p.occupation,
                       b.branch_name, b.branch_code, b.address as branch_address, b.contact_number as branch_phone,
                       a.appointment_code, a.appointment_date, a.dental_concern,
                       u_dentist.full_name as attending_dentist_name
                FROM billing_invoices i
                JOIN patients p ON i.patient_id = p.id
                JOIN branches b ON i.branch_id = b.id
                JOIN appointments a ON i.appointment_id = a.id
                LEFT JOIN dentists d ON a.assigned_dentist_id = d.id
                LEFT JOIN users u_dentist ON d.user_id = u_dentist.id
                WHERE i.id = ?";
        return Database::fetch($sql, [$id]);
    }

    public static function findByAppointment(int $appointmentId): ?array {
        return Database::fetch("SELECT * FROM billing_invoices WHERE appointment_id = ?", [$appointmentId]);
    }

    /**
     * Create or Sync Invoice for an Appointment from Performed Treatments
     */
    public static function syncFromAppointment(int $appointmentId, float $discount = 0.00): int {
        $appointment = Database::fetch("SELECT * FROM appointments WHERE id = ?", [$appointmentId]);
        if (!$appointment || empty($appointment['patient_id'])) {
            throw new Exception("Appointment or linked patient not found.");
        }

        // Sum of treatments performed
        $subtotal = (float) Database::fetchValue("SELECT COALESCE(SUM(actual_price), 0) FROM appointment_treatments WHERE appointment_id = ?", [$appointmentId]);
        $totalAmount = max(0, $subtotal - $discount);

        $existing = self::findByAppointment($appointmentId);

        if ($existing) {
            $paidAmount = (float)$existing['paid_amount'];
            $balance = max(0, $totalAmount - $paidAmount);
            $status = ($balance <= 0 && $totalAmount > 0) ? 'paid' : (($paidAmount > 0) ? 'partially_paid' : 'unpaid');

            $sql = "UPDATE billing_invoices 
                    SET subtotal = ?, discount_amount = ?, total_amount = ?, balance_amount = ?, payment_status = ? 
                    WHERE id = ?";
            Database::query($sql, [$subtotal, $discount, $totalAmount, $balance, $status, $existing['id']]);
            return (int) $existing['id'];
        } else {
            $invNum = self::generateInvoiceNumber();
            $sql = "INSERT INTO billing_invoices (invoice_number, appointment_id, patient_id, branch_id, subtotal, discount_amount, total_amount, paid_amount, balance_amount, payment_status) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, 0.00, ?, 'unpaid')";
            Database::query($sql, [
                $invNum,
                $appointmentId,
                $appointment['patient_id'],
                $appointment['branch_id'],
                $subtotal,
                $discount,
                $totalAmount,
                $totalAmount
            ]);
            return (int) Database::lastInsertId();
        }
    }

    /**
     * Record a payment transaction (Cash, Online, Card)
     */
    public static function recordPayment(int $invoiceId, float $amount, string $paymentMethod, ?string $refNumber, int $receivedBy, ?string $notes = null): int {
        $invoice = self::findById($invoiceId);
        if (!$invoice) {
            throw new Exception("Invoice not found.");
        }

        if ($amount <= 0) {
            throw new Exception("Payment amount must be greater than zero.");
        }

        $receiptNumber = self::generateReceiptNumber();
        $sql = "INSERT INTO payments (receipt_number, invoice_id, patient_id, branch_id, amount_paid, payment_method, reference_number, received_by, notes) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        Database::query($sql, [
            $receiptNumber,
            $invoiceId,
            $invoice['patient_id'],
            $invoice['branch_id'],
            $amount,
            $paymentMethod,
            $refNumber,
            $receivedBy,
            $notes
        ]);
        $paymentId = (int) Database::lastInsertId();

        // Update Invoice Paid Amount and Balance
        $newPaid = (float)$invoice['paid_amount'] + $amount;
        $newBalance = max(0, (float)$invoice['total_amount'] - $newPaid);
        $newStatus = ($newBalance <= 0) ? 'paid' : 'partially_paid';

        Database::query("UPDATE billing_invoices SET paid_amount = ?, balance_amount = ?, payment_status = ? WHERE id = ?", [
            $newPaid,
            $newBalance,
            $newStatus,
            $invoiceId
        ]);

        return $paymentId;
    }

    /**
     * Get all payments made against an invoice
     */
    public static function getPaymentsByInvoice(int $invoiceId): array {
        $sql = "SELECT p.*, u.full_name as received_by_name 
                FROM payments p 
                JOIN users u ON p.received_by = u.id 
                WHERE p.invoice_id = ? 
                ORDER BY p.payment_date ASC";
        return Database::fetchAll($sql, [$invoiceId]);
    }

    /**
     * Retrieve complete receipt data for printing
     */
    public static function getReceiptDetails(int $paymentId): ?array {
        $sql = "SELECT p.*, 
                       i.invoice_number, i.subtotal, i.discount_amount, i.total_amount, i.paid_amount as total_invoice_paid, i.balance_amount as remaining_balance,
                       pt.patient_code, pt.first_name, pt.last_name, pt.phone as patient_phone, pt.occupation,
                       b.branch_name, b.branch_code, b.address as branch_address, b.contact_number as branch_phone,
                       a.appointment_code, a.appointment_date, a.dental_concern,
                       u_staff.full_name as cashier_name,
                       u_dentist.full_name as attending_dentist_name, d.license_no as dentist_license
                FROM payments p
                JOIN billing_invoices i ON p.invoice_id = i.id
                JOIN patients pt ON p.patient_id = pt.id
                JOIN branches b ON p.branch_id = b.id
                JOIN appointments a ON i.appointment_id = a.id
                JOIN users u_staff ON p.received_by = u_staff.id
                LEFT JOIN dentists d ON a.assigned_dentist_id = d.id
                LEFT JOIN users u_dentist ON d.user_id = u_dentist.id
                WHERE p.id = ?";
        $receipt = Database::fetch($sql, [$paymentId]);
        if ($receipt) {
            // Also fetch treatments performed in this session
            $receipt['treatments'] = Treatment::getByAppointment($receipt['appointment_id']);
        }
        return $receipt;
    }

    /**
     * Get All Invoices with remaining balance tracking
     */
    public static function getAllInvoices(array $filters = []): array {
        $sql = "SELECT i.*, 
                       p.patient_code, p.first_name, p.last_name, p.phone as patient_phone,
                       b.branch_name,
                       a.appointment_code, a.appointment_date
                FROM billing_invoices i
                JOIN patients p ON i.patient_id = p.id
                JOIN branches b ON i.branch_id = b.id
                JOIN appointments a ON i.appointment_id = a.id
                WHERE 1=1";
        $params = [];

        if (!empty($filters['branch_id'])) {
            $sql .= " AND i.branch_id = ?";
            $params[] = $filters['branch_id'];
        }
        if (!empty($filters['payment_status'])) {
            $sql .= " AND i.payment_status = ?";
            $params[] = $filters['payment_status'];
        }
        if (!empty($filters['patient_id'])) {
            $sql .= " AND i.patient_id = ?";
            $params[] = $filters['patient_id'];
        }

        $sql .= " ORDER BY i.id DESC";
        return Database::fetchAll($sql, $params);
    }
}

