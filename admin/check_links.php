<?php
declare(strict_types=1);

/**
 * Admin Panel - Check Links
 * 
 * Admin wrapper for the link checker.
 * Allows admins to check for broken links from the admin panel.
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

// Check links
$result = false;
$messages = [];
$linkCheckPath = '../link_report.html';
$linkCheckUrl = str_replace('admin/', '', dirname($_SERVER['PHP_SELF'])) . '/link_report.html';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Include the link checker
        require_once '../check_links.php';
        
        // The script above should have already generated the link report
        $messages[] = [
            'type' => 'success',
            'text' => 'Link check completed at ' . date('Y-m-d H:i:s') . '. View the report for details.'
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
                'check_links',
                'Generated link check report'
            ]);
        } catch (PDOException $e) {
            error_log('Error logging link check: ' . $e->getMessage());
        }
    } catch (Exception $e) {
        $messages[] = [
            'type' => 'error',
            'text' => 'Error checking links: ' . $e->getMessage()
        ];
        error_log('Link check error: ' . $e->getMessage());
    }
}

// Get link report info
$linkReportInfo = [
    'exists' => file_exists($linkCheckPath),
    'size' => file_exists($linkCheckPath) ? filesize($linkCheckPath) : 0,
    'modified' => file_exists($linkCheckPath) ? date('Y-m-d H:i:s', filemtime($linkCheckPath)) : 'Never',
    'url' => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$linkCheckUrl"
];

// Page title
$pageTitle = 'Check Links';

// Include admin header
include 'includes/admin_header.php';
?>

<div class="container px-6 py-8 mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800">Check Links</h1>
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
    
    <!-- Link Report Information -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Link Check Report</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-gray-600 mb-2">
                        <span class="font-medium">Status:</span> 
                        <?php if ($linkReportInfo['exists']): ?>
                            <span class="text-green-600">Generated</span>
                        <?php else: ?>
                            <span class="text-red-600">Not Generated</span>
                        <?php endif; ?>
                    </p>
                    <p class="text-gray-600 mb-2">
                        <span class="font-medium">Last Check:</span> 
                        <?= htmlspecialchars($linkReportInfo['modified']) ?>
                    </p>
                    <p class="text-gray-600 mb-2">
                        <span class="font-medium">Report Size:</span> 
                        <?= $linkReportInfo['exists'] ? round($linkReportInfo['size'] / 1024, 2) . ' KB' : 'N/A' ?>
                    </p>
                </div>
                <div>
                    <?php if ($linkReportInfo['exists']): ?>
                        <p class="text-gray-600 mb-2">
                            <span class="font-medium">View Report:</span> 
                            <a href="<?= htmlspecialchars($linkReportInfo['url']) ?>" target="_blank" class="text-blue-600 hover:underline">
                                Open Link Check Report
                            </a>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Check Links Form -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Run Link Check</h2>
        </div>
        <div class="p-6">
            <p class="text-gray-600 mb-6">
                Running a link check will scan your website for broken links and generate a detailed report.
                This process may take several minutes to complete, especially for larger websites.
            </p>
            
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            This process will check both internal and external links on your website. It may take some time to complete.
                        </p>
                    </div>
                </div>
            </div>
            
            <form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
                <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">
                    Check Links
                </button>
            </form>
        </div>
    </div>
    
    <!-- Link Check Tips -->
    <div class="mt-6 bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Link Check Tips</h2>
        </div>
        <div class="p-6">
            <ul class="list-disc pl-5 text-gray-600 space-y-2">
                <li>Regularly check for broken links to ensure a good user experience.</li>
                <li>Fix broken internal links immediately to maintain site integrity.</li>
                <li>For broken external links, either remove them or update them to working alternatives.</li>
                <li>Consider setting up automated link checks on a regular schedule.</li>
                <li>Broken links can negatively impact your SEO ranking, so it's important to fix them promptly.</li>
            </ul>
        </div>
    </div>
</div>

<?php
// Include admin footer
include 'includes/admin_footer.php';
?> 