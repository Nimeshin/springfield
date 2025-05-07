<?php
declare(strict_types=1);

// Start session
session_start();

// Authentication check
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

// Set page title
$pageTitle = 'Admin Layout Test';

// Include admin header
include 'includes/admin_header.php';
?>

<!-- Test Content -->
<div class="w-full max-w-full">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Admin Layout Test</h1>
        <a href="#" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
            Test Button
        </a>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Layout Testing</h2>
        <p class="mb-4">This page is designed to test if the admin layout is working correctly.</p>
        <p class="mb-4">If you can see this content and the "Test Button" in the top right corner, the layout is working.</p>
        
        <div class="mt-6 p-4 bg-gray-100 rounded-lg">
            <h3 class="font-medium mb-2">Debug Information:</h3>
            <ul class="list-disc pl-5">
                <li>Current page: <?= $_SERVER['PHP_SELF'] ?></li>
                <li>User agent: <?= htmlspecialchars($_SERVER['HTTP_USER_AGENT']) ?></li>
                <li>Template files:
                    <ul class="list-disc pl-5 mt-1">
                        <li>Header: <?= realpath('includes/admin_header.php') ?></li>
                        <li>Footer: <?= realpath('includes/admin_footer.php') ?></li>
                        <li>CSS: <?= realpath('css/custom.css') ?></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Troubleshooting Steps</h2>
        <ol class="list-decimal pl-5">
            <li class="mb-2">Clear your browser cache (Ctrl+F5 or Cmd+Shift+R)</li>
            <li class="mb-2">Verify that all CSS files are loading correctly</li>
            <li class="mb-2">Check for any JavaScript errors in the browser console</li>
            <li class="mb-2">Try a different browser to see if the issue persists</li>
            <li class="mb-2">Check that you're not using any browser extensions that might interfere with the page</li>
            <li class="mb-2">Ensure that your WAMP server is properly configured</li>
        </ol>
        
        <div class="mt-6">
            <a href="blog_posts.php" class="inline-block px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                Try Blog Posts Page
            </a>
        </div>
    </div>
</div>

<?php
// Include admin footer
include 'includes/admin_footer.php';
?> 