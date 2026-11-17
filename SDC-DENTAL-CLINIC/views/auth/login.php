<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../config/session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Login - <?= APP_NAME ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl border border-slate-200 overflow-hidden text-slate-800">
        <!-- Brand Header with Logo -->
        <div class="bg-white p-8 text-center border-b border-slate-100">
            <div class="w-20 h-20 mx-auto mb-3.5 bg-slate-900 rounded-2xl p-2.5 shadow-sm flex items-center justify-center">
                <img src="<?= BASE_URL ?>/assets/images/logo.png" alt="SDC Dental Care Logo" class="w-full h-full object-contain">
            </div>
            <h1 class="text-xl font-extrabold tracking-tight text-slate-900 uppercase"><?= APP_NAME ?></h1>
            <p class="text-slate-500 text-xs tracking-wider uppercase mt-1 font-semibold">Clinical & Staff Management Portal</p>
        </div>

        <div class="p-8 bg-white">
            <!-- Flash Messages -->
            <?php if ($error = Session::flash('error')): ?>
                <div class="mb-5 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-medium flex items-center space-x-2">
                    <i data-lucide="alert-circle" class="w-4 h-4 text-rose-500 flex-shrink-0"></i>
                    <span><?= htmlspecialchars($error) ?></span>
                </div>
            <?php endif; ?>

            <?php if ($info = Session::flash('info')): ?>
                <div class="mb-5 p-3.5 rounded-xl bg-slate-100 border border-slate-200 text-slate-700 text-xs font-medium flex items-center space-x-2">
                    <i data-lucide="info" class="w-4 h-4 text-slate-500 flex-shrink-0"></i>
                    <span><?= htmlspecialchars($info) ?></span>
                </div>
            <?php endif; ?>

            <form action="<?= BASE_URL ?>/index.php?page=do_login" method="POST" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Username or Email</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                            <i data-lucide="user" class="w-4 h-4"></i>
                        </span>
                        <input type="text" name="username" required autofocus placeholder="e.g. admin or dr.maria" 
                               class="w-full pl-10 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-slate-900 transition">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400">
                            <i data-lucide="lock" class="w-4 h-4"></i>
                        </span>
                        <input type="password" name="password" required placeholder="••••••••" 
                               class="w-full pl-10 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 focus:border-slate-900 transition">
                    </div>
                </div>

                <button type="submit" class="w-full py-3 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl shadow-xs transition flex items-center justify-center space-x-2 mt-2">
                    <span>Sign In to System</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </button>
            </form>

            <!-- Quick Demo Credentials helper -->
            <div class="mt-6 pt-5 border-t border-slate-100">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-2">Demo Credentials (Password: <code class="text-slate-700 font-mono font-semibold">password123</code>):</span>
                <div class="grid grid-cols-2 gap-2 text-[11px]">
                    <div class="p-2 bg-slate-50 rounded-xl border border-slate-200 text-slate-600">
                        <strong class="text-slate-900 block font-medium">Admin:</strong> <code class="text-slate-700">admin</code>
                    </div>
                    <div class="p-2 bg-slate-50 rounded-xl border border-slate-200 text-slate-600">
                        <strong class="text-slate-900 block font-medium">Dentist:</strong> <code class="text-slate-700">dr.maria</code>
                    </div>
                    <div class="p-2 bg-slate-50 rounded-xl border border-slate-200 text-slate-600">
                        <strong class="text-slate-900 block font-medium">Front Desk:</strong> <code class="text-slate-700">reception</code>
                    </div>
                    <div class="p-2 bg-slate-50 rounded-xl border border-slate-200 text-slate-600">
                        <strong class="text-slate-900 block font-medium">Assistant:</strong> <code class="text-slate-700">assistant</code>
                    </div>
                </div>
            </div>

            <!-- Link back to public landing page -->
            <div class="mt-6 text-center">
                <a href="<?= BASE_URL ?>/index.php" class="text-xs text-slate-500 hover:text-slate-900 font-medium inline-flex items-center space-x-1.5 transition">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                    <span>Back to Clinic Website</span>
                </a>
            </div>
        </div>
    </div>
    <script>lucide.createIcons();</script>
</body>
</html>
