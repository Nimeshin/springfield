<?php
declare(strict_types=1);

/**
 * Admin Panel - Dashboard
 * 
 * The main admin panel interface that shows key site statistics and provides
 * navigation to different admin functions.
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

// Initialize database connection
try {
    $db = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

// Fetch site statistics
$stats = [
    'blog_posts' => 0,
    'testimonials' => 0,
    'contact_messages' => 0,
    'recent_logins' => []
];

try {
    // Count blog posts
    $stmt = $db->query('SELECT COUNT(*) FROM blog_posts');
    $stats['blog_posts'] = (int)$stmt->fetchColumn();
    
    // Count testimonials
    $stmt = $db->query('SELECT COUNT(*) FROM testimonials');
    $stats['testimonials'] = (int)$stmt->fetchColumn();
    
    // Count contact messages
    $stmt = $db->query('SELECT COUNT(*) FROM contact_messages');
    $stats['contact_messages'] = (int)$stmt->fetchColumn();
    
    // Get recent logins
    $stmt = $db->query('
        SELECT username, ip_address, login_time, status 
        FROM admin_login_logs 
        ORDER BY login_time DESC 
        LIMIT 5
    ');
    $stats['recent_logins'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    error_log('Dashboard stats error: ' . $e->getMessage());
}

// Get recent contact messages
try {
    $stmt = $db->query('
        SELECT id, name, email, subject, message, created_at, is_read 
        FROM contact_messages 
        ORDER BY created_at DESC 
        LIMIT 5
    ');
    $recentMessages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log('Recent messages error: ' . $e->getMessage());
    $recentMessages = [];
}

// Page title
$pageTitle = 'Admin Dashboard';

// Include admin header
include 'includes/admin_header.php';
?>

<div class="container px-6 py-8 mx-auto">
    <h1 class="text-2xl font-semibold text-gray-800">Dashboard</h1>
    <p class="mt-2 text-gray-600">Welcome back, <?= htmlspecialchars($_SESSION['admin_username']) ?>!</p>
    
    <!-- Stats Cards -->
    <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <!-- Blog Posts -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-indigo-100 text-indigo-500">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h2 class="text-xl font-semibold text-gray-700">Blog Posts</h2>
                    <p class="mt-2 text-3xl font-bold text-gray-900"><?= $stats['blog_posts'] ?></p>
                </div>
            </div>
            <div class="mt-6">
                <a href="blog_posts.php" class="inline-block px-4 py-2 text-sm text-indigo-600 bg-indigo-100 rounded-lg hover:bg-indigo-200">Manage Posts</a>
            </div>
        </div>
        
        <!-- Testimonials -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-500">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h2 class="text-xl font-semibold text-gray-700">Testimonials</h2>
                    <p class="mt-2 text-3xl font-bold text-gray-900"><?= $stats['testimonials'] ?></p>
                </div>
            </div>
            <div class="mt-6">
                <a href="testimonials.php" class="inline-block px-4 py-2 text-sm text-green-600 bg-green-100 rounded-lg hover:bg-green-200">Manage Testimonials</a>
            </div>
        </div>
        
        <!-- Contact Messages -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-500">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h2 class="text-xl font-semibold text-gray-700">Contact Messages</h2>
                    <p class="mt-2 text-3xl font-bold text-gray-900"><?= $stats['contact_messages'] ?></p>
                </div>
            </div>
            <div class="mt-6">
                <a href="messages.php" class="inline-block px-4 py-2 text-sm text-yellow-600 bg-yellow-100 rounded-lg hover:bg-yellow-200">View Messages</a>
            </div>
        </div>
    </div>
    
    <!-- Recent Messages -->
    <div class="mt-8">
        <h2 class="text-xl font-semibold text-gray-800">Recent Messages</h2>
        <div class="mt-4 bg-white rounded-lg shadow-md overflow-hidden">
            <?php if (empty($recentMessages)): ?>
                <div class="p-6 text-gray-600">No recent messages.</div>
            <?php else: ?>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($recentMessages as $message): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($message['name']) ?></div>
                                    <div class="text-sm text-gray-500"><?= htmlspecialchars($message['email']) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?= htmlspecialchars($message['subject']) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500"><?= date('M d, Y', strtotime($message['created_at'])) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if ($message['is_read']): ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Read</span>
                                    <?php else: ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Unread</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="view_message.php?id=<?= $message['id'] ?>" class="text-indigo-600 hover:text-indigo-900">View</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
            <div class="bg-gray-50 px-6 py-3">
                <a href="messages.php" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">View all messages</a>
            </div>
        </div>
    </div>
    
    <!-- Recent Login Activity -->
    <div class="mt-8">
        <h2 class="text-xl font-semibold text-gray-800">Recent Login Activity</h2>
        <div class="mt-4 bg-white rounded-lg shadow-md overflow-hidden">
            <?php if (empty($stats['recent_logins'])): ?>
                <div class="p-6 text-gray-600">No recent login activity.</div>
            <?php else: ?>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($stats['recent_logins'] as $login): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?= htmlspecialchars($login['username']) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500"><?= htmlspecialchars($login['ip_address']) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500"><?= date('M d, Y H:i:s', strtotime($login['login_time'])) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if ($login['status'] === 'success'): ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Success</span>
                                    <?php else: ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Failed</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="mt-8">
        <h2 class="text-xl font-semibold text-gray-800">Quick Actions</h2>
        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <a href="blog_posts.php?action=new" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-indigo-100 text-indigo-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">New Blog Post</h3>
                    </div>
                </div>
            </a>
            
            <a href="testimonials.php?action=new" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">New Testimonial</h3>
                    </div>
                </div>
            </a>
            
            <a href="generate_sitemap.php" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">Update Sitemap</h3>
                    </div>
                </div>
            </a>
            
            <a href="check_links.php" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100 text-red-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">Check Links</h3>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<?php
// Include admin footer
include 'includes/admin_footer.php';
?> 