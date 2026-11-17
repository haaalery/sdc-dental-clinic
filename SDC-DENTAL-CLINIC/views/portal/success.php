<?php
require_once __DIR__ . '/../../config/constants.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmed - <?= APP_NAME ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-50 min-h-screen text-slate-800 antialiased flex flex-col items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl border border-slate-200 overflow-hidden text-center p-8">
        <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4">
            <i data-lucide="check" class="w-8 h-8"></i>
        </div>

        <h2 class="text-2xl font-bold text-slate-900 mb-1">Booking Submitted!</h2>
        <p class="text-xs text-slate-500 mb-6">Your online appointment request has been safely forwarded to our front desk team.</p>

        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 text-left text-xs space-y-2 mb-6">
            <div class="flex justify-between border-b border-slate-200 pb-2">
                <span class="text-slate-500">Booking Reference:</span>
                <span class="font-mono font-bold text-teal-700"><?= htmlspecialchars($appointment['appointment_code']) ?></span>
            </div>
            <div class="flex justify-between border-b border-slate-200 pb-2">
                <span class="text-slate-500">Patient Name:</span>
                <span class="font-semibold text-slate-800"><?= htmlspecialchars($appointment['guest_name'] ?? '') ?></span>
            </div>
            <div class="flex justify-between border-b border-slate-200 pb-2">
                <span class="text-slate-500">Schedule:</span>
                <span class="font-semibold text-slate-800"><?= date('F j, Y', strtotime($appointment['appointment_date'])) ?> at <?= date('h:i A', strtotime($appointment['appointment_time'])) ?></span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Dental Concern:</span>
                <span class="font-semibold text-slate-800 truncate max-w-[180px]"><?= htmlspecialchars($appointment['dental_concern']) ?></span>
            </div>
        </div>

        <div class="space-y-2">
            <a href="<?= BASE_URL ?>/index.php#booking-form" class="block w-full py-3 px-4 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-xl transition shadow">
                Book Another Appointment
            </a>
            <a href="<?= BASE_URL ?>/index.php" class="block w-full py-2.5 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl transition text-xs">
                Back to Clinic Home
            </a>
        </div>
    </div>
    <script>lucide.createIcons();</script>
</body>
</html>

