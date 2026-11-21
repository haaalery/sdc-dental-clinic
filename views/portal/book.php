<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../config/session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Online - <?= APP_NAME ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-50 min-h-screen text-slate-800 antialiased flex flex-col">
    <!-- Navbar -->
    <header class="bg-white border-b border-slate-200 py-4 px-6 md:px-12 flex items-center justify-between sticky top-0 z-30 shadow-xs">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-teal-600 to-cyan-500 flex items-center justify-center text-white shadow-md shadow-teal-500/20">
                <i data-lucide="activity" class="w-6 h-6"></i>
            </div>
            <div>
                <h1 class="font-bold text-slate-900 text-lg leading-tight"><?= APP_NAME ?></h1>
                <p class="text-xs text-teal-600 font-medium">Patient Online Appointment Portal</p>
            </div>
        </div>
        <div>
            <a href="<?= BASE_URL ?>/index.php?page=login" class="text-xs font-semibold text-slate-600 hover:text-teal-600 flex items-center space-x-1.5 px-3 py-2 rounded-lg border border-slate-200 hover:border-teal-500 transition">
                <i data-lucide="lock" class="w-3.5 h-3.5"></i>
                <span>Staff Portal</span>
            </a>
        </div>
    </header>

    <!-- Main Container -->
    <main class="flex-1 max-w-4xl mx-auto w-full p-4 md:p-8">
        <!-- Hero Banner -->
        <div class="bg-gradient-to-r from-teal-700 via-teal-600 to-cyan-700 rounded-3xl p-8 md:p-10 text-white shadow-xl mb-8 relative overflow-hidden">
            <div class="relative z-10 max-w-xl">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-white/20 backdrop-blur-md mb-3">
                    Fast & Convenient Booking
                </span>
                <h2 class="text-2xl md:text-3xl font-extrabold tracking-tight">Schedule Your Dental Visit Online</h2>
                <p class="text-teal-100 text-sm mt-2 leading-relaxed">
                    Choose your preferred schedule, specify your dental concerns, and our clinic reception will confirm your slot right away.
                </p>
            </div>
            <div class="absolute -right-10 -bottom-10 opacity-15 pointer-events-none">
                <i data-lucide="smile" class="w-64 h-64 text-white"></i>
            </div>
        </div>

        <?php if ($portalError = Session::flash('portal_error')): ?>
            <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center space-x-3 shadow-xs">
                <i data-lucide="alert-circle" class="w-5 h-5 text-rose-600 flex-shrink-0"></i>
                <span><?= htmlspecialchars($portalError) ?></span>
            </div>
        <?php endif; ?>

        <!-- Online Booking Form (All interview-requested fields) -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-6 md:p-10">
            <h3 class="text-lg font-bold text-slate-900 mb-1 flex items-center space-x-2">
                <i data-lucide="calendar-plus" class="w-5 h-5 text-teal-600"></i>
                <span>Appointment Details</span>
            </h3>
            <p class="text-xs text-slate-500 mb-6">All fields marked with an asterisk (<span class="text-rose-500">*</span>) are required.</p>

            <form action="<?= BASE_URL ?>/index.php?page=portal_submit" method="POST" class="space-y-6">
                <!-- Patient Name (First Name, Middle Initial Optional, Last Name) -->
                <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                    <!-- First Name -->
                    <div class="sm:col-span-5">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            First Name <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                                <i data-lucide="user" class="w-4 h-4"></i>
                            </span>
                            <input type="text" name="first_name" required placeholder="e.g. Maria"
                                   class="w-full pl-10 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:bg-white transition">
                        </div>
                    </div>

                    <!-- Middle Initial -->
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            M.I. <span class="text-slate-400 font-normal text-[10px]">(Optional)</span>
                        </label>
                        <input type="text" name="middle_initial" maxlength="5" placeholder="e.g. D."
                               class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-center uppercase focus:outline-none focus:ring-2 focus:ring-teal-500 focus:bg-white transition">
                    </div>

                    <!-- Last Name -->
                    <div class="sm:col-span-5">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Last Name <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="last_name" required placeholder="e.g. Santos"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:bg-white transition">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Phone Number -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Phone Number <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                                <i data-lucide="phone" class="w-4 h-4"></i>
                            </span>
                            <input type="tel" name="phone" required placeholder="e.g. 0917 123 4567"
                                   class="w-full pl-10 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:bg-white transition">
                        </div>
                    </div>

                    <!-- Branch Selection -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Clinic Branch
                        </label>
                        <select name="branch_id" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:bg-white transition">
                            <?php foreach ($branches as $branch): ?>
                                <option value="<?= $branch['id'] ?>"><?= htmlspecialchars($branch['branch_name']) ?> (<?= htmlspecialchars($branch['address']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Preferred Date -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Preferred Date <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                                <i data-lucide="calendar" class="w-4 h-4"></i>
                            </span>
                            <input type="date" name="preferred_date" required min="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d', strtotime('+1 day')) ?>"
                                   class="w-full pl-10 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:bg-white transition">
                        </div>
                    </div>

                    <!-- Preferred Time -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            Preferred Time <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                                <i data-lucide="clock" class="w-4 h-4"></i>
                            </span>
                            <select name="preferred_time" required class="w-full pl-10 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:bg-white transition">
                                <option value="09:00:00">09:00 AM - Morning Slot</option>
                                <option value="10:00:00">10:00 AM - Morning Slot</option>
                                <option value="11:00:00">11:00 AM - Morning Slot</option>
                                <option value="13:30:00">01:30 PM - Afternoon Slot</option>
                                <option value="14:30:00">02:30 PM - Afternoon Slot</option>
                                <option value="15:30:00">03:30 PM - Afternoon Slot</option>
                                <option value="16:30:00">04:30 PM - Late Afternoon</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div>
                    <!-- Preferred Dentist Selection -->
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Preferred Dentist (Optional)
                    </label>
                    <select name="preferred_dentist_id" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:bg-white transition">
                        <option value="">Any Available Dentist</option>
                        <?php foreach ($dentists as $d): ?>
                            <option value="<?= $d['dentist_id'] ?>"><?= htmlspecialchars($d['full_name']) ?> (<?= htmlspecialchars($d['specialization']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3.5 px-6 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-2xl shadow-lg shadow-teal-600/30 transition flex items-center justify-center space-x-2 text-base">
                        <i data-lucide="check-circle" class="w-5 h-5"></i>
                        <span>Confirm and Book Appointment</span>
                    </button>
                    <p class="text-center text-xs text-slate-400 mt-3">
                        Our reception desk will contact your phone number to confirm your booking and dental health screening.
                    </p>
                </div>
            </form>
        </div>
    </main>

    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-500 mt-12">
        <p>&copy; <?= date('Y') ?> <?= APP_NAME ?>. Professional Dental Care & Oral Health.</p>
    </footer>

    <script>lucide.createIcons();</script>
</body>
</html>

