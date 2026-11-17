<?php
$pageTitle = 'Commission Management & Payouts';
require_once __DIR__ . '/../layouts/header.php';

$filterDentist = $_GET['dentist_id'] ?? '';
$filterTreatment = $_GET['treatment_id'] ?? '';
$filterStartDate = $_GET['start_date'] ?? '';
$filterEndDate = $_GET['end_date'] ?? '';
?>

<!-- Header -->
<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Dentist Commission Ledger & Payouts</h2>
        <p class="text-xs text-slate-500 mt-1">Review dentist commission shares, clinic retention portions, and treatment-level earnings.</p>
    </div>
</div>

<!-- Filters Bar (Filter by Treatment and Dentist as requested in interview) -->
<div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs mb-6">
    <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 text-xs">
        <input type="hidden" name="page" value="commissions">

        <!-- Dentist Filter -->
        <div>
            <label class="block font-semibold text-slate-600 mb-1">Filter by Dentist</label>
            <select name="dentist_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-teal-500">
                <option value="">All Dentists</option>
                <?php foreach ($dentists as $d): ?>
                    <option value="<?= $d['dentist_id'] ?>" <?= ($filterDentist == $d['dentist_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($d['full_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Treatment Filter -->
        <div>
            <label class="block font-semibold text-slate-600 mb-1">Filter by Treatment</label>
            <select name="treatment_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-teal-500">
                <option value="">All Treatments</option>
                <?php foreach ($treatments as $t): ?>
                    <option value="<?= $t['id'] ?>" <?= ($filterTreatment == $t['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($t['treatment_name']) ?> (<?= (int)$t['fixed_commission_rate'] ?>%)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Start Date -->
        <div>
            <label class="block font-semibold text-slate-600 mb-1">From Date</label>
            <input type="date" name="start_date" value="<?= htmlspecialchars($filterStartDate) ?>" 
                   class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-teal-500">
        </div>

        <!-- End Date -->
        <div>
            <label class="block font-semibold text-slate-600 mb-1">To Date</label>
            <input type="date" name="end_date" value="<?= htmlspecialchars($filterEndDate) ?>" 
                   class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-teal-500">
        </div>

        <div class="flex items-end space-x-2">
            <button type="submit" class="w-full py-2 bg-slate-800 hover:bg-slate-900 text-white font-semibold rounded-lg transition">
                Filter
            </button>
            <a href="<?= BASE_URL ?>/index.php?page=commissions" class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg transition">
                Reset
            </a>
        </div>
    </form>
</div>

<!-- Summary Cards by Dentist -->
<div class="mb-8">
    <h3 class="text-base font-bold text-slate-900 mb-3">Dentist Earnings & Commission Share Summary</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        <?php foreach ($reportData['summary_by_dentist'] as $sum): ?>
            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-bold text-slate-900 text-sm">Dr. <?= htmlspecialchars($sum['dentist_name']) ?></span>
                        <span class="text-xs px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 font-medium font-mono">
                            <?= $sum['total_cases'] ?> procedure<?= $sum['total_cases'] === 1 ? '' : 's' ?>
                        </span>
                    </div>
                    <?php if (!empty($sum['license_no'])): ?>
                        <p class="text-[11px] text-slate-400 mb-3">License: <?= htmlspecialchars($sum['license_no']) ?></p>
                    <?php endif; ?>

                    <div class="space-y-1.5 py-2 border-y border-slate-100 text-xs">
                        <div class="flex justify-between text-slate-500">
                            <span>Total Billed Services:</span>
                            <span class="font-mono font-bold text-slate-800"><?= APP_CURRENCY ?><?= number_format($sum['total_revenue'], 2) ?></span>
                        </div>
                        <div class="flex justify-between text-slate-500">
                            <span>Clinic Retention:</span>
                            <span class="font-mono font-semibold text-slate-600"><?= APP_CURRENCY ?><?= number_format($sum['clinic_share'], 2) ?></span>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-2 flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-teal-800">Earned Commission</span>
                    <span class="text-lg font-extrabold text-teal-700 font-mono">
                        <?= APP_CURRENCY ?><?= number_format($sum['dentist_share'], 2) ?>
                    </span>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Detailed Itemized Commission Transactions -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
    <div class="p-5 border-b border-slate-100 flex items-center justify-between">
        <div>
            <h3 class="text-base font-bold text-slate-900">Itemized Procedure Ledger</h3>
            <p class="text-xs text-slate-500">Every treatment recorded with split rates and calculation inspector.</p>
        </div>
        <div class="text-xs text-slate-600">
            Total Revenue: <strong class="font-mono font-bold text-slate-900"><?= APP_CURRENCY ?><?= number_format($reportData['total_revenue'], 2) ?></strong> | 
            Dentist Payouts: <strong class="font-mono font-bold text-teal-700"><?= APP_CURRENCY ?><?= number_format($reportData['total_commission'], 2) ?></strong>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-xs">
            <thead>
                <tr class="bg-slate-50 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-200">
                    <th class="py-3 px-4">Date</th>
                    <th class="py-3 px-4">Dentist</th>
                    <th class="py-3 px-4">Patient</th>
                    <th class="py-3 px-4">Treatment</th>
                    <th class="py-3 px-4 text-right">Fee</th>
                    <th class="py-3 px-4 text-center">Split %</th>
                    <th class="py-3 px-4 text-right">Dentist Share</th>
                    <th class="py-3 px-4 text-right">Clinic Share</th>
                    <th class="py-3 px-4 text-center">Transparent Math</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-slate-700">
                <?php if (empty($reportData['records'])): ?>
                    <tr>
                        <td colspan="9" class="text-center py-8 text-slate-400 italic">No procedures match the selected criteria.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($reportData['records'] as $rec): ?>
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-3 px-4 font-mono"><?= date('M d, Y', strtotime($rec['appointment_date'])) ?></td>
                            <td class="py-3 px-4 font-semibold text-slate-900">Dr. <?= htmlspecialchars($rec['dentist_name']) ?></td>
                            <td class="py-3 px-4 font-medium"><?= htmlspecialchars($rec['first_name'] . ' ' . $rec['last_name']) ?></td>
                            <td class="py-3 px-4">
                                <span class="font-medium text-slate-800"><?= htmlspecialchars($rec['treatment_name']) ?></span>
                                <span class="text-[10px] text-slate-400 block"><?= htmlspecialchars($rec['category']) ?></span>
                            </td>
                            <td class="py-3 px-4 text-right font-mono font-bold text-slate-900">
                                <?= APP_CURRENCY ?><?= number_format($rec['actual_price'], 2) ?>
                            </td>
                            <td class="py-3 px-4 text-center font-bold text-teal-600">
                                <?= (int)$rec['commission_rate'] ?>%
                            </td>
                            <td class="py-3 px-4 text-right font-mono font-bold text-teal-700">
                                <?= APP_CURRENCY ?><?= number_format($rec['dentist_commission_amount'], 2) ?>
                            </td>
                            <td class="py-3 px-4 text-right font-mono text-slate-500">
                                <?= APP_CURRENCY ?><?= number_format($rec['clinic_share_amount'], 2) ?>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <button onclick="openTreatmentFormulaModal('<?= addslashes(htmlspecialchars($rec['treatment_name'])) ?>', '<?= addslashes(htmlspecialchars($rec['dentist_name'])) ?>', <?= (float)$rec['actual_price'] ?>, <?= (float)$rec['commission_rate'] ?>, <?= (float)$rec['dentist_commission_amount'] ?>, <?= (float)$rec['clinic_share_amount'] ?>)" 
                                        class="px-2.5 py-1 bg-slate-100 hover:bg-teal-50 hover:text-teal-700 text-slate-600 text-[10px] font-semibold rounded border border-slate-200 transition">
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

