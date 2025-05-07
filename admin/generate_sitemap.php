<?php
declare(strict_types=1);

/**
 * Admin Panel - Generate Sitemap
 * 
 * Admin wrapper for the sitemap generator.
 * Allows admins to regenerate the sitemap from the admin panel.
 */

// Start session
session_start();

// Include configuration and functions
require_once '../config.php';
require_once '../includes/functions.php';

// Authentication check
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

// Check for session timeout (30 minutes)
if (isset($_SESSION['admin_last_activity']) && (time() - $_SESSION['admin_last_activity'] > 1800)) {
    // Session expired
    session_unset();
    session_destroy();
    header('Location: index.php?timeout=1');
    exit;
}

// Update last activity time
$_SESSION['admin_last_activity'] = time();

// Generate sitemap
$result = false;
$messages = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Include the sitemap generator
        require_once '../generate_sitemap.php';
        
        // The script above should have already generated the sitemap
        $messages[] = [
            'type' => 'success',
            'text' => 'Sitemap has been successfully generated at ' . date('Y-m-d H:i:s')
        ];
        
        $result = true;
        
        // Log the action
        try {
            $db = new PDO(
                'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
                DB_USER,
                DB_PASS,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            
            $stmt = $db->prepare('INSERT INTO admin_activity_logs (user_id, username, action, details) VALUES (?, ?, ?, ?)');
            $stmt->execute([
                $_SESSION['admin_id'] ?? null,
                $_SESSION['admin_username'] ?? 'Unknown',
                'generate_sitemap',
                'Generated sitemap.xml'
            ]);
        } catch (PDOException $e) {
            error_log('Error logging sitemap generation: ' . $e->getMessage());
        }
    } catch (Exception $e) {
        $messages[] = [
            'type' => 'error',
            'text' => 'Error generating sitemap: ' . $e->getMessage()
        ];
        error_log('Sitemap generation error: ' . $e->getMessage());
    }
}

// Get sitemap info
$sitemapFile = '../sitemap.xml';
$sitemapInfo = [
    'exists' => file_exists($sitemapFile),
    'size' => file_exists($sitemapFile) ? filesize($sitemapFile) : 0,
    'modified' => file_exists($sitemapFile) ? date('Y-m-d H:i:s', filemtime($sitemapFile)) : 'Never',
    'url' => SITE_URL . '/sitemap.xml'
];

// Page title
$pageTitle = 'Generate Sitemap';

// Include admin header
include 'includes/admin_header.php';
?>

<div class="container px-6 py-8 mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">Generate Sitemap</h1>
        <a href="dashboard.php" class="px-4 py-2 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
            Back to Dashboard
        </a>
    </div>
    
    <!-- Messages -->
    <?php if (!empty($messages)): ?>
        <?php foreach ($messages as $message): ?>
            <div class="mb-6 p-4 rounded-lg <?= $message['type'] === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                <?= htmlspecialchars($message['text']) ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- Sitemap Information -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Sitemap Information</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-gray-600 mb-2">
                        <span class="font-medium">Status:</span> 
                        <?php if ($sitemapInfo['exists']): ?>
                            <span class="text-green-600">Generated</span>
                        <?php else: ?>
                            <span class="text-red-600">Not Generated</span>
                        <?php endif; ?>
                    </p>
                    <p class="text-gray-600 mb-2">
                        <span class="font-medium">Last Modified:</span> 
                        <?= htmlspecialchars($sitemapInfo['modified']) ?>
                    </p>
                    <p class="text-gray-600 mb-2">
                        <span class="font-medium">File Size:</span> 
                        <?= $sitemapInfo['exists'] ? round($sitemapInfo['size'] / 1024, 2) . ' KB' : 'N/A' ?>
                    </p>
                </div>
                <div>
                    <?php if ($sitemapInfo['exists']): ?>
                        <p class="text-gray-600 mb-2">
                            <span class="font-medium">URL:</span> 
                            <a href="<?= htmlspecialchars($sitemapInfo['url']) ?>" target="_blank" class="text-blue-600 hover:underline">
                                <?= htmlspecialchars($sitemapInfo['url']) ?>
                            </a>
                        </p>
                        <p class="text-gray-600 mb-2">
                            <span class="font-medium">View File:</span> 
                            <a href="../sitemap.xml" target="_blank" class="text-blue-600 hover:underline">View Sitemap Content</a>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Generate Sitemap Form -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Generate Sitemap</h2>
        </div>
        <div class="p-6">
            <p class="text-gray-600 mb-6">
                Generating a sitemap will create an XML file containing all the pages on your website.
                This helps search engines discover and index your content more efficiently.
            </p>
            
            <form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
                <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">
                    Generate Sitemap
                </button>
            </form>
        </div>
    </div>
    
    <!-- Sitemap Tips -->
    <div class="mt-6 bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Sitemap Tips</h2>
        </div>
        <div class="p-6">
            <ul class="list-disc pl-5 text-gray-600 space-y-2">
                <li>Submit your sitemap to search engines like Google and Bing through their webmaster tools.</li>
                <li>Regenerate your sitemap whenever you add new pages or make significant changes to your website.</li>
                <li>Your sitemap URL is automatically added to your robots.txt file.</li>
                <li>The sitemap includes all your main pages and published blog posts.</li>
            </ul>
        </div>
    </div>
</div>

<?php
// Include admin footer
include 'includes/admin_footer.php';
?> 