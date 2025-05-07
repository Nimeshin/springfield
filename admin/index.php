<?php
declare(strict_types=1);

/**
 * Admin Panel - Login Page
 * 
 * This is the entry point to the admin area of the Springfield Panel and Paint website.
 * It handles user authentication and provides access to the admin dashboard.
 */

// Start session
session_start();

// Include configuration
require_once '../config.php';

// Redirect to dashboard if already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: dashboard.php');
    exit;
}

// Handle login form submission
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password';
    } else {
        try {
            // Connect to database
            $db = new PDO(
                'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
                DB_USER,
                DB_PASS,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            
            // Query to check admin credentials
            $stmt = $db->prepare('SELECT id, username, password_hash FROM admin_users WHERE username = ? AND active = 1 LIMIT 1');
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user && password_verify($password, $user['password_hash'])) {
                // Valid login
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $user['id'];
                $_SESSION['admin_username'] = $user['username'];
                $_SESSION['admin_last_activity'] = time();
                
                // Regenerate session ID to prevent session fixation
                session_regenerate_id(true);
                
                // Log successful login
                $ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
                $stmt = $db->prepare('INSERT INTO admin_login_logs (user_id, username, ip_address, status) VALUES (?, ?, ?, ?)');
                $stmt->execute([$user['id'], $user['username'], $ip, 'success']);
                
                // Redirect to dashboard
                header('Location: dashboard.php');
                exit;
            } else {
                // Invalid login
                $error = 'Invalid username or password';
                
                // Log failed login attempt
                $ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
                $stmt = $db->prepare('INSERT INTO admin_login_logs (username, ip_address, status) VALUES (?, ?, ?)');
                $stmt->execute([$username, $ip, 'failed']);
            }
        } catch (PDOException $e) {
            // Database error
            error_log('Login error: ' . $e->getMessage());
            $error = 'A system error occurred. Please try again later.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Springfield Panel and Paint</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7fafc;
        }
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="bg-white p-10 rounded-lg shadow-md w-full max-w-md">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Admin Login</h1>
                <p class="text-gray-600 mt-2">Springfield Panel and Paint</p>
            </div>
            
            <?php if (isset($_GET['timeout']) && $_GET['timeout'] == 1): ?>
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6" role="alert">
                    <p>Your session has timed out due to inactivity. Please log in again.</p>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_GET['db_error']) && $_GET['db_error'] == 1): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p>Database error: Required tables may not exist.</p>
                    <p class="mt-2">
                        <a href="db_init.php" class="text-red-700 font-bold underline">Click here to initialize the database</a>
                    </p>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($error)): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p><?= htmlspecialchars($error) ?></p>
                </div>
            <?php endif; ?>
            
            <form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
                <div class="mb-6">
                    <label for="username" class="block text-gray-700 text-sm font-semibold mb-2">Username</label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        class="w-full px-3 py-2 placeholder-gray-400 border rounded-lg focus:outline-none focus:ring focus:ring-indigo-200"
                        placeholder="Enter your username"
                        required
                    >
                </div>
                
                <div class="mb-6">
                    <label for="password" class="block text-gray-700 text-sm font-semibold mb-2">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="w-full px-3 py-2 placeholder-gray-400 border rounded-lg focus:outline-none focus:ring focus:ring-indigo-200"
                        placeholder="Enter your password"
                        required
                    >
                </div>
                
                <div class="mb-6">
                    <button
                        type="submit"
                        class="w-full py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50"
                    >
                        Login
                    </button>
                </div>
                
                <div class="text-center">
                    <a href="../index.php" class="text-sm text-indigo-600 hover:text-indigo-800">
                        Return to Homepage
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html> 