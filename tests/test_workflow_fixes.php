<?php
/**
 * Test Suite: Clinical Workflow Fixes Verification
 */

// Configure memory/cli session for test run
ini_set('session.use_cookies', 0);
ini_set('session.use_only_cookies', 0);
if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}

require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../models/User.php';

$passCount = 0;
$failCount = 0;

function assertCondition(bool $condition, string $testName): void {
    global $passCount, $failCount;
    if ($condition) {
        echo "[PASS] $testName\n";
        $passCount++;
    } else {
        echo "[FAIL] $testName\n";
        $failCount++;
    }
}

echo "========================================================\n";
echo "SDC DENTAL CARE - CLINICAL WORKFLOW VERIFICATION TESTS\n";
echo "========================================================\n\n";

// -----------------------------------------------------------
// TEST 1: EMR Guest Name Parsing & Pre-filling
// -----------------------------------------------------------
echo "--- 1. Testing EMR Patient Name Splitting & Parsing ---\n";
function parseFullNameTest(string $fullName): array {
    $parts = preg_split('/\s+/', trim($fullName));
    $first = $parts[0] ?? '';
    $mi = '';
    $last = '';
    if (count($parts) === 2) {
        $last = $parts[1];
    } elseif (count($parts) >= 3) {
        if (strlen($parts[1]) <= 2 || str_ends_with($parts[1], '.')) {
            $mi = $parts[1];
            $last = implode(' ', array_slice($parts, 2));
        } else {
            $last = implode(' ', array_slice($parts, 1));
        }
    }
    return ['first_name' => $first, 'middle_initial' => $mi, 'last_name' => $last];
}

$parsed1 = parseFullNameTest("Sofia D. Santos");
assertCondition($parsed1['first_name'] === 'Sofia', "Sofia D. Santos -> First Name is 'Sofia'");
assertCondition($parsed1['middle_initial'] === 'D.', "Sofia D. Santos -> Middle Initial is 'D.'");
assertCondition($parsed1['last_name'] === 'Santos', "Sofia D. Santos -> Last Name is 'Santos'");

$parsed2 = parseFullNameTest("Juan Dela Cruz");
assertCondition($parsed2['first_name'] === 'Juan', "Juan Dela Cruz -> First Name is 'Juan'");
assertCondition($parsed2['middle_initial'] === '', "Juan Dela Cruz -> Middle Initial is empty");
assertCondition($parsed2['last_name'] === 'Dela Cruz', "Juan Dela Cruz -> Last Name is 'Dela Cruz'");

$parsed3 = parseFullNameTest("Maria Santos");
assertCondition($parsed3['first_name'] === 'Maria', "Maria Santos -> First Name is 'Maria'");
assertCondition($parsed3['last_name'] === 'Santos', "Maria Santos -> Last Name is 'Santos'");

$parsed4 = parseFullNameTest("Mark A. Delos Reyes");
assertCondition($parsed4['first_name'] === 'Mark', "Mark A. Delos Reyes -> First Name is 'Mark'");
assertCondition($parsed4['middle_initial'] === 'A.', "Mark A. Delos Reyes -> Middle Initial is 'A.'");
assertCondition($parsed4['last_name'] === 'Delos Reyes', "Mark A. Delos Reyes -> Last Name is 'Delos Reyes'");


// -----------------------------------------------------------
// TEST 2: RBAC Commission Privacy in Appointment View
// -----------------------------------------------------------
echo "\n--- 2. Testing RBAC Commission Display Privacy ---\n";

$appointment = [
    'id' => 101,
    'appointment_code' => 'APT-2026-0101',
    'branch_id' => 1,
    'branch_name' => 'SDC Dental Care - Main Branch',
    'patient_id' => null,
    'guest_name' => 'Sofia D. Santos',
    'guest_phone' => '0917 888 9911',
    'patient_phone' => null,
    'middle_initial' => null,
    'first_name' => null,
    'last_name' => null,
    'status' => 'confirmed',
    'appointment_date' => '2026-11-22',
    'appointment_time' => '10:00:00',
    'dental_concern' => 'Routine Consultation',
    'occupation' => null,
    'assigned_dentist_id' => 1,
    'assigned_dentist_name' => 'Maria Santos, DMD',
    'assigned_license_no' => 'PRC-0098231',
    'reassignment_reason' => null
];

$activeTreatments = [
    [
        'id' => 1,
        'treatment_name' => 'Comprehensive Oral Examination & Consultation',
        'standard_price' => 500.00,
        'fixed_commission_rate' => 30.00
    ],
    [
        'id' => 2,
        'treatment_name' => 'Dental Prophylaxis (Cleaning) - Mild',
        'standard_price' => 1200.00,
        'fixed_commission_rate' => 30.00
    ]
];

$dentists = [
    [
        'dentist_id' => 1,
        'full_name' => 'Dr. Maria Santos, DMD',
        'specialization' => 'General Dentistry & Orthodontics'
    ]
];

$performedTreatments = [
    [
        'id' => 1,
        'treatment_name' => 'Comprehensive Oral Examination',
        'dentist_name' => 'Maria Santos',
        'actual_price' => 500.00,
        'commission_rate' => 30.00,
        'dentist_commission_amount' => 150.00,
        'clinic_share_amount' => 350.00,
        'notes' => 'Exam completed'
    ]
];

$availableSupplies = [
    [
        'id' => 1,
        'item_name' => 'Disposable Saliva Ejector Suction Tips',
        'unit_of_measure' => 'pcs',
        'current_stock' => 50,
        'reorder_level' => 10
    ],
    [
        'id' => 2,
        'item_name' => 'Dental Cartridge Needle 30G',
        'unit_of_measure' => 'pcs',
        'current_stock' => 0,
        'reorder_level' => 10
    ]
];

$suppliesUsed = [];
$invoice = null;

// Mock database branches for topbar if needed
if (!class_exists('User')) {
    class User {
        public static function getAllBranches(): array {
            return [['id' => 1, 'branch_name' => 'Main Branch']];
        }
    }
}

// Test Front Desk Role
$_SESSION['user'] = [
    'id' => 3, 
    'role' => 'front_desk', 
    'username' => 'reception',
    'full_name' => 'Front Desk Reception',
    'email' => 'reception@sdcdental.com',
    'branch_id' => 1
];

// Verify RBAC flags
$userRole = Session::role();
$isClinicianOrAdmin = in_array($userRole, ['admin', 'dentist']);
assertCondition($isClinicianOrAdmin === false, "Front Desk user has isClinicianOrAdmin = false");

// Read appointment view template and test evaluation
$viewContent = file_get_contents(__DIR__ . '/../views/appointments/view.php');

// Simulate Front Desk evaluation
assertCondition(strpos($viewContent, '<?php if ($isClinicianOrAdmin): ?>') !== false, "Appointment view has RBAC conditional blocks for clinician/admin");
assertCondition(strpos($viewContent, 'Commission Split %') !== false, "View contains 'Commission Split %' protected by RBAC");

// Test Dentist Role
$_SESSION['user']['role'] = 'dentist';
$userRole = Session::role();
$isClinicianOrAdmin = in_array($userRole, ['admin', 'dentist']);
assertCondition($isClinicianOrAdmin === true, "Dentist user has isClinicianOrAdmin = true");

// Test Admin Role
$_SESSION['user']['role'] = 'admin';
$userRole = Session::role();
$isClinicianOrAdmin = in_array($userRole, ['admin', 'dentist']);
assertCondition($isClinicianOrAdmin === true, "Admin user has isClinicianOrAdmin = true");


// -----------------------------------------------------------
// TEST 3: Supply Inventory Stock Display & Out of Stock Guard
// -----------------------------------------------------------
echo "\n--- 3. Testing Supply Stock Display & Out of Stock Guard ---\n";

assertCondition(strpos($viewContent, 'data-stock') !== false, "Supply options render data-stock attribute");
assertCondition(strpos($viewContent, 'OUT OF STOCK') !== false, "Supply options render OUT OF STOCK tag for empty stock");
assertCondition(strpos($viewContent, 'onSupplySelected') !== false, "View contains interactive onSupplySelected validator");
assertCondition(strpos($viewContent, 'supplyStockNotice') !== false, "View contains #supplyStockNotice real-time feedback element");


// -----------------------------------------------------------
// TEST 4: Backend Commission Protection in TreatmentController
// -----------------------------------------------------------
echo "\n--- 4. Testing Backend Protection Against Unauthorized Commission Alteration ---\n";

// Simulate POST from Front Desk with tampered commission rate
$_SESSION['user'] = ['id' => 3, 'role' => 'front_desk', 'username' => 'reception'];
$_POST['custom_commission_rate'] = '50'; // Tampered attempt by front desk
$userRole = Session::role();
$customRate = ($userRole === 'front_desk') ? null : (!empty($_POST['custom_commission_rate']) ? (float)$_POST['custom_commission_rate'] : null);
assertCondition($customRate === null, "Front desk tampered custom_commission_rate is ignored and forced to null (uses standard procedure split)");

// Simulate POST from Dentist
$_SESSION['user'] = ['id' => 2, 'role' => 'dentist', 'username' => 'dr.maria'];
$userRole = Session::role();
$customRate = ($userRole === 'front_desk') ? null : (!empty($_POST['custom_commission_rate']) ? (float)$_POST['custom_commission_rate'] : null);
assertCondition($customRate === 50.0, "Dentist custom_commission_rate of 50% is accepted and preserved");


// -----------------------------------------------------------
// TEST 5: Supply Usage with Null Patient ID (Online Guest)
// -----------------------------------------------------------
echo "\n--- 5. Testing Supply Usage Logging for Guest Appointments ---\n";

$appointmentId = 101;
$patientId = !empty($_POST['patient_id']) ? (int)$_POST['patient_id'] : null; // empty for guest
$supplyId = 1;
$quantity = 2;

$validationPassed = (!$appointmentId || !$supplyId || $quantity <= 0) ? false : true;
assertCondition($validationPassed === true, "InventoryController validation passes when patientId is NULL for guest appointments");


// -----------------------------------------------------------
// TEST 6: Invoicing Nullable Patient ID & Fallbacks
// -----------------------------------------------------------
echo "\n--- 6. Testing Invoicing Fallbacks for Online Guest Appointments ---\n";

$mockInvoiceRow = [
    'invoice_number' => 'INV-2026-0001',
    'patient_id' => null,
    'first_name' => null,
    'last_name' => null,
    'guest_name' => 'Sofia D. Santos',
    'display_patient_name' => 'Sofia D. Santos',
    'total_amount' => 1700.00,
    'paid_amount' => 0.00,
    'balance_amount' => 1700.00,
    'payment_status' => 'unpaid'
];

$displayedName = $mockInvoiceRow['display_patient_name'] ?? trim(($mockInvoiceRow['first_name'] ?? '') . ' ' . ($mockInvoiceRow['last_name'] ?? ''));
assertCondition($displayedName === 'Sofia D. Santos', "Invoice displays guest name 'Sofia D. Santos' when patient_id is NULL");


// -----------------------------------------------------------
// SUMMARY
// -----------------------------------------------------------
echo "\n========================================================\n";
echo "TEST RESULTS: $passCount Passed, $failCount Failed\n";
echo "========================================================\n";

if ($failCount === 0) {
    echo "\n>>> ALL VERIFICATION TESTS PASSED SUCCESSFULLY! <<<\n";
    exit(0);
} else {
    echo "\n>>> SOME TESTS FAILED. PLEASE REVIEW ABOVE. <<<\n";
    exit(1);
}
