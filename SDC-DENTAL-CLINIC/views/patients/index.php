<?php
$pageTitle = 'Patient Directory';
require_once __DIR__ . '/../layouts/header.php';

$search = $_GET['search'] ?? '';
?>

<!-- Header -->
<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Patient Directory & EMR</h2>
        <p class="text-xs text-slate-500 mt-1">Manage electronic medical records, occupations, and patient files.</p>
    </div>
    <a href="<?= BASE_URL ?>/index.php?page=patient_create" 
       class="inline-flex items-center space-x-1.5 bg-teal-600 hover:bg-teal-700 text-white text-xs font-semibold px-4 py-2.5 rounded-xl shadow-sm transition">
        <i data-lucide="user-plus" class="w-4 h-4"></i>
        <span>Register New Patient</span>
    </a>
</div>

<!-- Search Bar -->
<div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs mb-6">
    <form method="GET" class="flex gap-3">
        <input type="hidden" name="page" value="patients">
        <div class="relative flex-1">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                <i data-lucide="search" class="w-4 h-4"></i>
            </span>
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" 
                   placeholder="Search by name, patient code, phone number, or occupation..." 
                   class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-1 focus:ring-teal-500 focus:bg-white transition">
        </div>
        <button type="submit" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold rounded-xl transition">
            Search
        </button>
        <?php if (!empty($search)): ?>
            <a href="<?= BASE_URL ?>/index.php?page=patients" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold rounded-xl transition flex items-center">
                Clear
            </a>
        <?php endif; ?>
    </form>
</div>

<!-- Patients Table -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-xs">
            <thead>
                <tr class="bg-slate-50 text-slate-500 uppercase tracking-wider font-bold border-b border-slate-200">
                    <th class="py-3 px-4">Patient Code</th>
                    <th class="py-3 px-4">Full Name</th>
                    <th class="py-3 px-4">Contact Phone</th>
                    <th class="py-3 px-4">Occupation</th>
                    <th class="py-3 px-4 text-center">Total Visits</th>
                    <th class="py-3 px-4">Last Visit</th>
                    <th class="py-3 px-4 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-slate-700">
                <?php if (empty($patients)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-8 text-slate-400 italic">No patients found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($patients as $p): ?>
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3 px-4 font-mono font-bold text-teal-700">
                                <?= htmlspecialchars($p['patient_code']) ?>
                            </td>
                            <td class="py-3 px-4">
                                <span class="font-bold text-slate-900 block"><?= htmlspecialchars($p['first_name'] . ' ' . $p['last_name']) ?></span>
                                <span class="text-[11px] text-slate-400"><?= ucfirst($p['gender']) ?><?= $p['date_of_birth'] ? ', ' . (date('Y') - date('Y', strtotime($p['date_of_birth']))) . ' yrs' : '' ?></span>
                            </td>
                            <td class="py-3 px-4 font-mono"><?= htmlspecialchars($p['phone']) ?></td>
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md font-medium bg-slate-100 text-slate-700">
                                    <?= htmlspecialchars($p['occupation'] ?: 'Not Specified') ?>
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center font-bold text-slate-800"><?= $p['total_visits'] ?></td>
                            <td class="py-3 px-4 text-slate-500">
                                <?= $p['last_visit'] ? date('M d, Y', strtotime($p['last_visit'])) : 'No visits yet' ?>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <a href="<?= BASE_URL ?>/index.php?page=patient_view&id=<?= $p['id'] ?>" 
                                   class="px-3 py-1.5 bg-slate-100 hover:bg-teal-600 hover:text-white text-slate-700 text-xs font-semibold rounded-lg border border-slate-200 transition">
                                    View Record
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

