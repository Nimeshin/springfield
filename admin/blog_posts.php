<?php
declare(strict_types=1);

/**
 * Admin Panel - Blog Posts Management
 * 
 * Handles listing, creating, editing, and deleting blog posts.
 */

// Start session
session_start();

// Include configuration and functions
require_once '../config.php';
require_once '../includes/functions.php';

// Include the upload handler
require_once 'includes/upload_handler.php';

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
$action = $_GET['action'] ?? 'list';
$post_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$messages = [];

// Delete post
if ($action === 'delete' && $post_id > 0) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
        try {
            // Get post info for activity log
            $stmt = $db->prepare('SELECT title FROM blog_posts WHERE id = ?');
            $stmt->execute([$post_id]);
            $post = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Delete the post
            $stmt = $db->prepare('DELETE FROM blog_posts WHERE id = ?');
            $stmt->execute([$post_id]);
            
            // Log the action
            $stmt = $db->prepare('INSERT INTO admin_activity_logs (user_id, username, action, details) VALUES (?, ?, ?, ?)');
            $stmt->execute([
                $_SESSION['admin_id'] ?? null,
                $_SESSION['admin_username'] ?? 'Unknown',
                'delete_blog_post',
                'Deleted blog post: ' . ($post['title'] ?? 'Unknown post')
            ]);
            
            $messages[] = [
                'type' => 'success',
                'text' => 'Blog post deleted successfully.'
            ];
            
            // Redirect to list
            header('Location: blog_posts.php?deleted=1');
            exit;
        } catch (PDOException $e) {
            $messages[] = [
                'type' => 'error',
                'text' => 'Error deleting post: ' . $e->getMessage()
            ];
        }
    }
}

// Save post (create or edit)
if (($action === 'new' || $action === 'edit') && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $author = $_POST['author'] ?? $_SESSION['admin_username'] ?? 'Admin';
    $image = $_POST['image'] ?? '';
    
    // Handle file upload if present
    if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] !== UPLOAD_ERR_NO_FILE) {
        $uploadResult = handleImageUpload($_FILES['featured_image']);
        if ($uploadResult['success']) {
            $image = $uploadResult['path'];
        } else {
            $errors[] = $uploadResult['message'];
        }
    }
    
    $errors = [];
    
    // Validate input
    if (empty($title)) {
        $errors[] = 'Title is required.';
    }
    
    if (empty($content)) {
        $errors[] = 'Content is required.';
    }
    
    if (empty($errors)) {
        try {
            if ($action === 'edit' && $post_id > 0) {
                // Update existing post
                $stmt = $db->prepare('UPDATE blog_posts SET title = ?, content = ?, author = ?, image = ? WHERE id = ?');
                $stmt->execute([$title, $content, $author, $image, $post_id]);
                
                // Log the action
                $stmt = $db->prepare('INSERT INTO admin_activity_logs (user_id, username, action, details) VALUES (?, ?, ?, ?)');
                $stmt->execute([
                    $_SESSION['admin_id'] ?? null,
                    $_SESSION['admin_username'] ?? 'Unknown',
                    'edit_blog_post',
                    'Edited blog post: ' . $title
                ]);
                
                $messages[] = [
                    'type' => 'success',
                    'text' => 'Blog post updated successfully.'
                ];
            } else {
                // Create new post
                $stmt = $db->prepare('INSERT INTO blog_posts (title, content, author, image) VALUES (?, ?, ?, ?)');
                $stmt->execute([$title, $content, $author, $image]);
                
                // Get the new post ID
                $post_id = $db->lastInsertId();
                
                // Log the action
                $stmt = $db->prepare('INSERT INTO admin_activity_logs (user_id, username, action, details) VALUES (?, ?, ?, ?)');
                $stmt->execute([
                    $_SESSION['admin_id'] ?? null,
                    $_SESSION['admin_username'] ?? 'Unknown',
                    'create_blog_post',
                    'Created new blog post: ' . $title
                ]);
                
                $messages[] = [
                    'type' => 'success',
                    'text' => 'Blog post created successfully.'
                ];
                
                // Redirect to edit mode
                header('Location: blog_posts.php?action=edit&id=' . $post_id . '&created=1');
                exit;
            }
        } catch (PDOException $e) {
            $messages[] = [
                'type' => 'error',
                'text' => 'Error saving post: ' . $e->getMessage()
            ];
        }
    } else {
        foreach ($errors as $error) {
            $messages[] = [
                'type' => 'error',
                'text' => $error
            ];
        }
    }
}

// Get blog post for editing
$post = null;
if ($action === 'edit' && $post_id > 0) {
    try {
        $stmt = $db->prepare('SELECT * FROM blog_posts WHERE id = ?');
        $stmt->execute([$post_id]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$post) {
            $messages[] = [
                'type' => 'error',
                'text' => 'Blog post not found.'
            ];
            $action = 'list'; // Fall back to list view
        }
    } catch (PDOException $e) {
        $messages[] = [
            'type' => 'error',
            'text' => 'Error fetching post: ' . $e->getMessage()
        ];
        $action = 'list'; // Fall back to list view
    }
}

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

<!-- Main Content Container -->
<div class="w-full max-w-full">
    <!-- Header with New Post Button -->
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-800"><?= htmlspecialchars($pageTitle) ?></h1>
        <?php if ($action === 'list'): ?>
            <a href="blog_posts.php?action=new" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                New Blog Post
            </a>
        <?php else: ?>
            <a href="blog_posts.php" class="px-4 py-2 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                Back to All Posts
            </a>
        <?php endif; ?>
    </div>
    
    <!-- Messages -->
    <?php if (!empty($messages)): ?>
        <?php foreach ($messages as $message): ?>
            <div class="mb-6 p-4 rounded-lg <?= $message['type'] === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                <?= htmlspecialchars($message['text']) ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <?php if (isset($_GET['created']) && $_GET['created'] == 1): ?>
        <div class="mb-6 p-4 rounded-lg bg-green-100 text-green-700">
            Blog post created successfully.
        </div>
    <?php endif; ?>
    
    <?php if (isset($_GET['deleted']) && $_GET['deleted'] == 1): ?>
        <div class="mb-6 p-4 rounded-lg bg-green-100 text-green-700">
            Blog post deleted successfully.
        </div>
    <?php endif; ?>
    
    <?php if ($action === 'list'): ?>
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
    <?php elseif ($action === 'new' || $action === 'edit'): ?>
        <!-- Blog Post Form -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">
                    <?= $action === 'new' ? 'Create New Post' : 'Edit Post' ?>
                </h2>
            </div>
            <div class="p-6">
                <form method="post" action="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                        <input
                            type="text"
                            id="title"
                            name="title"
                            value="<?= htmlspecialchars($post['title'] ?? '') ?>"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                            required
                        >
                    </div>
                    
                    <div class="mb-4">
                        <label for="author" class="block text-sm font-medium text-gray-700 mb-2">Author</label>
                        <input
                            type="text"
                            id="author"
                            name="author"
                            value="<?= htmlspecialchars($post['author'] ?? ($_SESSION['admin_username'] ?? 'Admin')) ?>"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        >
                    </div>
                    
                    <div class="mb-4">
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Featured Image</label>
                        <div class="flex items-center space-x-4">
                            <input
                                type="text"
                                id="image"
                                name="image"
                                value="<?= htmlspecialchars($post['image'] ?? '') ?>"
                                placeholder="images/blog/your-image.jpg"
                                class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                            >
                            <label class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 cursor-pointer">
                                Browse
                                <input
                                    type="file"
                                    name="featured_image"
                                    accept="image/jpeg,image/png,image/gif,image/webp"
                                    class="hidden"
                                    onchange="updateImagePath(this)"
                                >
                            </label>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">
                            Upload an image or provide a relative path from your website root. Example: images/blog/post1.jpg
                        </p>
                        <?php if (!empty($post['image'])): ?>
                            <div class="mt-2">
                                <img src="../<?= htmlspecialchars($post['image']) ?>" alt="Current featured image" class="max-w-xs rounded-lg shadow-sm">
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-4">
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Content</label>
                        <textarea
                            id="content"
                            name="content"
                            rows="15"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 formatted-textarea"
                            required
                        ><?= htmlspecialchars($post['content'] ?? '') ?></textarea>
                        <p class="mt-1 text-sm text-gray-500">
                            You can use HTML tags for formatting or select text and use the formatting buttons above.
                        </p>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <a href="blog_posts.php" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Cancel
                        </a>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm font-medium hover:bg-indigo-700">
                            <?= $action === 'new' ? 'Create Post' : 'Update Post' ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    <?php elseif ($action === 'delete'): ?>
        <!-- Delete Confirmation -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 bg-red-50 border-b border-red-200">
                <h2 class="text-lg font-semibold text-red-800">Confirm Deletion</h2>
            </div>
            <div class="p-6">
                <p class="text-gray-700 mb-6">
                    Are you sure you want to delete this blog post? This action cannot be undone.
                </p>
                
                <form method="post" action="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>">
                    <input type="hidden" name="confirm_delete" value="1">
                    <div class="flex justify-end space-x-3">
                        <a href="blog_posts.php" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Cancel
                        </a>
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md text-sm font-medium hover:bg-red-700">
                            Delete Post
                        </button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
// Simple client-side validation
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const title = document.getElementById('title').value.trim();
            const content = document.getElementById('content').value.trim();
            
            if (!title || !content) {
                e.preventDefault();
                alert('Please fill in all required fields');
            }
        });
    }
});

// Handle file selection
function updateImagePath(input) {
    if (input.files && input.files[0]) {
        const imageInput = document.getElementById('image');
        imageInput.value = input.files[0].name;
    }
}
</script>

<?php
// Include admin footer
include 'includes/admin_footer.php';
?> 