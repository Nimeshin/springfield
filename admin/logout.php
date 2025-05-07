<?php
declare(strict_types=1);

/**
 * Admin Panel - Logout
 * 
 * Handles user sign-out from the admin panel.
 * Destroys session and redirects to login page.
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include configuration
require_once '../config.php';

// Log the logout event if user was logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    try {
        // Connect to database
        $db = new PDO(
            'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
            DB_USER,
            DB_PASS,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        
        // Log logout
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
        $stmt = $db->prepare('INSERT INTO admin_login_logs (user_id, username, ip_address, status, notes) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([
            $_SESSION['admin_id'] ?? null,
            $_SESSION['admin_username'] ?? 'Unknown',
            $ip,
            'logout',
            'User logged out'
        ]);
    } catch (PDOException $e) {
        // Just log the error, don't stop the logout process
        error_log('Logout logging error: ' . $e->getMessage());
    }
}

// Clear all session variables
$_SESSION = [];

// If a session cookie is used, destroy it
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );
}

// Destroy the session
session_destroy();

// Redirect to login page
header('Location: index.php?logout=success');
exit; 