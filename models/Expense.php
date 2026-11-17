<?php
/**
 * SDC Dental Care Management System
 * Expense Model
 */

require_once __DIR__ . '/../config/database.php';

class Expense {
    public static function getAll(array $filters = []): array {
        $sql = "SELECT e.*, b.branch_name, u.full_name as recorded_by_name 
                FROM expenses e 
                JOIN branches b ON e.branch_id = b.id 
                JOIN users u ON e.recorded_by = u.id 
                WHERE 1=1";
        $params = [];

        if (!empty($filters['branch_id'])) {
            $sql .= " AND e.branch_id = ?";
            $params[] = $filters['branch_id'];
        }
        if (!empty($filters['date'])) {
            $sql .= " AND e.expense_date = ?";
            $params[] = $filters['date'];
        }
        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $sql .= " AND e.expense_date BETWEEN ? AND ?";
            $params[] = $filters['start_date'];
            $params[] = $filters['end_date'];
        }
        if (!empty($filters['category'])) {
            $sql .= " AND e.category = ?";
            $params[] = $filters['category'];
        }

        $sql .= " ORDER BY e.expense_date DESC, e.id DESC";
        return Database::fetchAll($sql, $params);
    }

    public static function findById(int $id): ?array {
        $sql = "SELECT e.*, b.branch_name, u.full_name as recorded_by_name 
                FROM expenses e 
                JOIN branches b ON e.branch_id = b.id 
                JOIN users u ON e.recorded_by = u.id 
                WHERE e.id = ?";
        return Database::fetch($sql, [$id]);
    }

    public static function create(array $data): int {
        $sql = "INSERT INTO expenses (branch_id, category, title, amount, payment_method, expense_date, receipt_attachment, recorded_by, notes) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        Database::query($sql, [
            $data['branch_id'],
            $data['category'],
            $data['title'],
            $data['amount'],
            $data['payment_method'] ?? 'Cash',
            $data['expense_date'],
            $data['receipt_attachment'] ?? null,
            $data['recorded_by'],
            $data['notes'] ?? null
        ]);
        return (int) Database::lastInsertId();
    }

    public static function delete(int $id): bool {
        $expense = self::findById($id);
        if ($expense && !empty($expense['receipt_attachment'])) {
            $fullPath = BASE_PATH . '/' . ltrim($expense['receipt_attachment'], '/');
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }
        Database::query("DELETE FROM expenses WHERE id = ?", [$id]);
        return true;
    }
}

