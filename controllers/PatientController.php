<?php
/**
 * SDC Dental Care Management System
 * Patient Controller (EMR, Profile with Occupation, File/X-Ray Uploads)
 */

require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../models/Patient.php';
require_once __DIR__ . '/../models/Supply.php';
require_once __DIR__ . '/../models/Invoice.php';

class PatientController {
    public function index(): void {
        Session::requireLogin();
        $search = trim($_GET['search'] ?? '');
        $patients = Patient::getAll(!empty($search) ? $search : null);
        require_once __DIR__ . '/../views/patients/index.php';
    }

    public function create(): void {
        Session::requireLogin();
        require_once __DIR__ . '/../views/patients/create.php';
    }

    public function store(): void {
        Session::requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/index.php?page=patients');
            exit;
        }

        try {
            $data = [
                'first_name'      => trim($_POST['first_name'] ?? ''),
                'middle_initial'  => trim($_POST['middle_initial'] ?? ''),
                'last_name'       => trim($_POST['last_name'] ?? ''),
                'gender'          => $_POST['gender'] ?? 'other',
                'date_of_birth'   => !empty($_POST['date_of_birth']) ? $_POST['date_of_birth'] : null,
                'phone'           => trim($_POST['phone'] ?? ''),
                'email'           => trim($_POST['email'] ?? ''),
                'address'         => trim($_POST['address'] ?? ''),
                'occupation'      => trim($_POST['occupation'] ?? ''), // Explicit interview requirement
                'medical_history' => trim($_POST['medical_history'] ?? ''),
                'allergies'       => trim($_POST['allergies'] ?? ''),
            ];

            if (empty($data['first_name']) || empty($data['last_name']) || empty($data['phone'])) {
                throw new Exception("First name, last name, and contact phone are required.");
            }

            $patientId = Patient::create($data);
            Session::flash('success', "Patient registered successfully! Profile ID created.");

            // Check if patient was being registered from an online appointment link
            if (!empty($_POST['link_appointment_id'])) {
                Appointment::linkPatient((int)$_POST['link_appointment_id'], $patientId);
                header('Location: ' . BASE_URL . '/index.php?page=appointment_view&id=' . (int)$_POST['link_appointment_id']);
                exit;
            }

            header('Location: ' . BASE_URL . '/index.php?page=patient_view&id=' . $patientId);
            exit;
        } catch (Throwable $e) {
            Session::flash('error', $e->getMessage());
            header('Location: ' . BASE_URL . '/index.php?page=patient_create');
            exit;
        }
    }

    public function view(): void {
        Session::requireLogin();
        $id = (int)($_GET['id'] ?? 0);
        $patient = Patient::findById($id);

        if (!$patient) {
            Session::flash('error', 'Patient record not found.');
            header('Location: ' . BASE_URL . '/index.php?page=patients');
            exit;
        }

        $treatmentHistory = Patient::getTreatmentHistory($id);
        $files = Patient::getFiles($id);
        $suppliesUsed = Supply::getUsageByPatient($id);
        $invoices = Invoice::getAllInvoices(['patient_id' => $id]);

        require_once __DIR__ . '/../views/patients/view.php';
    }

    public function uploadFile(): void {
        Session::requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/index.php?page=patients');
            exit;
        }

        $patientId = (int)($_POST['patient_id'] ?? 0);
        $fileTitle = trim($_POST['file_title'] ?? '');

        if (!$patientId || empty($fileTitle) || empty($_FILES['patient_file']['name'])) {
            Session::flash('error', 'Please provide a file title and select a valid document or X-ray.');
            header('Location: ' . BASE_URL . '/index.php?page=patient_view&id=' . $patientId);
            exit;
        }

        $file = $_FILES['patient_file'];
        if ($file['error'] !== UPLOAD_ERR_OK) {
            Session::flash('error', 'Upload failed. File error code: ' . $file['error']);
            header('Location: ' . BASE_URL . '/index.php?page=patient_view&id=' . $patientId);
            exit;
        }

        // Validate file type
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf', 'dcm', 'webp'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedExtensions)) {
            Session::flash('error', 'Invalid file type. Allowed: JPG, PNG, WEBP, PDF, DCM (DICOM).');
            header('Location: ' . BASE_URL . '/index.php?page=patient_view&id=' . $patientId);
            exit;
        }

        // Ensure patient directory exists
        $targetDir = PATIENT_UPLOAD_DIR . '/' . $patientId;
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $newFileName = uniqid('px_', true) . '.' . $ext;
        $destPath = $targetDir . '/' . $newFileName;
        $relativeDbPath = 'uploads/patients/' . $patientId . '/' . $newFileName;

        if (move_uploaded_file($file['tmp_name'], $destPath)) {
            Patient::addFile([
                'patient_id'      => $patientId,
                'file_title'      => $fileTitle,
                'file_path'       => $relativeDbPath,
                'file_type'       => $file['type'] ?: $ext,
                'file_size_bytes' => $file['size'],
                'uploaded_by'     => Session::user()['id']
            ]);
            Session::flash('success', 'File/X-ray uploaded successfully.');
        } else {
            Session::flash('error', 'Could not move uploaded file to destination.');
        }

        header('Location: ' . BASE_URL . '/index.php?page=patient_view&id=' . $patientId);
        exit;
    }

    public function deleteFile(): void {
        Session::requireLogin();
        $fileId = (int)($_POST['file_id'] ?? 0);
        $patientId = (int)($_POST['patient_id'] ?? 0);

        if ($fileId) {
            Patient::deleteFile($fileId);
            Session::flash('success', 'File removed successfully.');
        }
        header('Location: ' . BASE_URL . '/index.php?page=patient_view&id=' . $patientId);
        exit;
    }
}

