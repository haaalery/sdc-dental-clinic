<?php
$pageTitle = 'Patient Record: ' . htmlspecialchars($patient['first_name'] . ' ' . $patient['last_name']);
require_once __DIR__ . '/../layouts/header.php';
?>

<!-- Header -->
<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div class="flex items-center space-x-4">
        <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-teal-600 to-cyan-500 text-white font-bold text-xl flex items-center justify-center shadow-md shadow-teal-500/20">
            <?= strtoupper(substr($patient['first_name'], 0, 1) . substr($patient['last_name'], 0, 1)) ?>
        </div>
        <div>
            <div class="flex items-center space-x-3">
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight"><?= htmlspecialchars($patient['first_name'] . ' ' . $patient['last_name']) ?></h2>
                <span class="font-mono text-xs font-bold px-2 py-0.5 rounded bg-slate-200 text-slate-700"><?= htmlspecialchars($patient['patient_code']) ?></span>
            </div>
            <p class="text-xs text-slate-500 mt-0.5">
                <?= ucfirst($patient['gender']) ?> • <?= $patient['date_of_birth'] ? (date('Y') - date('Y', strtotime($patient['date_of_birth']))) . ' years old (' . date('M d, Y', strtotime($patient['date_of_birth'])) . ')' : 'DOB not provided' ?>
                • <strong class="text-teal-700 font-semibold">Occupation: <?= htmlspecialchars($patient['occupation'] ?: 'Not Specified') ?></strong>
            </p>
        </div>
    </div>

    <div class="flex items-center space-x-3">
        <a href="<?= BASE_URL ?>/index.php?page=appointment_create&patient_id=<?= $patient['id'] ?>" 
           class="px-4 py-2.5 bg-teal-600 hover:bg-teal-700 text-white text-xs font-semibold rounded-xl shadow-xs transition flex items-center space-x-1.5">
            <i data-lucide="calendar-plus" class="w-4 h-4"></i>
            <span>Schedule Appointment</span>
        </a>
        <a href="<?= BASE_URL ?>/index.php?page=patients" class="px-4 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-semibold rounded-xl transition">
            Back
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Left Column: Demographics & Medical Warnings -->
    <div class="space-y-6">
        <!-- Demographics & Contact Card -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4 flex items-center space-x-2">
                <i data-lucide="contact" class="w-4 h-4 text-teal-600"></i>
                <span>Contact & Demographics</span>
            </h3>

            <div class="space-y-3 text-xs">
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span class="text-slate-500">Occupation:</span>
                    <span class="font-bold text-slate-900"><?= htmlspecialchars($patient['occupation'] ?: 'None Specified') ?></span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span class="text-slate-500">Phone:</span>
                    <span class="font-mono font-semibold text-slate-800"><?= htmlspecialchars($patient['phone']) ?></span>
                </div>
                <div class="flex justify-between border-b border-slate-100 pb-2">
                    <span class="text-slate-500">Email:</span>
                    <span class="font-medium text-slate-800"><?= htmlspecialchars($patient['email'] ?: 'No email') ?></span>
                </div>
                <div class="pt-1">
                    <span class="text-slate-500 block mb-1">Residential Address:</span>
                    <p class="text-slate-700 bg-slate-50 p-2 rounded-lg border border-slate-200/60"><?= htmlspecialchars($patient['address'] ?: 'Not recorded') ?></p>
                </div>
            </div>
        </div>

        <!-- Medical Alerts & Allergies -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5">
            <h3 class="text-xs font-bold uppercase tracking-wider text-rose-500 mb-4 flex items-center space-x-2">
                <i data-lucide="shield-alert" class="w-4 h-4 text-rose-600"></i>
                <span>Clinical & Medical Alerts</span>
            </h3>

            <div class="space-y-3 text-xs">
                <div class="p-3 rounded-xl bg-rose-50 border border-rose-200">
                    <span class="font-bold text-rose-900 uppercase text-[10px] block mb-1">Known Drug / Latex Allergies:</span>
                    <p class="text-rose-800 font-medium"><?= htmlspecialchars($patient['allergies'] ?: 'No known drug allergies reported.') ?></p>
                </div>

                <div class="p-3 rounded-xl bg-amber-50 border border-amber-200">
                    <span class="font-bold text-amber-900 uppercase text-[10px] block mb-1">Systemic Medical History:</span>
                    <p class="text-amber-800"><?= htmlspecialchars($patient['medical_history'] ?: 'No systemic illnesses reported.') ?></p>
                </div>
            </div>
        </div>

        <!-- Patient Files & X-rays (Explicit Interview Requirement) -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 flex items-center space-x-2">
                    <i data-lucide="file-text" class="w-4 h-4 text-teal-600"></i>
                    <span>X-Rays & Dental Documents</span>
                </h3>
            </div>

            <!-- Upload File Form -->
            <form action="<?= BASE_URL ?>/index.php?page=patient_upload_file" method="POST" enctype="multipart/form-data" class="mb-4 p-3 bg-slate-50 rounded-xl border border-slate-200 space-y-2.5">
                <input type="hidden" name="patient_id" value="<?= $patient['id'] ?>">

                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase">Document Title / View</label>
                    <input type="text" name="file_title" required placeholder="e.g. Panoramic X-Ray, Periapical #46" 
                           class="w-full p-2 bg-white border border-slate-200 rounded-lg text-xs">
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase">Choose File (X-ray JPG, PNG, PDF)</label>
                    <input type="file" name="patient_file" required accept=".jpg,.jpeg,.png,.pdf,.webp,.dcm" 
                           class="w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-teal-600 file:text-white hover:file:bg-teal-700">
                </div>

                <button type="submit" class="w-full py-2 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-lg text-xs transition">
                    Upload Attachment
                </button>
            </form>

            <!-- Uploaded Files List -->
            <div class="space-y-2 max-h-48 overflow-y-auto text-xs">
                <?php if (empty($files)): ?>
                    <p class="text-slate-400 text-center py-3 italic">No X-rays or files attached yet.</p>
                <?php else: ?>
                    <?php foreach ($files as $f): ?>
                        <div class="p-2.5 bg-white border border-slate-200 rounded-xl flex items-center justify-between hover:bg-slate-50 transition">
                            <div class="min-w-0 pr-2">
                                <a href="<?= BASE_URL ?>/<?= htmlspecialchars($f['file_path']) ?>" target="_blank" class="font-semibold text-slate-800 hover:text-teal-600 truncate block">
                                    <?= htmlspecialchars($f['file_title']) ?>
                                </a>
                                <span class="text-[10px] text-slate-400">
                                    <?= date('M d, Y', strtotime($f['uploaded_at'])) ?> • <?= round($f['file_size_bytes'] / 1024, 1) ?> KB
                                </span>
                            </div>
                            <div class="flex items-center space-x-1 flex-shrink-0">
                                <a href="<?= BASE_URL ?>/<?= htmlspecialchars($f['file_path']) ?>" target="_blank" title="Preview / Open" class="p-1 text-slate-500 hover:text-teal-600">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                                <form action="<?= BASE_URL ?>/index.php?page=patient_delete_file" method="POST" onsubmit="return confirm('Delete this file?');" class="inline">
                                    <input type="hidden" name="file_id" value="<?= $f['id'] ?>">
                                    <input type="hidden" name="patient_id" value="<?= $patient['id'] ?>">
                                    <button type="submit" title="Remove" class="p-1 text-rose-500 hover:text-rose-700">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Right 2 Columns: Full Treatment History with Attending Dentist & Concern -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Treatment History Table (Explicit Interview Requirement) -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Clinical Treatment History</h3>
                    <p class="text-xs text-slate-500">Comprehensive ledger of all procedures, attending dentists, and dental concerns.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 uppercase font-bold border-b border-slate-200">
                            <th class="py-3 px-3">Date</th>
                            <th class="py-3 px-3">Dental Concern / Chief Complaint</th>
                            <th class="py-3 px-3">Treatment Performed</th>
                            <th class="py-3 px-3">Attending Dentist</th>
                            <th class="py-3 px-3 text-right">Fee</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php if (empty($treatmentHistory)): ?>
                            <tr>
                                <td colspan="5" class="text-center py-8 text-slate-400 italic">No treatment history recorded for this patient.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($treatmentHistory as $th): ?>
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="py-3 px-3 font-mono font-medium text-slate-600">
                                        <?= date('M d, Y', strtotime($th['appointment_date'])) ?>
                                    </td>
                                    <td class="py-3 px-3 max-w-xs text-slate-700 font-medium">
                                        <?= htmlspecialchars($th['dental_concern']) ?>
                                    </td>
                                    <td class="py-3 px-3">
                                        <span class="font-bold text-slate-900 block"><?= htmlspecialchars($th['treatment_name']) ?></span>
                                        <?php if (!empty($th['notes'])): ?>
                                            <span class="text-[11px] text-slate-500 block"><?= htmlspecialchars($th['notes']) ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-3 px-3 text-teal-800 font-semibold">
                                        Dr. <?= htmlspecialchars($th['dentist_name']) ?>
                                    </td>
                                    <td class="py-3 px-3 text-right font-mono font-bold text-slate-900">
                                        <?= APP_CURRENCY ?><?= number_format($th['actual_price'], 2) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Invoices & Ongoing Treatment Balances -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Invoices & Treatment Balances</h3>
                    <p class="text-xs text-slate-500">Installments and outstanding balances for ongoing dental treatments.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 uppercase font-bold border-b border-slate-200">
                            <th class="py-2.5 px-3">Invoice #</th>
                            <th class="py-2.5 px-3">Date</th>
                            <th class="py-2.5 px-3 text-right">Total Bill</th>
                            <th class="py-2.5 px-3 text-right">Paid</th>
                            <th class="py-2.5 px-3 text-right">Remaining Balance</th>
                            <th class="py-2.5 px-3 text-center">Status</th>
                            <th class="py-2.5 px-3 text-center">Receipt</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php if (empty($invoices)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-6 text-slate-400 italic">No invoices issued.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($invoices as $inv): ?>
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="py-2.5 px-3 font-mono font-bold text-teal-700"><?= htmlspecialchars($inv['invoice_number']) ?></td>
                                    <td class="py-2.5 px-3"><?= date('M d, Y', strtotime($inv['created_at'])) ?></td>
                                    <td class="py-2.5 px-3 text-right font-mono font-bold"><?= APP_CURRENCY ?><?= number_format($inv['total_amount'], 2) ?></td>
                                    <td class="py-2.5 px-3 text-right font-mono text-emerald-600 font-bold"><?= APP_CURRENCY ?><?= number_format($inv['paid_amount'], 2) ?></td>
                                    <td class="py-2.5 px-3 text-right font-mono font-bold <?= (float)$inv['balance_amount'] > 0 ? 'text-rose-600' : 'text-slate-400' ?>">
                                        <?= APP_CURRENCY ?><?= number_format($inv['balance_amount'], 2) ?>
                                    </td>
                                    <td class="py-2.5 px-3 text-center">
                                        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold uppercase <?= $inv['payment_status'] === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' ?>">
                                            <?= $inv['payment_status'] ?>
                                        </span>
                                    </td>
                                    <td class="py-2.5 px-3 text-center">
                                        <a href="<?= BASE_URL ?>/index.php?page=invoice_view&id=<?= $inv['id'] ?>" class="text-teal-600 hover:text-teal-800 font-semibold underline">
                                            View / Pay
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

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

