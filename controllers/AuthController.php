<?php
/**
 * SDC Dental Care Management System
 * Authentication Controller
 */

require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../models/User.php';

class AuthController {
    public function showLogin(): void {
        if (Session::isLoggedIn()) {
            header('Location: ' . BASE_URL . '/index.php?page=dashboard');
            exit;
        }
        require_once __DIR__ . '/../views/auth/login.php';
    }

    public function login(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/index.php?page=login');
            exit;
        }

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            Session::flash('error', 'Please enter both username and password.');
            header('Location: ' . BASE_URL . '/index.php?page=login');
            exit;
        }

        $user = User::authenticate($username, $password);

        if ($user) {
            Session::set('user_id', $user['id']);
            Session::set('user', $user);
            Session::flash('success', "Welcome back, " . htmlspecialchars($user['full_name']) . "!");

            // Role-based routing
            if ($user['role'] === 'admin') {
                header('Location: ' . BASE_URL . '/index.php?page=dashboard');
            } elseif ($user['role'] === 'dentist') {
                header('Location: ' . BASE_URL . '/index.php?page=dentist_dashboard');
            } elseif ($user['role'] === 'assistant') {
                header('Location: ' . BASE_URL . '/index.php?page=inventory');
            } else {
                header('Location: ' . BASE_URL . '/index.php?page=appointments');
            }
            exit;
        } else {
            Session::flash('error', 'Invalid username or password.');
            header('Location: ' . BASE_URL . '/index.php?page=login');
            exit;
        }
    }

    public function logout(): void {
        session_unset();
        session_destroy();
        session_start();
        Session::flash('info', 'You have been successfully logged out.');
        header('Location: ' . BASE_URL . '/index.php?page=login');
        exit;
    }
}

