<?php
/**
 * SDC Dental Care Management System
 * Patient Model (Includes Occupation & File Attachments)
 */

require_once __DIR__ . '/../config/database.php';

class Patient {
    public static function generateCode(): string {
        $year = date('Y');
        $count = (int) Database::fetchValue("SELECT COUNT(*) FROM patients WHERE patient_code LIKE ?", ["PAT-$year-%"]);
        return sprintf("PAT-%s-%03d", $year, $count + 1);
    }

    public static function getAll(?string $search = null): array {
        $sql = "SELECT p.*, 
                       (SELECT COUNT(*) FROM appointments a WHERE a.patient_id = p.id) as total_visits,
                       (SELECT MAX(appointment_date) FROM appointments a WHERE a.patient_id = p.id) as last_visit
                FROM patients p";
        $params = [];
        if ($search) {
            $sql .= " WHERE p.first_name LIKE ? OR p.middle_initial LIKE ? OR p.last_name LIKE ? OR p.patient_code LIKE ? OR p.phone LIKE ? OR p.occupation LIKE ?";
            $term = "%$search%";
            $params = [$term, $term, $term, $term, $term, $term];
        }
        $sql .= " ORDER BY p.id DESC";
        return Database::fetchAll($sql, $params);
    }

    public static function findById(int $id): ?array {
        return Database::fetch("SELECT * FROM patients WHERE id = ?", [$id]);
    }

    public static function create(array $data): int {
        $code = self::generateCode();
        $sql = "INSERT INTO patients (patient_code, first_name, middle_initial, last_name, gender, date_of_birth, phone, email, address, occupation, medical_history, allergies) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        Database::query($sql, [
            $code,
            $data['first_name'],
            !empty($data['middle_initial']) ? trim($data['middle_initial']) : null,
            $data['last_name'],
            $data['gender'] ?? 'other',
            !empty($data['date_of_birth']) ? $data['date_of_birth'] : null,
            $data['phone'],
            $data['email'] ?? null,
            $data['address'] ?? null,
            $data['occupation'] ?? null, // Explicit requirement
            $data['medical_history'] ?? null,
            $data['allergies'] ?? null,
        ]);
        return (int) Database::lastInsertId();
    }

    public static function update(int $id, array $data): bool {
        $sql = "UPDATE patients SET first_name = ?, middle_initial = ?, last_name = ?, gender = ?, date_of_birth = ?, phone = ?, email = ?, address = ?, occupation = ?, medical_history = ?, allergies = ? 
                WHERE id = ?";
        Database::query($sql, [
            $data['first_name'],
            !empty($data['middle_initial']) ? trim($data['middle_initial']) : null,
            $data['last_name'],
            $data['gender'] ?? 'other',
            !empty($data['date_of_birth']) ? $data['date_of_birth'] : null,
            $data['phone'],
            $data['email'] ?? null,
            $data['address'] ?? null,
            $data['occupation'] ?? null,
            $data['medical_history'] ?? null,
            $data['allergies'] ?? null,
            $id
        ]);
        return true;
    }

    /**
     * Retrieve complete patient treatment history with attending dentist and dental concern
     */
    public static function getTreatmentHistory(int $patientId): array {
        $sql = "SELECT at.*, t.treatment_name, t.category, 
                       a.appointment_code, a.appointment_date, a.dental_concern, a.status as appointment_status,
                       u.full_name as dentist_name, d.specialization as dentist_specialization,
                       b.branch_name
                FROM appointment_treatments at
                JOIN appointments a ON at.appointment_id = a.id
                JOIN treatments t ON at.treatment_id = t.id
                JOIN dentists d ON at.dentist_id = d.id
                JOIN users u ON d.user_id = u.id
                LEFT JOIN branches b ON a.branch_id = b.id
                WHERE a.patient_id = ?
                ORDER BY a.appointment_date DESC, at.id DESC";
        return Database::fetchAll($sql, [$patientId]);
    }

    /**
     * Get patient files (X-rays, lab documents)
     */
    public static function getFiles(int $patientId): array {
        $sql = "SELECT pf.*, u.full_name as uploaded_by_name 
                FROM patient_files pf 
                JOIN users u ON pf.uploaded_by = u.id 
                WHERE pf.patient_id = ? 
                ORDER BY pf.uploaded_at DESC";
        return Database::fetchAll($sql, [$patientId]);
    }

    public static function addFile(array $data): int {
        $sql = "INSERT INTO patient_files (patient_id, file_title, file_path, file_type, file_size_bytes, uploaded_by) 
                VALUES (?, ?, ?, ?, ?, ?)";
        Database::query($sql, [
            $data['patient_id'],
            $data['file_title'],
            $data['file_path'],
            $data['file_type'],
            $data['file_size_bytes'],
            $data['uploaded_by']
        ]);
        return (int) Database::lastInsertId();
    }

    public static function deleteFile(int $fileId): bool {
        $file = Database::fetch("SELECT * FROM patient_files WHERE id = ?", [$fileId]);
        if ($file) {
            $fullPath = BASE_PATH . '/' . ltrim($file['file_path'], '/');
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
            Database::query("DELETE FROM patient_files WHERE id = ?", [$fileId]);
            return true;
        }
        return false;
    }
}

