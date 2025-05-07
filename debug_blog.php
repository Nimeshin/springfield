<?php
declare(strict_types=1);

// Display all errors
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>Blog Posts Debugging</h1>";

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
    
    // Check if blog_posts table exists
    echo "<h2>Blog Posts Table Check</h2>";
    
    $tables = $db->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<p>Tables in database:</p>";
    echo "<ul>";
    foreach ($tables as $table) {
        echo "<li>" . htmlspecialchars($table) . "</li>";
    }
    echo "</ul>";
    
    // Check if blog_posts table exists
    $blog_posts_exists = in_array('blog_posts', $tables);
    
    if ($blog_posts_exists) {
        echo "<p style='color:green'>blog_posts table exists!</p>";
        
        // Count posts
        $count = $db->query("SELECT COUNT(*) FROM blog_posts")->fetchColumn();
        echo "<p>Number of blog posts: " . $count . "</p>";
        
        if ($count > 0) {
            echo "<h3>First 5 blog posts:</h3>";
            $posts = $db->query("SELECT id, title, author, created_at FROM blog_posts ORDER BY created_at DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
            
            echo "<table border='1' cellpadding='5'>";
            echo "<tr><th>ID</th><th>Title</th><th>Author</th><th>Created At</th></tr>";
            
            foreach ($posts as $post) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars((string)$post['id']) . "</td>";
                echo "<td>" . htmlspecialchars($post['title']) . "</td>";
                echo "<td>" . htmlspecialchars($post['author']) . "</td>";
                echo "<td>" . htmlspecialchars($post['created_at']) . "</td>";
                echo "</tr>";
            }
            
            echo "</table>";
        }
    } else {
        echo "<p style='color:red'>blog_posts table does not exist!</p>";
        
        // Create the table
        echo "<h3>Creating blog_posts table...</h3>";
        
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
            echo "<p style='color:green'>Table created successfully!</p>";
            
            // Insert sample post
            $sample = $db->prepare("INSERT INTO blog_posts (title, content, author) VALUES (?, ?, ?)");
            $sample->execute([
                'Welcome to our Blog',
                '<p>This is our first blog post on the new website. We will be sharing updates, news, and helpful information related to panel beating and vehicle repairs.</p><p>Stay tuned for more content!</p>',
                'Admin'
            ]);
            
            echo "<p style='color:green'>Sample blog post created!</p>";
            
        } catch (PDOException $e) {
            echo "<p style='color:red'>Error creating table: " . $e->getMessage() . "</p>";
        }
    }
    
} catch (PDOException $e) {
    echo "<p style='color:red'>Database connection failed: " . $e->getMessage() . "</p>";
}
?> 