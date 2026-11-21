<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../config/session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Official Receipt - <?= htmlspecialchars($receipt['receipt_number']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .receipt-mono { font-family: 'Space Mono', monospace; }
    </style>
</head>
<body class="bg-slate-100 min-h-screen p-4 md:p-8 flex flex-col items-center justify-center font-sans">

    <!-- Action Toolbar (Hidden during print) -->
    <div class="max-w-md w-full mb-4 flex items-center justify-between no-print">
        <a href="<?= BASE_URL ?>/index.php?page=invoice_view&id=<?= $receipt['invoice_id'] ?>" class="text-xs text-slate-600 hover:text-slate-900 font-semibold flex items-center space-x-1">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Back to Invoice</span>
        </a>
        <button onclick="window.print()" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold rounded-xl shadow-xs transition flex items-center space-x-1.5 btn-print">
            <i data-lucide="printer" class="w-4 h-4"></i>
            <span>Print Official Receipt</span>
        </button>
    </div>

    <!-- Official Receipt Container (80mm Thermal & A5 compatible) -->
    <div class="print-container max-w-md w-full bg-white rounded-2xl shadow-lg border border-slate-200 p-6 md:p-8 text-slate-800">
        <!-- Clinic Branding Header with Logo -->
        <div class="text-center pb-4 border-b border-dashed border-slate-300">
            <img src="<?= BASE_URL ?>/assets/images/logo.png" alt="SDC Dental Logo" class="w-16 h-16 mx-auto mb-2 object-contain">
            <h1 class="text-xl font-bold tracking-widest font-serif text-slate-900 uppercase"><?= APP_NAME ?></h1>
            <p class="text-xs text-slate-600 font-medium"><?= htmlspecialchars($receipt['branch_name']) ?></p>
            <p class="text-[11px] text-slate-500 mt-0.5"><?= htmlspecialchars($receipt['branch_address']) ?></p>
            <p class="text-[11px] text-slate-500">Tel: <?= htmlspecialchars($receipt['branch_phone']) ?></p>
            
            <div class="mt-3 inline-block px-3 py-1 bg-slate-100 rounded-md text-xs font-bold uppercase tracking-wider text-slate-800 border border-slate-200">
                Official Payment Receipt
            </div>
        </div>

        <!-- Receipt Metadata -->
        <div class="py-3 border-b border-dashed border-slate-300 text-xs space-y-1 receipt-mono">
            <div class="flex justify-between">
                <span class="text-slate-500">Receipt No:</span>
                <span class="font-bold text-slate-900"><?= htmlspecialchars($receipt['receipt_number']) ?></span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Invoice Ref:</span>
                <span class="font-bold text-slate-900"><?= htmlspecialchars($receipt['invoice_number']) ?></span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Date & Time:</span>
                <span><?= date('M d, Y h:i A', strtotime($receipt['payment_date'])) ?></span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Cashier:</span>
                <span><?= htmlspecialchars($receipt['cashier_name']) ?></span>
            </div>
        </div>

        <!-- Patient & Attending Dentist Details -->
        <?php
        $receiptPatientName = $receipt['display_patient_name'] ?? trim(($receipt['first_name'] ?? '') . ' ' . ($receipt['last_name'] ?? ''));
        $receiptPatientId = !empty($receipt['patient_code']) ? htmlspecialchars($receipt['patient_code']) : 'ONLINE-GUEST';
        ?>
        <div class="py-3 border-b border-dashed border-slate-300 text-xs space-y-1">
            <div class="flex justify-between">
                <span class="text-slate-500">Patient Name:</span>
                <span class="font-bold text-slate-900"><?= htmlspecialchars($receiptPatientName) ?></span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Patient ID:</span>
                <span class="receipt-mono font-medium"><?= $receiptPatientId ?></span>
            </div>
            <?php if (!empty($receipt['occupation'])): ?>
                <div class="flex justify-between">
                    <span class="text-slate-500">Occupation:</span>
                    <span><?= htmlspecialchars($receipt['occupation']) ?></span>
                </div>
            <?php endif; ?>
            <div class="flex justify-between">
                <span class="text-slate-500">Attending Dentist:</span>
                <span class="font-semibold text-slate-800">Dr. <?= htmlspecialchars($receipt['attending_dentist_name'] ?? 'Attending Clinician') ?></span>
            </div>
            <?php if (!empty($receipt['dentist_license'])): ?>
                <div class="flex justify-between">
                    <span class="text-slate-500">PRC License:</span>
                    <span class="receipt-mono"><?= htmlspecialchars($receipt['dentist_license']) ?></span>
                </div>
            <?php endif; ?>
        </div>

        <!-- Itemized Dental Treatments -->
        <div class="py-3 border-b border-dashed border-slate-300">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-2">Performed Services / Treatments</span>
            <table class="w-full text-xs print-table">
                <thead>
                    <tr class="text-slate-500 border-b border-slate-200">
                        <th class="text-left py-1 font-semibold">Service</th>
                        <th class="text-right py-1 font-semibold">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if (!empty($receipt['treatments'])): ?>
                        <?php foreach ($receipt['treatments'] as $t): ?>
                            <tr>
                                <td class="py-1 text-slate-800"><?= htmlspecialchars($t['treatment_name']) ?></td>
                                <td class="py-1 text-right receipt-mono font-semibold"><?= APP_CURRENCY ?><?= number_format($t['actual_price'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td class="py-1 text-slate-800">Dental Consultation & Services</td>
                            <td class="py-1 text-right receipt-mono font-semibold"><?= APP_CURRENCY ?><?= number_format($receipt['total_amount'], 2) ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Bill Totals & Payment Summary -->
        <div class="py-3 border-b border-dashed border-slate-300 text-xs space-y-1.5 receipt-mono">
            <div class="flex justify-between text-slate-600">
                <span>Treatment Total:</span>
                <span><?= APP_CURRENCY ?><?= number_format($receipt['total_amount'], 2) ?></span>
            </div>
            <div class="flex justify-between text-slate-600">
                <span>Total Accumulated Payments:</span>
                <span><?= APP_CURRENCY ?><?= number_format($receipt['total_invoice_paid'], 2) ?></span>
            </div>
            
            <!-- This Transaction Payment -->
            <div class="flex justify-between text-sm font-bold text-slate-900 pt-1 border-t border-slate-200">
                <span>THIS PAYMENT:</span>
                <span class="text-slate-900"><?= APP_CURRENCY ?><?= number_format($receipt['amount_paid'], 2) ?></span>
            </div>
            <div class="flex justify-between text-[11px] text-slate-500">
                <span>Payment Method:</span>
                <span class="font-bold text-slate-700">
                    <?= htmlspecialchars($receipt['payment_method']) ?>
                    <?= !empty($receipt['reference_number']) ? ' (' . htmlspecialchars($receipt['reference_number']) . ')' : '' ?>
                </span>
            </div>

            <!-- Remaining Balance for Ongoing Treatment (Explicit Interview Requirement) -->
            <div class="flex justify-between text-xs font-bold pt-2 border-t border-slate-200 <?= (float)$receipt['remaining_balance'] > 0 ? 'text-rose-600' : 'text-emerald-700' ?>">
                <span>REMAINING BALANCE DUE:</span>
                <span><?= APP_CURRENCY ?><?= number_format($receipt['remaining_balance'], 2) ?></span>
            </div>
        </div>

        <!-- Footer / Thank you note -->
        <div class="pt-4 text-center text-xs text-slate-500 space-y-1">
            <p class="font-medium text-slate-700">Thank you for trusting <?= APP_NAME ?>!</p>
            <p class="text-[10px] text-slate-400">Keep this receipt for your records and health insurance claims.</p>
            <div class="pt-4 text-[9px] text-slate-300 font-mono">
                *** END OF OFFICIAL RECEIPT ***
            </div>
        </div>
    </div>

    <script>lucide.createIcons();</script>
</body>
</html>

