<?php
$currentPage = $_GET['page'] ?? 'dashboard';
$userRole = Session::role();
?>
<aside class="w-64 bg-white text-slate-600 flex flex-col flex-shrink-0 border-r border-slate-200 select-none hidden md:flex font-sans">
    <!-- Brand / Logo Area -->
    <div class="p-4 border-b border-slate-100 flex items-center space-x-3 bg-white">
        <div class="w-10 h-10 rounded-xl bg-slate-900 flex items-center justify-center p-1.5 shadow-xs flex-shrink-0">
            <img src="<?= BASE_URL ?>/assets/images/logo.png" alt="SDC Dental Care" class="w-full h-full object-contain">
        </div>
        <div class="min-w-0">
            <h1 class="font-extrabold text-slate-900 tracking-tight text-sm leading-tight">SDC DENTAL</h1>
            <p class="text-[10px] text-slate-400 font-semibold tracking-wider uppercase">Care Management</p>
        </div>
    </div>

    <!-- Navigation Links -->
    <nav class="flex-1 px-3 py-5 space-y-1 overflow-y-auto text-xs">
        <div class="px-3 pb-1.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Main Menu</div>

        <!-- Dashboard -->
        <?php if ($userRole === 'admin' || $userRole === 'front_desk'): ?>
            <a href="<?= BASE_URL ?>/index.php?page=dashboard" 
               class="flex items-center space-x-3 px-3 py-2.5 rounded-xl font-medium transition <?= $currentPage === 'dashboard' ? 'bg-slate-900 text-white font-semibold shadow-xs' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' ?>">
                <i data-lucide="layout-dashboard" class="w-4 h-4 <?= $currentPage === 'dashboard' ? 'text-white' : 'text-slate-400' ?>"></i>
                <span>Executive Dashboard</span>
            </a>
        <?php endif; ?>

        <?php if ($userRole === 'dentist'): ?>
            <a href="<?= BASE_URL ?>/index.php?page=dentist_dashboard" 
               class="flex items-center space-x-3 px-3 py-2.5 rounded-xl font-medium transition <?= $currentPage === 'dentist_dashboard' ? 'bg-slate-900 text-white font-semibold shadow-xs' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' ?>">
                <i data-lucide="stethoscope" class="w-4 h-4 <?= $currentPage === 'dentist_dashboard' ? 'text-white' : 'text-slate-400' ?>"></i>
                <span>Dentist Portal</span>
            </a>
        <?php endif; ?>

        <!-- Appointments & Scheduling -->
        <a href="<?= BASE_URL ?>/index.php?page=appointments" 
           class="flex items-center space-x-3 px-3 py-2.5 rounded-xl font-medium transition <?= in_array($currentPage, ['appointments', 'appointment_create', 'appointment_view']) ? 'bg-slate-900 text-white font-semibold shadow-xs' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' ?>">
            <i data-lucide="calendar" class="w-4 h-4 <?= in_array($currentPage, ['appointments', 'appointment_create', 'appointment_view']) ? 'text-white' : 'text-slate-400' ?>"></i>
            <span>Appointments</span>
        </a>

        <!-- Patients EMR (Includes Occupation & X-rays) -->
        <a href="<?= BASE_URL ?>/index.php?page=patients" 
           class="flex items-center space-x-3 px-3 py-2.5 rounded-xl font-medium transition <?= in_array($currentPage, ['patients', 'patient_create', 'patient_view']) ? 'bg-slate-900 text-white font-semibold shadow-xs' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' ?>">
            <i data-lucide="users" class="w-4 h-4 <?= in_array($currentPage, ['patients', 'patient_create', 'patient_view']) ? 'text-white' : 'text-slate-400' ?>"></i>
            <span>Patients & EMR</span>
        </a>

        <!-- Clinical Treatments & Commission Rates -->
        <a href="<?= BASE_URL ?>/index.php?page=treatments" 
           class="flex items-center space-x-3 px-3 py-2.5 rounded-xl font-medium transition <?= $currentPage === 'treatments' ? 'bg-slate-900 text-white font-semibold shadow-xs' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' ?>">
            <i data-lucide="clipboard-list" class="w-4 h-4 <?= $currentPage === 'treatments' ? 'text-white' : 'text-slate-400' ?>"></i>
            <span>Treatments Catalog</span>
        </a>

        <!-- Billing, Balances & Receipts -->
        <?php if (in_array($userRole, ['admin', 'front_desk'])): ?>
            <div class="pt-4 px-3 pb-1.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Financials</div>

            <a href="<?= BASE_URL ?>/index.php?page=billing" 
               class="flex items-center space-x-3 px-3 py-2.5 rounded-xl font-medium transition <?= in_array($currentPage, ['billing', 'invoice_view', 'receipt']) ? 'bg-slate-900 text-white font-semibold shadow-xs' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' ?>">
                <i data-lucide="receipt" class="w-4 h-4 <?= in_array($currentPage, ['billing', 'invoice_view', 'receipt']) ? 'text-white' : 'text-slate-400' ?>"></i>
                <span>Billing & Receipts</span>
            </a>

            <!-- Daily Expenses -->
            <a href="<?= BASE_URL ?>/index.php?page=expenses" 
               class="flex items-center space-x-3 px-3 py-2.5 rounded-xl font-medium transition <?= $currentPage === 'expenses' ? 'bg-slate-900 text-white font-semibold shadow-xs' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' ?>">
                <i data-lucide="wallet" class="w-4 h-4 <?= $currentPage === 'expenses' ? 'text-white' : 'text-slate-400' ?>"></i>
                <span>Daily Expenses</span>
            </a>
        <?php endif; ?>

        <!-- Dental Supplies & Assistant Usage -->
        <div class="pt-4 px-3 pb-1.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Inventory</div>

        <a href="<?= BASE_URL ?>/index.php?page=inventory" 
           class="flex items-center space-x-3 px-3 py-2.5 rounded-xl font-medium transition <?= $currentPage === 'inventory' ? 'bg-slate-900 text-white font-semibold shadow-xs' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' ?>">
            <i data-lucide="package" class="w-4 h-4 <?= $currentPage === 'inventory' ? 'text-white' : 'text-slate-400' ?>"></i>
            <span>Supply Tracking</span>
        </a>

        <!-- Commission Reports (Admin & Dentists) -->
        <?php if (in_array($userRole, ['admin', 'dentist'])): ?>
            <div class="pt-4 px-3 pb-1.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Reports</div>

            <a href="<?= BASE_URL ?>/index.php?page=commissions" 
               class="flex items-center space-x-3 px-3 py-2.5 rounded-xl font-medium transition <?= $currentPage === 'commissions' ? 'bg-slate-900 text-white font-semibold shadow-xs' : 'text-slate-600 hover:bg-slate-100/80 hover:text-slate-900' ?>">
                <i data-lucide="percent" class="w-4 h-4 <?= $currentPage === 'commissions' ? 'text-white' : 'text-slate-400' ?>"></i>
                <span>Commission Splits</span>
            </a>
        <?php endif; ?>

        <!-- External Public Landing Link -->
        <div class="pt-4 px-3 pb-1.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Public Site</div>
        <a href="<?= BASE_URL ?>/index.php" target="_blank" 
           class="flex items-center space-x-3 px-3 py-2.5 rounded-xl font-medium text-slate-600 hover:bg-slate-100/80 hover:text-slate-900 transition">
            <i data-lucide="globe" class="w-4 h-4 text-slate-400"></i>
            <span>Public Clinic Website</span>
        </a>
    </nav>

    <!-- User Profile & Branch Card at Bottom -->
    <div class="p-3.5 border-t border-slate-200 bg-slate-50/70">
        <div class="flex items-center space-x-3">
            <div class="w-8 h-8 rounded-full bg-slate-200 text-slate-700 flex items-center justify-center font-bold text-xs border border-slate-300">
                <?= strtoupper(substr($currentUser['full_name'] ?? 'U', 0, 1)) ?>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-xs font-semibold text-slate-900 truncate"><?= htmlspecialchars($currentUser['full_name'] ?? 'User') ?></p>
                <p class="text-[10px] text-slate-500 capitalize truncate"><?= htmlspecialchars(str_replace('_', ' ', $currentUser['role'] ?? 'Staff')) ?></p>
            </div>
            <a href="<?= BASE_URL ?>/index.php?page=logout" title="Sign Out" class="text-slate-400 hover:text-rose-600 transition p-1">
                <i data-lucide="log-out" class="w-4 h-4"></i>
            </a>
        </div>
    </div>
</aside>
