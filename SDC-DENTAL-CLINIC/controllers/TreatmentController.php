<?php
/**
 * SDC Dental Care Management System
 * Treatment Catalog & Performed Treatment Recording Controller
 */

require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../models/Treatment.php';
require_once __DIR__ . '/../models/Invoice.php';
require_once __DIR__ . '/../models/User.php';

class TreatmentController {
    public function index(): void {
        Session::requireLogin();
        $treatments = Treatment::getAll();
        require_once __DIR__ . '/../views/treatments/index.php';
    }

    public function store(): void {
        Session::requireRole(['admin']);
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/index.php?page=treatments');
            exit;
        }

        try {
            $data = [
                'treatment_code'        => trim($_POST['treatment_code'] ?? ''),
                'treatment_name'        => trim($_POST['treatment_name'] ?? ''),
                'category'              => trim($_POST['category'] ?? 'General'),
                'standard_price'        => (float)($_POST['standard_price'] ?? 0),
                'fixed_commission_rate' => (float)($_POST['fixed_commission_rate'] ?? 30.00),
                'description'           => trim($_POST['description'] ?? ''),
                'is_active'             => isset($_POST['is_active']) ? 1 : 0
            ];

            if (empty($data['treatment_code']) || empty($data['treatment_name']) || $data['standard_price'] <= 0) {
                throw new Exception("Please enter a valid treatment code, name, and standard price.");
            }

            Treatment::create($data);
            Session::flash('success', 'New treatment added with fixed commission rate.');
        } catch (Throwable $e) {
            Session::flash('error', $e->getMessage());
        }

        header('Location: ' . BASE_URL . '/index.php?page=treatments');
        exit;
    }

    public function update(): void {
        Session::requireRole(['admin']);
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/index.php?page=treatments');
            exit;
        }

        $id = (int)($_POST['id'] ?? 0);
        try {
            $data = [
                'treatment_code'        => trim($_POST['treatment_code'] ?? ''),
                'treatment_name'        => trim($_POST['treatment_name'] ?? ''),
                'category'              => trim($_POST['category'] ?? 'General'),
                'standard_price'        => (float)($_POST['standard_price'] ?? 0),
                'fixed_commission_rate' => (float)($_POST['fixed_commission_rate'] ?? 30.00),
                'description'           => trim($_POST['description'] ?? ''),
                'is_active'             => isset($_POST['is_active']) ? 1 : 0
            ];

            Treatment::update($id, $data);
            Session::flash('success', 'Treatment catalog entry updated.');
        } catch (Throwable $e) {
            Session::flash('error', $e->getMessage());
        }

        header('Location: ' . BASE_URL . '/index.php?page=treatments');
        exit;
    }

    /**
     * Dentist / Assistant records treatment performed under an appointment
     * This automatically calculates the dentist commission and clinic share!
     */
    public function recordPerformed(): void {
        Session::requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/index.php?page=appointments');
            exit;
        }

        $appointmentId = (int)($_POST['appointment_id'] ?? 0);
        $treatmentId = (int)($_POST['treatment_id'] ?? 0);
        $dentistId = (int)($_POST['dentist_id'] ?? 0);
        $actualPrice = (float)($_POST['actual_price'] ?? 0);
        $customRate = !empty($_POST['custom_commission_rate']) ? (float)$_POST['custom_commission_rate'] : null;
        $notes = trim($_POST['notes'] ?? '');

        if (!$appointmentId || !$treatmentId || !$dentistId || $actualPrice <= 0) {
            Session::flash('error', 'Please fill in all treatment details.');
            header('Location: ' . BASE_URL . '/index.php?page=appointment_view&id=' . $appointmentId);
            exit;
        }

        try {
            Treatment::recordPerformedTreatment($appointmentId, $treatmentId, $dentistId, $actualPrice, $customRate, $notes);
            
            // Automatically update or generate invoice draft for this appointment
            Invoice::syncFromAppointment($appointmentId);

            Session::flash('success', 'Treatment recorded! Commission and clinic split computed transparently.');
        } catch (Throwable $e) {
            Session::flash('error', 'Error recording treatment: ' . $e->getMessage());
        }

        header('Location: ' . BASE_URL . '/index.php?page=appointment_view&id=' . $appointmentId);
        exit;
    }

    public function deletePerformed(): void {
        Session::requireLogin();
        $id = (int)($_POST['id'] ?? 0);
        $appointmentId = (int)($_POST['appointment_id'] ?? 0);

        if ($id) {
            Treatment::deletePerformedTreatment($id);
            if ($appointmentId) {
                Invoice::syncFromAppointment($appointmentId);
            }
            Session::flash('success', 'Treatment item removed.');
        }

        header('Location: ' . BASE_URL . '/index.php?page=appointment_view&id=' . $appointmentId);
        exit;
    }
}

