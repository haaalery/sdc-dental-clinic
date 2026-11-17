<?php
/**
 * SDC Dental Care Management System
 * Treatment & Commission Calculation Model
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/User.php';

class Treatment {
    public static function getAll(): array {
        return Database::fetchAll("SELECT * FROM treatments ORDER BY category ASC, treatment_name ASC");
    }

    public static function getActive(): array {
        return Database::fetchAll("SELECT * FROM treatments WHERE is_active = 1 ORDER BY category ASC, treatment_name ASC");
    }

    public static function findById(int $id): ?array {
        return Database::fetch("SELECT * FROM treatments WHERE id = ?", [$id]);
    }

    public static function create(array $data): int {
        $sql = "INSERT INTO treatments (treatment_code, treatment_name, category, standard_price, fixed_commission_rate, description, is_active) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        Database::query($sql, [
            $data['treatment_code'],
            $data['treatment_name'],
            $data['category'] ?? 'General',
            $data['standard_price'],
            $data['fixed_commission_rate'] ?? 30.00,
            $data['description'] ?? null,
            isset($data['is_active']) ? (int)$data['is_active'] : 1
        ]);
        return (int) Database::lastInsertId();
    }

    public static function update(int $id, array $data): bool {
        $sql = "UPDATE treatments SET treatment_code = ?, treatment_name = ?, category = ?, standard_price = ?, fixed_commission_rate = ?, description = ?, is_active = ? 
                WHERE id = ?";
        Database::query($sql, [
            $data['treatment_code'],
            $data['treatment_name'],
            $data['category'] ?? 'General',
            $data['standard_price'],
            $data['fixed_commission_rate'],
            $data['description'] ?? null,
            isset($data['is_active']) ? (int)$data['is_active'] : 1,
            $id
        ]);
        return true;
    }

    /**
     * Record a performed treatment under an appointment and calculate commission
     * Commission calculation:
     * - Rate is chosen from treatment fixed rate, or dentist default commission rate if specified
     * - Dentist Commission = Price * (Rate / 100)
     * - Clinic Share = Price - Dentist Commission
     */
    public static function recordPerformedTreatment(int $appointmentId, int $treatmentId, int $dentistId, float $actualPrice, ?float $customCommissionRate = null, ?string $notes = null): int {
        $treatment = self::findById($treatmentId);
        $dentist = User::getDentistById($dentistId);

        // Determine rate: Custom Rate > Treatment Fixed Rate > Dentist Default Rate > 30%
        if ($customCommissionRate !== null && $customCommissionRate > 0) {
            $commissionRate = $customCommissionRate;
        } elseif (!empty($treatment['fixed_commission_rate'])) {
            $commissionRate = (float)$treatment['fixed_commission_rate'];
        } elseif (!empty($dentist['default_commission_rate'])) {
            $commissionRate = (float)$dentist['default_commission_rate'];
        } else {
            $commissionRate = 30.00;
        }

        $dentistCommission = round($actualPrice * ($commissionRate / 100), 2);
        $clinicShare = round($actualPrice - $dentistCommission, 2);

        $sql = "INSERT INTO appointment_treatments (appointment_id, treatment_id, dentist_id, actual_price, commission_rate, dentist_commission_amount, clinic_share_amount, notes) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        Database::query($sql, [
            $appointmentId,
            $treatmentId,
            $dentistId,
            $actualPrice,
            $commissionRate,
            $dentistCommission,
            $clinicShare,
            $notes
        ]);

        return (int) Database::lastInsertId();
    }

    /**
     * Get all treatments performed in an appointment
     */
    public static function getByAppointment(int $appointmentId): array {
        $sql = "SELECT at.*, t.treatment_name, t.treatment_code, t.category, 
                       u.full_name as dentist_name, d.specialization as dentist_specialization
                FROM appointment_treatments at
                JOIN treatments t ON at.treatment_id = t.id
                JOIN dentists d ON at.dentist_id = d.id
                JOIN users u ON d.user_id = u.id
                WHERE at.appointment_id = ?
                ORDER BY at.id ASC";
        return Database::fetchAll($sql, [$appointmentId]);
    }

    /**
     * Delete performed treatment
     */
    public static function deletePerformedTreatment(int $id): bool {
        Database::query("DELETE FROM appointment_treatments WHERE id = ?", [$id]);
        return true;
    }
}

