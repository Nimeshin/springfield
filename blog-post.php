<?php
declare(strict_types=1);

// Set page title
$page_title = 'Blog Post';

// Include header and functions
require_once 'includes/header.php';
require_once 'includes/functions.php';

// Get post ID from URL
$post_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Initialize post variable
$post = null;

try {
    // Connect to database
    $db = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    // Get the post
    $stmt = $db->prepare('SELECT * FROM blog_posts WHERE id = ?');
    $stmt->execute([$post_id]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$post) {
        // Post not found
        header('Location: blog.php');
        exit;
    }
    
    // Get recent posts
    $stmt = $db->prepare('SELECT * FROM blog_posts WHERE id != ? ORDER BY created_at DESC LIMIT 4');
    $stmt->execute([$post_id]);
    $recent_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    // Log error and redirect to blog page
    error_log("Database error: " . $e->getMessage());
    header('Location: blog.php');
    exit;
}

// Update page title with post title
$page_title = htmlspecialchars($post['title']) . ' - Blog';
?>

<!-- Blog Post Content -->
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Post Header -->
        <header class="mb-8">
            <h1 class="text-4xl font-bold mb-4"><?= htmlspecialchars($post['title']) ?></h1>
            <div class="text-gray-600">
                <span>
                    <i class="far fa-calendar-alt mr-1"></i> 
                    <?= date('F j, Y', strtotime($post['created_at'])) ?>
                </span>
            </div>
        </header>

        <!-- Post Image -->
        <?php if (!empty($post['image'])): ?>
        <div class="mb-8">
            <img src="<?= htmlspecialchars($post['image']) ?>" 
                 alt="<?= htmlspecialchars($post['title']) ?>" 
                 class="w-full h-auto rounded-lg shadow-lg">
        </div>
        <?php endif; ?>

        <!-- Post Content -->
        <div class="prose max-w-none mb-12">
            <?= nl2br(safe_html($post['content'])) ?>
        </div>
        
        <!-- Back to Blog -->
        <div class="text-center">
            <a href="blog.php" class="inline-block bg-primary text-white font-semibold py-2 px-4 rounded hover:bg-red-700 transition">
                <i class="fas fa-arrow-left mr-2"></i> Back to Blog
            </a>
        </div>
    </div>
</div>

<?php
// Include footer
require_once 'includes/footer.php';
?> 