<?php
$pageTitle = 'Register Patient Profile';
require_once __DIR__ . '/../layouts/header.php';

$presetFirstName = $_GET['first_name'] ?? '';
$presetMi = $_GET['middle_initial'] ?? '';
$presetLastName = $_GET['last_name'] ?? '';

// Fallback: If legacy single 'name' parameter is passed (e.g. "Sofia D. Santos" or "Juan Dela Cruz")
if (empty($presetFirstName) && !empty($_GET['name'])) {
    $parts = preg_split('/\s+/', trim($_GET['name']));
    $presetFirstName = $parts[0] ?? '';
    if (count($parts) === 2) {
        $presetLastName = $parts[1];
    } elseif (count($parts) >= 3) {
        // Check if second token is a middle initial (e.g. "D.", "D", length <= 2 or ends with dot)
        if (strlen($parts[1]) <= 2 || str_ends_with($parts[1], '.')) {
            $presetMi = $parts[1];
            $presetLastName = implode(' ', array_slice($parts, 2));
        } else {
            $presetLastName = implode(' ', array_slice($parts, 1));
        }
    }
}
$presetPhone = $_GET['phone'] ?? '';
$linkAppointmentId = $_GET['link_appointment_id'] ?? null;
?>

<div class="max-w-3xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Register Patient Profile</h2>
            <p class="text-xs text-slate-500 mt-1">Create an electronic health record with demographic, medical, and occupation details.</p>
        </div>
        <a href="<?= BASE_URL ?>/index.php?page=patients" class="text-xs font-semibold text-slate-600 hover:text-slate-900">
            &larr; Back to Directory
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 md:p-8">
        <form action="<?= BASE_URL ?>/index.php?page=patient_store" method="POST" class="space-y-6">
            <?php if ($linkAppointmentId): ?>
                <input type="hidden" name="link_appointment_id" value="<?= htmlspecialchars($linkAppointmentId) ?>">
                <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-800">
                    Registering guest patient from appointment #<?= htmlspecialchars($linkAppointmentId) ?>.
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-5">
                <div class="md:col-span-5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        First Name <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="first_name" required value="<?= htmlspecialchars($presetFirstName) ?>" placeholder="e.g. Sofia"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:bg-white transition">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        M.I. <span class="text-slate-400 font-normal lowercase">(opt)</span>
                    </label>
                    <input type="text" name="middle_initial" maxlength="10" value="<?= htmlspecialchars($presetMi) ?>" placeholder="e.g. D."
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:bg-white transition text-center uppercase">
                </div>

                <div class="md:col-span-5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Last Name <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="last_name" required value="<?= htmlspecialchars($presetLastName) ?>" placeholder="e.g. Santos"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:bg-white transition">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Gender</label>
                    <select name="gender" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:bg-white transition">
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Date of Birth</label>
                    <input type="date" name="date_of_birth"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:bg-white transition">
                </div>

                <!-- Occupation Field (Explicit Interview Requirement) -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-teal-800 mb-1.5 flex items-center justify-between">
                        <span>Occupation</span>
                        <span class="text-[10px] text-teal-600 font-normal">Requested Field</span>
                    </label>
                    <input type="text" name="occupation" placeholder="e.g. Teacher, Engineer, Student"
                           class="w-full px-3.5 py-2.5 bg-teal-50/40 border border-teal-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:bg-white transition">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Phone Number <span class="text-rose-500">*</span>
                    </label>
                    <input type="tel" name="phone" required value="<?= htmlspecialchars($presetPhone) ?>" placeholder="e.g. +63 917 123 4567"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:bg-white transition">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Email Address</label>
                    <input type="email" name="email" placeholder="patient@example.com"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:bg-white transition">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Home / Residential Address</label>
                <input type="text" name="address" placeholder="Unit, Street, Barangay, City"
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:bg-white transition">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Medical History & Conditions</label>
                    <textarea name="medical_history" rows="3" placeholder="Hypertension, Diabetes, Heart condition, Bleeding tendencies, Pregnancy..."
                              class="w-full p-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:bg-white transition"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Known Allergies (Medications / Latex)</label>
                    <textarea name="allergies" rows="3" placeholder="Penicillin, Local anesthesia, Latex, Aspirin, Seafood..."
                              class="w-full p-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:bg-white transition"></textarea>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="<?= BASE_URL ?>/index.php?page=patients" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-semibold transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-teal-600 hover:bg-teal-700 text-white rounded-xl text-xs font-semibold shadow-sm transition">
                    Create Patient Record
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

