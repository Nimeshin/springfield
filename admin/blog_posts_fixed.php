<?php
declare(strict_types=1);

/**
 * Admin Panel - Blog Posts Management (Fixed Version)
 * 
 * Handles listing, creating, editing, and deleting blog posts.
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

// Handle actions (create, edit, delete)
$action = isset($_GET['action']) ? htmlspecialchars($_GET['action']) : 'list';
$post_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$messages = [];

// Get all blog posts for listing
$posts = [];
if ($action === 'list') {
    try {
        // First check if blog_posts table exists
        $tables = $db->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        if (!in_array('blog_posts', $tables)) {
            // Redirect to index page with database error flag
            header('Location: index.php?db_error=1');
            exit;
        }
        
        $stmt = $db->query('SELECT id, title, author, created_at, updated_at FROM blog_posts ORDER BY created_at DESC');
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Check if the error is about the table not existing
        if (strpos($e->getMessage(), "doesn't exist") !== false) {
            // Redirect to index page with database error flag
            header('Location: index.php?db_error=1');
            exit;
        }
        
        $messages[] = [
            'type' => 'error',
            'text' => 'Error fetching posts: ' . $e->getMessage()
        ];
    }
}

// Set page title based on action
$pageTitle = match($action) {
    'new' => 'Create New Blog Post',
    'edit' => 'Edit Blog Post',
    'delete' => 'Delete Blog Post',
    default => 'Blog Posts'
};

// Include admin header
include 'includes/admin_header.php';
?>

<div class="container mx-auto px-6 py-8">
    <div class="mb-6 flex items-center justify-between" style="display: flex !important;">
        <h1 class="text-2xl font-semibold text-gray-800"><?= htmlspecialchars($pageTitle) ?></h1>
        
        <!-- Always show this button for testing -->
        <a href="blog_posts.php?action=new" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700" style="display: inline-block !important;">
            New Blog Post (Fixed)
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
    
    <!-- Blog Posts List -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">All Blog Posts</h2>
        </div>
        <div class="overflow-x-auto">
            <?php if (empty($posts)): ?>
                <div class="p-6 text-center text-gray-500">
                    No blog posts found. <a href="blog_posts.php?action=new" class="text-indigo-600">Create your first post</a>.
                </div>
            <?php else: ?>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Updated</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($posts as $post): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($post['title']) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500"><?= htmlspecialchars($post['author']) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500"><?= date('M d, Y', strtotime($post['created_at'])) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500"><?= date('M d, Y', strtotime($post['updated_at'])) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="../blog-post.php?id=<?= $post['id'] ?>" target="_blank" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                    <a href="blog_posts.php?action=edit&id=<?= $post['id'] ?>" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                    <a href="blog_posts.php?action=delete&id=<?= $post['id'] ?>" class="text-red-600 hover:text-red-900">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
// Include admin footer
include 'includes/admin_footer.php';
?> 