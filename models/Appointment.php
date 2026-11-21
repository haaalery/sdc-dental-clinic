<?php
/**
 * SDC Dental Care Management System
 * Appointment Model (Scheduling, Preferred Dentist, Reassignment, Online Bookings)
 */

require_once __DIR__ . '/../config/database.php';

class Appointment {
    public static function generateCode(): string {
        $year = date('Y');
        $count = (int) Database::fetchValue("SELECT COUNT(*) FROM appointments WHERE appointment_code LIKE ?", ["APT-$year-%"]);
        return sprintf("APT-%s-%04d", $year, $count + 1);
    }

    public static function getAll(array $filters = []): array {
        $sql = "SELECT a.*, 
                       p.patient_code, p.first_name, p.last_name, p.phone as patient_phone, p.occupation,
                       b.branch_name,
                       u_pref.full_name as preferred_dentist_name,
                       u_asgn.full_name as assigned_dentist_name,
                       i.id as invoice_id, i.invoice_number, i.total_amount, i.paid_amount, i.balance_amount, i.payment_status
                FROM appointments a
                LEFT JOIN patients p ON a.patient_id = p.id
                LEFT JOIN branches b ON a.branch_id = b.id
                LEFT JOIN dentists d_pref ON a.preferred_dentist_id = d_pref.id
                LEFT JOIN users u_pref ON d_pref.user_id = u_pref.id
                LEFT JOIN dentists d_asgn ON a.assigned_dentist_id = d_asgn.id
                LEFT JOIN users u_asgn ON d_asgn.user_id = u_asgn.id
                LEFT JOIN billing_invoices i ON i.appointment_id = a.id
                WHERE 1=1";
        $params = [];

        if (!empty($filters['date'])) {
            $sql .= " AND a.appointment_date = ?";
            $params[] = $filters['date'];
        }
        if (!empty($filters['status'])) {
            $sql .= " AND a.status = ?";
            $params[] = $filters['status'];
        }
        if (!empty($filters['branch_id'])) {
            $sql .= " AND a.branch_id = ?";
            $params[] = $filters['branch_id'];
        }
        if (!empty($filters['dentist_id'])) {
            $sql .= " AND (a.assigned_dentist_id = ? OR a.preferred_dentist_id = ?)";
            $params[] = $filters['dentist_id'];
            $params[] = $filters['dentist_id'];
        }
        if (!empty($filters['source'])) {
            $sql .= " AND a.source = ?";
            $params[] = $filters['source'];
        }

        $sql .= " ORDER BY a.appointment_date DESC, a.appointment_time DESC";
        return Database::fetchAll($sql, $params);
    }

    public static function findById(int $id): ?array {
        $sql = "SELECT a.*, 
                       p.patient_code, p.first_name, p.last_name, p.phone as patient_phone, p.email as patient_email, p.occupation,
                       b.branch_name, b.address as branch_address, b.contact_number as branch_phone,
                       u_pref.full_name as preferred_dentist_name,
                       u_asgn.full_name as assigned_dentist_name, d_asgn.license_no as assigned_license_no,
                       i.id as invoice_id, i.invoice_number, i.total_amount, i.paid_amount, i.balance_amount, i.payment_status
                FROM appointments a
                LEFT JOIN patients p ON a.patient_id = p.id
                LEFT JOIN branches b ON a.branch_id = b.id
                LEFT JOIN dentists d_pref ON a.preferred_dentist_id = d_pref.id
                LEFT JOIN users u_pref ON d_pref.user_id = u_pref.id
                LEFT JOIN dentists d_asgn ON a.assigned_dentist_id = d_asgn.id
                LEFT JOIN users u_asgn ON d_asgn.user_id = u_asgn.id
                LEFT JOIN billing_invoices i ON i.appointment_id = a.id
                WHERE a.id = ?";
        return Database::fetch($sql, [$id]);
    }

    /**
     * Create in-clinic or front-desk appointment
     */
    public static function create(array $data): int {
        $code = self::generateCode();
        $sql = "INSERT INTO appointments (
                    appointment_code, branch_id, patient_id, guest_name, guest_phone,
                    preferred_dentist_id, assigned_dentist_id, appointment_date, appointment_time,
                    dental_concern, source, status, notes
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $assignedDentist = !empty($data['assigned_dentist_id']) 
            ? $data['assigned_dentist_id'] 
            : (!empty($data['preferred_dentist_id']) ? $data['preferred_dentist_id'] : null);

        Database::query($sql, [
            $code,
            $data['branch_id'] ?? 1,
            $data['patient_id'] ?? null,
            $data['guest_name'] ?? null,
            $data['guest_phone'] ?? null,
            $data['preferred_dentist_id'] ?? null,
            $assignedDentist,
            $data['appointment_date'],
            $data['appointment_time'],
            $data['dental_concern'],
            $data['source'] ?? 'walk_in',
            $data['status'] ?? 'confirmed',
            $data['notes'] ?? null
        ]);
        return (int) Database::lastInsertId();
    }

    /**
     * Patient Online Booking Form Submission
     */
    public static function createOnlineBooking(array $data): int {
        $code = self::generateCode();
        $sql = "INSERT INTO appointments (
                    appointment_code, branch_id, guest_name, guest_phone,
                    preferred_dentist_id, assigned_dentist_id, appointment_date, appointment_time,
                    dental_concern, source, status, notes
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'online_portal', 'pending_confirmation', ?)";

        $preferredDentist = !empty($data['preferred_dentist_id']) ? $data['preferred_dentist_id'] : null;

        Database::query($sql, [
            $code,
            $data['branch_id'] ?? 1,
            $data['name'],
            $data['phone'],
            $preferredDentist,
            $preferredDentist, // initially defaults to preferred
            $data['preferred_date'],
            $data['preferred_time'],
            $data['dental_concern'],
            $data['notes'] ?? 'Online submission via patient portal'
        ]);
        return (int) Database::lastInsertId();
    }

    /**
     * Dentist Reassignment with reason tracking
     */
    public static function reassignDentist(int $appointmentId, int $newDentistId, string $reason): bool {
        $sql = "UPDATE appointments 
                SET assigned_dentist_id = ?, reassignment_reason = ? 
                WHERE id = ?";
        Database::query($sql, [$newDentistId, $reason, $appointmentId]);
        return true;
    }

    /**
     * Update appointment status
     */
    public static function updateStatus(int $id, string $status): bool {
        Database::query("UPDATE appointments SET status = ? WHERE id = ?", [$status, $id]);
        return true;
    }

    /**
     * Link registered patient to an online guest appointment
     */
    public static function linkPatient(int $appointmentId, int $patientId): bool {
        Database::query("UPDATE appointments SET patient_id = ?, status = 'confirmed' WHERE id = ?", [$patientId, $appointmentId]);

        // Backfill linked patient_id across supplies, invoices, and payment records
        Database::query("UPDATE patient_supply_usage SET patient_id = ? WHERE appointment_id = ? AND (patient_id IS NULL OR patient_id = 0)", [$patientId, $appointmentId]);
        Database::query("UPDATE billing_invoices SET patient_id = ? WHERE appointment_id = ?", [$patientId, $appointmentId]);
        Database::query("UPDATE payments SET patient_id = ? WHERE invoice_id IN (SELECT id FROM billing_invoices WHERE appointment_id = ?)", [$patientId, $appointmentId]);

        // Re-sync invoice so patient totals and details update immediately
        try {
            Invoice::syncFromAppointment($appointmentId);
        } catch (Throwable $e) {
            // Ignore if no treatments recorded yet
        }

        return true;
    }
}

