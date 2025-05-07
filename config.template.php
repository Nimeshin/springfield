<?php
declare(strict_types=1);

// Database configuration
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'database_username');
define('DB_PASSWORD', 'database_password');
define('DB_NAME', 'database_name');

// Add compatibility constants for admin panel
define('DB_HOST', DB_SERVER);
define('DB_USER', DB_USERNAME); 
define('DB_PASS', DB_PASSWORD);

// Establish database connection
try {
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    
    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    // Log error to file instead of displaying to user
    error_log($e->getMessage());
    // Don't display actual error to users in production
    // echo "Database connection error. Please try again later.";
}

// Site settings
define('SITE_NAME', 'Springfield Panel and Paint');
define('SITE_EMAIL', 'info@example.com');
define('SITE_PHONE', 'your-phone-number');
define('SITE_ADDRESS', 'your-address');

// Error reporting - change to 0 in production
error_reporting(E_ALL);
ini_set('display_errors', '1');
?> 