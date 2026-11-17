<?php
/**
 * SDC Dental Care Management System
 * Database Installer & Schema Seeder (CLI & Web UI)
 */

$isCli = (php_sapi_name() === 'cli');

$dbHost = '127.0.0.1';
$dbPort = '3306';
$dbUser = 'root';
$dbPass = '';
$dbName = 'sdc_dental_db';

$messages = [];
$error = null;
$success = false;

if ($isCli || ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' || isset($_GET['auto'])) {
    try {
        // Connect to MySQL server without database selected
        $pdo = new PDO("mysql:host=$dbHost;port=$dbPort;charset=utf8mb4", $dbUser, $dbPass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
        $messages[] = "Connected to MySQL Server successfully.";

        // Create Database if not exists
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
        $pdo->exec("USE `$dbName`;");
        $messages[] = "Database `$dbName` initialized.";

        // Execute Schema SQL
        $schemaFile = __DIR__ . '/database/schema.sql';
        if (!file_exists($schemaFile)) {
            throw new Exception("schema.sql not found at $schemaFile");
        }
        $schemaSql = file_get_contents($schemaFile);
        $pdo->exec($schemaSql);
        $messages[] = "Database tables and relationships created successfully.";

        // Execute Seeders SQL
        $seedersFile = __DIR__ . '/database/seeders.sql';
        if (file_exists($seedersFile)) {
            $seedersSql = file_get_contents($seedersFile);
            $pdo->exec($seedersSql);
            $messages[] = "Initial seeds (branches, accounts, treatments, supplies, patients) loaded successfully.";
        }

        // Ensure Upload Folders Exist
        $dirs = [
            __DIR__ . '/uploads',
            __DIR__ . '/uploads/patients',
            __DIR__ . '/uploads/expenses'
        ];
        foreach ($dirs as $dir) {
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
        }
        $messages[] = "Storage upload directories created.";

        $success = true;
    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}

if ($isCli) {
    echo "========================================\n";
    echo "  SDC DENTAL CARE - DATABASE INSTALLER  \n";
    echo "========================================\n";
    foreach ($messages as $msg) {
        echo "[OK] $msg\n";
    }
    if ($error) {
        echo "[ERROR] $error\n";
        exit(1);
    }
    echo "Installation Completed Successfully!\n";
    echo "Default Login Credentials:\n";
    echo " - Admin: username 'admin' | password 'password123'\n";
    echo " - Dentist: username 'dr.maria' | password 'password123'\n";
    echo " - Front Desk: username 'reception' | password 'password123'\n";
    echo " - Assistant: username 'assistant' | password 'password123'\n";
    exit(0);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Setup - SDC Dental Care</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4 font-sans">
    <div class="max-w-xl w-full bg-white rounded-3xl shadow-xl border border-slate-200 overflow-hidden">
        <div class="bg-slate-900 p-7 text-white text-center border-b border-slate-800">
            <div class="w-16 h-16 mx-auto mb-2.5 bg-slate-950 rounded-2xl p-2 shadow-xs flex items-center justify-center">
                <img src="assets/images/logo.png" alt="SDC Dental Care" class="w-full h-full object-contain">
            </div>
            <h1 class="text-xl font-extrabold tracking-tight uppercase">SDC DENTAL CARE</h1>
            <p class="text-slate-400 text-xs tracking-wider uppercase mt-0.5 font-medium">System Database & Setup Utility</p>
        </div>

        <div class="p-6">
            <?php if ($success): ?>
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 mb-6">
                    <div class="flex items-center space-x-2 font-semibold text-emerald-900 mb-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Database Installed & Seeded Successfully!</span>
                    </div>
                    <ul class="text-xs space-y-1 text-emerald-700 pl-6 list-disc">
                        <?php foreach ($messages as $m): ?>
                            <li><?= htmlspecialchars($m) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 mb-6">
                    <h3 class="font-semibold text-slate-800 text-sm mb-2">Demo User Accounts (Password: <code class="text-teal-700 font-mono">password123</code>)</h3>
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <div class="p-2 bg-white rounded border border-slate-200">
                            <span class="font-bold text-slate-700">Admin:</span> <span class="text-slate-600">admin</span>
                        </div>
                        <div class="p-2 bg-white rounded border border-slate-200">
                            <span class="font-bold text-slate-700">Dentist:</span> <span class="text-slate-600">dr.maria</span>
                        </div>
                        <div class="p-2 bg-white rounded border border-slate-200">
                            <span class="font-bold text-slate-700">Front Desk:</span> <span class="text-slate-600">reception</span>
                        </div>
                        <div class="p-2 bg-white rounded border border-slate-200">
                            <span class="font-bold text-slate-700">Assistant:</span> <span class="text-slate-600">assistant</span>
                        </div>
                    </div>
                </div>

                <div class="flex space-x-3">
                    <a href="index.php?page=login" class="flex-1 text-center py-2.5 px-4 bg-zinc-900 hover:bg-black text-white font-medium rounded-xl shadow-sm transition">
                        Proceed to Login
                    </a>
                    <a href="index.php" class="flex-1 text-center py-2.5 px-4 bg-zinc-100 hover:bg-zinc-200 text-zinc-800 font-medium rounded-xl border border-zinc-300 transition">
                        Clinic Website
                    </a>
                </div>

            <?php elseif ($error): ?>
                <div class="bg-rose-50 border border-rose-200 text-rose-800 rounded-xl p-4 mb-6">
                    <div class="flex items-center space-x-2 font-semibold text-rose-900 mb-1">
                        <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Installation Error</span>
                    </div>
                    <p class="text-xs text-rose-700"><?= htmlspecialchars($error) ?></p>
                    <p class="text-xs text-slate-600 mt-2">Make sure Apache and MySQL services are running in your <strong>XAMPP Control Panel</strong>.</p>
                </div>
                <form method="POST">
                    <button type="submit" class="w-full py-3 bg-zinc-900 hover:bg-black text-white font-semibold rounded-xl shadow transition">
                        Retry Installation
                    </button>
                </form>
            <?php else: ?>
                <p class="text-sm text-slate-600 mb-6 leading-relaxed">
                    Click the button below to automatically initialize the <strong>sdc_dental_db</strong> MySQL database, construct tables, and seed test branches, dental treatments, commission rates, and sample clinic records.
                </p>
                <form method="POST">
                    <button type="submit" class="w-full py-3 bg-zinc-900 hover:bg-black text-white font-semibold rounded-xl shadow-md transition flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        <span>Start System Setup</span>
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

