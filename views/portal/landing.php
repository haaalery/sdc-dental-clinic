<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../config/session.php';
$isLoggedIn = Session::isLoggedIn();
$currentUser = Session::user();
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth scroll-pt-[72px]">

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
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,400;1,600&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .font-serif {
            font-family: 'Playfair Display', Georgia, serif;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Clean Architectural Vertical Strip Accordion (Stationary, Crisp) */
        .gallery-strip {
            transition: width 0.75s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.4s ease, z-index 0.2s ease;
            flex-shrink: 0;
            will-change: width;
        }

        .gallery-strip:hover {
            z-index: 30;
            box-shadow: 0 35px 70px -15px rgba(28, 25, 23, 0.45);
        }

        /* Smooth Infinite Editorial Marquee */
        @keyframes tickerTape {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-50%);
            }
        }

        .ticker-track {
            display: flex;
            width: 200%;
            animation: tickerTape 32s linear infinite;
        }

        .ticker-track:hover {
            animation-play-state: paused;
        }

        /* Interactive Clinical Hotspot Pulse */
        @keyframes pulseGlow {

            0%,
            100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(20, 184, 166, 0.7);
            }

            50% {
                transform: scale(1.1);
                box-shadow: 0 0 0 10px rgba(20, 184, 166, 0);
            }
        }

        .hotspot-pulse {
            animation: pulseGlow 2.8s infinite;
        }

        /* 3D Perspective Container */
        .hero-perspective {
            perspective: 1200px;
        }

        .hero-tilt-card {
            transform-style: preserve-3d;
            transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: transform;
        }
    </style>
</head>

<body class="bg-[#F7F6F2] text-stone-900 antialiased min-h-screen flex flex-col font-sans">

    <!-- Full Hero Section with Navigation Inside (Faithfully matching Image 1 Aesthetic & Image 2 Wireframe Layout, Zero Round Borders) -->
    <section id="heroSection"
        class="relative w-full lg:h-screen min-h-screen bg-white border-b border-[#E5E1D8] flex flex-col justify-between overflow-hidden rounded-none select-none pt-[72px] group">

        <div
            class="absolute right-12 top-20 text-[18vw] font-black text-black/[0.015] pointer-events-none uppercase font-mono tracking-tighter z-0">
            SDC
        </div>

        <!-- 1. NAVIGATION BAR (Solid Opaque White, Sticky/Fixed Top across page, Integrated inside Hero, Image 1 Aesthetic, Zero Round Borders) -->
        <nav id="mainHeroNav"
            class="fixed top-0 left-0 w-full z-40 bg-white border-b border-stone-200 px-6 sm:px-10 lg:px-14 py-3.5 sm:py-4 flex items-center justify-between transition-shadow duration-200 rounded-none">
            <!-- Brand with Official Logo -->
            <div class="flex items-center lg:pr-12 xl:pr-20 lg:border-r lg:border-stone-200/80">
                <a href="<?= BASE_URL ?>/index.php" id="brandLogoLink" onclick="handleBrandClick(event)"
                    class="flex items-center space-x-3 cursor-pointer select-none group" title="Return to top">
                    <div
                        class="w-10 h-10 rounded-none bg-black flex items-center justify-center p-1.5 shadow-xs flex-shrink-0">
                        <img src="<?= BASE_URL ?>/assets/images/logo.png" alt="SDC Dental Care"
                            class="w-full h-full object-contain">
                    </div>
                    <div>
                        <h1 class="text-base font-extrabold text-black tracking-tight leading-none uppercase">SDC DENTAL
                        </h1>
                        <p class="text-[9px] text-stone-500 font-mono tracking-[0.2em] uppercase font-bold mt-1">Clinical
                            Atelier</p>
                    </div>
                </a>
            </div>

            <!-- Navigation Links (Matching Image 1 Style & Spacing) -->
            <div
                class="hidden md:flex items-center space-x-8 uppercase font-mono text-[11px] font-bold tracking-[0.2em] text-stone-600">
                <a href="#services" class="hover:text-black transition">Services</a>
                <a href="#standards" class="hover:text-black transition">Standards</a>
                <a href="#dentists" class="hover:text-black transition">Doctors</a>
                <a href="#locations" class="hover:text-black transition">Branches</a>
            </div>

            <!-- Right Actions (Staff Login & Appointment CTA with zero rounded borders) -->
            <div class="flex items-center space-x-4 lg:pl-10 xl:pl-14 lg:border-l lg:border-stone-200/80">
                <div class="hidden xl:flex items-center space-x-2 text-[11px] font-mono text-stone-500 mr-2">
                    <i data-lucide="phone" class="w-3.5 h-3.5 text-stone-400"></i>
                    <span>+63 917 111 2233</span>
                </div>

                <?php if ($isLoggedIn): ?>
                    <a href="<?= BASE_URL ?>/index.php?page=dashboard"
                        class="hidden sm:inline-flex items-center space-x-1.5 uppercase font-mono text-[11px] font-bold tracking-wider text-black hover:text-stone-600 transition">
                        <i data-lucide="layout-dashboard" class="w-3.5 h-3.5"></i>
                        <span>Dashboard</span>
                    </a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/index.php?page=login"
                        class="hidden sm:inline-flex items-center space-x-1.5 uppercase font-mono text-[11px] font-bold tracking-wider text-stone-600 hover:text-black transition">
                        <i data-lucide="lock" class="w-3 h-3 text-stone-400"></i>
                        <span>Staff Login</span>
                    </a>
                <?php endif; ?>

                <a href="#booking-form"
                    class="inline-flex items-center space-x-2 px-5 py-2.5 bg-black hover:bg-stone-800 text-white text-xs font-bold uppercase tracking-wider rounded-none shadow-xs transition">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                    <span>Book Appointment</span>
                </a>
            </div>
        </nav>

        <!-- 2. Main Hero Canvas: Cloned Multi-Layer 3D Porcelain Tooth & Mirror Composition -->
        <div
            class="w-full flex-1 min-h-[640px] lg:min-h-0 relative flex flex-col justify-between px-6 sm:px-10 lg:px-14 py-6 lg:py-8 overflow-hidden">

            <!-- Architectural Corner Crosshairs -->
            <span
                class="absolute top-2 left-6 sm:left-10 text-stone-300 font-mono text-xs select-none pointer-events-none z-10">+</span>
            <span
                class="absolute top-2 right-6 sm:right-10 text-stone-300 font-mono text-xs select-none pointer-events-none z-10">+</span>
            <span
                class="absolute bottom-2 left-6 sm:left-10 text-stone-300 font-mono text-xs select-none pointer-events-none z-10">+</span>
            <span
                class="absolute bottom-2 right-6 sm:right-10 text-stone-300 font-mono text-xs select-none pointer-events-none z-10">+</span>

            <!-- Technical Leader Line connecting Tooth Target Dot to 01 / OUR APPROACH Card (Desktop) -->
            <svg class="hidden lg:block absolute inset-0 w-full h-full pointer-events-none z-[12]" viewBox="0 0 1440 760" preserveAspectRatio="none">
                <polyline points="858,205 1122,205 1152,162 1175,162" fill="none" stroke="#DFDDD8" stroke-width="1.2" />
            </svg>
            <!-- Target Node Dot on Right Crown of Molar Tooth -->
            <div class="hidden lg:flex absolute left-[59.6%] top-[27%] -translate-x-1/2 -translate-y-1/2 w-4 h-4 rounded-full bg-white border border-stone-300 shadow-xs items-center justify-center pointer-events-none z-[15]">
                <span class="w-1.5 h-1.5 rounded-full bg-stone-800"></span>
            </div>

            <!-- LAYER 1 (z-10): 3D Porcelain Molar Tooth Base & Glossy Studio Floor Reflection (Sits Behind SDC DENTAL CARE) -->
            <div
                class="relative lg:absolute lg:inset-y-0 lg:left-[46%] lg:-translate-x-1/2 z-10 flex items-end justify-center pointer-events-none select-none my-4 lg:my-0 h-[380px] sm:h-[460px] lg:h-full">
                <img src="<?= BASE_URL ?>/assets/images/dental_tooth_mirror_base.png"
                    alt="SDC 3D Porcelain Molar Tooth and Dental Mirror"
                    class="h-[97%] w-auto max-w-none object-contain rounded-none">
            </div>

            <!-- LAYER 2 (z-20): Giant Monumental Full-Width Typography (In Front of White Tooth, Behind Chrome Mirror Stem) -->
            <div
                class="absolute inset-x-0 top-1/2 -translate-y-1/2 flex items-center justify-center pointer-events-none select-none z-20 px-4 overflow-hidden">
                <h2
                    class="text-[10.8vw] lg:text-[10.4vw] font-black uppercase tracking-tighter leading-none text-stone-950 whitespace-nowrap text-center">
                    SDC&nbsp;DENTAL&nbsp;CARE
                </h2>
            </div>

            <!-- LAYER 3 (z-[25]): Foreground Chrome Dental Mirror Stem & Head (Slices Diagonally In Front of N and T) -->
            <div
                class="hidden lg:flex lg:absolute lg:inset-y-0 lg:left-[46%] lg:-translate-x-1/2 z-[25] items-end justify-center pointer-events-none select-none lg:h-full">
                <img src="<?= BASE_URL ?>/assets/images/dental_mirror_foreground.png"
                    alt=""
                    aria-hidden="true"
                    class="h-[97%] w-auto max-w-none object-contain filter drop-shadow-[0_10px_14px_rgba(0,0,0,0.28)] rounded-none">
            </div>

            <!-- LAYER 3A (z-30): TOP ROW (Top-Left Display Headline + Subcopy & Top-Right 01 / OUR APPROACH Card) -->
            <div
                class="relative z-30 flex flex-col lg:flex-row items-start justify-between gap-6 sm:pl-6 lg:pl-10 xl:pl-16 lg:pt-3">

                <!-- Top-Left: Large 3-Line Staggered Display Headline + Editorial Subcopy -->
                <div class="bg-white/95 lg:bg-transparent p-2 lg:p-0 rounded-none">
                    <h1
                        class="text-4xl sm:text-5xl lg:text-[3.35rem] xl:text-[3.85rem] font-black tracking-tight uppercase leading-[0.92] text-black font-sans">
                        <span class="block pl-6 sm:pl-10">CRAFTING</span>
                        <span class="block">CONFIDENT</span>
                        <span class="inline-flex items-center pl-6 sm:pl-10 gap-4">
                            <span>SMILES</span>
                            <span
                                class="text-[9px] sm:text-[10px] font-mono font-bold tracking-[0.2em] uppercase text-stone-500 leading-tight">
                                GENTLE CARE &amp;<br>MODERN DENTISTRY
                            </span>
                        </span>
                    </h1>

                    <!-- Short Divider Dash + Sub-Headline & Body Copy (Cloned from Reference) -->
                    <div class="max-w-[270px] sm:max-w-[295px]">
                        <div class="w-6 h-[1.5px] bg-stone-400 mt-4 mb-2.5"></div>
                        <h3 class="text-sm sm:text-[15px] font-bold text-stone-950 tracking-tight leading-snug">
                            Dentistry, designed around you.
                        </h3>
                        <p class="text-xs sm:text-[12.5px] text-stone-500 leading-relaxed mt-1 font-normal">
                            Gentle care, modern techniques, and<br>personalized treatment for every smile.
                        </p>
                    </div>
                </div>

                <!-- Top-Right: 01 / OUR APPROACH Floating Callout Card (Cloned from Reference) -->
                <a href="#standards"
                    class="hidden lg:block w-[235px] xl:w-[248px] bg-white border border-stone-200/80 shadow-[0_18px_40px_rgba(0,0,0,0.06)] p-5 lg:mr-2 xl:mr-6 lg:mt-3 hover:border-stone-400 transition group/approach">
                    <div class="flex items-center justify-between text-[9px] font-mono tracking-[0.2em] uppercase text-stone-500 font-semibold">
                        <span>01 / OUR APPROACH</span>
                        <i data-lucide="arrow-up-right" class="w-3.5 h-3.5 text-stone-700 transition-transform group-hover/approach:translate-x-0.5 group-hover/approach:-translate-y-0.5"></i>
                    </div>
                    <h4 class="text-base xl:text-[17px] font-bold text-stone-950 leading-[1.2] tracking-tight mt-2.5">
                        Precision meets<br>comfort.
                    </h4>
                    <p class="text-[11px] text-stone-400 leading-relaxed mt-2 font-normal">
                        Thoughtful dentistry.<br>Exceptional attention to detail.
                    </p>
                </a>

            </div>

            <!-- LAYER 3B (z-30): BOTTOM ROW (Bottom-Left SCROLL TO EXPLORE & Bottom-Right High-Contrast Black Booking Card) -->
            <div
                class="relative z-30 flex flex-col lg:flex-row items-stretch lg:items-end justify-between gap-6 mt-6 lg:mt-0">

                <!-- Bottom-Left: SCROLL TO EXPLORE Mouse Indicator & Stepped Arrow Line (Cloned from Reference) -->
                <a href="#services" class="hidden lg:inline-flex flex-col items-start group/scroll select-none mb-1">
                    <div class="flex items-center space-x-3">
                        <!-- Minimalist Mouse Icon -->
                        <div class="w-[18px] h-[28px] rounded-full border border-stone-400 flex justify-center pt-1.5 group-hover/scroll:border-black transition">
                            <span class="w-[1.5px] h-2 bg-stone-500 group-hover/scroll:bg-black"></span>
                        </div>
                        <span class="text-[9px] font-sans font-bold tracking-[0.18em] uppercase text-stone-700 group-hover/scroll:text-black transition">
                            SCROLL TO EXPLORE
                        </span>
                    </div>
                    <!-- Horizontal Line bending down to Arrow -->
                    <div class="flex items-start mt-2.5 pl-0.5">
                        <div class="w-28 h-px bg-stone-300"></div>
                        <div class="flex flex-col items-center -ml-px">
                            <div class="w-px h-2 bg-stone-300"></div>
                            <span class="text-[9px] leading-none text-stone-400 -mt-0.5">&darr;</span>
                        </div>
                    </div>
                </a>

                <!-- Bottom-Right: Sleek Solid Black Status & Booking Card (Inspo: Bottom-Right Dark Match Card, Zero Round Borders) -->
                <div
                    class="w-full sm:w-[340px] bg-stone-950 text-white border border-black p-5 shadow-xl rounded-none flex flex-col justify-between space-y-4">

                    <!-- Card Top Row: Badge + Clinic Hours -->
                    <div class="flex items-center justify-between">
                        <span
                            class="px-2.5 py-1 bg-white text-black font-mono text-[9px] font-black uppercase tracking-widest rounded-none">
                            DIRECT BOOKING
                        </span>
                        <span class="text-[10px] font-mono text-stone-400 uppercase tracking-wider">
                            Mon–Sat · 09:00–18:00
                        </span>
                    </div>

                    <!-- Card Middle Row: Branches / Chief Clinician Matchup -->
                    <div class="flex items-center justify-between py-2 border-y border-stone-800">
                        <div class="flex items-center space-x-2.5">
                            <div
                                class="w-8 h-8 bg-stone-800 border border-stone-700 overflow-hidden flex-shrink-0 rounded-none">
                                <img src="https://images.unsplash.com/photo-1629909613654-28e377c37b09?auto=format&fit=crop&w=200&q=80"
                                    alt="Dr. Eleanor Vance"
                                    class="w-full h-full object-cover filter grayscale contrast-125 rounded-none">
                            </div>
                            <div>
                                <div class="text-xs font-extrabold uppercase tracking-tight text-white">Makati Suite
                                </div>
                                <div class="text-[9px] font-mono text-stone-400 uppercase">Dr. E. Vance, DMD</div>
                            </div>
                        </div>

                        <div
                            class="px-2 py-1 border border-stone-700 text-[9px] font-mono font-bold text-stone-400 uppercase rounded-none">
                            &amp;
                        </div>

                        <div class="text-right">
                            <div class="text-xs font-extrabold uppercase tracking-tight text-white">Pasig Suite</div>
                            <div class="text-[9px] font-mono text-emerald-400 uppercase">Slots Open</div>
                        </div>
                    </div>

                    <!-- Card Bottom Row: Clinical Label + Direct CTA -->
                    <div class="flex items-center justify-between pt-0.5">
                        <span class="text-[10px] font-mono text-stone-400 uppercase tracking-wider">
                            Class-B Sterile Suites
                        </span>
                        <a href="#booking-form"
                            class="inline-flex items-center space-x-1.5 text-[11px] font-black uppercase tracking-[0.18em] text-white hover:text-emerald-400 transition group/cta rounded-none">
                            <span>Book Now</span>
                            <i data-lucide="arrow-right"
                                class="w-3.5 h-3.5 transition-transform group-hover/cta:translate-x-1"></i>
                        </a>
                    </div>

                </div>

            </div>

        </div>

        <!-- 3. Infinite Scope of Practice Editorial Marquee Ribbon (All Clinic Services, Inside Hero at Bottom, Zero Round Borders) -->
        <div
            class="w-full border-t border-[#E5E1D8] bg-[#F7F6F2] py-3 overflow-hidden select-none rounded-none flex-shrink-0">
            <div
                class="ticker-track flex items-center space-x-8 whitespace-nowrap font-mono text-[11px] font-bold tracking-[0.26em] uppercase text-stone-500">
                <?php for ($loop = 0; $loop < 2; $loop++): ?>
                    <?php foreach ($treatments as $t): ?>
                        <span>✦ <?= htmlspecialchars($t['treatment_name']) ?></span>
                        <span class="text-stone-300">/</span>
                    <?php endforeach; ?>
                <?php endfor; ?>
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
    <section id="services" class="py-24 bg-[#EFECE6] text-stone-900 overflow-hidden border-b border-[#E5E1D8] relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-10">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div>
                    <span class="text-[11px] uppercase tracking-[0.25em] font-semibold text-stone-500 block mb-2">
                        Clinical Catalog
                    </span>
                    <h3 class="text-3xl sm:text-5xl font-extrabold text-stone-950 tracking-tight">
                        Signature Treatments<span class="font-serif italic font-normal text-stone-500">.</span>
                    </h3>
                    <p class="text-stone-600 text-xs sm:text-sm mt-3 max-w-xl leading-relaxed">
                        Nine focused dental disciplines across our Makati and Pasig clinics. Hover across each slice to
                        expand, or click any picture to open the full procedure lookbook.
                    </p>
                </div>

                <!-- Minimalist Navigation Controls -->
                <div class="flex items-center space-x-5">
                    <span class="hidden sm:inline-block text-[11px] uppercase tracking-widest text-stone-500 font-mono">
                        [ 01 — 09 ]
                    </span>
                    <div class="flex items-center space-x-2">
                        <button id="sliderPrevBtn" aria-label="Previous treatment"
                            class="w-12 h-12 rounded-full border border-[#D5CFC3] hover:border-stone-950 hover:bg-stone-950 hover:text-white text-stone-800 bg-white/70 backdrop-blur-sm flex items-center justify-center transition shadow-xs">
                            <i data-lucide="chevron-left" class="w-5 h-5"></i>
                        </button>
                        <button id="sliderNextBtn" aria-label="Next treatment"
                            class="w-12 h-12 rounded-full border border-[#D5CFC3] hover:border-stone-950 hover:bg-stone-950 hover:text-white text-stone-800 bg-white/70 backdrop-blur-sm flex items-center justify-center transition shadow-xs">
                            <i data-lucide="chevron-right" class="w-5 h-5"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Seamless Edge-to-Edge Straight Slideshow (Expands on Hover, Clicks to Open Modal) -->
        <div class="w-full relative overflow-hidden py-4">
            <div
                class="absolute left-0 top-0 bottom-0 w-16 bg-gradient-to-r from-[#EFECE6] to-transparent z-20 pointer-events-none">
            </div>
            <div
                class="absolute right-0 top-0 bottom-0 w-16 bg-gradient-to-l from-[#EFECE6] to-transparent z-20 pointer-events-none">
            </div>

            <div id="servicesTrack"
                class="flex items-stretch space-x-0 py-6 px-8 sm:px-16 overflow-x-auto no-scrollbar scroll-smooth cursor-grab active:cursor-grabbing select-none">
                <?php foreach ($treatmentsData as $idx => $trt): ?>
                    <!-- Pure Photographic Strip (Straight, Stationary, Zero Text) -->
                    <button type="button" onclick="openProcedureModal(<?= $idx ?>)"
                        title="Click to view full details for <?= htmlspecialchars($trt['name']) ?>"
                        class="gallery-strip group relative h-[590px] w-[170px] sm:w-[200px] md:w-[220px] hover:w-[580px] sm:hover:w-[720px] md:hover:w-[840px] -ml-[1px] first:ml-0 overflow-hidden border-r border-t border-b border-[#D5CFC3] first:border-l bg-stone-900 block cursor-pointer text-left focus:outline-none shadow-sm">

                        <div class="gallery-strip-inner absolute inset-0 w-full h-full">
                            <img src="<?= htmlspecialchars($trt['image']) ?>" alt="<?= htmlspecialchars($trt['name']) ?>"
                                class="absolute inset-0 w-full h-full object-cover">
                        </div>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Editorial Procedure Exhibition Modal (Only Details & Photos of THAT Selected Procedure) -->
    <div id="procedureModal"
        class="fixed inset-0 z-[70] flex items-center justify-center bg-black/80 backdrop-blur-md p-3 sm:p-6 md:p-8 hidden opacity-0 transition-opacity duration-300">

        <!-- Modal Container (Warm Linen Luxury Aesthetic from Reference Screenshot) -->
        <div class="bg-[#f4efe8] text-stone-900 rounded-3xl overflow-hidden shadow-2xl max-w-6xl w-full max-h-[92vh] flex flex-col border border-stone-300 transform scale-95 transition-transform duration-300"
            id="modalContainer">

            <!-- Top Minimalist Catalogue Bar -->
            <div
                class="flex items-center justify-between border-b border-stone-300/80 px-6 sm:px-8 py-4 text-[11px] uppercase tracking-[0.22em] font-mono text-stone-500 bg-[#f4efe8]">
                <span id="modalTopCode">PROCEDURE // SPECIFICATION</span>
                <span class="font-serif italic font-normal text-stone-800 text-sm normal-case hidden sm:inline">SDC
                    Dental Atelier</span>
                <div class="flex items-center space-x-4">
                    <span id="modalActiveCounter" class="font-semibold text-stone-800">VIEW 01 / 04</span>
                    <button type="button" onclick="closeProcedureModal()"
                        class="text-stone-500 hover:text-stone-950 font-bold uppercase tracking-wider text-xs flex items-center space-x-1 transition">
                        <span>Close [✕]</span>
                    </button>
                </div>
            </div>

            <!-- Middle Stage: Left Big Picture / Right Large Editorial Headline & Details -->
            <div class="flex-1 overflow-y-auto no-scrollbar p-6 sm:p-10">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">

                    <!-- Left: Big Picture Display Frame (Swaps when clicking thumbnails below) -->
                    <div class="lg:col-span-5 relative">
                        <div
                            class="relative w-full aspect-[3/4] sm:h-[460px] rounded-2xl overflow-hidden shadow-xl bg-stone-900 border border-stone-300/80 group">
                            <img id="modalBigImage" src="" alt=""
                                class="w-full h-full object-cover transition-all duration-500 ease-out filter brightness-95">
                        </div>
                    </div>

                    <!-- Right: Big Editorial Headline & Clinical Info for THAT Procedure -->
                    <div class="lg:col-span-7 flex flex-col justify-center">
                        <div class="mb-3">
                            <span id="modalCategory"
                                class="text-xs uppercase tracking-[0.25em] font-mono text-stone-500 block mb-2">
                                01 / COSMETIC DENTISTRY
                            </span>

                            <!-- Large High-Fashion Headline (Le Oh Botanic style) -->
                            <h3 id="modalTitle"
                                class="text-3xl sm:text-5xl lg:text-6xl font-serif font-bold text-stone-950 tracking-tight leading-[1.08]">
                                Laser Teeth Whitening
                            </h3>
                        </div>

                        <!-- Upfront Fee -->
                        <div class="flex items-baseline space-x-3 my-4">
                            <span id="modalPrice"
                                class="text-3xl sm:text-4xl font-mono font-bold text-stone-950 tracking-tight">
                                ₱6,500.00
                            </span>
                            <span class="text-xs font-mono text-stone-500">standard clinical rate</span>
                        </div>

                        <!-- Clinical Overview Description -->
                        <p id="modalDescription"
                            class="text-stone-700 text-sm sm:text-base leading-relaxed max-w-xl font-light mb-6">
                            Professional in-clinic clinical bleaching engineered to gently lift deep enamel
                            discoloration by up to 6–8 shades in a single sitting.
                        </p>

                        <!-- Editorial Specifications Bar -->
                        <div
                            class="grid grid-cols-3 gap-3 border-t border-b border-stone-300/80 py-4 mb-2 text-xs max-w-xl">
                            <div>
                                <span
                                    class="block text-[9px] uppercase tracking-widest text-stone-400 mb-0.5">Session</span>
                                <span id="modalDuration" class="font-bold text-stone-900 text-sm">60 Mins</span>
                            </div>
                            <div>
                                <span
                                    class="block text-[9px] uppercase tracking-widest text-stone-400 mb-0.5">Comfort</span>
                                <span id="modalComfort" class="font-bold text-stone-900 text-sm">Enamel-Safe</span>
                            </div>
                            <div>
                                <span
                                    class="block text-[9px] uppercase tracking-widest text-stone-400 mb-0.5">Scope</span>
                                <span id="modalInclusions" class="font-bold text-stone-900 text-sm truncate block">Blue
                                    LED Laser</span>
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
                    <div id="modalGalleryFilmstrip"
                        class="flex items-center space-x-3 sm:space-x-4 overflow-x-auto no-scrollbar py-2 select-none">
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
        document.addEventListener('DOMContentLoaded', function () {
            const track = document.getElementById('servicesTrack');
            const prevBtn = document.getElementById('sliderPrevBtn');
            const nextBtn = document.getElementById('sliderNextBtn');

            if (track) {
                const scrollStep = 380;
                if (prevBtn) {
                    prevBtn.addEventListener('click', function () {
                        track.scrollBy({ left: -scrollStep, behavior: 'smooth' });
                    });
                }
                if (nextBtn) {
                    nextBtn.addEventListener('click', function () {
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

    <!-- Our Attending Dentists (Aligned with Hero & Signature Treatments Editorial Design — Zero Round Borders) -->
    <?php
    // Authentic dental clinic & operatory clinician imagery (no hospital stethoscopes)
    $docPortraits = [
        0 => 'https://images.unsplash.com/photo-1629909615184-74f495363b67?auto=format&fit=crop&w=1000&q=85',
        1 => 'https://images.unsplash.com/photo-1588776814546-1ffcf47267a5?auto=format&fit=crop&w=1000&q=85',
        2 => 'https://images.unsplash.com/photo-1606811841689-23dfddce3e95?auto=format&fit=crop&w=1000&q=85',
        3 => 'https://images.unsplash.com/photo-1598256989800-fe5f95da9787?auto=format&fit=crop&w=1000&q=85',
    ];
    ?>
    <section id="dentists" class="relative py-24 bg-white border-b border-[#E5E1D8] overflow-hidden select-none">
        <!-- Monumental Background Watermark Typography (Echoing Hero Section) -->
        <div class="absolute inset-x-0 top-1/2 -translate-y-1/2 flex items-center justify-center pointer-events-none select-none z-0 overflow-hidden">
            <span class="text-[12vw] font-black uppercase tracking-tighter leading-none text-stone-950/[0.025] whitespace-nowrap">
                DENTAL&nbsp;SPECIALISTS
            </span>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Section Header (Aligned with Signature Treatments & Hero Typography) -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-14">
                <div>
                    <span class="text-[11px] uppercase tracking-[0.25em] font-semibold text-stone-500 block mb-2">
                        Dental Specialists
                    </span>
                    <h3 class="text-3xl sm:text-5xl font-extrabold text-stone-950 tracking-tight">
                        Our Attending Dentists<span class="font-serif italic font-normal text-stone-500">.</span>
                    </h3>
                    <div class="w-6 h-[1.5px] bg-stone-400 mt-4 mb-2.5"></div>
                    <p class="text-stone-600 text-xs sm:text-sm max-w-xl leading-relaxed">
                        Experienced dentists dedicated to gentle chairside care, natural tooth preservation, and confident smiles across our Makati and Pasig clinics.
                    </p>
                </div>
            </div>

            <!-- Dental Clinicians Editorial Grid (Pure Unobstructed Portraits + Clean Architectural Typography) -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                <?php foreach ($dentists as $dIdx => $doc):
                    $docNumber = str_pad($dIdx + 1, 2, '0', STR_PAD_LEFT);
                    $portraitUrl = $docPortraits[$dIdx % count($docPortraits)];
                    $spec = $doc['specialization'] ?: 'General & Aesthetic Dentistry';
                    $isSurgical = stripos($spec, 'Surgery') !== false || stripos($spec, 'Endo') !== false;
                    $headlinePhilosophy = $isSurgical
                        ? 'Pain-free root canals & gentle oral surgery.'
                        : 'Harmonious alignment & natural smile aesthetics.';
                    $bioText = $isSurgical
                        ? 'Focused on saving natural teeth through microscopic endodontics, stress-free wisdom tooth extractions, and soothing chairside care.'
                        : 'Dedicated to custom orthodontic braces, seamless shade-matched composite restorations, and gentle preventive dental care for every patient.';
                    $proceduresList = $isSurgical
                        ? ['Root Canal Therapy', 'Wisdom Tooth Surgery', 'Gentle Extractions']
                        : ['Orthodontic Braces', 'Cosmetic Fillings', 'Oral Prophylaxis & Whitening'];
                ?>
                    <article class="grid grid-cols-1 sm:grid-cols-12 bg-white border border-[#E5E1D8] hover:border-stone-950 transition-colors duration-300 shadow-[0_18px_40px_rgba(0,0,0,0.04)] rounded-none group">
                        
                        <!-- 1. Pure Unobstructed Picture Column (Zero Text, Zero Overlays, Zero Badges on Image) -->
                        <div class="sm:col-span-5 relative min-h-[300px] sm:min-h-[380px] bg-stone-900 overflow-hidden border-b sm:border-b-0 sm:border-r border-[#E5E1D8] rounded-none">
                            <img src="<?= htmlspecialchars($portraitUrl) ?>"
                                 alt="<?= htmlspecialchars($doc['full_name']) ?>"
                                 class="w-full h-full object-cover object-center filter grayscale contrast-110 group-hover:grayscale-0 transition-all duration-700 rounded-none">
                        </div>

                        <!-- 2. Editorial Clinician Profile Column -->
                        <div class="sm:col-span-7 p-7 sm:p-9 bg-white flex flex-col justify-between">
                            <div>
                                <div class="text-[10px] font-mono tracking-[0.22em] uppercase text-stone-400 font-semibold">
                                    <?= $docNumber ?> / <?= htmlspecialchars($spec) ?>
                                </div>

                                <h4 class="text-2xl sm:text-[1.75rem] font-black text-stone-950 tracking-tight uppercase leading-tight mt-2.5">
                                    <?= htmlspecialchars($doc['full_name']) ?>
                                </h4>

                                <!-- Signature Short 24px Gray Dash + Bold Sub-Heading (Matching Hero) -->
                                <div class="w-6 h-[1.5px] bg-stone-400 my-4"></div>
                                <h5 class="text-sm sm:text-[15px] font-bold text-stone-950 tracking-tight leading-snug">
                                    <?= htmlspecialchars($headlinePhilosophy) ?>
                                </h5>
                                <p class="text-xs sm:text-[12.5px] text-stone-500 leading-relaxed mt-1.5 font-normal">
                                    <?= htmlspecialchars($bioText) ?>
                                </p>
                            </div>

                            <div class="pt-5 mt-6 border-t border-stone-200/80 space-y-4">
                                <div>
                                    <span class="text-[9px] font-mono uppercase tracking-[0.2em] text-stone-400 font-bold block mb-2">
                                        CLINICAL FOCUS
                                    </span>
                                    <div class="flex flex-wrap gap-1.5">
                                        <?php foreach ($proceduresList as $procName): ?>
                                            <span class="px-2.5 py-1 bg-[#F7F6F2] border border-[#E5E1D8] text-[10px] font-mono font-semibold text-stone-700 uppercase tracking-wider rounded-none">
                                                <?= htmlspecialchars($procName) ?>
                                            </span>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                                <div class="pt-3 border-t border-stone-100 flex items-center justify-between text-[10px] font-mono uppercase tracking-wider text-stone-400">
                                    <span>Attending Dentist</span>
                                    <span>Makati &amp; Pasig Suites</span>
                                </div>
                            </div>
                        </div>

                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Clinic Hours & Branch Locations -->
    <section id="locations" class="py-16 bg-[#FAF9F5] border-b border-[#E5E1D8]">
        <div class="max-w-6xl mx-auto px-4">
            <div class="mb-10">
                <span class="text-xs uppercase font-bold tracking-wider text-stone-500 block mb-1">Visit Us</span>
                <h3 class="text-2xl font-extrabold text-stone-950 tracking-tight">Clinic Hours & Branch Locations</h3>
                <p class="text-xs text-stone-600 mt-1">Conveniently accessible clinic branches with clean clinical
                    operatories.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs text-stone-700">
                <?php foreach ($branches as $br): ?>
                    <div class="p-6 rounded-2xl bg-white border border-[#E5E1D8] shadow-2xs space-y-3">
                        <div class="flex items-center justify-between">
                            <h4 class="font-bold text-stone-950 text-sm flex items-center space-x-2">
                                <i data-lucide="map-pin" class="w-4 h-4 text-stone-900"></i>
                                <span><?= htmlspecialchars($br['branch_name']) ?></span>
                            </h4>
                            <span
                                class="font-mono text-[11px] font-bold text-stone-700 px-2 py-0.5 rounded bg-[#EFECE6] border border-[#E5E1D8]">
                                <?= htmlspecialchars($br['branch_code']) ?>
                            </span>
                        </div>
                        <p class="text-stone-600"><?= htmlspecialchars($br['address'] ?: 'Clinic address on file.') ?></p>
                        <p class="flex items-center space-x-1.5 text-stone-600">
                            <i data-lucide="phone" class="w-3.5 h-3.5 text-stone-400"></i>
                            <span><?= htmlspecialchars($br['contact_number'] ?: '+63 917 111 2233') ?></span>
                        </p>
                        <div class="pt-2 border-t border-[#EFECE6] text-stone-500 flex justify-between items-center">
                            <span>Regular Operating Hours:</span>
                            <span class="font-semibold text-stone-900">Mon - Sat: 9:00 AM – 6:00 PM</span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Online Appointment Booking Form (Warm Linen Surface) -->
    <section id="booking-form" class="py-20 bg-[#F0ECE4] border-b border-[#E5E1D8]">
        <div class="max-w-4xl mx-auto px-4">
            <div class="text-center mb-8">
                <span class="text-xs uppercase font-bold tracking-wider text-stone-500 block mb-1">Direct
                    Scheduling</span>
                <h3 class="text-2xl md:text-3xl font-extrabold text-stone-950 tracking-tight">Request an Appointment
                    Online</h3>
                <p class="text-xs text-stone-600 mt-1 max-w-lg mx-auto">
                    Fill out the form below with your preferred schedule and dental concern. Our reception desk will
                    review availability and confirm your booking right away.
                </p>
            </div>

            <?php if ($portalError = Session::flash('portal_error')): ?>
                <div
                    class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold flex items-center space-x-2 shadow-2xs">
                    <i data-lucide="alert-circle" class="w-4 h-4 text-rose-500 flex-shrink-0"></i>
                    <span><?= htmlspecialchars($portalError) ?></span>
                </div>
            <?php endif; ?>

            <div class="bg-white rounded-3xl p-6 md:p-10 border border-[#E5E1D8] shadow-xl text-stone-800">
                <form action="<?= BASE_URL ?>/index.php?page=portal_submit" method="POST" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Patient Full Name -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-stone-600 mb-1.5">
                                Your Full Name <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="name" required placeholder="e.g. Maria Santos"
                                class="w-full px-3.5 py-2.5 bg-[#FAF9F6] border border-[#E5E1D8] rounded-xl text-sm text-stone-900 placeholder-stone-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-stone-900 focus:border-stone-900 transition">
                        </div>

                        <!-- Phone Number -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-stone-600 mb-1.5">
                                Contact Phone Number <span class="text-rose-500">*</span>
                            </label>
                            <input type="tel" name="phone" required placeholder="e.g. 0917 123 4567"
                                class="w-full px-3.5 py-2.5 bg-[#FAF9F6] border border-[#E5E1D8] rounded-xl text-sm text-stone-900 placeholder-stone-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-stone-900 focus:border-stone-900 transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Preferred Date -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-stone-600 mb-1.5">
                                Preferred Date <span class="text-rose-500">*</span>
                            </label>
                            <input type="date" name="preferred_date" required min="<?= date('Y-m-d') ?>"
                                value="<?= date('Y-m-d', strtotime('+1 day')) ?>"
                                class="w-full px-3.5 py-2.5 bg-[#FAF9F6] border border-[#E5E1D8] rounded-xl text-sm text-stone-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-stone-900 focus:border-stone-900 transition">
                        </div>

                        <!-- Preferred Time Slot -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-stone-600 mb-1.5">
                                Preferred Time <span class="text-rose-500">*</span>
                            </label>
                            <select name="preferred_time" required
                                class="w-full px-3.5 py-2.5 bg-[#FAF9F6] border border-[#E5E1D8] rounded-xl text-sm text-stone-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-stone-900 focus:border-stone-900 transition">
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
                            <label class="block text-xs font-bold uppercase tracking-wider text-stone-600 mb-1.5">
                                Clinic Branch
                            </label>
                            <select name="branch_id"
                                class="w-full px-3.5 py-2.5 bg-[#FAF9F6] border border-[#E5E1D8] rounded-xl text-sm text-stone-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-stone-900 focus:border-stone-900 transition">
                                <?php foreach ($branches as $branch): ?>
                                    <option value="<?= $branch['id'] ?>">
                                        <?= htmlspecialchars($branch['branch_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Preferred Dentist Selection -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-stone-600 mb-1.5">
                                Preferred Dentist (Optional)
                            </label>
                            <select name="preferred_dentist_id"
                                class="w-full px-3.5 py-2.5 bg-[#FAF9F6] border border-[#E5E1D8] rounded-xl text-sm text-stone-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-stone-900 focus:border-stone-900 transition">
                                <option value="">Any Available Attending Dentist</option>
                                <?php foreach ($dentists as $d): ?>
                                    <option value="<?= $d['dentist_id'] ?>">
                                        <?= htmlspecialchars($d['full_name']) ?>
                                        (<?= htmlspecialchars($d['specialization']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Dental Concern (Required by Interview Spec) -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-stone-600 mb-1.5">
                            Dental Concern / Reason for Visit <span class="text-rose-500">*</span>
                        </label>
                        <textarea name="dental_concern" id="inpDentalConcern" rows="3" required
                            placeholder="Please describe what you are experiencing (e.g. Toothache, routine dental cleaning, filling replacement, wisdom tooth extraction, braces consultation)..."
                            class="w-full p-3.5 bg-[#FAF9F6] border border-[#E5E1D8] rounded-xl text-sm text-stone-900 placeholder-stone-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-stone-900 focus:border-stone-900 transition"></textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit"
                            class="w-full py-3.5 px-6 bg-stone-950 hover:bg-stone-800 text-white font-bold rounded-2xl shadow-xs transition flex items-center justify-center space-x-2 text-sm">
                            <i data-lucide="check-circle" class="w-4 h-4"></i>
                            <span>Submit Appointment Request</span>
                        </button>
                        <p class="text-center text-[11px] text-stone-500 mt-2.5">
                            No upfront payment required. Our front desk staff will verify clinic availability and
                            contact you directly.
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Clinic Footer with Logo (Warm Linen Tone) -->
    <footer class="bg-[#EFECE6] text-stone-600 text-xs py-14 border-t border-[#E5E1D8]">
        <div class="max-w-6xl mx-auto px-4 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center space-x-3.5 cursor-pointer select-none" onclick="handleBrandClick(event)"
                title="Return to top">
                <div
                    class="w-10 h-10 rounded-xl bg-stone-950 flex items-center justify-center p-1.5 shadow-xs flex-shrink-0">
                    <img src="<?= BASE_URL ?>/assets/images/logo.png" alt="SDC Logo"
                        class="w-full h-full object-contain">
                </div>
                <div>
                    <p class="font-extrabold text-stone-950 text-sm tracking-tight"><?= APP_NAME ?></p>
                    <p class="text-[11px] text-stone-500 mt-0.5">Professional Healthcare & Clinical Dental Services</p>
                    <p class="text-[11px] text-stone-400 mt-1">&copy; <?= date('Y') ?> <?= APP_NAME ?>. All rights
                        reserved.</p>
                </div>
            </div>

            <div class="flex items-center space-x-6 text-stone-600 font-medium">
                <a href="#services" class="hover:text-stone-950 transition">Services</a>
                <a href="#standards" class="hover:text-stone-950 transition">Clinical Standards</a>
                <a href="#dentists" class="hover:text-stone-950 transition">Dentists</a>
                <a href="#booking-form" class="hover:text-stone-950 transition">Book Visit</a>
                <a href="<?= BASE_URL ?>/index.php?page=login"
                    class="text-stone-950 hover:underline font-bold transition">Staff Login &rarr;</a>
            </div>
        </div>
    </footer>

    <script>
        lucide.createIcons();

        function handleBrandClick(event) {
            if (event) event.preventDefault();

            // Smoothly scroll window to top
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

        // Rotating Hero Headline Word
        (function initRotatingHeroWord() {
            const el = document.getElementById('rotatingHeroWord');
            if (!el) return;
            const words = ['your smile.', 'confidence.', 'lifelong health.', 'gentle care.', 'natural aesthetics.'];
            let idx = 0;
            setInterval(() => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(-6px)';
                setTimeout(() => {
                    idx = (idx + 1) % words.length;
                    el.innerText = words[idx];
                    el.style.transform = 'translateY(6px)';
                    void el.offsetWidth;
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0)';
                }, 250);
            }, 3200);
        })();

        // Sticky Navbar Scroll Elevation (Solid opaque white, clean border)
        (function initStickyNavShadow() {
            const nav = document.getElementById('mainHeroNav');
            if (!nav) return;
            window.addEventListener('scroll', () => {
                if (window.scrollY > 20) {
                    nav.classList.add('shadow-sm');
                } else {
                    nav.classList.remove('shadow-sm');
                }
            }, { passive: true });
        })();
    </script>
</body>

</html>