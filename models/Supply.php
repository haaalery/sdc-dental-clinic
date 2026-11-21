<?php
/**
 * SDC Dental Care Management System
 * Dental Supplies & Per-Patient Usage Model
 */

require_once __DIR__ . '/../config/database.php';

class Supply {
    public static function getAll(): array {
        return Database::fetchAll("SELECT * FROM supplies ORDER BY category ASC, item_name ASC");
    }

    public static function getActive(): array {
        return Database::fetchAll("SELECT * FROM supplies WHERE is_active = 1 ORDER BY category ASC, item_name ASC");
    }

    public static function getLowStock(): array {
        return Database::fetchAll("SELECT * FROM supplies WHERE is_active = 1 AND current_stock <= reorder_level ORDER BY current_stock ASC");
    }

    public static function findById(int $id): ?array {
        return Database::fetch("SELECT * FROM supplies WHERE id = ?", [$id]);
    }

    public static function create(array $data): int {
        $sql = "INSERT INTO supplies (item_code, item_name, category, unit_of_measure, current_stock, reorder_level, unit_cost, is_active) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        Database::query($sql, [
            $data['item_code'],
            $data['item_name'],
            $data['category'] ?? 'General',
            $data['unit_of_measure'],
            $data['current_stock'] ?? 0,
            $data['reorder_level'] ?? 10,
            $data['unit_cost'] ?? 0.00,
            isset($data['is_active']) ? (int)$data['is_active'] : 1
        ]);
        return (int) Database::lastInsertId();
    }

    public static function updateStock(int $id, int $adjustmentQuantity): bool {
        Database::query("UPDATE supplies SET current_stock = current_stock + ? WHERE id = ?", [$adjustmentQuantity, $id]);
        return true;
    }

    /**
     * Record per-patient supply usage (Recorded by Assistant / Front Desk)
     */
    public static function recordPatientUsage(int $appointmentId, ?int $patientId, int $supplyId, int $quantityUsed, int $recordedBy, ?string $notes = null): int {
        if ($quantityUsed <= 0) {
            throw new Exception("Quantity used must be greater than 0.");
        }

        $supply = self::findById($supplyId);
        if (!$supply) {
            throw new Exception("Supply item not found.");
        }

        if ((int)$supply['current_stock'] < $quantityUsed) {
            throw new Exception("Requested quantity ($quantityUsed) exceeds available stock (" . $supply['current_stock'] . " " . $supply['unit_of_measure'] . ").");
        }

        // Deduct inventory stock
        Database::query("UPDATE supplies SET current_stock = GREATEST(0, current_stock - ?) WHERE id = ?", [$quantityUsed, $supplyId]);

        // Log patient usage (patient_id can be null for guest bookings)
        $sql = "INSERT INTO patient_supply_usage (appointment_id, patient_id, supply_id, quantity_used, recorded_by, notes) 
                VALUES (?, ?, ?, ?, ?, ?)";
        Database::query($sql, [
            $appointmentId,
            $patientId ?: null,
            $supplyId,
            $quantityUsed,
            $recordedBy,
            $notes
        ]);

        return (int) Database::lastInsertId();
    }

    /**
     * Get supplies used during a specific appointment
     */
    public static function getUsageByAppointment(int $appointmentId): array {
        $sql = "SELECT psu.*, s.item_name, s.item_code, s.unit_of_measure, u.full_name as recorded_by_name 
                FROM patient_supply_usage psu
                JOIN supplies s ON psu.supply_id = s.id
                JOIN users u ON psu.recorded_by = u.id
                WHERE psu.appointment_id = ?
                ORDER BY psu.id ASC";
        return Database::fetchAll($sql, [$appointmentId]);
    }

    /**
     * Get all supplies used for a patient across all visits
     */
    public static function getUsageByPatient(int $patientId): array {
        $sql = "SELECT psu.*, s.item_name, s.item_code, s.unit_of_measure, 
                       a.appointment_code, a.appointment_date, u.full_name as recorded_by_name 
                FROM patient_supply_usage psu
                JOIN supplies s ON psu.supply_id = s.id
                JOIN appointments a ON psu.appointment_id = a.id
                JOIN users u ON psu.recorded_by = u.id
                WHERE psu.patient_id = ?
                ORDER BY a.appointment_date DESC, psu.id DESC";
        return Database::fetchAll($sql, [$patientId]);
    }
}

