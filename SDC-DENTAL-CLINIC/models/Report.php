<?php
/**
 * SDC Dental Care Management System
 * Financial & Commission Reporting Model
 * Provides transparent step-by-step computations and breakdowns
 */

require_once __DIR__ . '/../config/database.php';

class Report {
    /**
     * Get Total Income for specific period (Day, Week, Month, Year)
     */
    public static function getIncomeSummary(?int $branchId = null): array {
        $branchFilter = $branchId ? " AND branch_id = $branchId" : "";

        // Today
        $todayIncome = (float) Database::fetchValue("SELECT COALESCE(SUM(amount_paid), 0) FROM payments WHERE DATE(payment_date) = CURDATE() $branchFilter");

        // This Week (Monday to Sunday)
        $weekIncome = (float) Database::fetchValue("SELECT COALESCE(SUM(amount_paid), 0) FROM payments WHERE YEARWEEK(payment_date, 1) = YEARWEEK(CURDATE(), 1) $branchFilter");

        // This Month
        $monthIncome = (float) Database::fetchValue("SELECT COALESCE(SUM(amount_paid), 0) FROM payments WHERE YEAR(payment_date) = YEAR(CURDATE()) AND MONTH(payment_date) = MONTH(CURDATE()) $branchFilter");

        // This Year
        $yearIncome = (float) Database::fetchValue("SELECT COALESCE(SUM(amount_paid), 0) FROM payments WHERE YEAR(payment_date) = YEAR(CURDATE()) $branchFilter");

        // Today Expenses
        $todayExpenses = (float) Database::fetchValue("SELECT COALESCE(SUM(amount), 0) FROM expenses WHERE expense_date = CURDATE() $branchFilter");

        // Month Expenses
        $monthExpenses = (float) Database::fetchValue("SELECT COALESCE(SUM(amount), 0) FROM expenses WHERE YEAR(expense_date) = YEAR(CURDATE()) AND MONTH(expense_date) = MONTH(CURDATE()) $branchFilter");

        // Today Net Income
        $todayNetIncome = $todayIncome - $todayExpenses;
        $monthNetIncome = $monthIncome - $monthExpenses;

        return [
            'today_income'    => $todayIncome,
            'week_income'     => $weekIncome,
            'month_income'    => $monthIncome,
            'year_income'     => $yearIncome,
            'today_expenses'  => $todayExpenses,
            'month_expenses'  => $monthExpenses,
            'today_net'       => $todayNetIncome,
            'month_net'       => $monthNetIncome,
        ];
    }

    /**
     * Income breakdown by Payment Method (Cash, Online, Card)
     */
    public static function getPaymentMethodBreakdown(?string $date = null, ?int $branchId = null): array {
        $targetDate = $date ?: date('Y-m-d');
        $params = [$targetDate];
        $branchSql = "";
        if ($branchId) {
            $branchSql = " AND branch_id = ?";
            $params[] = $branchId;
        }

        $sql = "SELECT payment_method, 
                       COUNT(*) as transaction_count, 
                       COALESCE(SUM(amount_paid), 0) as total_amount 
                FROM payments 
                WHERE DATE(payment_date) = ? $branchSql 
                GROUP BY payment_method";
        $results = Database::fetchAll($sql, $params);

        // Normalize for Cash, Online, Card
        $methods = [
            'Cash'   => ['count' => 0, 'total' => 0.00],
            'Online' => ['count' => 0, 'total' => 0.00],
            'Card'   => ['count' => 0, 'total' => 0.00],
        ];

        foreach ($results as $row) {
            if (isset($methods[$row['payment_method']])) {
                $methods[$row['payment_method']]['count'] = (int)$row['transaction_count'];
                $methods[$row['payment_method']]['total'] = (float)$row['total_amount'];
            }
        }

        // Also fetch expense breakdown by method for net calculation
        $expParams = [$targetDate];
        $expBranchSql = "";
        if ($branchId) {
            $expBranchSql = " AND branch_id = ?";
            $expParams[] = $branchId;
        }
        $expSql = "SELECT payment_method, COALESCE(SUM(amount), 0) as total_exp 
                  FROM expenses 
                  WHERE expense_date = ? $expBranchSql 
                  GROUP BY payment_method";
        $expResults = Database::fetchAll($expSql, $expParams);

        $expensesByMethod = ['Cash' => 0.0, 'Online' => 0.0, 'Card' => 0.0];
        foreach ($expResults as $r) {
            if (isset($expensesByMethod[$r['payment_method']])) {
                $expensesByMethod[$r['payment_method']] = (float)$r['total_exp'];
            }
        }

        // Compute Net per payment method
        $breakdown = [];
        $totalGross = 0;
        $totalNet = 0;
        foreach (['Cash', 'Online', 'Card'] as $m) {
            $gross = $methods[$m]['total'];
            $exp = $expensesByMethod[$m];
            $net = $gross - $exp;
            $breakdown[$m] = [
                'transactions' => $methods[$m]['count'],
                'gross'        => $gross,
                'expenses'     => $exp,
                'net'          => $net,
            ];
            $totalGross += $gross;
            $totalNet += $net;
        }

        return [
            'date'        => $targetDate,
            'methods'     => $breakdown,
            'total_gross' => $totalGross,
            'total_net'   => $totalNet,
        ];
    }

    /**
     * Dentist Earnings & Commission Breakdown
     * Filterable by dentist, treatment, branch, and date range
     */
    public static function getDentistCommissions(array $filters = []): array {
        $sql = "SELECT at.*, 
                       t.treatment_name, t.treatment_code, t.category,
                       d.id as dentist_id, d.license_no,
                       u.full_name as dentist_name,
                       a.appointment_code, a.appointment_date,
                       p.patient_code, p.first_name, p.last_name,
                       b.branch_name
                FROM appointment_treatments at
                JOIN treatments t ON at.treatment_id = t.id
                JOIN dentists d ON at.dentist_id = d.id
                JOIN users u ON d.user_id = u.id
                JOIN appointments a ON at.appointment_id = a.id
                JOIN patients p ON a.patient_id = p.id
                JOIN branches b ON a.branch_id = b.id
                WHERE 1=1";
        $params = [];

        if (!empty($filters['dentist_id'])) {
            $sql .= " AND at.dentist_id = ?";
            $params[] = $filters['dentist_id'];
        }
        if (!empty($filters['treatment_id'])) {
            $sql .= " AND at.treatment_id = ?";
            $params[] = $filters['treatment_id'];
        }
        if (!empty($filters['branch_id'])) {
            $sql .= " AND a.branch_id = ?";
            $params[] = $filters['branch_id'];
        }
        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $sql .= " AND a.appointment_date BETWEEN ? AND ?";
            $params[] = $filters['start_date'];
            $params[] = $filters['end_date'];
        } elseif (!empty($filters['month']) && !empty($filters['year'])) {
            $sql .= " AND MONTH(a.appointment_date) = ? AND YEAR(a.appointment_date) = ?";
            $params[] = $filters['month'];
            $params[] = $filters['year'];
        }

        $sql .= " ORDER BY a.appointment_date DESC, at.id DESC";
        $records = Database::fetchAll($sql, $params);

        // Aggregate by Dentist
        $summary = [];
        $totalRevenue = 0.0;
        $totalCommission = 0.0;
        $totalClinicShare = 0.0;

        foreach ($records as $rec) {
            $dId = $rec['dentist_id'];
            if (!isset($summary[$dId])) {
                $summary[$dId] = [
                    'dentist_id'     => $dId,
                    'dentist_name'   => $rec['dentist_name'],
                    'license_no'     => $rec['license_no'],
                    'total_cases'    => 0,
                    'total_revenue'  => 0.0,
                    'dentist_share'  => 0.0,
                    'clinic_share'   => 0.0,
                ];
            }
            $summary[$dId]['total_cases']++;
            $summary[$dId]['total_revenue'] += (float)$rec['actual_price'];
            $summary[$dId]['dentist_share'] += (float)$rec['dentist_commission_amount'];
            $summary[$dId]['clinic_share'] += (float)$rec['clinic_share_amount'];

            $totalRevenue += (float)$rec['actual_price'];
            $totalCommission += (float)$rec['dentist_commission_amount'];
            $totalClinicShare += (float)$rec['clinic_share_amount'];
        }

        return [
            'records'            => $records,
            'summary_by_dentist' => array_values($summary),
            'total_revenue'      => $totalRevenue,
            'total_commission'   => $totalCommission,
            'total_clinic_share' => $totalClinicShare,
        ];
    }

    /**
     * Itemized calculation audit data for Transparent Calculations Modal
     */
    public static function getTransparentCalculationAudit(string $type, string $date, ?int $branchId = null): array {
        $branchSql = $branchId ? " AND branch_id = $branchId" : "";
        if ($type === 'daily_net') {
            $payments = Database::fetchAll("SELECT p.*, pt.first_name, pt.last_name, b.branch_name, i.invoice_number 
                                            FROM payments p 
                                            JOIN patients pt ON p.patient_id = pt.id 
                                            JOIN branches b ON p.branch_id = b.id 
                                            JOIN billing_invoices i ON p.invoice_id = i.id 
                                            WHERE DATE(p.payment_date) = ? $branchSql", [$date]);

            $expenses = Database::fetchAll("SELECT e.*, b.branch_name, u.full_name as recorded_by_name 
                                            FROM expenses e 
                                            JOIN branches b ON e.branch_id = b.id 
                                            JOIN users u ON e.recorded_by = u.id 
                                            WHERE e.expense_date = ? $branchSql", [$date]);

            $totalIncome = array_sum(array_column($payments, 'amount_paid'));
            $totalExpense = array_sum(array_column($expenses, 'amount'));
            $net = $totalIncome - $totalExpense;

            return [
                'type'          => 'Daily Net Income Calculation',
                'formula'       => 'Total Collected Payments Today (₱' . number_format($totalIncome, 2) . ') - Total Operating Expenses Today (₱' . number_format($totalExpense, 2) . ')',
                'total_income'  => $totalIncome,
                'total_expense' => $totalExpense,
                'net_income'    => $net,
                'income_items'  => $payments,
                'expense_items' => $expenses,
            ];
        }

        return [];
    }

    /**
     * Monthly trend data for Chart.js
     */
    public static function getMonthlyTrend(int $year, ?int $branchId = null): array {
        $branchSql = $branchId ? " AND branch_id = $branchId" : "";
        $incomeData = [];
        $expenseData = [];
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        for ($m = 1; $m <= 12; $m++) {
            $inc = (float) Database::fetchValue("SELECT COALESCE(SUM(amount_paid), 0) FROM payments WHERE YEAR(payment_date) = ? AND MONTH(payment_date) = ? $branchSql", [$year, $m]);
            $exp = (float) Database::fetchValue("SELECT COALESCE(SUM(amount), 0) FROM expenses WHERE YEAR(expense_date) = ? AND MONTH(expense_date) = ? $branchSql", [$year, $m]);
            $incomeData[] = $inc;
            $expenseData[] = $exp;
        }

        return [
            'labels'   => $months,
            'income'   => $incomeData,
            'expenses' => $expenseData,
        ];
    }
}

