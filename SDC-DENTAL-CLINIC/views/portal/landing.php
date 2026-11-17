<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../config/session.php';
$isLoggedIn = Session::isLoggedIn();
$currentUser = Session::user();
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> - Premium Dental Care</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        slate: {
                            50: '#f8fafc',
                            100: '#f1f5f9',
                            200: '#e2e8f0',
                            300: '#cbd5e1',
                            400: '#94a3b8',
                            500: '#64748b',
                            600: '#475569',
                            700: '#334155',
                            800: '#1e293b',
                            900: '#0f172a',
                            950: '#020617',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'Inter', '-apple-system', 'BlinkMacSystemFont', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col font-sans">

    <!-- Top Emergency & Info Banner (Light Slate) -->
    <div class="bg-slate-100 text-slate-600 text-xs py-2 px-4 border-b border-slate-200">
        <div class="max-w-6xl mx-auto flex flex-col sm:flex-row justify-between items-center gap-2">
            <div class="flex items-center space-x-4">
                <span class="flex items-center space-x-1.5">
                    <i data-lucide="phone" class="w-3.5 h-3.5 text-slate-500"></i>
                    <span>Inquiries & Appointments: <strong class="text-slate-900 font-bold">+63 917 111 2233</strong></span>
                </span>
                <span class="hidden md:inline text-slate-300">|</span>
                <span class="hidden md:flex items-center space-x-1.5">
                    <i data-lucide="clock" class="w-3.5 h-3.5 text-slate-500"></i>
                    <span>Mon – Sat: 9:00 AM – 6:00 PM</span>
                </span>
            </div>
            <div class="flex items-center space-x-4">
                <span class="text-slate-500">Branches in Makati & Pasig</span>
                <?php if ($isLoggedIn): ?>
                    <a href="<?= BASE_URL ?>/index.php?page=dashboard" class="text-slate-900 hover:text-black font-bold underline">
                        Staff Dashboard (<?= htmlspecialchars($currentUser['username']) ?>) &rarr;
                    </a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/index.php?page=login" class="text-slate-600 hover:text-slate-900 font-semibold flex items-center space-x-1 transition">
                        <i data-lucide="lock" class="w-3 h-3 text-slate-500"></i>
                        <span>Staff Login</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <header class="bg-white text-slate-800 border-b border-slate-200 sticky top-0 z-30 shadow-xs">
        <div class="max-w-6xl mx-auto px-4 py-3.5 flex items-center justify-between">
            <!-- Brand with Official Logo -->
            <a href="<?= BASE_URL ?>/index.php" class="flex items-center space-x-3 group">
                <div class="w-10 h-10 rounded-xl bg-slate-900 flex items-center justify-center p-1.5 shadow-xs flex-shrink-0 transition transform group-hover:scale-105">
                    <img src="<?= BASE_URL ?>/assets/images/logo.png" alt="SDC Dental Care" class="w-full h-full object-contain">
                </div>
                <div>
                    <h1 class="text-base font-extrabold text-slate-900 tracking-tight leading-tight">SDC DENTAL</h1>
                    <p class="text-[10px] text-slate-500 font-bold tracking-wider uppercase">Care Management</p>
                </div>
            </a>

            <!-- Navigation Links -->
            <nav class="hidden md:flex items-center space-x-8 text-xs font-semibold text-slate-600">
                <a href="#services" class="hover:text-slate-900 transition">Services & Treatments</a>
                <a href="#standards" class="hover:text-slate-900 transition">Clinical Standards</a>
                <a href="#dentists" class="hover:text-slate-900 transition">Our Doctors</a>
                <a href="#locations" class="hover:text-slate-900 transition">Hours & Branches</a>
            </nav>

            <!-- Action Button -->
            <div class="flex items-center space-x-3">
                <a href="#booking-form" class="inline-flex items-center space-x-1.5 px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold rounded-xl shadow-xs transition">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                    <span>Book Appointment</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Hero Section (Light, Clean Gray Aesthetic) -->
    <section class="bg-gradient-to-b from-white via-slate-50 to-slate-100/60 border-b border-slate-200 py-16 md:py-20 relative overflow-hidden">
        <div class="max-w-6xl mx-auto px-4 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                <div class="lg:col-span-8">
                    <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full text-xs font-semibold bg-white text-slate-700 border border-slate-200 shadow-2xs mb-5">
                        <i data-lucide="sparkles" class="w-3.5 h-3.5 text-slate-500"></i>
                        <span>Premier Clinical Dentistry & Oral Health</span>
                    </div>
                    <h2 class="text-3xl md:text-5xl font-extrabold text-slate-900 tracking-tight leading-tight">
                        Gentle, Comprehensive Dental Care for You & Your Family.
                    </h2>
                    <p class="text-slate-600 text-base md:text-lg mt-4 leading-relaxed max-w-2xl font-normal">
                        From preventive oral prophylaxis and restorative fillings to root canal therapy and orthodontic braces, SDC Dental Care offers high-standard dental procedures with complete pricing transparency and certified attending doctors.
                    </p>

                    <!-- Quick Practice Highlights -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mt-8 text-xs font-semibold text-slate-700">
                        <div class="flex items-center space-x-2.5 p-3.5 bg-white rounded-xl border border-slate-200 shadow-2xs">
                            <i data-lucide="shield-check" class="w-4 h-4 text-slate-800 flex-shrink-0"></i>
                            <span>Hospital-Grade Sterilization</span>
                        </div>
                        <div class="flex items-center space-x-2.5 p-3.5 bg-white rounded-xl border border-slate-200 shadow-2xs">
                            <i data-lucide="receipt" class="w-4 h-4 text-slate-800 flex-shrink-0"></i>
                            <span>Transparent, Itemized Receipts</span>
                        </div>
                        <div class="flex items-center space-x-2.5 p-3.5 bg-white rounded-xl border border-slate-200 shadow-2xs">
                            <i data-lucide="credit-card" class="w-4 h-4 text-slate-800 flex-shrink-0"></i>
                            <span>Cash, GCash/Maya & Card</span>
                        </div>
                    </div>

                    <div class="mt-8 flex flex-wrap items-center gap-3">
                        <a href="#booking-form" class="px-6 py-3.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-sm rounded-xl shadow-xs transition inline-flex items-center space-x-2">
                            <span>Book an Appointment Online</span>
                            <i data-lucide="arrow-down" class="w-4 h-4"></i>
                        </a>
                        <a href="#services" class="px-5 py-3.5 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-sm rounded-xl border border-slate-200 shadow-2xs transition">
                            Browse Dental Services
                        </a>
                    </div>
                </div>

                <!-- Prominent Logo Showcase in Hero -->
                <div class="lg:col-span-4 flex justify-center lg:justify-end">
                    <div class="p-6 bg-slate-900 border border-slate-800 rounded-3xl shadow-xl text-center max-w-xs text-white">
                        <img src="<?= BASE_URL ?>/assets/images/logo.png" alt="SDC Dental Emblem" class="w-48 h-48 mx-auto object-contain">
                        <div class="mt-3 pt-3 border-t border-slate-800 text-center">
                            <span class="text-xs font-bold text-white tracking-wider uppercase block">SDC DENTAL CARE</span>
                            <span class="text-[11px] text-slate-400">Professional Healthcare Standards</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services & Treatments Section -->
    <section id="services" class="py-16 bg-white border-b border-slate-200">
        <div class="max-w-6xl mx-auto px-4">
            <div class="mb-10">
                <span class="text-xs uppercase font-bold tracking-wider text-slate-500 block mb-1">Clinical Catalog</span>
                <h3 class="text-2xl font-extrabold text-slate-900 tracking-tight">Our Services & Treatments</h3>
                <p class="text-xs text-slate-500 mt-1 max-w-xl">
                    Every procedure is conducted using sterile instruments and certified clinical dental materials. All calculations are transparent with itemized receipts.
                </p>
            </div>

            <!-- Services Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                <?php if (!empty($treatments)): ?>
                    <?php foreach ($treatments as $trt): ?>
                        <div class="bg-slate-50/70 hover:bg-white rounded-2xl p-5 border border-slate-200 hover:border-slate-300 shadow-2xs transition flex flex-col justify-between">
                            <div>
                                <div class="flex items-start justify-between gap-2 mb-2">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-700 bg-white px-2 py-0.5 rounded border border-slate-200">
                                        <?= htmlspecialchars($trt['category']) ?>
                                    </span>
                                    <span class="font-mono text-xs font-bold text-slate-900">
                                        <?= APP_CURRENCY ?><?= number_format($trt['standard_price'], 2) ?>
                                    </span>
                                </div>
                                <h4 class="font-bold text-slate-900 text-sm mb-1.5"><?= htmlspecialchars($trt['treatment_name']) ?></h4>
                                <p class="text-xs text-slate-500 leading-relaxed">
                                    <?= htmlspecialchars($trt['description'] ?: 'Professional clinical procedure conducted with dental examination and consultation.') ?>
                                </p>
                            </div>
                            <div class="mt-4 pt-3 border-t border-slate-200/80 flex items-center justify-between text-xs">
                                <span class="text-slate-400 font-mono"><?= htmlspecialchars($trt['treatment_code']) ?></span>
                                <a href="#booking-form" onclick="prefillConcern('<?= addslashes(htmlspecialchars($trt['treatment_name'])) ?>')" class="text-slate-900 hover:text-black font-bold inline-flex items-center space-x-1">
                                    <span>Schedule &rarr;</span>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-xs text-slate-400 italic">No services listed at this moment.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Clinical Standards & What to Expect -->
    <section id="standards" class="py-16 bg-slate-50 border-b border-slate-200">
        <div class="max-w-6xl mx-auto px-4">
            <div class="max-w-xl mb-10">
                <span class="text-xs uppercase font-bold tracking-wider text-slate-500 block mb-1">Our Practice</span>
                <h3 class="text-2xl font-extrabold text-slate-900 tracking-tight">Clinical Standards You Can Trust</h3>
                <p class="text-xs text-slate-500 mt-1">
                    We maintain hospital-grade infection control and an ethical, patient-centered approach to dental healthcare.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-xs text-slate-600">
                <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-2xs">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 border border-slate-200 text-slate-800 flex items-center justify-center font-bold text-base mb-4">
                        <i data-lucide="shield" class="w-5 h-5 text-slate-800"></i>
                    </div>
                    <h4 class="font-bold text-slate-900 text-sm mb-2">Hospital-Grade Sterilization</h4>
                    <p class="leading-relaxed">
                        Every surgical instrument, scaler, and bur goes through ultrasonic cleaning, surgical packaging, and hospital-grade autoclave steam sterilization. Disposables are strictly single-use.
                    </p>
                </div>

                <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-2xs">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 border border-slate-200 text-slate-800 flex items-center justify-center font-bold text-base mb-4">
                        <i data-lucide="smile" class="w-5 h-5 text-slate-800"></i>
                    </div>
                    <h4 class="font-bold text-slate-900 text-sm mb-2">Gentle, Patient-First Care</h4>
                    <p class="leading-relaxed">
                        We know visiting the dentist can feel stressful. Our attending dentists explain every step beforehand, offer gentle anesthesia, and work comfortably at your own pace.
                    </p>
                </div>

                <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-2xs">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 border border-slate-200 text-slate-800 flex items-center justify-center font-bold text-base mb-4">
                        <i data-lucide="file-check" class="w-5 h-5 text-slate-800"></i>
                    </div>
                    <h4 class="font-bold text-slate-900 text-sm mb-2">Transparent Treatment Planning</h4>
                    <p class="leading-relaxed">
                        No surprise fees or unnecessary procedures. We present clear diagnostic findings, treatment options, itemized fees, and installment options for ongoing care (like braces or endodontics).
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Attending Dentists -->
    <section id="dentists" class="py-16 bg-white border-b border-slate-200">
        <div class="max-w-6xl mx-auto px-4">
            <div class="mb-10">
                <span class="text-xs uppercase font-bold tracking-wider text-slate-500 block mb-1">Medical Team</span>
                <h3 class="text-2xl font-extrabold text-slate-900 tracking-tight">Our Attending Dentists</h3>
                <p class="text-xs text-slate-500 mt-1">Licensed dental clinicians dedicated to gentle, quality oral healthcare.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <?php foreach ($dentists as $doc): ?>
                    <div class="bg-slate-50/70 rounded-2xl p-5 border border-slate-200 shadow-2xs flex items-start space-x-4">
                        <div class="w-12 h-12 rounded-xl bg-slate-900 text-white font-bold flex items-center justify-center text-sm flex-shrink-0">
                            Dr
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 text-sm"><?= htmlspecialchars($doc['full_name']) ?></h4>
                            <p class="text-xs text-slate-600 font-semibold mt-0.5"><?= htmlspecialchars($doc['specialization'] ?: 'General Dentistry') ?></p>
                            <?php if (!empty($doc['license_no'])): ?>
                                <p class="text-[11px] text-slate-400 mt-0.5 font-mono">PRC License: <?= htmlspecialchars($doc['license_no']) ?></p>
                            <?php endif; ?>
                            <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                                Attending clinician for oral diagnosis, restorations, surgical procedures, and specialized patient care.
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Clinic Hours & Branch Locations -->
    <section id="locations" class="py-16 bg-slate-50 border-b border-slate-200">
        <div class="max-w-6xl mx-auto px-4">
            <div class="mb-10">
                <span class="text-xs uppercase font-bold tracking-wider text-slate-500 block mb-1">Visit Us</span>
                <h3 class="text-2xl font-extrabold text-slate-900 tracking-tight">Clinic Hours & Branch Locations</h3>
                <p class="text-xs text-slate-500 mt-1">Conveniently accessible clinic branches with clean clinical operatories.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs text-slate-700">
                <?php foreach ($branches as $br): ?>
                    <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-2xs space-y-3">
                        <div class="flex items-center justify-between">
                            <h4 class="font-bold text-slate-900 text-sm flex items-center space-x-2">
                                <i data-lucide="map-pin" class="w-4 h-4 text-slate-800"></i>
                                <span><?= htmlspecialchars($br['branch_name']) ?></span>
                            </h4>
                            <span class="font-mono text-[11px] font-bold text-slate-700 px-2 py-0.5 rounded bg-slate-100 border border-slate-200">
                                <?= htmlspecialchars($br['branch_code']) ?>
                            </span>
                        </div>
                        <p class="text-slate-600"><?= htmlspecialchars($br['address'] ?: 'Clinic address on file.') ?></p>
                        <p class="flex items-center space-x-1.5 text-slate-600">
                            <i data-lucide="phone" class="w-3.5 h-3.5 text-slate-400"></i>
                            <span><?= htmlspecialchars($br['contact_number'] ?: '+63 917 111 2233') ?></span>
                        </p>
                        <div class="pt-2 border-t border-slate-100 text-slate-500 flex justify-between items-center">
                            <span>Regular Operating Hours:</span>
                            <span class="font-semibold text-slate-900">Mon - Sat: 9:00 AM – 6:00 PM</span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Online Appointment Booking Form (Directly on Landing Page) -->
    <section id="booking-form" class="py-16 bg-slate-100/70 border-b border-slate-200">
        <div class="max-w-4xl mx-auto px-4">
            <div class="text-center mb-8">
                <span class="text-xs uppercase font-bold tracking-wider text-slate-500 block mb-1">Direct Scheduling</span>
                <h3 class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight">Request an Appointment Online</h3>
                <p class="text-xs text-slate-500 mt-1 max-w-lg mx-auto">
                    Fill out the form below with your preferred schedule and dental concern. Our reception desk will review availability and confirm your booking right away.
                </p>
            </div>

            <?php if ($portalError = Session::flash('portal_error')): ?>
                <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold flex items-center space-x-2 shadow-2xs">
                    <i data-lucide="alert-circle" class="w-4 h-4 text-rose-500 flex-shrink-0"></i>
                    <span><?= htmlspecialchars($portalError) ?></span>
                </div>
            <?php endif; ?>

            <div class="bg-white rounded-3xl p-6 md:p-10 border border-slate-200 shadow-md text-slate-800">
                <form action="<?= BASE_URL ?>/index.php?page=portal_submit" method="POST" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Patient Full Name -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                                Your Full Name <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="name" required placeholder="e.g. Maria Santos"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-slate-900 transition">
                        </div>

                        <!-- Phone Number -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                                Contact Phone Number <span class="text-rose-500">*</span>
                            </label>
                            <input type="tel" name="phone" required placeholder="e.g. 0917 123 4567"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-slate-900 transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Preferred Date -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                                Preferred Date <span class="text-rose-500">*</span>
                            </label>
                            <input type="date" name="preferred_date" required min="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d', strtotime('+1 day')) ?>"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-slate-900 transition">
                        </div>

                        <!-- Preferred Time Slot -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                                Preferred Time <span class="text-rose-500">*</span>
                            </label>
                            <select name="preferred_time" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-slate-900 transition">
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

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Branch Selection -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                                Clinic Branch
                            </label>
                            <select name="branch_id" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-slate-900 transition">
                                <?php foreach ($branches as $branch): ?>
                                    <option value="<?= $branch['id'] ?>">
                                        <?= htmlspecialchars($branch['branch_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Preferred Dentist Selection -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                                Preferred Dentist (Optional)
                            </label>
                            <select name="preferred_dentist_id" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-slate-900 transition">
                                <option value="">Any Available Attending Dentist</option>
                                <?php foreach ($dentists as $d): ?>
                                    <option value="<?= $d['dentist_id'] ?>">
                                        <?= htmlspecialchars($d['full_name']) ?> (<?= htmlspecialchars($d['specialization']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Dental Concern (Required by Interview Spec) -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                            Dental Concern / Reason for Visit <span class="text-rose-500">*</span>
                        </label>
                        <textarea name="dental_concern" id="inpDentalConcern" rows="3" required placeholder="Please describe what you are experiencing (e.g. Toothache, routine dental cleaning, filling replacement, wisdom tooth extraction, braces consultation)..."
                                  class="w-full p-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-slate-900 transition"></textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit" class="w-full py-3.5 px-6 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-2xl shadow-xs transition flex items-center justify-center space-x-2 text-sm">
                            <i data-lucide="check-circle" class="w-4 h-4"></i>
                            <span>Submit Appointment Request</span>
                        </button>
                        <p class="text-center text-[11px] text-slate-500 mt-2.5">
                            No upfront payment required. Our front desk staff will verify clinic availability and contact you directly.
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Clinic Footer with Logo -->
    <footer class="bg-white text-slate-500 text-xs py-12 border-t border-slate-200">
        <div class="max-w-6xl mx-auto px-4 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center space-x-3.5">
                <div class="w-10 h-10 rounded-xl bg-slate-900 flex items-center justify-center p-1.5 shadow-xs flex-shrink-0">
                    <img src="<?= BASE_URL ?>/assets/images/logo.png" alt="SDC Logo" class="w-full h-full object-contain">
                </div>
                <div>
                    <p class="font-extrabold text-slate-900 text-sm tracking-tight"><?= APP_NAME ?></p>
                    <p class="text-[11px] text-slate-500 mt-0.5">Professional Healthcare & Clinical Dental Services</p>
                    <p class="text-[11px] text-slate-400 mt-1">&copy; <?= date('Y') ?> <?= APP_NAME ?>. All rights reserved.</p>
                </div>
            </div>

            <div class="flex items-center space-x-6 text-slate-600 font-medium">
                <a href="#services" class="hover:text-slate-900 transition">Services</a>
                <a href="#standards" class="hover:text-slate-900 transition">Clinical Standards</a>
                <a href="#dentists" class="hover:text-slate-900 transition">Dentists</a>
                <a href="#booking-form" class="hover:text-slate-900 transition">Book Visit</a>
                <a href="<?= BASE_URL ?>/index.php?page=login" class="text-slate-900 hover:underline font-bold transition">Staff Login &rarr;</a>
            </div>
        </div>
    </footer>

    <script>
        lucide.createIcons();

        function prefillConcern(serviceName) {
            const el = document.getElementById('inpDentalConcern');
            if (el) {
                el.value = 'Inquiry / Treatment request for: ' + serviceName;
                el.focus();
            }
        }
    </script>
</body>
</html>
