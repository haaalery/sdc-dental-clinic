<?php
$pageTitle = 'Treatments & Commission Rates Catalog';
require_once __DIR__ . '/../layouts/header.php';
$isAdmin = Session::hasRole(['admin']);
?>

<!-- Header -->
<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Clinical Treatments & Commission Split Catalog</h2>
        <p class="text-xs text-slate-500 mt-1">Configure standard treatment fees and fixed commission rates per treatment (20%, 30%, 40%, 50%).</p>
    </div>
    <?php if ($isAdmin): ?>
        <button onclick="document.getElementById('addTreatmentModal').classList.remove('hidden')" 
                class="inline-flex items-center space-x-1.5 bg-teal-600 hover:bg-teal-700 text-white text-xs font-semibold px-4 py-2.5 rounded-xl shadow-sm transition">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Add New Treatment</span>
        </button>
    <?php endif; ?>
</div>

<!-- Treatments Table -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-xs">
            <thead>
                <tr class="bg-slate-50 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-200">
                    <th class="py-3 px-4">Code</th>
                    <th class="py-3 px-4">Treatment Name</th>
                    <th class="py-3 px-4">Category</th>
                    <th class="py-3 px-4 text-right">Standard Fee</th>
                    <th class="py-3 px-4 text-center">Dentist Split</th>
                    <th class="py-3 px-4 text-center">Clinic Split</th>
                    <th class="py-3 px-4 text-center">Status</th>
                    <?php if ($isAdmin): ?>
                        <th class="py-3 px-4 text-center">Action</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-slate-700">
                <?php foreach ($treatments as $t): 
                    $dentistSplit = (int)$t['fixed_commission_rate'];
                    $clinicSplit = 100 - $dentistSplit;
                ?>
                    <tr class="hover:bg-slate-50 transition">
                        <td class="py-3 px-4 font-mono font-bold text-teal-700"><?= htmlspecialchars($t['treatment_code']) ?></td>
                        <td class="py-3 px-4">
                            <span class="font-bold text-slate-900 block"><?= htmlspecialchars($t['treatment_name']) ?></span>
                            <?php if (!empty($t['description'])): ?>
                                <span class="text-[11px] text-slate-400 block"><?= htmlspecialchars($t['description']) ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 font-medium"><?= htmlspecialchars($t['category']) ?></span>
                        </td>
                        <td class="py-3 px-4 text-right font-mono font-bold text-slate-900">
                            <?= APP_CURRENCY ?><?= number_format($t['standard_price'], 2) ?>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <span class="font-bold text-teal-600 bg-teal-50 px-2 py-0.5 rounded-full border border-teal-200">
                                <?= $dentistSplit ?>%
                            </span>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <span class="font-bold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-full">
                                <?= $clinicSplit ?>%
                            </span>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <span class="inline-block w-2 h-2 rounded-full <?= $t['is_active'] ? 'bg-emerald-500' : 'bg-slate-300' ?>"></span>
                        </td>
                        <?php if ($isAdmin): ?>
                            <td class="py-3 px-4 text-center">
                                <button onclick="editTreatment(<?= htmlspecialchars(json_encode($t)) ?>)" 
                                        class="px-2.5 py-1 bg-slate-100 hover:bg-teal-50 hover:text-teal-700 rounded text-slate-600 font-semibold border border-slate-200 transition">
                                    Edit
                                </button>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add / Edit Treatment Modal -->
<?php if ($isAdmin): ?>
<div id="addTreatmentModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl shadow-2xl border border-slate-100 max-w-lg w-full overflow-hidden animate-fade">
        <div class="bg-gradient-to-r from-teal-700 to-cyan-700 text-white p-5 flex items-center justify-between">
            <h3 id="trtModalTitle" class="font-bold text-base">Add New Treatment</h3>
            <button onclick="document.getElementById('addTreatmentModal').classList.add('hidden')" class="text-white/80 hover:text-white p-1 rounded-lg">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form id="trtForm" action="<?= BASE_URL ?>/index.php?page=treatment_store" method="POST" class="p-6 space-y-4">
            <input type="hidden" name="id" id="trtId">

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Code <span class="text-rose-500">*</span></label>
                    <input type="text" name="treatment_code" id="trtCode" required placeholder="e.g. TRT-010" 
                           class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Category</label>
                    <input type="text" name="category" id="trtCategory" placeholder="e.g. Orthodontics, Restorative" 
                           class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Treatment Name <span class="text-rose-500">*</span></label>
                <input type="text" name="treatment_name" id="trtName" required placeholder="e.g. Ceramic Crown Placement" 
                       class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Standard Fee (<?= APP_CURRENCY ?>) <span class="text-rose-500">*</span></label>
                    <input type="number" step="0.01" name="standard_price" id="trtPrice" required placeholder="0.00" 
                           class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold">
                </div>
                <div>
                    <label class="block text-xs font-bold text-teal-800 uppercase mb-1">Dentist Commission Rate</label>
                    <select name="fixed_commission_rate" id="trtRate" class="w-full p-2.5 bg-teal-50 border border-teal-200 rounded-xl text-xs font-bold text-teal-900">
                        <option value="20.00">20% Dentist (80% Clinic)</option>
                        <option value="30.00" selected>30% Dentist (70% Clinic)</option>
                        <option value="40.00">40% Dentist (60% Clinic)</option>
                        <option value="50.00">50% Dentist (50% Clinic)</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Description</label>
                <textarea name="description" id="trtDesc" rows="2" placeholder="Clinical notes or procedure explanation..."
                          class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs"></textarea>
            </div>

            <div class="flex items-center space-x-2 pt-1">
                <input type="checkbox" name="is_active" id="trtActive" value="1" checked class="rounded text-teal-600">
                <label for="trtActive" class="text-xs text-slate-700">Active and available for treatment recording</label>
            </div>

            <div class="flex justify-end space-x-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('addTreatmentModal').classList.add('hidden')" class="px-4 py-2 bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2 bg-teal-600 hover:bg-teal-700 text-white text-xs font-semibold rounded-lg shadow-sm">
                    Save Treatment
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function editTreatment(t) {
    document.getElementById('trtModalTitle').innerText = 'Edit Treatment';
    document.getElementById('trtForm').action = '<?= BASE_URL ?>/index.php?page=treatment_update';
    document.getElementById('trtId').value = t.id;
    document.getElementById('trtCode').value = t.treatment_code;
    document.getElementById('trtName').value = t.treatment_name;
    document.getElementById('trtCategory').value = t.category;
    document.getElementById('trtPrice').value = t.standard_price;
    document.getElementById('trtRate').value = parseFloat(t.fixed_commission_rate).toFixed(2);
    document.getElementById('trtDesc').value = t.description || '';
    document.getElementById('trtActive').checked = t.is_active == 1;

    document.getElementById('addTreatmentModal').classList.remove('hidden');
}
</script>
<?php endif; ?>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

