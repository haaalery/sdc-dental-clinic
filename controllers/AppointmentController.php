<?php
/**
 * SDC Dental Care Management System
 * Appointment Controller
 */

require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../models/Appointment.php';
require_once __DIR__ . '/../models/Patient.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Treatment.php';
require_once __DIR__ . '/../models/Invoice.php';
require_once __DIR__ . '/../models/Supply.php';

class AppointmentController {
    public function index(): void {
        Session::requireLogin();
        $user = Session::user();

        $filters = [];
        if (!empty($_GET['date'])) {
            $filters['date'] = $_GET['date'];
        }
        if (!empty($_GET['status'])) {
            $filters['status'] = $_GET['status'];
        }
        if (!empty($_GET['branch_id'])) {
            $filters['branch_id'] = (int)$_GET['branch_id'];
        } elseif ($user['role'] !== 'admin' && !empty($user['branch_id'])) {
            $filters['branch_id'] = (int)$user['branch_id'];
        }

        // If logged in as dentist, filter by assigned dentist by default unless specified
        if ($user['role'] === 'dentist' && !empty($user['dentist_id'])) {
            $filters['dentist_id'] = (int)$user['dentist_id'];
        } elseif (!empty($_GET['dentist_id'])) {
            $filters['dentist_id'] = (int)$_GET['dentist_id'];
        }

        $appointments = Appointment::getAll($filters);
        $dentists = User::getAllDentists();
        $branches = User::getAllBranches();

        require_once __DIR__ . '/../views/appointments/index.php';
    }

    public function create(): void {
        Session::requireLogin();
        $dentists = User::getAllDentists();
        $branches = User::getAllBranches();
        $patients = Patient::getAll();
        $selectedPatientId = isset($_GET['patient_id']) ? (int)$_GET['patient_id'] : null;

        require_once __DIR__ . '/../views/appointments/create.php';
    }

    public function store(): void {
        Session::requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/index.php?page=appointments');
            exit;
        }

        try {
            $patientId = !empty($_POST['patient_id']) ? (int)$_POST['patient_id'] : null;
            $preferredDentistId = !empty($_POST['preferred_dentist_id']) ? (int)$_POST['preferred_dentist_id'] : null;
            $assignedDentistId = !empty($_POST['assigned_dentist_id']) ? (int)$_POST['assigned_dentist_id'] : $preferredDentistId;

            $data = [
                'branch_id'            => (int)($_POST['branch_id'] ?? 1),
                'patient_id'           => $patientId,
                'guest_name'           => $_POST['guest_name'] ?? null,
                'guest_phone'          => $_POST['guest_phone'] ?? null,
                'preferred_dentist_id' => $preferredDentistId,
                'assigned_dentist_id'  => $assignedDentistId,
                'appointment_date'     => $_POST['appointment_date'],
                'appointment_time'     => $_POST['appointment_time'],
                'dental_concern'       => trim($_POST['dental_concern'] ?? ''),
                'source'               => $_POST['source'] ?? 'walk_in',
                'status'               => $_POST['status'] ?? 'confirmed',
                'notes'                => $_POST['notes'] ?? null
            ];

            $appointmentId = Appointment::create($data);
            Session::flash('success', 'Appointment scheduled successfully!');
            header('Location: ' . BASE_URL . '/index.php?page=appointment_view&id=' . $appointmentId);
            exit;
        } catch (Throwable $e) {
            Session::flash('error', 'Error scheduling appointment: ' . $e->getMessage());
            header('Location: ' . BASE_URL . '/index.php?page=appointment_create');
            exit;
        }
    }

    public function view(): void {
        Session::requireLogin();
        $id = (int)($_GET['id'] ?? 0);
        $appointment = Appointment::findById($id);

        if (!$appointment) {
            Session::flash('error', 'Appointment not found.');
            header('Location: ' . BASE_URL . '/index.php?page=appointments');
            exit;
        }

        $performedTreatments = Treatment::getByAppointment($id);
        $activeTreatments = Treatment::getActive();
        $dentists = User::getAllDentists();
        $invoice = Invoice::findByAppointment($id);
        $payments = $invoice ? Invoice::getPaymentsByInvoice($invoice['id']) : [];
        $suppliesUsed = Supply::getUsageByAppointment($id);
        $availableSupplies = Supply::getActive();

        require_once __DIR__ . '/../views/appointments/view.php';
    }

    public function reassign(): void {
        Session::requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/index.php?page=appointments');
            exit;
        }

        $appointmentId = (int)($_POST['appointment_id'] ?? 0);
        $newDentistId = (int)($_POST['new_dentist_id'] ?? 0);
        $reason = trim($_POST['reassignment_reason'] ?? '');

        if (!$appointmentId || !$newDentistId || empty($reason)) {
            Session::flash('error', 'Please provide the new dentist and reason for reassignment.');
            header('Location: ' . BASE_URL . '/index.php?page=appointment_view&id=' . $appointmentId);
            exit;
        }

        Appointment::reassignDentist($appointmentId, $newDentistId, $reason);
        Session::flash('success', 'Dentist reassigned successfully. Reason logged for clinic audit.');
        header('Location: ' . BASE_URL . '/index.php?page=appointment_view&id=' . $appointmentId);
        exit;
    }

    public function updateStatus(): void {
        Session::requireLogin();
        $id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
        $status = $_GET['status'] ?? $_POST['status'] ?? '';

        if ($id && in_array($status, [STATUS_CONFIRMED, STATUS_IN_PROGRESS, STATUS_COMPLETED, STATUS_CANCELLED, STATUS_NO_SHOW])) {
            Appointment::updateStatus($id, $status);
            Session::flash('success', "Appointment status updated to " . ucfirst(str_replace('_', ' ', $status)));
        }

        header('Location: ' . BASE_URL . '/index.php?page=appointment_view&id=' . $id);
        exit;
    }

    /**
     * Patient Online Landing Page & Booking Portal
     */
    public function showPortal(): void {
        $dentists = User::getAllDentists();
        $branches = User::getAllBranches();
        $treatments = Treatment::getActive();
        require_once __DIR__ . '/../views/portal/landing.php';
    }

    public function submitPortalBooking(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/index.php#booking-form');
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $date = $_POST['preferred_date'] ?? '';
        $time = $_POST['preferred_time'] ?? '';
        $concern = trim($_POST['dental_concern'] ?? '');

        if (empty($name) || empty($phone) || empty($date) || empty($time) || empty($concern)) {
            Session::flash('portal_error', 'Please fill in all required fields (Name, Phone, Preferred Date, Preferred Time, and Dental Concern).');
            header('Location: ' . BASE_URL . '/index.php#booking-form');
            exit;
        }

        $data = [
            'name'                 => $name,
            'phone'                => $phone,
            'preferred_date'       => $date,
            'preferred_time'       => $time,
            'dental_concern'       => $concern,
            'branch_id'            => !empty($_POST['branch_id']) ? (int)$_POST['branch_id'] : 1,
            'preferred_dentist_id' => !empty($_POST['preferred_dentist_id']) ? (int)$_POST['preferred_dentist_id'] : null,
            'notes'                => 'Online booking submitted by patient.'
        ];

        $appointmentId = Appointment::createOnlineBooking($data);
        $appointment = Appointment::findById($appointmentId);

        require_once __DIR__ . '/../views/portal/success.php';
    }
}

