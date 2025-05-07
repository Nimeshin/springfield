<?php
require_once 'config.php';

echo '<h1>Database Connection Test</h1>';

if(isset($conn) && $conn instanceof mysqli && !$conn->connect_error) {
    echo '<div style="color: green; padding: 10px; border: 1px solid green; background-color: #eaffea; margin: 20px 0;">';
    echo '<p><strong>Success!</strong> Connected to the database successfully.</p>';
    echo '<p>Database name: ' . DB_NAME . '</p>';
    echo '<p>PHP version: ' . phpversion() . '</p>';
    echo '<p>MySQL version: ' . $conn->server_info . '</p>';
    
    // Test query
    $result = $conn->query("SHOW TABLES");
    if($result) {
        echo '<h3>Tables in database:</h3>';
        echo '<ul>';
        while($row = $result->fetch_array()) {
            echo '<li>' . $row[0] . '</li>';
        }
        echo '</ul>';
    }
    
    echo '</div>';
} else {
    echo '<div style="color: red; padding: 10px; border: 1px solid red; background-color: #ffeeee; margin: 20px 0;">';
    echo '<p><strong>Error!</strong> Could not connect to the database.</p>';
    echo '<p>Please check your database credentials in config.php:</p>';
    echo '<ul>';
    echo '<li>DB_SERVER: ' . DB_SERVER . '</li>';
    echo '<li>DB_USERNAME: ' . DB_USERNAME . '</li>';
    echo '<li>DB_NAME: ' . DB_NAME . '</li>';
    echo '</ul>';
    echo '<p>Error message: ' . ($conn->connect_error ?? 'Unknown error') . '</p>';
    echo '</div>';
}
?> 