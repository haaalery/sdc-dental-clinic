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

<?php
// Comprehensive Clinical Details & Visuals for Treatments
$treatmentDetailsMap = [
    'TRT-001' => [
        'name' => 'Comprehensive Oral Examination & Consultation',
        'category' => 'Preventive Dentistry',
        'image' => BASE_URL . '/assets/images/treatments/trt-001.svg',
        'duration' => '30 – 45 Minutes',
        'overview' => 'A comprehensive visual, tactile, and radiographic assessment of your entire oral cavity to detect early decay, periodontal status, and bite alignment before discomfort occurs.',
        'steps' => [
            'Digital radiographic imaging (bitewing and periapical X-rays)',
            'Intraoral soft tissue, palate, and tongue oral cancer screening',
            'Periodontal probing to evaluate gum health and pocket depth',
            'One-on-one consultation with your attending dentist to review diagnostic findings'
        ],
        'indications' => 'Essential for all new patients, annual routine checkups, or patients experiencing oral discomfort.',
        'care_tips' => 'Schedule preventive oral examinations every 6 months to maintain peak oral health.'
    ],
    'TRT-002' => [
        'name' => 'Dental Prophylaxis (Cleaning) - Mild',
        'category' => 'Preventive Dentistry',
        'image' => BASE_URL . '/assets/images/treatments/trt-002.svg',
        'duration' => '30 – 45 Minutes',
        'overview' => 'Professional preventive dental cleaning to remove hardened supragingival tartar, bacterial plaque, and surface food stains that standard brushing and flossing cannot reach.',
        'steps' => [
            'Gentle ultrasonic scaling to break down hardened calculus deposits',
            'Manual fine curette instrumentation around contact points',
            'Polishing with fine prophy paste for a smooth, high-gloss enamel surface',
            'Protective fluoride therapy application to strengthen tooth enamel'
        ],
        'indications' => 'Recommended every 6 months for individuals with healthy or mildly inflamed gums.',
        'care_tips' => 'Avoid dark staining beverages (coffee, tea, soda) for 2 hours following fluoride application.'
    ],
    'TRT-003' => [
        'name' => 'Dental Prophylaxis (Cleaning) - Deep Scaling',
        'category' => 'Periodontics',
        'image' => BASE_URL . '/assets/images/treatments/trt-003.svg',
        'duration' => '45 – 60 Minutes',
        'overview' => 'A therapeutic periodontal treatment designed to clean deeply beneath the gumline, eradicating pathogenic bacteria and deep tartar from tooth root surfaces to stop bone loss.',
        'steps' => [
            'Gentle local anesthesia to ensure complete comfort throughout',
            'Ultrasonic and specialized Gracey curette subgingival debridement',
            'Root planing to smooth rough root areas and encourage gum reattachment',
            'Antiseptic pocket irrigation to reduce localized inflammation'
        ],
        'indications' => 'Recommended for patients with persistent gum bleeding, swollen gums, deep periodontal pockets, or periodontitis.',
        'care_tips' => 'Rinse with warm saltwater for 48 hours and use an ultra-soft toothbrush while gums recover.'
    ],
    'TRT-004' => [
        'name' => 'Composite Resin Restoration (Tooth Filling)',
        'category' => 'Restorative Dentistry',
        'image' => BASE_URL . '/assets/images/treatments/trt-004.svg',
        'duration' => '30 – 50 Minutes per tooth',
        'overview' => 'Direct tooth restoration using biomimetic composite resin that matches the natural shade, translucency, and anatomical contours of your natural tooth.',
        'steps' => [
            'Conservative removal of decayed and undermined enamel/dentin',
            'Application of phosphoric acid etchant and high-strength bonding adhesive',
            'Layer-by-layer anatomical sculpting of tooth-colored composite resin',
            'Blue LED light polymerisation and high-precision occlusion polishing'
        ],
        'indications' => 'Used for restoring active dental cavities, replacing old dark amalgam fillings, or repairing chipped enamel.',
        'care_tips' => 'You may eat immediately after light-curing, but avoid chewing extremely hard items on newly restored surfaces.'
    ],
    'TRT-005' => [
        'name' => 'Simple Tooth Extraction',
        'category' => 'Oral Surgery',
        'image' => BASE_URL . '/assets/images/treatments/trt-005.svg',
        'duration' => '30 – 45 Minutes',
        'overview' => 'A gentle, atraumatic procedure to safely remove an erupted tooth that cannot be saved due to severe decay, non-restorable fracture, or advanced periodontal looseness.',
        'steps' => [
            'Profound local anesthesia ensuring total numbness around the treatment site',
            'Careful luxation of the tooth within its socket using precision elevators',
            'Atraumatic delivery with surgical forceps preserving surrounding jawbone',
            'Placement of sterile gauze pressure packs and post-op care instruction'
        ],
        'indications' => 'Necessary for non-restorable teeth, severe root fractures, or to relieve localized chronic infection.',
        'care_tips' => 'Bite firmly on gauze for 45 minutes. Do not rinse vigorously, drink through a straw, or smoke for 24 hours.'
    ],
    'TRT-006' => [
        'name' => 'Surgical Extraction / Odontectomy (Impacted Molar)',
        'category' => 'Oral Surgery',
        'image' => BASE_URL . '/assets/images/treatments/trt-006.svg',
        'duration' => '45 – 75 Minutes',
        'overview' => 'Specialized oral surgery to remove an impacted or partially erupted third molar (wisdom tooth) embedded within jawbone or soft tissue.',
        'steps' => [
            'Comprehensive panoramic/CBCT review to locate adjacent nerve structures',
            'Profound local anesthesia for a painless surgical experience',
            'Small conservative flap incision and precise tooth sectioning',
            'Gentle removal in controlled fragments followed by sterile suturing'
        ],
        'indications' => 'Recommended for painful, swollen, crowded, or mesioangular impacted wisdom teeth that cause pericoronitis.',
        'care_tips' => 'Apply cold compress for 15-minute intervals during the first 24 hours. Eat soft, cool foods and rest.'
    ],
    'TRT-007' => [
        'name' => 'Root Canal Therapy (Anterior)',
        'category' => 'Endodontics',
        'image' => BASE_URL . '/assets/images/treatments/trt-007.svg',
        'duration' => '1 – 2 Appointments (60 mins each)',
        'overview' => 'A tooth-saving endodontic treatment that eliminates deep infection from the inner pulp chamber and seals the root canal space against reinfection.',
        'steps' => [
            'Complete isolation under sterile rubber dam to maintain a dry field',
            'Access cavity preparation and removal of inflamed/necrotic pulpal tissue',
            'Biomechanical shaping of canal with rotary files and antibacterial irrigation',
            'Hermetic 3D obturation using biocompatible gutta-percha and dental sealer'
        ],
        'indications' => 'For teeth with irreversible pulpitis, severe lingering hot/cold pain, trauma, or apical abscesses.',
        'care_tips' => 'Avoid biting hard items on the treated tooth until the final permanent restorative crown is placed.'
    ],
    'TRT-008' => [
        'name' => 'Orthodontic Braces (Package - Metal)',
        'category' => 'Orthodontics',
        'image' => BASE_URL . '/assets/images/treatments/trt-008.svg',
        'duration' => '12 – 24 Month Comprehensive Plan',
        'overview' => 'Fixed orthodontic appliance using precision medical-grade stainless steel brackets and smart memory archwires to gradually align crooked teeth and correct your bite.',
        'steps' => [
            'Full cephalometric X-ray analysis, facial photography, and study models',
            'Precision direct bonding of upper and lower bracket appliances',
            'Placement of initial super-elastic Nickel-Titanium alignment archwires',
            'Monthly wire adjustments, ligature color changes, and vector elastics'
        ],
        'indications' => 'For crowded teeth, gaps/spacing, overbites, underbites, crossbites, and aesthetic smile enhancement.',
        'care_tips' => 'Brush after every meal using orthodontic toothbrushes and interdental brushes. Avoid sticky and hard foods.'
    ],
    'TRT-009' => [
        'name' => 'Laser Teeth Whitening',
        'category' => 'Cosmetic Dentistry',
        'image' => BASE_URL . '/assets/images/treatments/trt-009.svg',
        'duration' => '60 – 75 Minutes',
        'overview' => 'Advanced in-office cosmetic laser bleaching that lifts deep enamel stains and brightens your smile by multiple shades in a single clinical visit.',
        'steps' => [
            'Shade guide measurement to document starting tooth baseline',
            'Protective light-cured gingival barrier placement over gum margins',
            'Application of professional-strength hydrogen peroxide whitening gel',
            'Three 15-minute photo-activation cycles under specialized laser light'
        ],
        'indications' => 'For extrinsic and intrinsic enamel discoloration caused by aging, coffee, tea, red wine, or tobacco.',
        'care_tips' => 'Follow a "white diet" (no dark liquids, sauces, or smoking) for 48 hours while enamel pores re-mineralize.'
    ]
];

// Build payload matching all active treatments from DB
$treatmentModalPayload = [];
if (!empty($treatments)) {
    foreach ($treatments as $t) {
        $c = $t['treatment_code'] ?? '';
        $base = $treatmentDetailsMap[$c] ?? [
            'name' => $t['treatment_name'],
            'category' => $t['category'],
            'image' => BASE_URL . '/assets/images/treatments/default.svg',
            'duration' => '30 – 60 Minutes',
            'overview' => $t['description'] ?: 'Professional clinical procedure conducted with certified dental materials and sterilized instruments.',
            'steps' => [
                'Initial oral evaluation and localized clinical diagnosis',
                'Procedure conducted under strict infection control standards',
                'Attending clinician guidance and post-treatment recommendations'
            ],
            'indications' => 'Recommended according to personalized dentist clinical evaluation.',
            'care_tips' => 'Maintain daily brushing, flossing, and follow clinician home care recommendations.'
        ];
        $base['name'] = $t['treatment_name'];
        $base['category'] = $t['category'];
        if (empty($base['image'])) {
            $base['image'] = BASE_URL . '/assets/images/treatments/default.svg';
        }
        $treatmentModalPayload[$c] = $base;
    }
}
?>

    <!-- Services & Treatments Section -->
    <section id="services" class="py-16 bg-white border-b border-slate-200">
        <div class="max-w-6xl mx-auto px-4">
            <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <span class="text-xs uppercase font-bold tracking-wider text-slate-500 block mb-1">Clinical Catalog</span>
                    <h3 class="text-2xl font-extrabold text-slate-900 tracking-tight">Our Services & Treatments</h3>
                    <p class="text-xs text-slate-500 mt-1 max-w-xl">
                        Explore our comprehensive clinical procedures conducted using hospital-grade sterilized instruments and certified dental materials. Click on any procedure to review clinical details and what to expect.
                    </p>
                </div>
                <div class="inline-flex items-center space-x-2 text-xs font-semibold text-slate-600 bg-slate-50 px-3.5 py-2 rounded-xl border border-slate-200 self-start md:self-auto">
                    <i data-lucide="mouse-pointer-click" class="w-4 h-4 text-slate-500"></i>
                    <span>Click any treatment to view details & pictures</span>
                </div>
            </div>

            <!-- Services Grid (Without Prices, Interactive Modal Triggers) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                <?php if (!empty($treatments)): ?>
                    <?php foreach ($treatments as $trt): 
                        $code = $trt['treatment_code'] ?? '';
                    ?>
                        <div onclick="openTreatmentModal('<?= htmlspecialchars($code) ?>')" 
                             class="bg-slate-50/70 hover:bg-white rounded-2xl p-5 border border-slate-200 hover:border-slate-300 shadow-2xs hover:shadow-md transition-all duration-200 flex flex-col justify-between cursor-pointer group relative overflow-hidden">
                            <!-- Top subtle highlight line on hover -->
                            <div class="absolute top-0 left-0 right-0 h-1 bg-slate-900 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            
                            <div>
                                <div class="flex items-start justify-between gap-2 mb-3">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-700 bg-white px-2.5 py-1 rounded-md border border-slate-200 shadow-2xs">
                                        <?= htmlspecialchars($trt['category']) ?>
                                    </span>
                                    <span class="inline-flex items-center space-x-1 text-[11px] font-semibold text-slate-400 group-hover:text-slate-900 bg-white group-hover:bg-slate-100 px-2 py-0.5 rounded-md border border-slate-200/80 transition">
                                        <i data-lucide="eye" class="w-3.5 h-3.5 text-slate-400 group-hover:text-slate-700"></i>
                                        <span>Details</span>
                                    </span>
                                </div>
                                <h4 class="font-bold text-slate-900 text-sm mb-1.5 group-hover:text-black transition leading-snug">
                                    <?= htmlspecialchars($trt['treatment_name']) ?>
                                </h4>
                                <p class="text-xs text-slate-500 leading-relaxed line-clamp-2">
                                    <?= htmlspecialchars($trt['description'] ?: 'Professional clinical dental procedure conducted by certified attending clinicians.') ?>
                                </p>
                            </div>
                            <div class="mt-4 pt-3 border-t border-slate-200/80 flex items-center justify-between text-xs">
                                <span class="text-slate-400 font-mono"><?= htmlspecialchars($trt['treatment_code']) ?></span>
                                <span class="text-slate-900 font-bold group-hover:underline inline-flex items-center space-x-1">
                                    <span>Learn More &rarr;</span>
                                </span>
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

    <!-- Treatment Detail Interactive Pop-up Modal -->
    <div id="treatmentDetailModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden" onclick="handleBackdropClick(event)">
        <div class="bg-white rounded-3xl shadow-2xl border border-slate-200 max-w-2xl w-full overflow-hidden animate-fade max-h-[92vh] flex flex-col" onclick="event.stopPropagation()">
            <!-- Modal Header Image Banner with Clinical Illustration -->
            <div class="relative bg-slate-950 h-52 sm:h-64 overflow-hidden flex-shrink-0 flex items-center justify-center border-b border-slate-800">
                <img id="modalTreatmentImage" src="" alt="Treatment Clinical Illustration" class="w-full h-full object-contain p-2">
                
                <!-- Close Button -->
                <button type="button" onclick="closeTreatmentModal()" 
                        class="absolute top-4 right-4 w-9 h-9 rounded-full bg-slate-900/80 hover:bg-slate-900 text-white flex items-center justify-center backdrop-blur-xs transition shadow-sm border border-white/10"
                        title="Close dialog (Esc)">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>

                <!-- Floating Badges on Banner -->
                <div class="absolute bottom-3 left-4 right-4 flex items-center justify-between pointer-events-none">
                    <span id="modalCategoryBadge" class="text-[10px] font-bold uppercase tracking-wider text-slate-900 bg-white px-2.5 py-1 rounded-md shadow-xs">
                    </span>
                    <span class="inline-flex items-center space-x-1.5 text-xs font-semibold px-2.5 py-1 rounded-md bg-slate-900/90 text-white backdrop-blur-xs border border-white/10">
                        <i data-lucide="clock" class="w-3.5 h-3.5 text-slate-300"></i>
                        <span id="modalDurationText"></span>
                    </span>
                </div>
            </div>

            <!-- Modal Scrollable Content -->
            <div class="p-6 overflow-y-auto flex-1 space-y-5">
                <div>
                    <div class="flex items-center space-x-2 text-slate-400 font-mono text-xs mb-1">
                        <span id="modalTreatmentCode" class="font-bold text-slate-500"></span>
                        <span>•</span>
                        <span>Certified Clinical Procedure</span>
                    </div>
                    <h3 id="modalTreatmentTitle" class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight leading-snug"></h3>
                    <p id="modalOverviewText" class="text-xs sm:text-sm text-slate-600 leading-relaxed mt-2.5"></p>
                </div>

                <!-- Step-by-Step Procedure Guide -->
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200">
                    <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-3 flex items-center space-x-1.5">
                        <i data-lucide="stethoscope" class="w-4 h-4 text-slate-700"></i>
                        <span>What to Expect During Your Visit</span>
                    </h4>
                    <ul id="modalStepsList" class="space-y-2.5 text-xs text-slate-600">
                        <!-- Populated dynamically via JS -->
                    </ul>
                </div>

                <!-- Clinical Indications & Care Advice -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div class="p-3.5 bg-slate-50/70 rounded-xl border border-slate-200">
                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block mb-1">Recommended For</span>
                        <p id="modalIndicationsText" class="text-xs text-slate-700 leading-relaxed"></p>
                    </div>
                    <div class="p-3.5 bg-slate-50/70 rounded-xl border border-slate-200">
                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block mb-1">Post-Treatment Guidance</span>
                        <p id="modalCareTipsText" class="text-xs text-slate-700 leading-relaxed"></p>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="p-4 px-6 bg-slate-50 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-3 flex-shrink-0">
                <span class="text-xs text-slate-500">Ready to consult with our attending dentists?</span>
                <div class="flex items-center space-x-2.5 w-full sm:w-auto">
                    <button type="button" onclick="closeTreatmentModal()" class="w-1/2 sm:w-auto px-4 py-2.5 bg-white hover:bg-slate-100 text-slate-700 font-semibold text-xs rounded-xl border border-slate-200 transition">
                        Close
                    </button>
                    <button type="button" onclick="bookCurrentTreatment()" class="w-1/2 sm:w-auto px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-xs transition inline-flex items-center justify-center space-x-2">
                        <span>Book This Treatment</span>
                        <i data-lucide="arrow-down" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();

        // Treatment clinical metadata payload
        const treatmentDataMap = <?= json_encode($treatmentModalPayload ?? []) ?>;
        let selectedTreatmentName = '';

        function openTreatmentModal(code) {
            const data = treatmentDataMap[code];
            if (!data) return;

            selectedTreatmentName = data.name;
            document.getElementById('modalTreatmentImage').src = data.image;
            document.getElementById('modalCategoryBadge').textContent = data.category;
            document.getElementById('modalDurationText').textContent = data.duration;
            document.getElementById('modalTreatmentCode').textContent = code;
            document.getElementById('modalTreatmentTitle').textContent = data.name;
            document.getElementById('modalOverviewText').textContent = data.overview;
            document.getElementById('modalIndicationsText').textContent = data.indications;
            document.getElementById('modalCareTipsText').textContent = data.care_tips;

            const stepsList = document.getElementById('modalStepsList');
            stepsList.innerHTML = '';
            if (data.steps && data.steps.length) {
                data.steps.forEach(function(step) {
                    const li = document.createElement('li');
                    li.className = 'flex items-start space-x-2.5';
                    li.innerHTML = '<span class="w-4 h-4 rounded-full bg-slate-200 text-slate-700 flex items-center justify-center flex-shrink-0 text-[10px] font-bold mt-0.5">✓</span><span>' + step + '</span>';
                    stepsList.appendChild(li);
                });
            }

            const modal = document.getElementById('treatmentDetailModal');
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            lucide.createIcons();
        }

        function closeTreatmentModal() {
            const modal = document.getElementById('treatmentDetailModal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        }

        function handleBackdropClick(event) {
            if (event.target.id === 'treatmentDetailModal') {
                closeTreatmentModal();
            }
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeTreatmentModal();
            }
        });

        function bookCurrentTreatment() {
            const name = selectedTreatmentName;
            closeTreatmentModal();
            prefillConcern(name);
            const target = document.getElementById('booking-form');
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
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
