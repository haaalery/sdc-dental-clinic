<?php
$currentUser = Session::user();
$allBranches = User::getAllBranches();
$activeBranchId = $_GET['branch_id'] ?? $currentUser['branch_id'] ?? null;
?>
<header class="bg-white border-b border-slate-200 px-6 py-3.5 flex items-center justify-between sticky top-0 z-20 shadow-xs">
    <!-- Left Section: Mobile Toggle & Branch Indicator -->
    <div class="flex items-center space-x-4">
        <!-- Branch Selector (Admin can switch between branches; others see their branch) -->
        <?php if ($currentUser['role'] === 'admin'): ?>
            <form method="GET" action="<?= BASE_URL ?>/index.php" class="flex items-center space-x-2">
                <input type="hidden" name="page" value="<?= htmlspecialchars($_GET['page'] ?? 'dashboard') ?>">
                <div class="flex items-center space-x-1.5 bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs text-slate-700">
                    <i data-lucide="building-2" class="w-3.5 h-3.5 text-teal-600"></i>
                    <select name="branch_id" onchange="this.form.submit()" class="bg-transparent font-medium focus:outline-hidden cursor-pointer">
                        <option value="">All SDC Branches (Consolidated)</option>
                        <?php foreach ($allBranches as $b): ?>
                            <option value="<?= $b['id'] ?>" <?= ($activeBranchId == $b['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($b['branch_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>
        <?php else: ?>
            <div class="flex items-center space-x-1.5 text-xs font-semibold text-slate-700 bg-slate-100 px-3 py-1.5 rounded-lg border border-slate-200">
                <i data-lucide="map-pin" class="w-3.5 h-3.5 text-teal-600"></i>
                <span><?= htmlspecialchars($currentUser['branch_name'] ?? 'Main Clinic') ?></span>
            </div>
        <?php endif; ?>

        <div class="hidden lg:flex items-center space-x-1 text-xs text-slate-400">
            <span>•</span>
            <i data-lucide="clock" class="w-3.5 h-3.5 ml-1 text-slate-400"></i>
            <span><?= date('l, F j, Y') ?></span>
        </div>
    </div>

    <!-- Right Section: Quick Action Buttons & Profile -->
    <div class="flex items-center space-x-3">
        <!-- Quick Appointment Button -->
        <a href="<?= BASE_URL ?>/index.php?page=appointment_create" 
           class="inline-flex items-center space-x-1.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold px-3 py-2 rounded-lg shadow-xs transition">
            <i data-lucide="plus" class="w-3.5 h-3.5"></i>
            <span>New Appointment</span>
        </a>

        <!-- Quick Patient Button -->
        <a href="<?= BASE_URL ?>/index.php?page=patient_create" 
           class="inline-flex items-center space-x-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold px-3 py-2 rounded-lg border border-slate-200 transition">
            <i data-lucide="user-plus" class="w-3.5 h-3.5 text-slate-500"></i>
            <span>Register Patient</span>
        </a>

        <!-- User Role Pill -->
        <div class="hidden sm:flex items-center space-x-2 pl-2 border-l border-slate-200 text-xs">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium bg-slate-100 text-slate-700 border border-slate-200 capitalize text-[11px]">
                <?= htmlspecialchars(str_replace('_', ' ', $currentUser['role'])) ?>
            </span>
        </div>
    </div>
</header>

