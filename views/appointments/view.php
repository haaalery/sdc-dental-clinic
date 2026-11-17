<?php
$pageTitle = 'Appointment: ' . htmlspecialchars($appointment['appointment_code']);
require_once __DIR__ . '/../layouts/header.php';
$userRole = Session::role();
$patientName = $appointment['patient_id'] 
    ? ($appointment['first_name'] . ' ' . $appointment['last_name']) 
    : ($appointment['guest_name'] . ' (Online Guest)');
?>

<!-- Header -->
<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <div class="flex items-center space-x-3">
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight"><?= htmlspecialchars($appointment['appointment_code']) ?></h2>
            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-teal-100 text-teal-800 border border-teal-200">
                <?= str_replace('_', ' ', $appointment['status']) ?>
            </span>
        </div>
        <p class="text-xs text-slate-500 mt-1">
            <?= htmlspecialchars($appointment['branch_name'] ?? 'Main Clinic') ?> • <?= date('l, F j, Y', strtotime($appointment['appointment_date'])) ?> at <?= date('h:i A', strtotime($appointment['appointment_time'])) ?>
        </p>
    </div>

    <!-- Quick Status Action Buttons -->
    <div class="flex items-center space-x-2">
        <?php if ($appointment['status'] === STATUS_PENDING): ?>
            <a href="<?= BASE_URL ?>/index.php?page=appointment_status&id=<?= $appointment['id'] ?>&status=<?= STATUS_CONFIRMED ?>" 
               class="px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl shadow-xs transition">
                Confirm Appointment
            </a>
        <?php endif; ?>

        <?php if ($appointment['status'] === STATUS_CONFIRMED): ?>
            <a href="<?= BASE_URL ?>/index.php?page=appointment_status&id=<?= $appointment['id'] ?>&status=<?= STATUS_IN_PROGRESS ?>" 
               class="px-3.5 py-2 bg-purple-600 hover:bg-purple-700 text-white text-xs font-semibold rounded-xl shadow-xs transition">
                Mark In-Progress
            </a>
        <?php endif; ?>

        <?php if ($appointment['status'] === STATUS_IN_PROGRESS): ?>
            <a href="<?= BASE_URL ?>/index.php?page=appointment_status&id=<?= $appointment['id'] ?>&status=<?= STATUS_COMPLETED ?>" 
               class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-xl shadow-xs transition">
                Complete Visit
            </a>
        <?php endif; ?>

        <a href="<?= BASE_URL ?>/index.php?page=appointments" class="px-3.5 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-semibold rounded-xl transition">
            Back
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Left Column: Patient & Dentist Info -->
    <div class="space-y-6">
        <!-- Patient Profile Card -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3 flex items-center space-x-2">
                <i data-lucide="user" class="w-4 h-4 text-teal-600"></i>
                <span>Patient Profile</span>
            </h3>

            <div>
                <h4 class="text-base font-bold text-slate-900"><?= htmlspecialchars($patientName) ?></h4>
                <p class="text-xs text-slate-500 mt-0.5">Contact: <?= htmlspecialchars($appointment['patient_phone'] ?: $appointment['guest_phone']) ?></p>
                <?php if (!empty($appointment['occupation'])): ?>
                    <p class="text-xs text-slate-600 mt-1"><strong class="text-slate-700">Occupation:</strong> <?= htmlspecialchars($appointment['occupation']) ?></p>
                <?php endif; ?>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-100">
                <span class="text-xs font-bold text-slate-600 block mb-1">Chief Dental Concern:</span>
                <p class="text-xs text-slate-700 bg-slate-50 p-2.5 rounded-xl border border-slate-200/60">
                    <?= nl2br(htmlspecialchars($appointment['dental_concern'])) ?>
                </p>
            </div>

            <?php if ($appointment['patient_id']): ?>
                <div class="mt-4">
                    <a href="<?= BASE_URL ?>/index.php?page=patient_view&id=<?= $appointment['patient_id'] ?>" 
                       class="text-xs text-teal-600 hover:text-teal-700 font-semibold inline-flex items-center space-x-1">
                        <span>View Full Clinical Record & X-Rays</span>
                        <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                    </a>
                </div>
            <?php else: ?>
                <div class="mt-4 p-3 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-800">
                    Online Guest Booking. 
                    <a href="<?= BASE_URL ?>/index.php?page=patient_create&name=<?= urlencode($appointment['guest_name']) ?>&phone=<?= urlencode($appointment['guest_phone']) ?>&link_appointment_id=<?= $appointment['id'] ?>" 
                       class="font-bold underline block mt-1">Register as Permanent Patient &rarr;</a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Dentist Assignment & Reassignment Card (Explicit Interview Requirement) -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3 flex items-center space-x-2">
                <i data-lucide="stethoscope" class="w-4 h-4 text-teal-600"></i>
                <span>Dentist Assignment</span>
            </h3>

            <div class="space-y-3 text-xs">
                <div class="p-3 rounded-xl bg-slate-50 border border-slate-200">
                    <span class="text-[11px] text-slate-500 font-bold uppercase block">Preferred Dentist</span>
                    <span class="font-semibold text-slate-800 text-sm">
                        <?= !empty($appointment['preferred_dentist_name']) ? 'Dr. ' . htmlspecialchars($appointment['preferred_dentist_name']) : 'No specific preference' ?>
                    </span>
                </div>

                <div class="p-3 rounded-xl bg-teal-50 border border-teal-200">
                    <span class="text-[11px] text-teal-700 font-bold uppercase block">Attending Dentist</span>
                    <span class="font-bold text-teal-900 text-sm">
                        Dr. <?= htmlspecialchars($appointment['assigned_dentist_name'] ?? 'Not Yet Assigned') ?>
                    </span>
                    <?php if (!empty($appointment['assigned_license_no'])): ?>
                        <span class="text-[10px] text-teal-600 block mt-0.5">License: <?= htmlspecialchars($appointment['assigned_license_no']) ?></span>
                    <?php endif; ?>
                </div>

                <?php if (!empty($appointment['reassignment_reason'])): ?>
                    <div class="p-3 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-xs">
                        <strong class="font-semibold block mb-0.5 flex items-center space-x-1">
                            <i data-lucide="alert-triangle" class="w-3.5 h-3.5 text-amber-600"></i>
                            <span>Reassignment Note:</span>
                        </strong>
                        <p><?= htmlspecialchars($appointment['reassignment_reason']) ?></p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Reassign Form Toggle -->
            <?php if (in_array($userRole, ['admin', 'front_desk'])): ?>
                <div class="mt-4 pt-3 border-t border-slate-100">
                    <button onclick="document.getElementById('reassignSection').classList.toggle('hidden')" 
                            class="w-full py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition flex items-center justify-center space-x-1.5">
                        <i data-lucide="user-check" class="w-3.5 h-3.5"></i>
                        <span>Reassign Dentist</span>
                    </button>

                    <div id="reassignSection" class="hidden mt-3 p-4 bg-slate-50 border border-slate-200 rounded-xl">
                        <form action="<?= BASE_URL ?>/index.php?page=appointment_reassign" method="POST" class="space-y-3">
                            <input type="hidden" name="appointment_id" value="<?= $appointment['id'] ?>">

                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">New Attending Dentist</label>
                                <select name="new_dentist_id" required class="w-full p-2 bg-white border border-slate-200 rounded-lg text-xs">
                                    <?php foreach ($dentists as $d): ?>
                                        <option value="<?= $d['dentist_id'] ?>" <?= ($appointment['assigned_dentist_id'] == $d['dentist_id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($d['full_name']) ?> (<?= htmlspecialchars($d['specialization']) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Reason for Reassignment</label>
                                <textarea name="reassignment_reason" rows="2" required placeholder="e.g. Preferred dentist on emergency leave or schedule conflict; confirmed with patient."
                                          class="w-full p-2 bg-white border border-slate-200 rounded-lg text-xs"></textarea>
                            </div>

                            <button type="submit" class="w-full py-2 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-lg text-xs transition">
                                Confirm Reassignment
                            </button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Billing & Balance Summary Card -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3 flex items-center space-x-2">
                <i data-lucide="credit-card" class="w-4 h-4 text-teal-600"></i>
                <span>Billing & Balance Due</span>
            </h3>

            <?php if ($invoice): ?>
                <div class="space-y-2 text-xs">
                    <div class="flex justify-between text-slate-500">
                        <span>Invoice Number:</span>
                        <span class="font-mono font-bold text-slate-800"><?= htmlspecialchars($invoice['invoice_number']) ?></span>
                    </div>
                    <div class="flex justify-between text-slate-500">
                        <span>Total Procedure Bill:</span>
                        <span class="font-bold text-slate-800"><?= APP_CURRENCY ?><?= number_format($invoice['total_amount'], 2) ?></span>
                    </div>
                    <div class="flex justify-between text-slate-500">
                        <span>Paid to Date:</span>
                        <span class="font-bold text-emerald-600"><?= APP_CURRENCY ?><?= number_format($invoice['paid_amount'], 2) ?></span>
                    </div>
                    <div class="flex justify-between pt-2 border-t border-slate-100 text-sm">
                        <span class="font-bold text-slate-700">Remaining Balance:</span>
                        <span class="font-extrabold <?= (float)$invoice['balance_amount'] > 0 ? 'text-rose-600' : 'text-emerald-600' ?>">
                            <?= APP_CURRENCY ?><?= number_format($invoice['balance_amount'], 2) ?>
                        </span>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100 space-y-2">
                    <a href="<?= BASE_URL ?>/index.php?page=invoice_view&id=<?= $invoice['id'] ?>" 
                       class="block w-full py-2 text-center bg-teal-600 hover:bg-teal-700 text-white text-xs font-semibold rounded-xl shadow-xs transition">
                        Manage Payments & Receipts
                    </a>
                </div>
            <?php else: ?>
                <div class="text-center py-4 text-xs text-slate-400">
                    No treatments billed yet. Invoice will generate once clinical treatments are logged below.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Right 2 Columns: Clinical Treatments Performed & Dental Supplies Consumed -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Treatments Performed & Commission Calculation Section -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Clinical Treatments Performed</h3>
                    <p class="text-xs text-slate-500">Procedures recorded by dentist with transparent commission splits.</p>
                </div>
                <button onclick="document.getElementById('addTreatmentForm').classList.toggle('hidden')" 
                        class="px-3 py-1.5 bg-teal-600 hover:bg-teal-700 text-white text-xs font-semibold rounded-lg flex items-center space-x-1 transition">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Add Performed Treatment</span>
                </button>
            </div>

            <!-- Form to Record Performed Treatment -->
            <div id="addTreatmentForm" class="hidden mb-6 p-4 bg-slate-50 border border-slate-200 rounded-xl">
                <form action="<?= BASE_URL ?>/index.php?page=treatment_record_performed" method="POST" class="space-y-4">
                    <input type="hidden" name="appointment_id" value="<?= $appointment['id'] ?>">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Select Treatment / Procedure</label>
                            <select name="treatment_id" id="selTreatment" onchange="updatePriceFromTreatment(this)" required class="w-full p-2 bg-white border border-slate-200 rounded-lg text-xs">
                                <option value="">-- Choose Procedure --</option>
                                <?php foreach ($activeTreatments as $trt): ?>
                                    <option value="<?= $trt['id'] ?>" data-price="<?= $trt['standard_price'] ?>" data-rate="<?= $trt['fixed_commission_rate'] ?>">
                                        <?= htmlspecialchars($trt['treatment_name']) ?> (<?= APP_CURRENCY ?><?= number_format($trt['standard_price'], 2) ?> | Split: <?= (int)$trt['fixed_commission_rate'] ?>%)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Attending Dentist (Receives Commission)</label>
                            <select name="dentist_id" required class="w-full p-2 bg-white border border-slate-200 rounded-lg text-xs">
                                <?php foreach ($dentists as $d): ?>
                                    <option value="<?= $d['dentist_id'] ?>" <?= ($appointment['assigned_dentist_id'] == $d['dentist_id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($d['full_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Actual Charged Fee (<?= APP_CURRENCY ?>)</label>
                            <input type="number" step="0.01" name="actual_price" id="inpActualPrice" required 
                                   class="w-full p-2 bg-white border border-slate-200 rounded-lg text-xs font-semibold">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Commission Split %</label>
                            <select name="custom_commission_rate" id="selCommRate" class="w-full p-2 bg-white border border-slate-200 rounded-lg text-xs">
                                <option value="20">20% Dentist / 80% Clinic</option>
                                <option value="30" selected>30% Dentist / 70% Clinic</option>
                                <option value="40">40% Dentist / 60% Clinic</option>
                                <option value="50">50% Dentist / 50% Clinic</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Clinical Notes (Tooth #, surfaces, observations)</label>
                        <input type="text" name="notes" placeholder="e.g. Tooth #36 occlusal surface composite filling" 
                               class="w-full p-2 bg-white border border-slate-200 rounded-lg text-xs">
                    </div>

                    <div class="flex justify-end space-x-2">
                        <button type="button" onclick="document.getElementById('addTreatmentForm').classList.add('hidden')" class="px-3 py-1.5 bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-1.5 bg-teal-600 hover:bg-teal-700 text-white text-xs font-semibold rounded-lg">
                            Save Procedure & Compute Split
                        </button>
                    </div>
                </form>
            </div>

            <!-- Treatments Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 uppercase font-bold border-b border-slate-200">
                            <th class="py-2.5 px-3">Procedure</th>
                            <th class="py-2.5 px-3">Dentist</th>
                            <th class="py-2.5 px-3 text-right">Fee</th>
                            <th class="py-2.5 px-3 text-center">Split</th>
                            <th class="py-2.5 px-3 text-right">Dentist Share</th>
                            <th class="py-2.5 px-3 text-center">Formula</th>
                            <th class="py-2.5 px-3 text-center">Remove</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php if (empty($performedTreatments)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-6 text-slate-400 italic">No treatments recorded yet for this appointment.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($performedTreatments as $pt): ?>
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="py-2.5 px-3 font-semibold text-slate-800">
                                        <?= htmlspecialchars($pt['treatment_name']) ?>
                                        <?php if (!empty($pt['notes'])): ?>
                                            <span class="block text-[10px] text-slate-400 font-normal"><?= htmlspecialchars($pt['notes']) ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-2.5 px-3 text-slate-600">Dr. <?= htmlspecialchars($pt['dentist_name']) ?></td>
                                    <td class="py-2.5 px-3 text-right font-mono font-bold text-slate-900"><?= APP_CURRENCY ?><?= number_format($pt['actual_price'], 2) ?></td>
                                    <td class="py-2.5 px-3 text-center font-bold text-teal-600"><?= (int)$pt['commission_rate'] ?>%</td>
                                    <td class="py-2.5 px-3 text-right font-mono font-bold text-teal-700"><?= APP_CURRENCY ?><?= number_format($pt['dentist_commission_amount'], 2) ?></td>
                                    <td class="py-2.5 px-3 text-center">
                                        <button onclick="openTreatmentFormulaModal('<?= addslashes(htmlspecialchars($pt['treatment_name'])) ?>', '<?= addslashes(htmlspecialchars($pt['dentist_name'])) ?>', <?= (float)$pt['actual_price'] ?>, <?= (float)$pt['commission_rate'] ?>, <?= (float)$pt['dentist_commission_amount'] ?>, <?= (float)$pt['clinic_share_amount'] ?>)" 
                                                class="px-2 py-1 bg-slate-100 hover:bg-teal-50 hover:text-teal-700 text-slate-600 text-[10px] font-semibold rounded border border-slate-200 transition">
                                            View Math
                                        </button>
                                    </td>
                                    <td class="py-2.5 px-3 text-center">
                                        <form action="<?= BASE_URL ?>/index.php?page=treatment_delete_performed" method="POST" onsubmit="return confirm('Remove this performed treatment?');">
                                            <input type="hidden" name="id" value="<?= $pt['id'] ?>">
                                            <input type="hidden" name="appointment_id" value="<?= $appointment['id'] ?>">
                                            <button type="submit" class="text-rose-500 hover:text-rose-700">
                                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Dental Supply Usage Tracking (Explicit Assistant Feature) -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Dental Supplies Consumed (Assistant Log)</h3>
                    <p class="text-xs text-slate-500">Track supplies utilized during treatment to deduct clinic inventory.</p>
                </div>
                <button onclick="document.getElementById('addSupplyUsageForm').classList.toggle('hidden')" 
                        class="px-3 py-1.5 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold rounded-lg flex items-center space-x-1 transition">
                    <i data-lucide="package-plus" class="w-3.5 h-3.5"></i>
                    <span>Log Supply Usage</span>
                </button>
            </div>

            <!-- Supply Usage Entry Form -->
            <div id="addSupplyUsageForm" class="hidden mb-6 p-4 bg-slate-50 border border-slate-200 rounded-xl">
                <form action="<?= BASE_URL ?>/index.php?page=inventory_record_usage" method="POST" class="space-y-3">
                    <input type="hidden" name="appointment_id" value="<?= $appointment['id'] ?>">
                    <input type="hidden" name="patient_id" value="<?= $appointment['patient_id'] ?>">

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 mb-1">Supply Item</label>
                            <select name="supply_id" required class="w-full p-2 bg-white border border-slate-200 rounded-lg text-xs">
                                <option value="">-- Choose Item From Stock --</option>
                                <?php foreach ($availableSupplies as $sup): ?>
                                    <option value="<?= $sup['id'] ?>">
                                        <?= htmlspecialchars($sup['item_name']) ?> (<?= $sup['current_stock'] ?> <?= $sup['unit_of_measure'] ?> available)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Quantity Used</label>
                            <input type="number" min="1" name="quantity_used" value="1" required 
                                   class="w-full p-2 bg-white border border-slate-200 rounded-lg text-xs">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Usage Notes / Details</label>
                        <input type="text" name="notes" placeholder="e.g. 2 carpules for local infiltration" 
                               class="w-full p-2 bg-white border border-slate-200 rounded-lg text-xs">
                    </div>

                    <div class="flex justify-end space-x-2">
                        <button type="button" onclick="document.getElementById('addSupplyUsageForm').classList.add('hidden')" class="px-3 py-1.5 bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-1.5 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold rounded-lg">
                            Log Consumption
                        </button>
                    </div>
                </form>
            </div>

            <!-- Supply Usage List -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 uppercase font-bold border-b border-slate-200">
                            <th class="py-2.5 px-3">Item Description</th>
                            <th class="py-2.5 px-3 text-center">Quantity</th>
                            <th class="py-2.5 px-3">Recorded By</th>
                            <th class="py-2.5 px-3">Notes</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php if (empty($suppliesUsed)): ?>
                            <tr>
                                <td colspan="4" class="text-center py-6 text-slate-400 italic">No supply usage recorded for this session yet.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($suppliesUsed as $su): ?>
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="py-2.5 px-3 font-semibold text-slate-800"><?= htmlspecialchars($su['item_name']) ?></td>
                                    <td class="py-2.5 px-3 text-center font-bold text-teal-700"><?= $su['quantity_used'] ?> <?= htmlspecialchars($su['unit_of_measure']) ?></td>
                                    <td class="py-2.5 px-3 text-slate-600"><?= htmlspecialchars($su['recorded_by_name']) ?></td>
                                    <td class="py-2.5 px-3 text-slate-400"><?= htmlspecialchars($su['notes'] ?: '-') ?></td>
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
function updatePriceFromTreatment(select) {
    const opt = select.options[select.selectedIndex];
    if (opt && opt.dataset.price) {
        document.getElementById('inpActualPrice').value = opt.dataset.price;
        if (opt.dataset.rate) {
            document.getElementById('selCommRate').value = parseInt(opt.dataset.rate);
        }
    }
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

