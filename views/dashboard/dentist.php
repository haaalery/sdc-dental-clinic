<?php
$pageTitle = 'Dentist Portal & Commission Summary';
require_once __DIR__ . '/../layouts/header.php';
?>

<!-- Header -->
<div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Dentist Practice & Commission Overview</h2>
        <p class="text-xs text-slate-500 mt-1">Review your completed clinical procedures, total patient billings, and earned commission share.</p>
    </div>
</div>

<!-- Dentist Summary Cards -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">My Earned Commission (This Month)</span>
            <div class="w-9 h-9 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center">
                <i data-lucide="award" class="w-5 h-5"></i>
            </div>
        </div>
        <div class="mt-3">
            <h3 class="text-2xl font-extrabold text-teal-700">
                <?= APP_CURRENCY ?><?= number_format($commissions['total_commission'], 2) ?>
            </h3>
            <p class="text-xs text-slate-400 mt-1">Net dentist payout share</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Treatment Billings</span>
            <div class="w-9 h-9 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center">
                <i data-lucide="clipboard-check" class="w-5 h-5"></i>
            </div>
        </div>
        <div class="mt-3">
            <h3 class="text-2xl font-extrabold text-slate-900">
                <?= APP_CURRENCY ?><?= number_format($commissions['total_revenue'], 2) ?>
            </h3>
            <p class="text-xs text-slate-400 mt-1">Across all performed cases</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Clinic Retention Share</span>
            <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center">
                <i data-lucide="building" class="w-5 h-5"></i>
            </div>
        </div>
        <div class="mt-3">
            <h3 class="text-2xl font-extrabold text-slate-700">
                <?= APP_CURRENCY ?><?= number_format($commissions['total_clinic_share'], 2) ?>
            </h3>
            <p class="text-xs text-slate-400 mt-1">Retained by clinic</p>
        </div>
    </div>
</div>

<!-- Performed Treatments & Commission Log -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden mb-8">
    <div class="p-6 border-b border-slate-100 flex items-center justify-between">
        <div>
            <h3 class="text-base font-bold text-slate-900">My Performed Treatments & Split Computations</h3>
            <p class="text-xs text-slate-500">Itemized ledger of procedures, split percentages, and transparent math breakdown.</p>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-xs">
            <thead>
                <tr class="bg-slate-50 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-200">
                    <th class="py-3 px-4">Date</th>
                    <th class="py-3 px-4">Patient</th>
                    <th class="py-3 px-4">Treatment / Procedure</th>
                    <th class="py-3 px-4 text-right">Fee</th>
                    <th class="py-3 px-4 text-center">Split %</th>
                    <th class="py-3 px-4 text-right">My Share</th>
                    <th class="py-3 px-4 text-right">Clinic Share</th>
                    <th class="py-3 px-4 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-slate-700">
                <?php if (empty($commissions['records'])): ?>
                    <tr>
                        <td colspan="8" class="text-center py-8 text-slate-400 italic">No treatments recorded yet this month.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($commissions['records'] as $rec): ?>
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3 px-4 font-mono"><?= date('M d, Y', strtotime($rec['appointment_date'])) ?></td>
                            <td class="py-3 px-4 font-medium text-slate-900"><?= htmlspecialchars($rec['first_name'] . ' ' . $rec['last_name']) ?></td>
                            <td class="py-3 px-4">
                                <span class="font-semibold text-slate-800"><?= htmlspecialchars($rec['treatment_name']) ?></span>
                                <span class="text-[10px] text-slate-400 block"><?= htmlspecialchars($rec['category']) ?></span>
                            </td>
                            <td class="py-3 px-4 text-right font-mono font-semibold"><?= APP_CURRENCY ?><?= number_format($rec['actual_price'], 2) ?></td>
                            <td class="py-3 px-4 text-center font-bold text-teal-600"><?= (int)$rec['commission_rate'] ?>%</td>
                            <td class="py-3 px-4 text-right font-mono font-bold text-teal-700"><?= APP_CURRENCY ?><?= number_format($rec['dentist_commission_amount'], 2) ?></td>
                            <td class="py-3 px-4 text-right font-mono text-slate-500"><?= APP_CURRENCY ?><?= number_format($rec['clinic_share_amount'], 2) ?></td>
                            <td class="py-3 px-4 text-center">
                                <button onclick="openTreatmentFormulaModal('<?= addslashes(htmlspecialchars($rec['treatment_name'])) ?>', '<?= addslashes(htmlspecialchars($rec['dentist_name'])) ?>', <?= (float)$rec['actual_price'] ?>, <?= (float)$rec['commission_rate'] ?>, <?= (float)$rec['dentist_commission_amount'] ?>, <?= (float)$rec['clinic_share_amount'] ?>)" 
                                        class="px-2 py-1 bg-slate-100 hover:bg-teal-50 hover:text-teal-700 text-slate-600 text-[10px] font-semibold rounded-lg border border-slate-200 transition">
                                    Formula
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

