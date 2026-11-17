<?php
$pageTitle = 'Executive KPI Dashboard';
require_once __DIR__ . '/../layouts/header.php';

$activeTab = $_GET['tab'] ?? 'day';
$branchId = $_GET['branch_id'] ?? null;
?>

<!-- Header Section -->
<div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Executive Financial Dashboard</h2>
        <p class="text-xs text-slate-500 mt-1">Real-time revenue metrics, daily net income, and payment method breakdown.</p>
    </div>

    <!-- Time Period Selector Tabs (Day, Week, Month, Year) -->
    <div class="inline-flex bg-slate-200/80 p-1 rounded-xl text-xs font-semibold">
        <a href="<?= BASE_URL ?>/index.php?page=dashboard&tab=day<?= $branchId ? '&branch_id=' . $branchId : '' ?>" 
           class="px-3 py-1.5 rounded-lg transition <?= $activeTab === 'day' ? 'bg-slate-900 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' ?>">
            Day
        </a>
        <a href="<?= BASE_URL ?>/index.php?page=dashboard&tab=week<?= $branchId ? '&branch_id=' . $branchId : '' ?>" 
           class="px-3 py-1.5 rounded-lg transition <?= $activeTab === 'week' ? 'bg-slate-900 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' ?>">
            Week
        </a>
        <a href="<?= BASE_URL ?>/index.php?page=dashboard&tab=month<?= $branchId ? '&branch_id=' . $branchId : '' ?>" 
           class="px-3 py-1.5 rounded-lg transition <?= $activeTab === 'month' ? 'bg-slate-900 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' ?>">
            Month
        </a>
        <a href="<?= BASE_URL ?>/index.php?page=dashboard&tab=year<?= $branchId ? '&branch_id=' . $branchId : '' ?>" 
           class="px-3 py-1.5 rounded-lg transition <?= $activeTab === 'year' ? 'bg-slate-900 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900' ?>">
            Year
        </a>
    </div>
</div>

<!-- Primary Financial KPI Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    <!-- Total Income Card (Selected Period) -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs relative overflow-hidden">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">
                Total Income (<?= ucfirst($activeTab) ?>)
            </span>
            <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-800 flex items-center justify-center">
                <i data-lucide="trending-up" class="w-5 h-5"></i>
            </div>
        </div>
        <div class="mt-3">
            <h3 class="text-2xl font-extrabold text-slate-900">
                <?= APP_CURRENCY ?><?= number_format($summary[$activeTab . '_income'] ?? $summary['today_income'], 2) ?>
            </h3>
            <p class="text-xs text-slate-400 mt-1">Verified collected payments</p>
        </div>
    </div>

    <!-- Daily Expenses -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs relative overflow-hidden">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Daily Expenses</span>
            <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                <i data-lucide="wallet" class="w-5 h-5"></i>
            </div>
        </div>
        <div class="mt-3">
            <h3 class="text-2xl font-extrabold text-rose-600">
                <?= APP_CURRENCY ?><?= number_format($summary['today_expenses'], 2) ?>
            </h3>
            <p class="text-xs text-slate-400 mt-1">Operating clinic disbursements</p>
        </div>
    </div>

    <!-- Daily Net Income (With Transparent Calculation Inspection) -->
    <div class="bg-slate-900 rounded-2xl p-5 text-white shadow-xs border border-slate-800 relative overflow-hidden">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-300">Daily Net Income</span>
            <button onclick="openCalculationModal('daily_net', '<?= date('Y-m-d') ?>', '<?= $branchId ?>')" 
                    title="View Computation Steps"
                    class="px-2 py-1 bg-white/10 hover:bg-white/20 text-white text-[10px] font-semibold rounded-lg flex items-center space-x-1 border border-white/20 transition">
                <i data-lucide="calculator" class="w-3 h-3"></i>
                <span>Inspect Math</span>
            </button>
        </div>
        <div class="mt-3">
            <h3 class="text-2xl font-extrabold text-white">
                <?= APP_CURRENCY ?><?= number_format($summary['today_net'], 2) ?>
            </h3>
            <p class="text-xs text-slate-400 mt-1">Total Income minus Expenses</p>
        </div>
    </div>

    <!-- Month Net Income -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs relative overflow-hidden">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Month Net Total</span>
            <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-800 flex items-center justify-center">
                <i data-lucide="bar-chart-2" class="w-5 h-5"></i>
            </div>
        </div>
        <div class="mt-3">
            <h3 class="text-2xl font-extrabold text-slate-900">
                <?= APP_CURRENCY ?><?= number_format($summary['month_net'], 2) ?>
            </h3>
            <p class="text-xs text-slate-400 mt-1">Accumulated current month</p>
        </div>
    </div>
</div>

<!-- Income Breakdown by Payment Method (Cash, Online, Card) - Explicit Interview Requirement -->
<div class="mb-8">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="text-base font-bold text-slate-900">Payment Method Breakdown (Today)</h3>
            <p class="text-xs text-slate-500">Gross revenue, method disbursements, and resulting net per payment channel.</p>
        </div>
        <span class="text-xs font-bold text-slate-600 bg-slate-200/60 px-3 py-1 rounded-full">
            Total Net: <?= APP_CURRENCY ?><?= number_format($methodBreakdown['total_net'], 2) ?>
        </span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <?php 
        $methodConfigs = [
            'Cash'   => ['icon' => 'banknote', 'bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200'],
            'Online' => ['icon' => 'smartphone', 'bg' => 'bg-sky-50', 'text' => 'text-sky-700', 'border' => 'border-sky-200'],
            'Card'   => ['icon' => 'credit-card', 'bg' => 'bg-purple-50', 'text' => 'text-purple-700', 'border' => 'border-purple-200'],
        ];
        foreach (['Cash', 'Online', 'Card'] as $method): 
            $m = $methodBreakdown['methods'][$method];
            $cfg = $methodConfigs[$method];
        ?>
            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 rounded-lg <?= $cfg['bg'] ?> <?= $cfg['text'] ?> flex items-center justify-center">
                                <i data-lucide="<?= $cfg['icon'] ?>" class="w-4 h-4"></i>
                            </div>
                            <span class="font-bold text-sm text-slate-800"><?= $method ?> Payments</span>
                        </div>
                        <span class="text-xs bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full font-medium">
                            <?= $m['transactions'] ?> txn<?= $m['transactions'] === 1 ? '' : 's' ?>
                        </span>
                    </div>

                    <div class="space-y-2 py-2 border-y border-slate-100 text-xs">
                        <div class="flex justify-between text-slate-500">
                            <span>Gross Received:</span>
                            <span class="font-semibold text-slate-800"><?= APP_CURRENCY ?><?= number_format($m['gross'], 2) ?></span>
                        </div>
                        <div class="flex justify-between text-slate-500">
                            <span>Disbursed Expenses:</span>
                            <span class="font-semibold text-rose-600">-<?= APP_CURRENCY ?><?= number_format($m['expenses'], 2) ?></span>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-2 flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Net <?= $method ?></span>
                    <span class="text-lg font-extrabold <?= $m['net'] >= 0 ? 'text-slate-900' : 'text-rose-600' ?>">
                        <?= APP_CURRENCY ?><?= number_format($m['net'], 2) ?>
                    </span>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Financial Charts & Today's Appointments Section -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    <!-- 12-Month Financial Performance Chart -->
    <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-slate-200 shadow-xs">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-base font-bold text-slate-900">Annual Financial Trends (<?= date('Y') ?>)</h3>
                <p class="text-xs text-slate-500">Monthly revenue collections vs operating expenses</p>
            </div>
        </div>
        <div class="h-64">
            <canvas id="monthlyTrendChart"></canvas>
        </div>
    </div>

    <!-- Today's Queue & Action Center -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs flex flex-col">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-base font-bold text-slate-900">Today's Schedule</h3>
            <span class="text-xs font-semibold text-teal-600 bg-teal-50 px-2 py-0.5 rounded-full">
                <?= count($todayAppointments) ?> visits
            </span>
        </div>

        <div class="flex-1 overflow-y-auto space-y-3 max-h-72">
            <?php if (empty($todayAppointments)): ?>
                <div class="text-center py-8 text-slate-400 text-xs">
                    <i data-lucide="calendar" class="w-8 h-8 mx-auto mb-2 opacity-50"></i>
                    No appointments scheduled for today yet.
                </div>
            <?php else: ?>
                <?php foreach ($todayAppointments as $apt): ?>
                    <a href="<?= BASE_URL ?>/index.php?page=appointment_view&id=<?= $apt['id'] ?>" 
                       class="block p-3 rounded-xl border border-slate-100 hover:border-teal-200 hover:bg-teal-50/30 transition">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-xs font-bold text-slate-900">
                                <?= htmlspecialchars(($apt['first_name'] ? $apt['first_name'] . ' ' . $apt['last_name'] : $apt['guest_name']) ?? 'Patient') ?>
                            </span>
                            <span class="text-[10px] font-mono px-1.5 py-0.5 rounded bg-slate-100 font-semibold text-slate-700">
                                <?= date('h:i A', strtotime($apt['appointment_time'])) ?>
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 truncate"><?= htmlspecialchars($apt['dental_concern']) ?></p>
                        <div class="mt-2 flex items-center justify-between text-[11px] text-slate-400">
                            <span>Dr. <?= htmlspecialchars($apt['assigned_dentist_name'] ?? 'TBA') ?></span>
                            <span class="capitalize font-semibold text-teal-700"><?= str_replace('_', ' ', $apt['status']) ?></span>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('monthlyTrendChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($monthlyTrend['labels']) ?>,
            datasets: [
                {
                    label: 'Total Collected Income (₱)',
                    data: <?= json_encode($monthlyTrend['income']) ?>,
                    backgroundColor: '#1e293b',
                    borderRadius: 6
                },
                {
                    label: 'Total Expenses (₱)',
                    data: <?= json_encode($monthlyTrend['expenses']) ?>,
                    backgroundColor: '#94a3b8',
                    borderRadius: 6
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(val) { return '₱' + val.toLocaleString(); }
                    }
                }
            }
        }
    });
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

