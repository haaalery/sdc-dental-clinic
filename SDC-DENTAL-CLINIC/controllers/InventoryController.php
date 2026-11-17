<?php
/**
 * SDC Dental Care Management System
 * Inventory & Per-Patient Supply Usage Controller
 */

require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../models/Supply.php';
require_once __DIR__ . '/../models/Appointment.php';
require_once __DIR__ . '/../models/Patient.php';

class InventoryController {
    public function index(): void {
        Session::requireLogin();
        $supplies = Supply::getAll();
        $lowStock = Supply::getLowStock();
        require_once __DIR__ . '/../views/inventory/index.php';
    }

    public function storeItem(): void {
        Session::requireRole(['admin', 'assistant', 'front_desk']);
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/index.php?page=inventory');
            exit;
        }

        try {
            $data = [
                'item_code'       => trim($_POST['item_code'] ?? ''),
                'item_name'       => trim($_POST['item_name'] ?? ''),
                'category'        => trim($_POST['category'] ?? 'General'),
                'unit_of_measure' => trim($_POST['unit_of_measure'] ?? 'piece'),
                'current_stock'   => (int)($_POST['current_stock'] ?? 0),
                'reorder_level'   => (int)($_POST['reorder_level'] ?? 10),
                'unit_cost'       => (float)($_POST['unit_cost'] ?? 0.00),
                'is_active'       => 1
            ];

            if (empty($data['item_code']) || empty($data['item_name'])) {
                throw new Exception("Item code and name are required.");
            }

            Supply::create($data);
            Session::flash('success', 'Dental supply added to catalog.');
        } catch (Throwable $e) {
            Session::flash('error', $e->getMessage());
        }

        header('Location: ' . BASE_URL . '/index.php?page=inventory');
        exit;
    }

    public function adjustStock(): void {
        Session::requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/index.php?page=inventory');
            exit;
        }

        $id = (int)($_POST['supply_id'] ?? 0);
        $adjustment = (int)($_POST['adjustment_qty'] ?? 0);

        if ($id && $adjustment != 0) {
            Supply::updateStock($id, $adjustment);
            Session::flash('success', "Stock updated by $adjustment units.");
        }

        header('Location: ' . BASE_URL . '/index.php?page=inventory');
        exit;
    }

    /**
     * Record per-patient supply usage (Assistant feature)
     */
    public function recordUsage(): void {
        Session::requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/index.php?page=appointments');
            exit;
        }

        $appointmentId = (int)($_POST['appointment_id'] ?? 0);
        $patientId = (int)($_POST['patient_id'] ?? 0);
        $supplyId = (int)($_POST['supply_id'] ?? 0);
        $quantity = (int)($_POST['quantity_used'] ?? 1);
        $notes = trim($_POST['notes'] ?? '');
        $recordedBy = Session::user()['id'];

        if (!$appointmentId || !$patientId || !$supplyId || $quantity <= 0) {
            Session::flash('error', 'Please select a supply item and enter a valid quantity.');
            header('Location: ' . BASE_URL . '/index.php?page=appointment_view&id=' . $appointmentId);
            exit;
        }

        try {
            Supply::recordPatientUsage($appointmentId, $patientId, $supplyId, $quantity, $recordedBy, $notes);
            Session::flash('success', "Logged $quantity unit(s) of supply consumed for this patient.");
        } catch (Throwable $e) {
            Session::flash('error', 'Error recording supply usage: ' . $e->getMessage());
        }

        header('Location: ' . BASE_URL . '/index.php?page=appointment_view&id=' . $appointmentId);
        exit;
    }
}

