<?php
declare(strict_types=1);

/**
 * Database Initialization Script
 * 
 * This script creates all the required tables for the admin panel to function properly.
 * Run this once to set up the database structure.
 */

// Display all errors for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>Database Initialization</h1>";

// Include configuration
require_once '../config.php';

echo "<h2>Database Connection</h2>";

try {
    // Try to connect to database
    $db = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    echo "<p style='color:green'>Database connection successful!</p>";
    
    // Create required tables
    echo "<h2>Creating Required Tables</h2>";
    
    // blog_posts table
    $tables_created = [];
    
    try {
        $sql = "CREATE TABLE IF NOT EXISTS blog_posts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            content TEXT NOT NULL,
            author VARCHAR(100) NOT NULL,
            image VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        
        $db->exec($sql);
        $tables_created[] = "blog_posts";
        echo "<p style='color:green'>blog_posts table created or already exists.</p>";
        
        // Check if we need to add a sample post
        $count = $db->query("SELECT COUNT(*) FROM blog_posts")->fetchColumn();
        if ($count == 0) {
            $stmt = $db->prepare("INSERT INTO blog_posts (title, content, author) VALUES (?, ?, ?)");
            $stmt->execute([
                'Welcome to our Blog',
                '<p>This is our first blog post on the new website. We will be sharing updates, news, and helpful information related to panel beating and vehicle repairs.</p><p>Stay tuned for more content!</p>',
                'Admin'
            ]);
            echo "<p style='color:green'>Sample blog post created!</p>";
        } else {
            echo "<p>blog_posts table already has data (" . $count . " entries).</p>";
        }
    } catch (PDOException $e) {
        echo "<p style='color:red'>Error creating blog_posts table: " . $e->getMessage() . "</p>";
    }
    
    // admin_activity_logs table
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
        $tables_created[] = "admin_activity_logs";
        echo "<p style='color:green'>admin_activity_logs table created or already exists.</p>";
    } catch (PDOException $e) {
        echo "<p style='color:red'>Error creating admin_activity_logs table: " . $e->getMessage() . "</p>";
    }
    
    // admin_login_logs table
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
        $tables_created[] = "admin_login_logs";
        echo "<p style='color:green'>admin_login_logs table created or already exists.</p>";
    } catch (PDOException $e) {
        echo "<p style='color:red'>Error creating admin_login_logs table: " . $e->getMessage() . "</p>";
    }
    
    // testimonials table
    try {
        $sql = "CREATE TABLE IF NOT EXISTS testimonials (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            position VARCHAR(100),
            company VARCHAR(100),
            content TEXT NOT NULL,
            rating TINYINT UNSIGNED,
            is_featured BOOLEAN DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        
        $db->exec($sql);
        $tables_created[] = "testimonials";
        echo "<p style='color:green'>testimonials table created or already exists.</p>";
    } catch (PDOException $e) {
        echo "<p style='color:red'>Error creating testimonials table: " . $e->getMessage() . "</p>";
    }
    
    // contact_messages table
    try {
        $sql = "CREATE TABLE IF NOT EXISTS contact_messages (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(255) NOT NULL,
            phone VARCHAR(20),
            subject VARCHAR(255) NOT NULL,
            message TEXT NOT NULL,
            is_read BOOLEAN DEFAULT 0,
            is_replied BOOLEAN DEFAULT 0,
            reply_text TEXT,
            reply_date DATETIME,
            replied_by INT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            ip_address VARCHAR(45)
        )";
        
        $db->exec($sql);
        $tables_created[] = "contact_messages";
        echo "<p style='color:green'>contact_messages table created or already exists.</p>";
    } catch (PDOException $e) {
        echo "<p style='color:red'>Error creating contact_messages table: " . $e->getMessage() . "</p>";
    }
    
    // Check for admin_users table and create if needed
    try {
        $sql = "CREATE TABLE IF NOT EXISTS admin_users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            password_hash VARCHAR(255) NOT NULL,
            email VARCHAR(100) NOT NULL,
            active BOOLEAN DEFAULT 1,
            last_login DATETIME,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        
        $db->exec($sql);
        $tables_created[] = "admin_users";
        echo "<p style='color:green'>admin_users table created or already exists.</p>";
        
        // Check if admin user exists
        $admin_count = $db->query("SELECT COUNT(*) FROM admin_users WHERE username = 'admin'")->fetchColumn();
        if ($admin_count == 0) {
            // Create default admin user (username: admin, password: admin123)
            $stmt = $db->prepare("INSERT INTO admin_users (username, password_hash, email) VALUES (?, ?, ?)");
            $stmt->execute([
                'admin',
                password_hash('admin123', PASSWORD_DEFAULT),
                'admin@example.com'
            ]);
            echo "<p style='color:green'>Default admin user created with username 'admin' and password 'admin123'.</p>";
            echo "<p style='color:orange'><strong>Important:</strong> Please change the default password immediately after logging in!</p>";
        } else {
            echo "<p>Admin user already exists.</p>";
        }
    } catch (PDOException $e) {
        echo "<p style='color:red'>Error creating admin_users table: " . $e->getMessage() . "</p>";
    }
    
    echo "<h2>Summary</h2>";
    echo "<p>Successfully created or verified " . count($tables_created) . " tables:</p>";
    echo "<ul>";
    foreach ($tables_created as $table) {
        echo "<li>" . htmlspecialchars($table) . "</li>";
    }
    echo "</ul>";
    
    echo "<p>Your database is now set up properly for the admin panel!</p>";
    echo "<p><a href='blog_posts.php' style='color:blue;text-decoration:underline;'>Go to Blog Posts</a></p>";
    
} catch (PDOException $e) {
    echo "<p style='color:red'>Database connection failed: " . $e->getMessage() . "</p>";
    echo "<p>Please make sure the database settings in config.php are correct:</p>";
    echo "<ul>";
    echo "<li>DB_HOST: " . htmlspecialchars(DB_HOST) . "</li>";
    echo "<li>DB_NAME: " . htmlspecialchars(DB_NAME) . "</li>";
    echo "<li>DB_USER: " . htmlspecialchars(DB_USER) . "</li>";
    echo "</ul>";
}
?> 