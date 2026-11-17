<?php
$pageTitle = 'Daily Operating Expenses';
require_once __DIR__ . '/../layouts/header.php';

$filterDate = $_GET['date'] ?? '';
$filterCategory = $_GET['category'] ?? '';
?>

<!-- Header -->
<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Daily Expense Management</h2>
        <p class="text-xs text-slate-500 mt-1">Track daily operating and miscellaneous disbursements for clinic net computation.</p>
    </div>
    <button onclick="document.getElementById('addExpenseModal').classList.remove('hidden')" 
            class="inline-flex items-center space-x-1.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold px-4 py-2.5 rounded-xl shadow-sm transition">
        <i data-lucide="plus" class="w-4 h-4"></i>
        <span>Record New Expense</span>
    </button>
</div>

<!-- Total Filtered Expense Callout -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-6">
    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs">
        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Filtered Total Expenses</span>
        <h3 class="text-2xl font-extrabold text-rose-600 mt-2">
            <?= APP_CURRENCY ?><?= number_format($totalExpenses, 2) ?>
        </h3>
        <p class="text-xs text-slate-400 mt-1"><?= count($expenses) ?> recorded transaction<?= count($expenses) === 1 ? '' : 's' ?></p>
    </div>
</div>

<!-- Filters Bar -->
<div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs mb-6">
    <form method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
        <input type="hidden" name="page" value="expenses">

        <div>
            <label class="block font-semibold text-slate-600 mb-1">Expense Date</label>
            <input type="date" name="date" value="<?= htmlspecialchars($filterDate) ?>" 
                   class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-teal-500">
        </div>

        <div>
            <label class="block font-semibold text-slate-600 mb-1">Category</label>
            <select name="category" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-teal-500">
                <option value="">All Categories</option>
                <?php foreach (EXPENSE_CATEGORIES as $k => $v): ?>
                    <option value="<?= $k ?>" <?= $filterCategory === $k ? 'selected' : '' ?>><?= htmlspecialchars($v) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="flex items-end space-x-2">
            <button type="submit" class="w-full py-2 bg-slate-800 hover:bg-slate-900 text-white font-semibold rounded-lg transition">
                Filter
            </button>
            <a href="<?= BASE_URL ?>/index.php?page=expenses" class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg transition">
                Reset
            </a>
        </div>
    </form>
</div>

<!-- Expenses Table -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-xs">
            <thead>
                <tr class="bg-slate-50 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-200">
                    <th class="py-3 px-4">Date</th>
                    <th class="py-3 px-4">Description</th>
                    <th class="py-3 px-4">Category</th>
                    <th class="py-3 px-4">Branch</th>
                    <th class="py-3 px-4">Method</th>
                    <th class="py-3 px-4 text-right">Amount</th>
                    <th class="py-3 px-4">Recorded By</th>
                    <th class="py-3 px-4 text-center">Receipt</th>
                    <?php if (Session::hasRole(['admin'])): ?>
                        <th class="py-3 px-4 text-center">Action</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-slate-700">
                <?php if (empty($expenses)): ?>
                    <tr>
                        <td colspan="9" class="text-center py-8 text-slate-400 italic">No expenses recorded for this selection.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($expenses as $exp): ?>
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-3 px-4 font-mono font-medium text-slate-600">
                                <?= date('M d, Y', strtotime($exp['expense_date'])) ?>
                            </td>
                            <td class="py-3 px-4">
                                <span class="font-bold text-slate-900 block"><?= htmlspecialchars($exp['title']) ?></span>
                                <?php if (!empty($exp['notes'])): ?>
                                    <span class="text-[10px] text-slate-400 block"><?= htmlspecialchars($exp['notes']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded font-semibold text-[10px] bg-slate-100 text-slate-700 uppercase">
                                    <?= htmlspecialchars($exp['category']) ?>
                                </span>
                            </td>
                            <td class="py-3 px-4 text-slate-600"><?= htmlspecialchars($exp['branch_name']) ?></td>
                            <td class="py-3 px-4">
                                <span class="font-medium text-slate-700"><?= htmlspecialchars($exp['payment_method']) ?></span>
                            </td>
                            <td class="py-3 px-4 text-right font-mono font-bold text-rose-600">
                                -<?= APP_CURRENCY ?><?= number_format($exp['amount'], 2) ?>
                            </td>
                            <td class="py-3 px-4 text-slate-600"><?= htmlspecialchars($exp['recorded_by_name']) ?></td>
                            <td class="py-3 px-4 text-center">
                                <?php if (!empty($exp['receipt_attachment'])): ?>
                                    <a href="<?= BASE_URL ?>/<?= htmlspecialchars($exp['receipt_attachment']) ?>" target="_blank" class="text-teal-600 hover:text-teal-800 p-1">
                                        <i data-lucide="paperclip" class="w-4 h-4 mx-auto"></i>
                                    </a>
                                <?php else: ?>
                                    <span class="text-slate-300">-</span>
                                <?php endif; ?>
                            </td>
                            <?php if (Session::hasRole(['admin'])): ?>
                                <td class="py-3 px-4 text-center">
                                    <form action="<?= BASE_URL ?>/index.php?page=expense_delete" method="POST" onsubmit="return confirm('Delete this expense entry?');">
                                        <input type="hidden" name="id" value="<?= $exp['id'] ?>">
                                        <button type="submit" class="text-rose-500 hover:text-rose-700">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </form>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Expense Modal -->
<div id="addExpenseModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl shadow-2xl border border-slate-100 max-w-lg w-full overflow-hidden animate-fade">
        <div class="bg-gradient-to-r from-rose-700 to-rose-600 text-white p-5 flex items-center justify-between">
            <h3 class="font-bold text-base">Record Operating Expense</h3>
            <button onclick="document.getElementById('addExpenseModal').classList.add('hidden')" class="text-white/80 hover:text-white p-1 rounded-lg">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form action="<?= BASE_URL ?>/index.php?page=expense_store" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Expense Title / Description <span class="text-rose-500">*</span></label>
                <input type="text" name="title" required placeholder="e.g. Electric Bill, Autoclave Distilled Water, Reception Flowers" 
                       class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Amount (<?= APP_CURRENCY ?>) <span class="text-rose-500">*</span></label>
                    <input type="number" step="0.01" min="0.01" name="amount" required placeholder="0.00" 
                           class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-rose-600">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Category <span class="text-rose-500">*</span></label>
                    <select name="category" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                        <?php foreach (EXPENSE_CATEGORIES as $k => $v): ?>
                            <option value="<?= $k ?>"><?= htmlspecialchars($v) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Payment Method</label>
                    <select name="payment_method" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                        <option value="Cash">Cash</option>
                        <option value="Online">Online / Bank Transfer</option>
                        <option value="Card">Card</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Date Incurred</label>
                    <input type="date" name="expense_date" value="<?= date('Y-m-d') ?>" required 
                           class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Receipt Attachment (Photo / PDF)</label>
                <input type="file" name="receipt_attachment" accept=".jpg,.jpeg,.png,.pdf,.webp" 
                       class="w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-slate-200 file:text-slate-700 hover:file:bg-slate-300">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Notes</label>
                <input type="text" name="notes" placeholder="Optional notes..." 
                       class="w-full p-2 bg-slate-50 border border-slate-200 rounded-lg text-xs">
            </div>

            <div class="flex justify-end space-x-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('addExpenseModal').classList.add('hidden')" class="px-4 py-2 bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold rounded-lg shadow-sm">
                    Record Expense
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

