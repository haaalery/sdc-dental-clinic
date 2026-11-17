<?php
/**
 * SDC Dental Care Management System
 * Session & Authentication Helper
 */

require_once __DIR__ . '/constants.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class Session {
    /**
     * Set a session variable
     */
    public static function set(string $key, mixed $value): void {
        $_SESSION[$key] = $value;
    }

    /**
     * Get a session variable
     */
    public static function get(string $key, mixed $default = null): mixed {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Check if a session variable exists
     */
    public static function has(string $key): bool {
        return isset($_SESSION[$key]);
    }

    /**
     * Remove a session variable
     */
    public static function remove(string $key): void {
        unset($_SESSION[$key]);
    }

    /**
     * Check if user is logged in
     */
    public static function isLoggedIn(): bool {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }

    /**
     * Get current logged-in user array
     */
    public static function user(): ?array {
        return $_SESSION['user'] ?? null;
    }

    /**
     * Get current user role
     */
    public static function role(): ?string {
        return $_SESSION['user']['role'] ?? null;
    }

    /**
     * Check if user has specific role or one of multiple roles
     */
    public static function hasRole(string|array $roles): bool {
        if (!self::isLoggedIn()) return false;
        $userRole = self::role();
        if (is_array($roles)) {
            return in_array($userRole, $roles, true);
        }
        return $userRole === $roles;
    }

    /**
     * Enforce login requirement
     */
    public static function requireLogin(): void {
        if (!self::isLoggedIn()) {
            self::flash('error', 'Please log in to continue.');
            header('Location: ' . BASE_URL . '/index.php?page=login');
            exit;
        }
    }

    /**
     * Enforce role requirement
     */
    public static function requireRole(string|array $roles): void {
        self::requireLogin();
        if (!self::hasRole($roles)) {
            self::flash('error', 'Access denied. You do not have permission to view this section.');
            header('Location: ' . BASE_URL . '/index.php?page=dashboard');
            exit;
        }
    }

    /**
     * Set or retrieve flash message
     */
    public static function flash(string $type, ?string $message = null): ?string {
        if ($message !== null) {
            $_SESSION['flash'][$type] = $message;
            return null;
        }

        if (isset($_SESSION['flash'][$type])) {
            $msg = $_SESSION['flash'][$type];
            unset($_SESSION['flash'][$type]);
            return $msg;
        }
        return null;
    }

    /**
     * Generate CSRF Token
     */
    public static function csrfToken(): string {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Validate CSRF Token
     */
    public static function validateCsrf(?string $token): bool {
        return !empty($token) && hash_equals($_SESSION['csrf_token'] ?? '', $token);
    }
}

