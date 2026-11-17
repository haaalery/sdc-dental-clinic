<?php
/**
 * SDC Dental Care Management System
 * Expense Controller (Daily Operating Expenses)
 */

require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../models/Expense.php';
require_once __DIR__ . '/../models/User.php';

class ExpenseController {
    public function index(): void {
        Session::requireLogin();
        $user = Session::user();

        $filters = [];
        if (!empty($_GET['date'])) {
            $filters['date'] = $_GET['date'];
        }
        if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
            $filters['start_date'] = $_GET['start_date'];
            $filters['end_date'] = $_GET['end_date'];
        }
        if (!empty($_GET['category'])) {
            $filters['category'] = $_GET['category'];
        }
        if (!empty($_GET['branch_id'])) {
            $filters['branch_id'] = (int)$_GET['branch_id'];
        } elseif ($user['role'] !== 'admin' && !empty($user['branch_id'])) {
            $filters['branch_id'] = (int)$user['branch_id'];
        }

        $expenses = Expense::getAll($filters);
        $branches = User::getAllBranches();

        // Calculate totals for currently filtered expenses
        $totalExpenses = array_sum(array_column($expenses, 'amount'));

        require_once __DIR__ . '/../views/expenses/index.php';
    }

    public function store(): void {
        Session::requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/index.php?page=expenses');
            exit;
        }

        try {
            $title = trim($_POST['title'] ?? '');
            $amount = (float)($_POST['amount'] ?? 0);
            $category = $_POST['category'] ?? 'miscellaneous';
            $branchId = (int)($_POST['branch_id'] ?? Session::user()['branch_id'] ?? 1);
            $paymentMethod = $_POST['payment_method'] ?? 'Cash';
            $expenseDate = $_POST['expense_date'] ?? date('Y-m-d');
            $notes = trim($_POST['notes'] ?? '');

            if (empty($title) || $amount <= 0) {
                throw new Exception("Please enter a valid expense title and amount.");
            }

            // Handle optional receipt upload
            $receiptAttachment = null;
            if (!empty($_FILES['receipt_attachment']['name']) && $_FILES['receipt_attachment']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['receipt_attachment'];
                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'pdf', 'webp'];
                if (in_array($ext, $allowed)) {
                    if (!is_dir(EXPENSE_UPLOAD_DIR)) {
                        mkdir(EXPENSE_UPLOAD_DIR, 0777, true);
                    }
                    $filename = uniqid('exp_', true) . '.' . $ext;
                    $dest = EXPENSE_UPLOAD_DIR . '/' . $filename;
                    if (move_uploaded_file($file['tmp_name'], $dest)) {
                        $receiptAttachment = 'uploads/expenses/' . $filename;
                    }
                }
            }

            Expense::create([
                'branch_id'          => $branchId,
                'category'           => $category,
                'title'              => $title,
                'amount'             => $amount,
                'payment_method'     => $paymentMethod,
                'expense_date'       => $expenseDate,
                'receipt_attachment' => $receiptAttachment,
                'recorded_by'        => Session::user()['id'],
                'notes'              => $notes
            ]);

            Session::flash('success', 'Expense of ' . APP_CURRENCY . number_format($amount, 2) . ' logged successfully.');
        } catch (Throwable $e) {
            Session::flash('error', $e->getMessage());
        }

        header('Location: ' . BASE_URL . '/index.php?page=expenses');
        exit;
    }

    public function delete(): void {
        Session::requireRole(['admin']);
        $id = (int)($_POST['id'] ?? 0);
        if ($id) {
            Expense::delete($id);
            Session::flash('success', 'Expense entry removed.');
        }
        header('Location: ' . BASE_URL . '/index.php?page=expenses');
        exit;
    }
}

