<?php
$pageTitle = 'Schedule New Appointment';
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="max-w-3xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Schedule New Appointment</h2>
            <p class="text-xs text-slate-500 mt-1">Select an existing patient or book for a walk-in visitor.</p>
        </div>
        <a href="<?= BASE_URL ?>/index.php?page=appointments" class="text-xs font-semibold text-slate-600 hover:text-slate-900">
            &larr; Back to Appointments
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 md:p-8">
        <form action="<?= BASE_URL ?>/index.php?page=appointment_store" method="POST" class="space-y-6">
            <!-- Patient Selector -->
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Patient Profile <span class="text-rose-500">*</span>
                    </label>
                    <a href="<?= BASE_URL ?>/index.php?page=patient_create" class="text-xs text-teal-600 hover:text-teal-700 font-semibold">
                        + Register New Patient
                    </a>
                </div>
                <select name="patient_id" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:bg-white transition">
                    <option value="">-- Choose Registered Patient --</option>
                    <?php foreach ($patients as $p): ?>
                        <option value="<?= $p['id'] ?>" <?= ($selectedPatientId === (int)$p['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($p['patient_code']) ?> - <?= htmlspecialchars($p['first_name'] . ' ' . $p['last_name']) ?> (<?= htmlspecialchars($p['phone']) ?><?= $p['occupation'] ? ' - ' . htmlspecialchars($p['occupation']) : '' ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Branch -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Branch</label>
                    <select name="branch_id" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:bg-white transition">
                        <?php foreach ($branches as $b): ?>
                            <option value="<?= $b['id'] ?>"><?= htmlspecialchars($b['branch_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Source -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Booking Source</label>
                    <select name="source" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:bg-white transition">
                        <option value="walk_in">Walk-in</option>
                        <option value="phone">Phone Call / WhatsApp</option>
                        <option value="online_portal">Website Portal</option>
                    </select>
                </div>
            </div>

            <!-- Preferred & Assigned Dentist (Explicit Interview Requirement) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 bg-teal-50/50 p-4 rounded-xl border border-teal-100">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-teal-900 mb-1.5">
                        Patient-Preferred Dentist
                    </label>
                    <select name="preferred_dentist_id" id="prefDentist" onchange="syncAssignedDentist()" class="w-full px-3.5 py-2.5 bg-white border border-teal-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 transition">
                        <option value="">No Preference</option>
                        <?php foreach ($dentists as $d): ?>
                            <option value="<?= $d['dentist_id'] ?>"><?= htmlspecialchars($d['full_name']) ?> (<?= htmlspecialchars($d['specialization']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                    <span class="text-[11px] text-teal-700 mt-1 block">Requested by the patient</span>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-teal-900 mb-1.5">
                        Assigned Attending Dentist <span class="text-rose-500">*</span>
                    </label>
                    <select name="assigned_dentist_id" id="asgnDentist" required class="w-full px-3.5 py-2.5 bg-white border border-teal-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 transition">
                        <option value="">-- Select Attending Dentist --</option>
                        <?php foreach ($dentists as $d): ?>
                            <option value="<?= $d['dentist_id'] ?>"><?= htmlspecialchars($d['full_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span class="text-[11px] text-teal-700 mt-1 block">Dentist scheduled to attend</span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Appointment Date -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Date <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" name="appointment_date" required value="<?= date('Y-m-d') ?>"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:bg-white transition">
                </div>

                <!-- Appointment Time -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Time Slot <span class="text-rose-500">*</span>
                    </label>
                    <input type="time" name="appointment_time" required value="09:00"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:bg-white transition">
                </div>
            </div>

            <!-- Dental Concern -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                    Dental Concern / Chief Complaint <span class="text-rose-500">*</span>
                </label>
                <textarea name="dental_concern" rows="3" required placeholder="Describe tooth issue, consultation purpose, or procedure request..."
                          class="w-full p-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:bg-white transition"></textarea>
            </div>

            <!-- Staff Notes -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Staff Notes</label>
                <input type="text" name="notes" placeholder="Optional notes for the dental assistant or doctor..."
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:bg-white transition">
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="<?= BASE_URL ?>/index.php?page=appointments" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-semibold transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-teal-600 hover:bg-teal-700 text-white rounded-xl text-xs font-semibold shadow-sm transition">
                    Save Appointment
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function syncAssignedDentist() {
    const pref = document.getElementById('prefDentist').value;
    const asgn = document.getElementById('asgnDentist');
    if (pref && !asgn.value) {
        asgn.value = pref;
    }
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

