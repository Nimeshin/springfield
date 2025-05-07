<?php
require_once "config.php";

echo "<h1>Creating Admin User</h1>";

try {
    // Create admin_users table
    $sql = "CREATE TABLE IF NOT EXISTS admin_users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        email VARCHAR(100) NOT NULL,
        full_name VARCHAR(100),
        role VARCHAR(20) DEFAULT 'admin',
        active TINYINT(1) DEFAULT 1,
        last_login DATETIME,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    if ($conn->query($sql) === TRUE) {
        echo "<p>admin_users table created successfully</p>";
    } else {
        throw new Exception("Error creating admin_users table: " . $conn->error);
    }
    
    // Create admin_login_logs table
    $sql = "CREATE TABLE IF NOT EXISTS admin_login_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        username VARCHAR(50) NOT NULL,
        ip_address VARCHAR(45) NOT NULL,
        status VARCHAR(20) NOT NULL,
        notes TEXT,
        login_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES admin_users(id) ON DELETE SET NULL
    )";
    
    if ($conn->query($sql) === TRUE) {
        echo "<p>admin_login_logs table created successfully</p>";
    } else {
        throw new Exception("Error creating admin_login_logs table: " . $conn->error);
    }
    
    // Create admin_activity_logs table
    $sql = "CREATE TABLE IF NOT EXISTS admin_activity_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        username VARCHAR(50) NOT NULL,
        action VARCHAR(50) NOT NULL,
        details TEXT,
        ip_address VARCHAR(45),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES admin_users(id) ON DELETE SET NULL
    )";
    
    if ($conn->query($sql) === TRUE) {
        echo "<p>admin_activity_logs table created successfully</p>";
    } else {
        throw new Exception("Error creating admin_activity_logs table: " . $conn->error);
    }
    
    // Add admin user
    $username = "admin";
    $password = "Metrofile2025";
    $email = "admin@springfieldpanelandpaint.co.za";
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    
    // Check if user exists
    $stmt = $conn->prepare("SELECT id FROM admin_users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $stmt = $conn->prepare("UPDATE admin_users SET password_hash = ?, email = ? WHERE username = ?");
        $stmt->bind_param("sss", $password_hash, $email, $username);
        $stmt->execute();
        echo "<p style=\"color:green;\">Admin user updated successfully!</p>";
    } else {
        $stmt = $conn->prepare("INSERT INTO admin_users (username, password_hash, email) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $password_hash, $email);
        $stmt->execute();
        echo "<p style=\"color:green;\">Admin user created successfully!</p>";
    }
    
    echo "<h2>Admin Login Details</h2>";
    echo "<p>Username: <strong>".$username."</strong></p>";
    echo "<p>Password: <strong>".$password."</strong></p>";
    echo "<p>You can now <a href=\"admin/\">login to the admin panel</a>.</p>";
    
} catch (Exception $e) {
    echo "<p style=\"color:red;\">Error: " . $e->getMessage() . "</p>";
}
?> 