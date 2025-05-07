<?php
declare(strict_types=1);

// Display all errors
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>Admin Activity Logs Debugging</h1>";

// Include configuration
require_once 'config.php';

echo "<h2>Database Connection Test</h2>";

try {
    // Try to connect to database
    $db = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    echo "<p style='color:green'>Database connection successful!</p>";
    
    // Check if admin_activity_logs table exists
    echo "<h2>Admin Activity Logs Table Check</h2>";
    
    $tables = $db->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    
    // Check if admin_activity_logs table exists
    $admin_logs_exists = in_array('admin_activity_logs', $tables);
    
    if ($admin_logs_exists) {
        echo "<p style='color:green'>admin_activity_logs table exists!</p>";
        
        // Count logs
        $count = $db->query("SELECT COUNT(*) FROM admin_activity_logs")->fetchColumn();
        echo "<p>Number of log entries: " . $count . "</p>";
    } else {
        echo "<p style='color:red'>admin_activity_logs table does not exist!</p>";
        
        // Create the table
        echo "<h3>Creating admin_activity_logs table...</h3>";
        
        try {
            $sql = "CREATE TABLE IF NOT EXISTS admin_activity_logs (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT,
                username VARCHAR(100) NOT NULL,
                action VARCHAR(100) NOT NULL,
                details TEXT,
                ip_address VARCHAR(45),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";
            
            $db->exec($sql);
            echo "<p style='color:green'>Table created successfully!</p>";
            
        } catch (PDOException $e) {
            echo "<p style='color:red'>Error creating table: " . $e->getMessage() . "</p>";
        }
    }
    
    // Check if admin_login_logs table exists (used by the admin dashboard)
    $admin_login_logs_exists = in_array('admin_login_logs', $tables);
    
    echo "<h2>Admin Login Logs Table Check</h2>";
    
    if ($admin_login_logs_exists) {
        echo "<p style='color:green'>admin_login_logs table exists!</p>";
        
        // Count logs
        $count = $db->query("SELECT COUNT(*) FROM admin_login_logs")->fetchColumn();
        echo "<p>Number of login log entries: " . $count . "</p>";
    } else {
        echo "<p style='color:red'>admin_login_logs table does not exist!</p>";
        
        // Create the table
        echo "<h3>Creating admin_login_logs table...</h3>";
        
        try {
            $sql = "CREATE TABLE IF NOT EXISTS admin_login_logs (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT,
                username VARCHAR(100) NOT NULL,
                ip_address VARCHAR(45) NOT NULL,
                login_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                status VARCHAR(20) NOT NULL
            )";
            
            $db->exec($sql);
            echo "<p style='color:green'>Table created successfully!</p>";
            
        } catch (PDOException $e) {
            echo "<p style='color:red'>Error creating table: " . $e->getMessage() . "</p>";
        }
    }
    
} catch (PDOException $e) {
    echo "<p style='color:red'>Database connection failed: " . $e->getMessage() . "</p>";
}
?> 