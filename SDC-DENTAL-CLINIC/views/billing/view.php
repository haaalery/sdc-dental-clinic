<?php
$pageTitle = 'Invoice: ' . htmlspecialchars($invoice['invoice_number']);
require_once __DIR__ . '/../layouts/header.php';
$balance = (float)$invoice['balance_amount'];
?>

<!-- Header -->
<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <div class="flex items-center space-x-3">
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight"><?= htmlspecialchars($invoice['invoice_number']) ?></h2>
            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider <?= $invoice['payment_status'] === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' ?>">
                <?= str_replace('_', ' ', $invoice['payment_status']) ?>
            </span>
        </div>
        <p class="text-xs text-slate-500 mt-1">
            Patient: <strong class="text-slate-800"><?= htmlspecialchars($invoice['first_name'] . ' ' . $invoice['last_name']) ?></strong> 
            (<?= htmlspecialchars($invoice['patient_code']) ?>) • <?= htmlspecialchars($invoice['branch_name']) ?>
        </p>
    </div>

    <a href="<?= BASE_URL ?>/index.php?page=billing" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-semibold rounded-xl transition">
        &larr; Back to Invoices
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Left Column: Record Payment Form -->
    <div class="space-y-6">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4 flex items-center space-x-2">
                <i data-lucide="wallet" class="w-4 h-4 text-teal-600"></i>
                <span>Record Patient Payment</span>
            </h3>

            <?php if ($balance <= 0): ?>
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-xs text-emerald-800 text-center font-medium">
                    <i data-lucide="check-circle" class="w-6 h-6 mx-auto mb-1 text-emerald-600"></i>
                    This invoice is fully settled. No remaining balance due.
                </div>
            <?php else: ?>
                <form action="<?= BASE_URL ?>/index.php?page=record_payment" method="POST" class="space-y-4">
                    <input type="hidden" name="invoice_id" value="<?= $invoice['id'] ?>">

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Amount to Pay (<?= APP_CURRENCY ?>)</label>
                        <input type="number" step="0.01" min="1" max="<?= $balance ?>" name="amount_paid" value="<?= $balance ?>" required
                               class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-900 focus:bg-white focus:ring-1 focus:ring-teal-500">
                        <span class="text-[11px] text-slate-400 mt-1 block">Maximum remaining balance: <?= APP_CURRENCY ?><?= number_format($balance, 2) ?></span>
                    </div>

                    <!-- Payment Method (Cash, Online, Card) - Explicit Interview Requirement -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Payment Method</label>
                        <select name="payment_method" id="selPaymentMethod" onchange="toggleRefField(this.value)" required 
                                class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:bg-white focus:ring-1 focus:ring-teal-500">
                            <option value="Cash">Cash</option>
                            <option value="Online">Online (GCash / Maya / Bank Transfer)</option>
                            <option value="Card">Card (Debit / Credit Card Terminal)</option>
                        </select>
                    </div>

                    <div id="refNumberContainer" class="hidden">
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Reference / Approval No.</label>
                        <input type="text" name="reference_number" id="inpRefNumber" placeholder="e.g. GCash Ref / POS Slip Approval #"
                               class="w-full p-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-mono">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Payment Notes</label>
                        <input type="text" name="notes" placeholder="e.g. Initial deposit for braces installment..."
                               class="w-full p-2 bg-slate-50 border border-slate-200 rounded-lg text-xs">
                    </div>

                    <button type="submit" class="w-full py-2.5 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-xl text-xs shadow-sm transition flex items-center justify-center space-x-1.5">
                        <i data-lucide="receipt" class="w-4 h-4"></i>
                        <span>Accept Payment & Print Receipt</span>
                    </button>
                </form>
            <?php endif; ?>
        </div>

        <!-- Balance Card -->
        <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-2xl p-5 text-white shadow-xs">
            <span class="text-xs uppercase font-bold text-slate-400">Account Summary</span>
            <div class="mt-3 space-y-2 text-xs">
                <div class="flex justify-between text-slate-300">
                    <span>Subtotal:</span>
                    <span class="font-mono"><?= APP_CURRENCY ?><?= number_format($invoice['subtotal'], 2) ?></span>
                </div>
                <div class="flex justify-between text-slate-300">
                    <span>Discount:</span>
                    <span class="font-mono text-amber-400">-<?= APP_CURRENCY ?><?= number_format($invoice['discount_amount'], 2) ?></span>
                </div>
                <div class="flex justify-between font-bold text-white border-t border-slate-700 pt-1">
                    <span>Net Invoice Total:</span>
                    <span class="font-mono text-sm"><?= APP_CURRENCY ?><?= number_format($invoice['total_amount'], 2) ?></span>
                </div>
                <div class="flex justify-between text-emerald-400 pt-1">
                    <span>Total Paid to Date:</span>
                    <span class="font-mono font-bold"><?= APP_CURRENCY ?><?= number_format($invoice['paid_amount'], 2) ?></span>
                </div>
                <div class="flex justify-between font-extrabold text-base border-t border-slate-700 pt-2 <?= $balance > 0 ? 'text-rose-400' : 'text-emerald-400' ?>">
                    <span>Remaining Balance:</span>
                    <span class="font-mono"><?= APP_CURRENCY ?><?= number_format($balance, 2) ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Right 2 Columns: Itemized Treatments & Payment Transactions -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Itemized Treatments -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6">
            <h3 class="text-base font-bold text-slate-900 mb-4">Itemized Treatments & Procedures Billed</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 uppercase font-bold border-b border-slate-200">
                            <th class="py-2.5 px-3">Procedure Description</th>
                            <th class="py-2.5 px-3">Attending Dentist</th>
                            <th class="py-2.5 px-3 text-right">Fee</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php foreach ($treatments as $t): ?>
                            <tr>
                                <td class="py-2.5 px-3 font-semibold text-slate-800">
                                    <?= htmlspecialchars($t['treatment_name']) ?>
                                    <?php if (!empty($t['notes'])): ?>
                                        <span class="text-[10px] text-slate-400 block"><?= htmlspecialchars($t['notes']) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-2.5 px-3 text-slate-600">Dr. <?= htmlspecialchars($t['dentist_name']) ?></td>
                                <td class="py-2.5 px-3 text-right font-mono font-bold text-slate-900"><?= APP_CURRENCY ?><?= number_format($t['actual_price'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Payment History Transactions & Printable Receipts -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6">
            <h3 class="text-base font-bold text-slate-900 mb-4">Payment Receipts Issued</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 uppercase font-bold border-b border-slate-200">
                            <th class="py-2.5 px-3">Receipt #</th>
                            <th class="py-2.5 px-3">Date</th>
                            <th class="py-2.5 px-3">Method</th>
                            <th class="py-2.5 px-3">Reference #</th>
                            <th class="py-2.5 px-3 text-right">Amount</th>
                            <th class="py-2.5 px-3 text-center">Print</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php if (empty($payments)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-6 text-slate-400 italic">No payments recorded yet for this invoice.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($payments as $pay): ?>
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="py-2.5 px-3 font-mono font-bold text-teal-700"><?= htmlspecialchars($pay['receipt_number']) ?></td>
                                    <td class="py-2.5 px-3 text-slate-600"><?= date('M d, Y h:i A', strtotime($pay['payment_date'])) ?></td>
                                    <td class="py-2.5 px-3">
                                        <span class="px-2 py-0.5 rounded font-semibold text-[10px] bg-slate-100 text-slate-700">
                                            <?= htmlspecialchars($pay['payment_method']) ?>
                                        </span>
                                    </td>
                                    <td class="py-2.5 px-3 font-mono text-slate-500"><?= htmlspecialchars($pay['reference_number'] ?: '-') ?></td>
                                    <td class="py-2.5 px-3 text-right font-mono font-bold text-emerald-600"><?= APP_CURRENCY ?><?= number_format($pay['amount_paid'], 2) ?></td>
                                    <td class="py-2.5 px-3 text-center">
                                        <a href="<?= BASE_URL ?>/index.php?page=receipt&id=<?= $pay['id'] ?>" target="_blank" 
                                           class="px-2.5 py-1 bg-slate-100 hover:bg-teal-50 hover:text-teal-700 text-slate-700 font-semibold rounded border border-slate-200 transition inline-flex items-center space-x-1">
                                            <i data-lucide="printer" class="w-3.5 h-3.5"></i>
                                            <span>Print</span>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function toggleRefField(method) {
    const container = document.getElementById('refNumberContainer');
    if (method === 'Online' || method === 'Card') {
        container.classList.remove('hidden');
    } else {
        container.classList.add('hidden');
    }
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

