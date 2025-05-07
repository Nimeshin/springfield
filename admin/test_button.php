<?php
declare(strict_types=1);

// Start session
session_start();

// Include configuration
require_once '../config.php';

// Authentication check
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

// Set page title
$pageTitle = 'Test Page';

// Include admin header
include 'includes/admin_header.php';
?>

<div class="container mx-auto px-6 py-8">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">Test Page</h1>
        <a href="#" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
            Test Button
        </a>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <p>This is a test page to check if buttons are displaying correctly.</p>
        <p>If you can see the "Test Button" in the top right corner of this page, then the issue is specific to the blog_posts.php page.</p>
        <p>Otherwise, there might be an issue with the admin template structure.</p>
    </div>
</div>

<?php
// Include admin footer
include 'includes/admin_footer.php';
?> 