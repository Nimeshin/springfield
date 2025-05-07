<?php
declare(strict_types=1);

// Start session
session_start();

// Authentication check
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

// Redirect to the blog_posts.php page with the new action
header('Location: blog_posts.php?action=new');
exit;
?> 