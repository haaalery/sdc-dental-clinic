<?php
$pageTitle = 'Dental Supplies & Inventory';
require_once __DIR__ . '/../layouts/header.php';
?>

<!-- Header -->
<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Dental Supplies & Inventory</h2>
        <p class="text-xs text-slate-500 mt-1">Manage consumables catalog, stock levels, and per-patient usage deductions.</p>
    </div>
    <button onclick="document.getElementById('addItemModal').classList.remove('hidden')" 
            class="inline-flex items-center space-x-1.5 bg-teal-600 hover:bg-teal-700 text-white text-xs font-semibold px-4 py-2.5 rounded-xl shadow-sm transition">
        <i data-lucide="plus" class="w-4 h-4"></i>
        <span>Add Supply Item</span>
    </button>
</div>

<!-- Low Stock Warning Alerts -->
<?php if (!empty($lowStock)): ?>
    <div class="mb-6 p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 shadow-xs">
        <div class="flex items-center space-x-2 font-bold text-xs uppercase tracking-wider mb-2 text-amber-800">
            <i data-lucide="alert-triangle" class="w-4 h-4 text-amber-600"></i>
            <span>Low Stock Reorder Alerts (<?= count($lowStock) ?> items require restocking)</span>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
            <?php foreach ($lowStock as $ls): ?>
                <div class="p-2.5 bg-white rounded-xl border border-amber-200 text-xs flex justify-between items-center">
                    <div>
                        <span class="font-bold text-slate-800 block"><?= htmlspecialchars($ls['item_name']) ?></span>
                        <span class="text-[10px] text-slate-400">Reorder Level: <?= $ls['reorder_level'] ?> <?= htmlspecialchars($ls['unit_of_measure']) ?></span>
                    </div>
                    <span class="px-2 py-1 rounded-md font-bold text-rose-700 bg-rose-50 border border-rose-200 font-mono">
                        <?= $ls['current_stock'] ?> <?= htmlspecialchars($ls['unit_of_measure']) ?>
                    </span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<!-- Inventory Table -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-xs">
            <thead>
                <tr class="bg-slate-50 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-200">
                    <th class="py-3 px-4">Item Code</th>
                    <th class="py-3 px-4">Item Description</th>
                    <th class="py-3 px-4">Category</th>
                    <th class="py-3 px-4">Unit of Measure</th>
                    <th class="py-3 px-4 text-center">Stock Level</th>
                    <th class="py-3 px-4 text-center">Reorder Point</th>
                    <th class="py-3 px-4 text-right">Unit Cost</th>
                    <th class="py-3 px-4 text-center">Restock</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-slate-700">
                <?php foreach ($supplies as $s): 
                    $isLow = ($s['current_stock'] <= $s['reorder_level']);
                ?>
                    <tr class="hover:bg-slate-50 transition">
                        <td class="py-3 px-4 font-mono font-bold text-teal-700"><?= htmlspecialchars($s['item_code']) ?></td>
                        <td class="py-3 px-4 font-bold text-slate-900"><?= htmlspecialchars($s['item_name']) ?></td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 font-medium"><?= htmlspecialchars($s['category']) ?></span>
                        </td>
                        <td class="py-3 px-4 text-slate-600"><?= htmlspecialchars($s['unit_of_measure']) ?></td>
                        <td class="py-3 px-4 text-center font-mono">
                            <span class="inline-block px-2.5 py-1 rounded-full font-bold text-xs <?= $isLow ? 'bg-rose-100 text-rose-800' : 'bg-emerald-100 text-emerald-800' ?>">
                                <?= $s['current_stock'] ?>
                            </span>
                        </td>
                        <td class="py-3 px-4 text-center font-mono text-slate-400"><?= $s['reorder_level'] ?></td>
                        <td class="py-3 px-4 text-right font-mono font-bold text-slate-900">
                            <?= APP_CURRENCY ?><?= number_format($s['unit_cost'], 2) ?>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <button onclick="promptStockAdjustment(<?= $s['id'] ?>, '<?= addslashes(htmlspecialchars($s['item_name'])) ?>')" 
                                    class="px-2.5 py-1 bg-slate-100 hover:bg-teal-50 hover:text-teal-700 font-semibold rounded border border-slate-200 transition">
                                Adjust / Restock
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Supply Modal -->
<div id="addItemModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl shadow-2xl border border-slate-100 max-w-lg w-full overflow-hidden animate-fade">
        <div class="bg-gradient-to-r from-teal-700 to-cyan-700 text-white p-5 flex items-center justify-between">
            <h3 class="font-bold text-base">Add Dental Supply to Catalog</h3>
            <button onclick="document.getElementById('addItemModal').classList.add('hidden')" class="text-white/80 hover:text-white p-1 rounded-lg">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form action="<?= BASE_URL ?>/index.php?page=inventory_store" method="POST" class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Item Code <span class="text-rose-500">*</span></label>
                    <input type="text" name="item_code" required placeholder="e.g. SUP-009" 
                           class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Category</label>
                    <input type="text" name="category" placeholder="e.g. Anesthesia, Restorative" 
                           class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Item Description <span class="text-rose-500">*</span></label>
                <input type="text" name="item_name" required placeholder="e.g. Articaine 4% Anesthesia Carpules" 
                       class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Unit of Measure</label>
                    <input type="text" name="unit_of_measure" required placeholder="e.g. carpule, box" 
                           class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Current Stock</label>
                    <input type="number" min="0" name="current_stock" value="0" required 
                           class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Reorder Point</label>
                    <input type="number" min="0" name="reorder_level" value="10" required 
                           class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                </div>
            </div>

            <div class="flex justify-end space-x-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('addItemModal').classList.add('hidden')" class="px-4 py-2 bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2 bg-teal-600 hover:bg-teal-700 text-white text-xs font-semibold rounded-lg shadow-sm">
                    Save Supply
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Stock Adjustment Form Modal -->
<div id="adjustStockModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl shadow-2xl border border-slate-100 max-w-sm w-full overflow-hidden animate-fade">
        <div class="bg-slate-800 text-white p-4 flex items-center justify-between">
            <h3 class="font-bold text-sm">Adjust Stock Level</h3>
            <button onclick="document.getElementById('adjustStockModal').classList.add('hidden')" class="text-white/80 hover:text-white p-1">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form action="<?= BASE_URL ?>/index.php?page=inventory_adjust" method="POST" class="p-5 space-y-3">
            <input type="hidden" name="supply_id" id="adjSupplyId">
            <div>
                <span class="text-xs text-slate-500 uppercase font-bold block mb-1">Supply Item</span>
                <p id="adjSupplyName" class="text-xs font-bold text-slate-800"></p>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Quantity to Add (or Subtract with -)</label>
                <input type="number" name="adjustment_qty" required placeholder="e.g. +50 or -5" 
                       class="w-full p-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-mono font-bold">
            </div>

            <div class="flex justify-end space-x-2 pt-2">
                <button type="button" onclick="document.getElementById('adjustStockModal').classList.add('hidden')" class="px-3 py-1.5 bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-1.5 bg-teal-600 hover:bg-teal-700 text-white text-xs font-semibold rounded-lg">
                    Update Stock
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function promptStockAdjustment(id, name) {
    document.getElementById('adjSupplyId').value = id;
    document.getElementById('adjSupplyName').innerText = name;
    document.getElementById('adjustStockModal').classList.remove('hidden');
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

