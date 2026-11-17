<?php
/**
 * SDC Dental Care Management System
 * Financial KPI & Commission Report Controller
 */

require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../models/Report.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Treatment.php';
require_once __DIR__ . '/../models/Expense.php';

class ReportController {
    /**
     * Executive KPI Dashboard (Financials, Income by Day/Week/Month/Year, Net, Method Breakdown)
     */
    public function dashboard(): void {
        Session::requireLogin();
        $user = Session::user();

        // Branch filter (Admins can switch, others locked to own branch)
        $branchId = null;
        if ($user['role'] === 'admin') {
            $branchId = !empty($_GET['branch_id']) ? (int)$_GET['branch_id'] : null;
        } else {
            $branchId = !empty($user['branch_id']) ? (int)$user['branch_id'] : null;
        }

        // Active Income Tab (day, week, month, year)
        $activeTab = $_GET['tab'] ?? 'day';

        $summary = Report::getIncomeSummary($branchId);
        $methodBreakdown = Report::getPaymentMethodBreakdown(date('Y-m-d'), $branchId);
        $branches = User::getAllBranches();
        $monthlyTrend = Report::getMonthlyTrend((int)date('Y'), $branchId);

        // Daily Net calculation audit data for transparent modal
        $auditDailyNet = Report::getTransparentCalculationAudit('daily_net', date('Y-m-d'), $branchId);

        // Recent appointments for front desk quick overview
        $todayAppointments = Appointment::getAll([
            'date'      => date('Y-m-d'),
            'branch_id' => $branchId
        ]);

        require_once __DIR__ . '/../views/dashboard/admin.php';
    }

    /**
     * Dentist Personal Dashboard / View
     */
    public function dentistDashboard(): void {
        Session::requireRole(['dentist', 'admin']);
        $user = Session::user();

        $dentistId = null;
        if ($user['role'] === 'dentist') {
            $dentistId = (int)$user['dentist_id'];
        } else {
            $dentistId = !empty($_GET['dentist_id']) ? (int)$_GET['dentist_id'] : null;
        }

        $commissions = Report::getDentistCommissions([
            'dentist_id' => $dentistId,
            'month'      => date('m'),
            'year'       => date('Y')
        ]);

        $myAppointments = Appointment::getAll([
            'dentist_id' => $dentistId,
            'date'       => date('Y-m-d')
        ]);

        require_once __DIR__ . '/../views/dashboard/dentist.php';
    }

    /**
     * Commission Management Report (Filterable by dentist, treatment, and dates)
     */
    public function commissions(): void {
        Session::requireLogin();
        $filters = [];

        if (!empty($_GET['dentist_id'])) {
            $filters['dentist_id'] = (int)$_GET['dentist_id'];
        }
        if (!empty($_GET['treatment_id'])) {
            $filters['treatment_id'] = (int)$_GET['treatment_id'];
        }
        if (!empty($_GET['branch_id'])) {
            $filters['branch_id'] = (int)$_GET['branch_id'];
        }
        if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
            $filters['start_date'] = $_GET['start_date'];
            $filters['end_date'] = $_GET['end_date'];
        }

        $reportData = Report::getDentistCommissions($filters);
        $dentists = User::getAllDentists();
        $treatments = Treatment::getAll();
        $branches = User::getAllBranches();

        require_once __DIR__ . '/../views/reports/commissions.php';
    }

    /**
     * JSON API Endpoint for Transparent Calculation Modals
     */
    public function getCalculationAudit(): void {
        Session::requireLogin();
        header('Content-Type: application/json');

        $type = $_GET['type'] ?? 'daily_net';
        $date = $_GET['date'] ?? date('Y-m-d');
        $branchId = !empty($_GET['branch_id']) ? (int)$_GET['branch_id'] : null;

        $data = Report::getTransparentCalculationAudit($type, $date, $branchId);
        echo json_encode($data);
        exit;
    }
}

