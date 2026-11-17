<?php
$pageTitle = 'Billing, Balances & Invoices';
require_once __DIR__ . '/../layouts/header.php';

$filterStatus = $_GET['status'] ?? '';
?>

<!-- Header -->
<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Billing & Outstanding Balances</h2>
        <p class="text-xs text-slate-500 mt-1">Manage patient invoices, track remaining balances for ongoing treatments, and print receipts.</p>
    </div>
</div>

<!-- Filters Bar -->
<div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs mb-6">
    <form method="GET" class="flex flex-wrap items-center gap-4 text-xs">
        <input type="hidden" name="page" value="billing">

        <div>
            <label class="block font-semibold text-slate-600 mb-1">Payment Status</label>
            <select name="status" onchange="this.form.submit()" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-teal-500">
                <option value="">All Invoices</option>
                <option value="unpaid" <?= $filterStatus === 'unpaid' ? 'selected' : '' ?>>Unpaid</option>
                <option value="partially_paid" <?= $filterStatus === 'partially_paid' ? 'selected' : '' ?>>Partially Paid (With Balance)</option>
                <option value="paid" <?= $filterStatus === 'paid' ? 'selected' : '' ?>>Fully Paid</option>
            </select>
        </div>

        <?php if (!empty($filterStatus)): ?>
            <div class="flex items-end">
                <a href="<?= BASE_URL ?>/index.php?page=billing" class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg transition">
                    Clear Filter
                </a>
            </div>
        <?php endif; ?>
    </form>
</div>

<!-- Invoices Table -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-xs">
            <thead>
                <tr class="bg-slate-50 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-200">
                    <th class="py-3 px-4">Invoice #</th>
                    <th class="py-3 px-4">Patient Name</th>
                    <th class="py-3 px-4">Appointment</th>
                    <th class="py-3 px-4">Branch</th>
                    <th class="py-3 px-4 text-right">Total Bill</th>
                    <th class="py-3 px-4 text-right">Paid</th>
                    <th class="py-3 px-4 text-right">Remaining Balance</th>
                    <th class="py-3 px-4 text-center">Status</th>
                    <th class="py-3 px-4 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-slate-700">
                <?php if (empty($invoices)): ?>
                    <tr>
                        <td colspan="9" class="text-center py-8 text-slate-400 italic">No invoices found matching criteria.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($invoices as $inv): 
                        $balance = (float)$inv['balance_amount'];
                        $isFullyPaid = ($inv['payment_status'] === 'paid');
                    ?>
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-3 px-4 font-mono font-bold text-teal-700">
                                <?= htmlspecialchars($inv['invoice_number']) ?>
                            </td>
                            <td class="py-3 px-4 font-bold text-slate-900">
                                <?= htmlspecialchars($inv['first_name'] . ' ' . $inv['last_name']) ?>
                                <span class="text-[10px] text-slate-400 block font-normal"><?= htmlspecialchars($inv['patient_code']) ?></span>
                            </td>
                            <td class="py-3 px-4 font-mono">
                                <a href="<?= BASE_URL ?>/index.php?page=appointment_view&id=<?= $inv['appointment_id'] ?>" class="text-teal-600 hover:underline">
                                    <?= htmlspecialchars($inv['appointment_code']) ?>
                                </a>
                                <span class="text-[10px] text-slate-400 block"><?= date('M d, Y', strtotime($inv['appointment_date'])) ?></span>
                            </td>
                            <td class="py-3 px-4 text-slate-600"><?= htmlspecialchars($inv['branch_name']) ?></td>
                            <td class="py-3 px-4 text-right font-mono font-bold text-slate-900">
                                <?= APP_CURRENCY ?><?= number_format($inv['total_amount'], 2) ?>
                            </td>
                            <td class="py-3 px-4 text-right font-mono font-bold text-emerald-600">
                                <?= APP_CURRENCY ?><?= number_format($inv['paid_amount'], 2) ?>
                            </td>
                            <td class="py-3 px-4 text-right font-mono font-bold <?= $balance > 0 ? 'text-rose-600' : 'text-slate-400' ?>">
                                <?= APP_CURRENCY ?><?= number_format($balance, 2) ?>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider <?= $isFullyPaid ? 'bg-emerald-100 text-emerald-800' : ($inv['paid_amount'] > 0 ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800') ?>">
                                    <?= str_replace('_', ' ', $inv['payment_status']) ?>
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <a href="<?= BASE_URL ?>/index.php?page=invoice_view&id=<?= $inv['id'] ?>" 
                                   class="px-3 py-1.5 bg-slate-100 hover:bg-teal-600 hover:text-white text-slate-700 font-semibold rounded-lg border border-slate-200 transition">
                                    Manage / Pay
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

