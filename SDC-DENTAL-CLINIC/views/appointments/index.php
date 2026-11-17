<?php
$pageTitle = 'Appointments Management';
require_once __DIR__ . '/../layouts/header.php';

$filterStatus = $_GET['status'] ?? '';
$filterDate = $_GET['date'] ?? '';
?>

<!-- Header -->
<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Appointments & Scheduling</h2>
        <p class="text-xs text-slate-500 mt-1">Manage patient bookings, dentist assignments, and online portal triage.</p>
    </div>
    <div class="flex items-center space-x-3">
        <a href="<?= BASE_URL ?>/index.php?page=appointment_create" 
           class="inline-flex items-center space-x-1.5 bg-teal-600 hover:bg-teal-700 text-white text-xs font-semibold px-4 py-2.5 rounded-xl shadow-sm transition">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Schedule Appointment</span>
        </a>
    </div>
</div>

<!-- Filters Bar -->
<div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs mb-6">
    <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-xs">
        <input type="hidden" name="page" value="appointments">

        <div>
            <label class="block font-semibold text-slate-600 mb-1">Date</label>
            <input type="date" name="date" value="<?= htmlspecialchars($filterDate) ?>" 
                   class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-teal-500">
        </div>

        <div>
            <label class="block font-semibold text-slate-600 mb-1">Status</label>
            <select name="status" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-teal-500">
                <option value="">All Statuses</option>
                <option value="pending_confirmation" <?= $filterStatus === 'pending_confirmation' ? 'selected' : '' ?>>Pending Confirmation (Online)</option>
                <option value="confirmed" <?= $filterStatus === 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                <option value="in_progress" <?= $filterStatus === 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                <option value="completed" <?= $filterStatus === 'completed' ? 'selected' : '' ?>>Completed</option>
                <option value="cancelled" <?= $filterStatus === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
            </select>
        </div>

        <div>
            <label class="block font-semibold text-slate-600 mb-1">Dentist</label>
            <select name="dentist_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-teal-500">
                <option value="">All Dentists</option>
                <?php foreach ($dentists as $d): ?>
                    <option value="<?= $d['dentist_id'] ?>" <?= (isset($_GET['dentist_id']) && $_GET['dentist_id'] == $d['dentist_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($d['full_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="flex items-end space-x-2">
            <button type="submit" class="w-full py-2 bg-slate-800 hover:bg-slate-900 text-white font-semibold rounded-lg transition">
                Filter
            </button>
            <a href="<?= BASE_URL ?>/index.php?page=appointments" class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg transition">
                Reset
            </a>
        </div>
    </form>
</div>

<!-- Appointments Table -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-xs">
            <thead>
                <tr class="bg-slate-50 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-200">
                    <th class="py-3 px-4">Code / Time</th>
                    <th class="py-3 px-4">Patient Name</th>
                    <th class="py-3 px-4">Dental Concern</th>
                    <th class="py-3 px-4">Dentist</th>
                    <th class="py-3 px-4">Source</th>
                    <th class="py-3 px-4">Status</th>
                    <th class="py-3 px-4 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-slate-700">
                <?php if (empty($appointments)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-8 text-slate-400 italic">No appointments match the selected filters.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($appointments as $apt): 
                        $statusColors = [
                            'pending_confirmation' => 'bg-amber-100 text-amber-800 border-amber-200',
                            'confirmed'            => 'bg-blue-100 text-blue-800 border-blue-200',
                            'in_progress'          => 'bg-purple-100 text-purple-800 border-purple-200',
                            'completed'            => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                            'cancelled'            => 'bg-rose-100 text-rose-800 border-rose-200',
                            'no_show'              => 'bg-slate-100 text-slate-800 border-slate-200',
                        ];
                        $badge = $statusColors[$apt['status']] ?? 'bg-slate-100 text-slate-800';
                        $patientName = $apt['patient_id'] ? ($apt['first_name'] . ' ' . $apt['last_name']) : $apt['guest_name'];
                    ?>
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3 px-4">
                                <span class="font-mono font-bold text-teal-700 block"><?= htmlspecialchars($apt['appointment_code']) ?></span>
                                <span class="text-slate-500"><?= date('M d, Y', strtotime($apt['appointment_date'])) ?> • <?= date('h:i A', strtotime($apt['appointment_time'])) ?></span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="font-bold text-slate-900 block"><?= htmlspecialchars($patientName) ?></span>
                                <span class="text-[11px] text-slate-400"><?= htmlspecialchars($apt['patient_phone'] ?: $apt['guest_phone']) ?></span>
                            </td>
                            <td class="py-3 px-4 max-w-xs">
                                <p class="truncate" title="<?= htmlspecialchars($apt['dental_concern']) ?>">
                                    <?= htmlspecialchars($apt['dental_concern']) ?>
                                </p>
                            </td>
                            <td class="py-3 px-4">
                                <span class="font-medium text-slate-800 block">Dr. <?= htmlspecialchars($apt['assigned_dentist_name'] ?? 'Not Assigned') ?></span>
                                <?php if (!empty($apt['preferred_dentist_name']) && $apt['preferred_dentist_name'] !== $apt['assigned_dentist_name']): ?>
                                    <span class="text-[10px] text-amber-600 block" title="Reassigned from preferred dentist">
                                        (Pref: Dr. <?= htmlspecialchars($apt['preferred_dentist_name']) ?>)
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 px-4">
                                <span class="capitalize text-slate-500"><?= str_replace('_', ' ', $apt['source']) ?></span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border <?= $badge ?>">
                                    <?= str_replace('_', ' ', $apt['status']) ?>
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <a href="<?= BASE_URL ?>/index.php?page=appointment_view&id=<?= $apt['id'] ?>" 
                                   class="px-3 py-1.5 bg-slate-100 hover:bg-teal-600 hover:text-white text-slate-700 text-xs font-semibold rounded-lg border border-slate-200 transition">
                                    Manage
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

