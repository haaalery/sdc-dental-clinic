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

    <!-- Google Fonts: Plus Jakarta Sans & Playfair Display -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,400;1,600&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-serif { font-family: 'Playfair Display', Georgia, serif; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        
        /* Clean Architectural Vertical Strip Accordion (Stationary, Crisp) */
        .gallery-strip {
            transition: width 0.75s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.4s ease, z-index 0.2s ease;
            flex-shrink: 0;
            will-change: width;
        }
        .gallery-strip:hover {
            z-index: 30;
            box-shadow: 0 35px 80px -20px rgba(0, 0, 0, 0.95);
        }

        /* Smooth Upward Click Glide Animation */
        @keyframes smoothGlideUp {
            0% {
                transform: translateY(0);
            }
            35% {
                transform: translateY(-8px);
            }
            70% {
                transform: translateY(-2px);
            }
            100% {
                transform: translateY(0);
            }
        }
        .animate-glide-up {
            animation: smoothGlideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
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
            <!-- Brand with Official Logo (Smooth Upward Animation & Scroll to Top on Click) -->
            <a href="<?= BASE_URL ?>/index.php" 
               id="brandLogoLink" 
               onclick="handleBrandClick(event)" 
               class="flex items-center space-x-3 group cursor-pointer transition-transform duration-300 ease-out hover:-translate-y-0.5 select-none"
               title="Return to top">
                <div class="w-10 h-10 rounded-xl bg-slate-900 flex items-center justify-center p-1.5 shadow-xs flex-shrink-0 transition-transform duration-300 ease-out group-hover:scale-105 group-hover:-translate-y-0.5">
                    <img src="<?= BASE_URL ?>/assets/images/logo.png" alt="SDC Dental Care" class="w-full h-full object-contain">
                </div>
                <div class="transition-transform duration-300 ease-out group-hover:-translate-y-0.5">
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

    <!-- Hero Section (Awwwards Editorial Aesthetic) -->
    <section class="bg-slate-50 border-b border-slate-200 py-20 md:py-28 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
                
                <!-- Left Editorial Headline -->
                <div class="lg:col-span-7">
                    <div class="inline-flex items-center space-x-2 text-[11px] font-bold tracking-[0.2em] uppercase text-slate-500 mb-6">
                        <span class="w-1.5 h-1.5 rounded-full bg-slate-900"></span>
                        <span>Makati & Pasig Suites · Clinical Dental Care</span>
                    </div>

                    <h2 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-slate-900 tracking-tight leading-[1.1]">
                        Modern dentistry, <br>
                        <span class="font-serif italic font-normal text-slate-700">thoughtfully practiced.</span>
                    </h2>

                    <p class="text-slate-600 text-base sm:text-lg mt-6 leading-relaxed max-w-xl font-normal">
                        A calm, unhurried clinical environment where preventive oral healthcare meets specialized restorative and aesthetic dentistry. Clear treatment plans, transparent pricing, and attentive attending doctors.
                    </p>

                    <!-- Editorial Practice Metrics (No Clutter, Clean Editorial Design) -->
                    <div class="grid grid-cols-3 gap-6 pt-8 mt-8 border-t border-slate-200/80 text-xs">
                        <div>
                            <span class="font-mono text-slate-400 block text-[10px] tracking-widest uppercase mb-1">01 / Doctors</span>
                            <span class="font-bold text-slate-900 text-sm block">Licensed MDs</span>
                            <span class="text-slate-500 text-[11px]">Direct clinician care</span>
                        </div>
                        <div>
                            <span class="font-mono text-slate-400 block text-[10px] tracking-widest uppercase mb-1">02 / Integrity</span>
                            <span class="font-bold text-slate-900 text-sm block">Transparent</span>
                            <span class="text-slate-500 text-[11px]">Itemized POS receipts</span>
                        </div>
                        <div>
                            <span class="font-mono text-slate-400 block text-[10px] tracking-widest uppercase mb-1">03 / Locations</span>
                            <span class="font-bold text-slate-900 text-sm block">2 Branches</span>
                            <span class="text-slate-500 text-[11px]">Makati & Pasig</span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-10 flex flex-wrap items-center gap-4">
                        <a href="#booking-form" class="px-7 py-4 bg-slate-900 hover:bg-black text-white font-semibold text-xs tracking-wider uppercase rounded-full shadow-md transition inline-flex items-center space-x-2">
                            <span>Reserve a Visit</span>
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </a>
                        <a href="#services" class="px-7 py-4 bg-white hover:bg-slate-100 text-slate-800 font-semibold text-xs tracking-wider uppercase rounded-full border border-slate-300 shadow-xs transition">
                            View Treatments
                        </a>
                    </div>
                </div>

                <!-- Right Visual: Architectural Editorial Photography Frame -->
                <div class="lg:col-span-5">
                    <div class="relative mx-auto max-w-md lg:max-w-none">
                        <div class="rounded-3xl overflow-hidden shadow-2xl border border-slate-200/80 aspect-[4/5] relative bg-slate-900 group">
                            <img src="https://images.unsplash.com/photo-1629909613654-28e377c37b09?auto=format&fit=crop&w=1200&q=85" 
                                 alt="SDC Dental Clinical Suite" 
                                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105 filter brightness-95">
                            
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent flex flex-col justify-end p-8 text-white">
                                <div class="flex items-center space-x-3 mb-2">
                                    <div class="w-8 h-8 rounded-lg bg-white/10 backdrop-blur-md p-1 border border-white/20">
                                        <img src="<?= BASE_URL ?>/assets/images/logo.png" alt="SDC" class="w-full h-full object-contain">
                                    </div>
                                    <span class="text-xs uppercase tracking-widest font-bold text-teal-300">SDC Dental Care</span>
                                </div>
                                <p class="text-xs text-slate-300 leading-relaxed font-light">
                                    State-of-the-art diagnostic imaging, surgical sterilization, and comfortable patient suites in Metro Manila.
                                </p>
                            </div>
                        </div>

                        <!-- Minimalist Floating Badge -->
                        <div class="absolute -bottom-5 -left-5 bg-white p-4 rounded-2xl shadow-xl border border-slate-200 hidden sm:flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-xl bg-teal-50 flex items-center justify-center text-teal-700">
                                <i data-lucide="check" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-900">Hospital-Grade Standards</p>
                                <p class="text-[11px] text-slate-500">Class-B steam autoclave</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Services & Treatments Section (Awwwards Contiguous Diagonal Slices) -->
    <?php
    $trtPhotos = [
        'TRT-001' => 'https://images.unsplash.com/photo-1606811841689-23dfddce3e95?auto=format&fit=crop&w=900&q=80',
        'TRT-002' => 'https://images.unsplash.com/photo-1579684385127-1ef15d508118?auto=format&fit=crop&w=900&q=80',
        'TRT-003' => 'https://images.unsplash.com/photo-1598256989800-fe5f95da9787?auto=format&fit=crop&w=900&q=80',
        'TRT-004' => 'https://images.unsplash.com/photo-1588776814546-daab30f310ce?auto=format&fit=crop&w=900&q=80',
        'TRT-005' => 'https://images.unsplash.com/photo-1551076805-e1869033e561?auto=format&fit=crop&w=900&q=80',
        'TRT-006' => 'https://images.unsplash.com/photo-1629909615184-74f495363b67?auto=format&fit=crop&w=900&q=80',
        'TRT-007' => 'https://images.unsplash.com/photo-1629909613654-28e377c37b09?auto=format&fit=crop&w=900&q=80',
        'TRT-008' => 'https://images.unsplash.com/photo-1588776814546-1ffcf47267a5?auto=format&fit=crop&w=900&q=80',
        'TRT-009' => 'https://images.unsplash.com/photo-1598256989800-fe5f95da9787?auto=format&fit=crop&w=900&q=80',
    ];

    // Real human clinical details for each procedure
    $trtDetails = [
        'TRT-001' => ['duration' => '30 mins', 'comfort' => 'Non-Invasive', 'inclusions' => 'Visual Exam & Oral Roadmap'],
        'TRT-002' => ['duration' => '45 mins', 'comfort' => 'Gentle Scaling', 'inclusions' => 'Ultrasonic Cleansing & Polish'],
        'TRT-003' => ['duration' => '60 mins', 'comfort' => 'Local Comfort', 'inclusions' => 'Subgingival Root Planing'],
        'TRT-004' => ['duration' => '40 mins', 'comfort' => 'Shade-Matched', 'inclusions' => 'Sculpted Composite Resin'],
        'TRT-005' => ['duration' => '35 mins', 'comfort' => 'Local Anesthesia', 'inclusions' => 'Simple Non-Surgical Extraction'],
        'TRT-006' => ['duration' => '60–90 mins', 'comfort' => 'Surgical Care', 'inclusions' => 'Odontectomy & Fine Suturing'],
        'TRT-007' => ['duration' => '75 mins', 'comfort' => 'Tooth-Saving', 'inclusions' => 'Canal Debridement & Sealing'],
        'TRT-008' => ['duration' => 'Monthly Care', 'comfort' => 'Custom Alignment', 'inclusions' => 'Banded Brackets & Adjustments'],
        'TRT-009' => ['duration' => '60 mins', 'comfort' => 'Enamel-Safe', 'inclusions' => 'Blue LED Laser Whitening System'],
    ];
    ?>
    <!-- Services & Treatments Section (Awwwards Horizontal Slideshow + Exhibition Modal) -->
    <?php
    // Multi-angle / procedure gallery for each specific treatment
    $trtGalleries = [
        'TRT-001' => [
            'https://images.unsplash.com/photo-1606811841689-23dfddce3e95?auto=format&fit=crop&w=1200&q=85',
            'https://images.unsplash.com/photo-1588776814546-daab30f310ce?auto=format&fit=crop&w=1200&q=85',
            'https://images.unsplash.com/photo-1629909615184-74f495363b67?auto=format&fit=crop&w=1200&q=85',
            'https://images.unsplash.com/photo-1629909613654-28e377c37b09?auto=format&fit=crop&w=1200&q=85'
        ],
        'TRT-002' => [
            'https://images.unsplash.com/photo-1579684385127-1ef15d508118?auto=format&fit=crop&w=1200&q=85',
            'https://images.unsplash.com/photo-1606811841689-23dfddce3e95?auto=format&fit=crop&w=1200&q=85',
            'https://images.unsplash.com/photo-1588776814546-1ffcf47267a5?auto=format&fit=crop&w=1200&q=85',
            'https://images.unsplash.com/photo-1598256989800-fe5f95da9787?auto=format&fit=crop&w=1200&q=85'
        ],
        'TRT-003' => [
            'https://images.unsplash.com/photo-1598256989800-fe5f95da9787?auto=format&fit=crop&w=1200&q=85',
            'https://images.unsplash.com/photo-1579684385127-1ef15d508118?auto=format&fit=crop&w=1200&q=85',
            'https://images.unsplash.com/photo-1629909613654-28e377c37b09?auto=format&fit=crop&w=1200&q=85',
            'https://images.unsplash.com/photo-1588776814546-1ffcf47267a5?auto=format&fit=crop&w=1200&q=85'
        ],
        'TRT-004' => [
            'https://images.unsplash.com/photo-1588776814546-daab30f310ce?auto=format&fit=crop&w=1200&q=85',
            'https://images.unsplash.com/photo-1606811841689-23dfddce3e95?auto=format&fit=crop&w=1200&q=85',
            'https://images.unsplash.com/photo-1588776814546-1ffcf47267a5?auto=format&fit=crop&w=1200&q=85',
            'https://images.unsplash.com/photo-1629909615184-74f495363b67?auto=format&fit=crop&w=1200&q=85'
        ],
        'TRT-005' => [
            'https://images.unsplash.com/photo-1551076805-e1869033e561?auto=format&fit=crop&w=1200&q=85',
            'https://images.unsplash.com/photo-1629909615184-74f495363b67?auto=format&fit=crop&w=1200&q=85',
            'https://images.unsplash.com/photo-1606811841689-23dfddce3e95?auto=format&fit=crop&w=1200&q=85',
            'https://images.unsplash.com/photo-1588776814546-daab30f310ce?auto=format&fit=crop&w=1200&q=85'
        ],
        'TRT-006' => [
            'https://images.unsplash.com/photo-1629909615184-74f495363b67?auto=format&fit=crop&w=1200&q=85',
            'https://images.unsplash.com/photo-1551076805-e1869033e561?auto=format&fit=crop&w=1200&q=85',
            'https://images.unsplash.com/photo-1629909613654-28e377c37b09?auto=format&fit=crop&w=1200&q=85',
            'https://images.unsplash.com/photo-1606811841689-23dfddce3e95?auto=format&fit=crop&w=1200&q=85'
        ],
        'TRT-007' => [
            'https://images.unsplash.com/photo-1629909613654-28e377c37b09?auto=format&fit=crop&w=1200&q=85',
            'https://images.unsplash.com/photo-1588776814546-daab30f310ce?auto=format&fit=crop&w=1200&q=85',
            'https://images.unsplash.com/photo-1606811841689-23dfddce3e95?auto=format&fit=crop&w=1200&q=85',
            'https://images.unsplash.com/photo-1588776814546-1ffcf47267a5?auto=format&fit=crop&w=1200&q=85'
        ],
        'TRT-008' => [
            'https://images.unsplash.com/photo-1588776814546-1ffcf47267a5?auto=format&fit=crop&w=1200&q=85',
            'https://images.unsplash.com/photo-1598256989800-fe5f95da9787?auto=format&fit=crop&w=1200&q=85',
            'https://images.unsplash.com/photo-1606811841689-23dfddce3e95?auto=format&fit=crop&w=1200&q=85',
            'https://images.unsplash.com/photo-1588776814546-daab30f310ce?auto=format&fit=crop&w=1200&q=85'
        ],
        'TRT-009' => [
            'https://images.unsplash.com/photo-1598256989800-fe5f95da9787?auto=format&fit=crop&w=1200&q=85',
            'https://images.unsplash.com/photo-1588776814546-1ffcf47267a5?auto=format&fit=crop&w=1200&q=85',
            'https://images.unsplash.com/photo-1606811841689-23dfddce3e95?auto=format&fit=crop&w=1200&q=85',
            'https://images.unsplash.com/photo-1579684385127-1ef15d508118?auto=format&fit=crop&w=1200&q=85'
        ],
    ];

    $treatmentsData = [];
    foreach ($treatments as $idx => $t) {
        $code = $t['treatment_code'];
        $gallery = $trtGalleries[$code] ?? [$trtPhotos[$code] ?? 'https://images.unsplash.com/photo-1629909615184-74f495363b67?auto=format&fit=crop&w=1200&q=85'];
        $treatmentsData[] = [
            'id' => $t['id'],
            'index' => str_pad($idx + 1, 2, '0', STR_PAD_LEFT),
            'code' => $code,
            'name' => $t['treatment_name'],
            'category' => $t['category'],
            'price' => APP_CURRENCY . number_format($t['standard_price'], 2),
            'description' => $t['description'] ?: 'Comprehensive procedure conducted by certified attending dental clinicians.',
            'image' => $gallery[0],
            'gallery' => $gallery,
            'duration' => $trtDetails[$code]['duration'] ?? '45 mins',
            'comfort' => $trtDetails[$code]['comfort'] ?? 'Clinical Grade',
            'inclusions' => $trtDetails[$code]['inclusions'] ?? 'Consultation Included'
        ];
    }
    ?>
    <section id="services" class="py-24 bg-[#0a0f18] text-white overflow-hidden border-b border-slate-800 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-10">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div>
                    <span class="text-[11px] uppercase tracking-[0.25em] font-semibold text-teal-400 block mb-2">
                        Clinical Catalog
                    </span>
                    <h3 class="text-3xl sm:text-5xl font-extrabold text-white tracking-tight">
                        Signature Treatments<span class="font-serif italic font-normal text-slate-400">.</span>
                    </h3>
                    <p class="text-slate-400 text-xs sm:text-sm mt-3 max-w-xl leading-relaxed">
                        Nine focused dental disciplines across our Makati and Pasig clinics. Hover across each slice to expand, or click any picture to open the full procedure lookbook.
                    </p>
                </div>

                <!-- Minimalist Navigation Controls -->
                <div class="flex items-center space-x-5">
                    <span class="hidden sm:inline-block text-[11px] uppercase tracking-widest text-slate-500 font-mono">
                        [ 01 — 09 ]
                    </span>
                    <div class="flex items-center space-x-2">
                        <button id="sliderPrevBtn" aria-label="Previous treatment" class="w-12 h-12 rounded-full border border-white/20 hover:border-white hover:bg-white hover:text-slate-950 text-white flex items-center justify-center transition shadow-lg">
                            <i data-lucide="chevron-left" class="w-5 h-5"></i>
                        </button>
                        <button id="sliderNextBtn" aria-label="Next treatment" class="w-12 h-12 rounded-full border border-white/20 hover:border-white hover:bg-white hover:text-slate-950 text-white flex items-center justify-center transition shadow-lg">
                            <i data-lucide="chevron-right" class="w-5 h-5"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Seamless Edge-to-Edge Straight Slideshow (Expands on Hover, Clicks to Open Modal) -->
        <div class="w-full relative overflow-hidden py-4">
            <div class="absolute left-0 top-0 bottom-0 w-16 bg-gradient-to-r from-[#0a0f18] to-transparent z-20 pointer-events-none"></div>
            <div class="absolute right-0 top-0 bottom-0 w-16 bg-gradient-to-l from-[#0a0f18] to-transparent z-20 pointer-events-none"></div>

            <div id="servicesTrack" class="flex items-stretch space-x-0 py-6 px-8 sm:px-16 overflow-x-auto no-scrollbar scroll-smooth cursor-grab active:cursor-grabbing select-none">
                <?php foreach ($treatmentsData as $idx => $trt): ?>
                    <!-- Pure Photographic Strip (Straight, Stationary, Zero Text) -->
                    <button type="button" 
                            onclick="openProcedureModal(<?= $idx ?>)"
                            title="Click to view full details for <?= htmlspecialchars($trt['name']) ?>"
                            class="gallery-strip group relative h-[590px] w-[170px] sm:w-[200px] md:w-[220px] hover:w-[580px] sm:hover:w-[720px] md:hover:w-[840px] -ml-[1px] first:ml-0 overflow-hidden border-r border-t border-b border-white/10 first:border-l bg-slate-950 block cursor-pointer text-left focus:outline-none">
                        
                        <div class="gallery-strip-inner absolute inset-0 w-full h-full">
                            <img src="<?= htmlspecialchars($trt['image']) ?>" 
                                 alt="<?= htmlspecialchars($trt['name']) ?>" 
                                 class="absolute inset-0 w-full h-full object-cover">
                        </div>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Editorial Procedure Exhibition Modal (Only Details & Photos of THAT Selected Procedure) -->
    <div id="procedureModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-md p-3 sm:p-6 md:p-8 hidden opacity-0 transition-opacity duration-300">
        
        <!-- Modal Container (Warm Linen Luxury Aesthetic from Reference Screenshot) -->
        <div class="bg-[#f4efe8] text-stone-900 rounded-3xl overflow-hidden shadow-2xl max-w-6xl w-full max-h-[92vh] flex flex-col border border-stone-300 transform scale-95 transition-transform duration-300" id="modalContainer">
            
            <!-- Top Minimalist Catalogue Bar -->
            <div class="flex items-center justify-between border-b border-stone-300/80 px-6 sm:px-8 py-4 text-[11px] uppercase tracking-[0.22em] font-mono text-stone-500 bg-[#f4efe8]">
                <span id="modalTopCode">PROCEDURE // SPECIFICATION</span>
                <span class="font-serif italic font-normal text-stone-800 text-sm normal-case hidden sm:inline">SDC Dental Atelier</span>
                <div class="flex items-center space-x-4">
                    <span id="modalActiveCounter" class="font-semibold text-stone-800">VIEW 01 / 04</span>
                    <button type="button" onclick="closeProcedureModal()" class="text-stone-500 hover:text-stone-950 font-bold uppercase tracking-wider text-xs flex items-center space-x-1 transition">
                        <span>Close [✕]</span>
                    </button>
                </div>
            </div>

            <!-- Middle Stage: Left Big Picture / Right Large Editorial Headline & Details -->
            <div class="flex-1 overflow-y-auto no-scrollbar p-6 sm:p-10">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                    
                    <!-- Left: Big Picture Display Frame (Swaps when clicking thumbnails below) -->
                    <div class="lg:col-span-5 relative">
                        <div class="relative w-full aspect-[3/4] sm:h-[460px] rounded-2xl overflow-hidden shadow-xl bg-stone-900 border border-stone-300/80 group">
                            <img id="modalBigImage" 
                                 src="" 
                                 alt="" 
                                 class="w-full h-full object-cover transition-all duration-500 ease-out filter brightness-95">
                        </div>
                    </div>

                    <!-- Right: Big Editorial Headline & Clinical Info for THAT Procedure -->
                    <div class="lg:col-span-7 flex flex-col justify-center">
                        <div class="mb-3">
                            <span id="modalCategory" class="text-xs uppercase tracking-[0.25em] font-mono text-stone-500 block mb-2">
                                01 / COSMETIC DENTISTRY
                            </span>
                            
                            <!-- Large High-Fashion Headline (Le Oh Botanic style) -->
                            <h3 id="modalTitle" class="text-3xl sm:text-5xl lg:text-6xl font-serif font-bold text-stone-950 tracking-tight leading-[1.08]">
                                Laser Teeth Whitening
                            </h3>
                        </div>

                        <!-- Upfront Fee -->
                        <div class="flex items-baseline space-x-3 my-4">
                            <span id="modalPrice" class="text-3xl sm:text-4xl font-mono font-bold text-stone-950 tracking-tight">
                                ₱6,500.00
                            </span>
                            <span class="text-xs font-mono text-stone-500">standard clinical rate</span>
                        </div>

                        <!-- Clinical Overview Description -->
                        <p id="modalDescription" class="text-stone-700 text-sm sm:text-base leading-relaxed max-w-xl font-light mb-6">
                            Professional in-clinic clinical bleaching engineered to gently lift deep enamel discoloration by up to 6–8 shades in a single sitting.
                        </p>

                        <!-- Editorial Specifications Bar -->
                        <div class="grid grid-cols-3 gap-3 border-t border-b border-stone-300/80 py-4 mb-2 text-xs max-w-xl">
                            <div>
                                <span class="block text-[9px] uppercase tracking-widest text-stone-400 mb-0.5">Session</span>
                                <span id="modalDuration" class="font-bold text-stone-900 text-sm">60 Mins</span>
                            </div>
                            <div>
                                <span class="block text-[9px] uppercase tracking-widest text-stone-400 mb-0.5">Comfort</span>
                                <span id="modalComfort" class="font-bold text-stone-900 text-sm">Enamel-Safe</span>
                            </div>
                            <div>
                                <span class="block text-[9px] uppercase tracking-widest text-stone-400 mb-0.5">Scope</span>
                                <span id="modalInclusions" class="font-bold text-stone-900 text-sm truncate block">Blue LED Laser</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bottom: Gallery of THIS Specific Procedure (Clicking transfers to the Big Picture) -->
                <div class="mt-8 pt-6 border-t border-stone-300">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[11px] uppercase tracking-widest font-mono text-stone-500">
                            Procedure Gallery & Clinical Angles (Click thumbnail to transfer into big picture):
                        </span>
                        <span class="text-xs text-stone-400 hidden sm:inline font-mono">
                            Multiple Angles
                        </span>
                    </div>

                    <!-- Filmstrip of Photos for THIS Procedure Only -->
                    <div id="modalGalleryFilmstrip" class="flex items-center space-x-3 sm:space-x-4 overflow-x-auto no-scrollbar py-2 select-none">
                        <!-- Populated dynamically via JavaScript with photos of the selected procedure -->
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        const allTreatments = <?= json_encode($treatmentsData) ?>;
        let currentTreatment = null;

        // Open Modal and display ONLY that procedure's details and photos
        function openProcedureModal(index) {
            const t = allTreatments[index];
            if (!t) return;
            currentTreatment = t;

            // Populate text details
            const title = document.getElementById('modalTitle');
            const category = document.getElementById('modalCategory');
            const topCode = document.getElementById('modalTopCode');
            const price = document.getElementById('modalPrice');
            const desc = document.getElementById('modalDescription');
            const duration = document.getElementById('modalDuration');
            const comfort = document.getElementById('modalComfort');
            const inclusions = document.getElementById('modalInclusions');

            if (title) title.innerText = t.name;
            if (category) category.innerText = `${t.index} / ${t.category.toUpperCase()}`;
            if (topCode) topCode.innerText = `${t.code} // ${t.category.toUpperCase()}`;
            if (price) price.innerText = t.price;
            if (desc) desc.innerText = t.description;
            if (duration) duration.innerText = t.duration;
            if (comfort) comfort.innerText = t.comfort;
            if (inclusions) inclusions.innerText = t.inclusions;

            // Set the big image to the first photo of this procedure
            setBigImage(t.gallery[0], 0, t.gallery.length);

            // Populate the bottom filmstrip with ONLY photos of this procedure
            const filmstrip = document.getElementById('modalGalleryFilmstrip');
            filmstrip.innerHTML = '';

            t.gallery.forEach((photoUrl, pIdx) => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = `modal-photo-thumb group relative h-28 sm:h-32 w-20 sm:w-24 rounded-xl overflow-hidden border-2 ${pIdx === 0 ? 'border-stone-950 ring-2 ring-stone-950/20' : 'border-stone-300/80 hover:border-stone-800'} transition-all flex-shrink-0 cursor-pointer shadow-sm`;
                btn.innerHTML = `
                    <img src="${photoUrl}" alt="${t.name} view ${pIdx + 1}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                `;
                btn.onclick = () => {
                    setBigImage(photoUrl, pIdx, t.gallery.length);
                    highlightPhotoThumb(pIdx);
                };
                btn.onmouseenter = () => {
                    setBigImage(photoUrl, pIdx, t.gallery.length);
                    highlightPhotoThumb(pIdx);
                };
                filmstrip.appendChild(btn);
            });

            // Show modal
            const modal = document.getElementById('procedureModal');
            const container = document.getElementById('modalContainer');
            if (modal && container) {
                modal.classList.remove('hidden');
                setTimeout(() => {
                    modal.classList.remove('opacity-0');
                    container.classList.remove('scale-95');
                    container.classList.add('scale-100');
                }, 10);
                document.body.style.overflow = 'hidden';
            }

            if (window.lucide) {
                lucide.createIcons();
            }
        }

        // Transfer clicked/hovered photo into the Big Picture frame
        function setBigImage(url, index, total) {
            const bigImg = document.getElementById('modalBigImage');
            const counter = document.getElementById('modalActiveCounter');
            if (bigImg) {
                bigImg.style.opacity = '0.35';
                setTimeout(() => {
                    bigImg.src = url;
                    bigImg.style.opacity = '1';
                }, 120);
            }
            if (counter) {
                counter.innerText = `VIEW 0${index + 1} / 0${total}`;
            }
        }

        function highlightPhotoThumb(index) {
            const thumbs = document.querySelectorAll('.modal-photo-thumb');
            thumbs.forEach((th, i) => {
                if (i === index) {
                    th.classList.add('border-stone-950', 'ring-2', 'ring-stone-950/20');
                    th.classList.remove('border-stone-300/80');
                } else {
                    th.classList.remove('border-stone-950', 'ring-2', 'ring-stone-950/20');
                    th.classList.add('border-stone-300/80');
                }
            });
        }

        // Close Modal
        function closeProcedureModal() {
            const modal = document.getElementById('procedureModal');
            const container = document.getElementById('modalContainer');
            if (modal && container) {
                modal.classList.add('opacity-0');
                container.classList.remove('scale-100');
                container.classList.add('scale-95');
                setTimeout(() => {
                    modal.classList.add('hidden');
                    document.body.style.overflow = '';
                }, 300);
            }
        }

        // Close on ESC key or clicking outside container
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeProcedureModal();
        });

        document.getElementById('procedureModal')?.addEventListener('click', (e) => {
            if (e.target === document.getElementById('procedureModal')) {
                closeProcedureModal();
            }
        });

        // Slideshow Navigation (Prev/Next buttons, wheel, drag)
        document.addEventListener('DOMContentLoaded', function() {
            const track = document.getElementById('servicesTrack');
            const prevBtn = document.getElementById('sliderPrevBtn');
            const nextBtn = document.getElementById('sliderNextBtn');
            
            if (track) {
                const scrollStep = 380;
                if (prevBtn) {
                    prevBtn.addEventListener('click', function() {
                        track.scrollBy({ left: -scrollStep, behavior: 'smooth' });
                    });
                }
                if (nextBtn) {
                    nextBtn.addEventListener('click', function() {
                        track.scrollBy({ left: scrollStep, behavior: 'smooth' });
                    });
                }

                track.addEventListener('wheel', (e) => {
                    if (e.deltaY !== 0) {
                        e.preventDefault();
                        track.scrollBy({ left: e.deltaY * 1.5, behavior: 'auto' });
                    }
                }, { passive: false });

                let isDown = false;
                let startX;
                let scrollLeft;

                track.addEventListener('mousedown', (e) => {
                    isDown = true;
                    startX = e.pageX - track.offsetLeft;
                    scrollLeft = track.scrollLeft;
                });
                track.addEventListener('mouseleave', () => { isDown = false; });
                track.addEventListener('mouseup', () => { isDown = false; });
                track.addEventListener('mousemove', (e) => {
                    if (!isDown) return;
                    e.preventDefault();
                    const x = e.pageX - track.offsetLeft;
                    const walk = (x - startX) * 1.6;
                    track.scrollLeft = scrollLeft - walk;
                });
            }
        });
    </script>

    <!-- Clinical Standards & What to Expect (Refined Editorial Layout) -->
    <section id="standards" class="py-24 bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-xl mb-14">
                <span class="text-[11px] uppercase tracking-[0.25em] font-semibold text-slate-400 block mb-2">Practice Standard</span>
                <h3 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                    Our Clinical Principles<span class="font-serif italic font-normal text-slate-500">.</span>
                </h3>
                <p class="text-sm text-slate-500 mt-3 leading-relaxed">
                    Conservative diagnostics, hospital-grade instrument reprocessing, and an ethical doctor-patient relationship.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Principle 01 -->
                <div class="p-8 rounded-3xl bg-slate-50 border border-slate-200/80 flex flex-col justify-between hover:border-slate-300 transition">
                    <div>
                        <span class="font-mono text-xs font-bold text-slate-400 tracking-widest uppercase block mb-6">01 / STERILIZATION</span>
                        <h4 class="text-lg font-bold text-slate-900 mb-3">Class-B Steam Autoclave</h4>
                        <p class="text-xs text-slate-600 leading-relaxed font-normal">
                            All surgical handpieces, burs, and diagnostic sets undergo enzymatic ultrasonic cleaning and Class-B vacuum autoclave steam sterilization, sealed in individual medical indicator pouches.
                        </p>
                    </div>
                    <div class="pt-6 mt-6 border-t border-slate-200/80 flex items-center space-x-2 text-[11px] text-slate-500 font-medium">
                        <i data-lucide="shield-check" class="w-4 h-4 text-teal-600"></i>
                        <span>Zero reuse on single-use items</span>
                    </div>
                </div>

                <!-- Principle 02 -->
                <div class="p-8 rounded-3xl bg-slate-50 border border-slate-200/80 flex flex-col justify-between hover:border-slate-300 transition">
                    <div>
                        <span class="font-mono text-xs font-bold text-slate-400 tracking-widest uppercase block mb-6">02 / DIAGNOSIS</span>
                        <h4 class="text-lg font-bold text-slate-900 mb-3">Conservative Treatment Planning</h4>
                        <p class="text-xs text-slate-600 leading-relaxed font-normal">
                            We advocate tooth preservation first. Every finding is backed by clinical diagnostics and detailed explanation before any procedure begins—no forced or unnecessary dental work.
                        </p>
                    </div>
                    <div class="pt-6 mt-6 border-t border-slate-200/80 flex items-center space-x-2 text-[11px] text-slate-500 font-medium">
                        <i data-lucide="user-check" class="w-4 h-4 text-teal-600"></i>
                        <span>Attending clinician consultation</span>
                    </div>
                </div>

                <!-- Principle 03 -->
                <div class="p-8 rounded-3xl bg-slate-50 border border-slate-200/80 flex flex-col justify-between hover:border-slate-300 transition">
                    <div>
                        <span class="font-mono text-xs font-bold text-slate-400 tracking-widest uppercase block mb-6">03 / BILLING</span>
                        <h4 class="text-lg font-bold text-slate-900 mb-3">Transparent Itemized Receipts</h4>
                        <p class="text-xs text-slate-600 leading-relaxed font-normal">
                            You receive a complete itemized POS breakdown before leaving the chair. Payments accepted via Cash, GCash, Maya, and major debit/credit cards with zero arbitrary surcharges.
                        </p>
                    </div>
                    <div class="pt-6 mt-6 border-t border-slate-200/80 flex items-center space-x-2 text-[11px] text-slate-500 font-medium">
                        <i data-lucide="receipt" class="w-4 h-4 text-teal-600"></i>
                        <span>Full fee transparency</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Attending Dentists (Clean Editorial Portrait Cards) -->
    <section id="dentists" class="py-24 bg-slate-50 border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-14">
                <div>
                    <span class="text-[11px] uppercase tracking-[0.25em] font-semibold text-slate-400 block mb-2">Clinical Team</span>
                    <h3 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                        Our Attending Dentists<span class="font-serif italic font-normal text-slate-500">.</span>
                    </h3>
                    <p class="text-sm text-slate-500 mt-2">Licensed practitioners leading patient consultations and surgical procedures.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <?php foreach ($dentists as $doc): ?>
                    <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-xs flex flex-col sm:flex-row items-start sm:items-center space-y-4 sm:space-y-0 sm:space-x-6 hover:shadow-md transition">
                        <div class="w-16 h-16 rounded-2xl bg-slate-900 text-white font-serif font-bold text-xl flex items-center justify-center flex-shrink-0 shadow-sm">
                            Dr
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center space-x-2">
                                <h4 class="font-bold text-slate-900 text-base tracking-tight"><?= htmlspecialchars($doc['full_name']) ?></h4>
                                <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-slate-100 text-slate-600">DMD</span>
                            </div>
                            <p class="text-xs text-slate-600 font-semibold mt-1"><?= htmlspecialchars($doc['specialization'] ?: 'General & Aesthetic Dentistry') ?></p>
                            <?php if (!empty($doc['license_no'])): ?>
                                <p class="text-[11px] text-slate-400 font-mono mt-0.5">PRC License: <?= htmlspecialchars($doc['license_no']) ?></p>
                            <?php endif; ?>
                            <p class="text-xs text-slate-500 mt-3 leading-relaxed">
                                Direct patient assessments, preventive care, complex restorative dentistry, and dental health consultations across Makati and Pasig branches.
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
            <div class="flex items-center space-x-3.5 cursor-pointer group transition-transform duration-300 ease-out hover:-translate-y-0.5 select-none" onclick="handleBrandClick(event)" title="Return to top">
                <div class="w-10 h-10 rounded-xl bg-slate-900 flex items-center justify-center p-1.5 shadow-xs flex-shrink-0 transition-transform duration-300 group-hover:scale-105">
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

        function handleBrandClick(event) {
            if (event) event.preventDefault();

            // Trigger smooth upward micro-animation on the logo element
            const brandLink = document.getElementById('brandLogoLink');
            if (brandLink) {
                brandLink.classList.remove('animate-glide-up');
                void brandLink.offsetWidth; // Force CSS repaint / reflow
                brandLink.classList.add('animate-glide-up');
            }

            // Smoothly glide window to top
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

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
