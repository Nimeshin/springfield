<?php
declare(strict_types=1);

// Set page title
$page_title = 'Blog';

// Include header and functions
require_once 'includes/header.php';
require_once 'includes/functions.php';

// Get blog posts from database
try {
    $db = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    // Get total number of posts for pagination
    $total_posts = $db->query("SELECT COUNT(*) FROM blog_posts")->fetchColumn();
    
    // Calculate pagination
    $posts_per_page = 5;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $posts_per_page;
$total_pages = ceil($total_posts / $posts_per_page);

// Get current page posts
    $stmt = $db->prepare("SELECT * FROM blog_posts ORDER BY created_at DESC LIMIT ? OFFSET ?");
    $stmt->bindValue(1, $posts_per_page, PDO::PARAM_INT);
    $stmt->bindValue(2, $offset, PDO::PARAM_INT);
    $stmt->execute();
    $blog_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    // Log error and show friendly message
    error_log("Database error: " . $e->getMessage());
    $blog_posts = [];
    echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline"> Unable to load blog posts. Please try again later.</span>
          </div>';
}
?>

<!-- Hero Section -->
<section class="hero-section h-72" style="background-image: url('images/Springfield P&P-21.JPG');">
    <div class="hero-overlay w-full h-full flex flex-col justify-center items-center text-center p-4">
        <div class="container mx-auto">
            <h1 class="text-4xl md:text-6xl font-bold mb-4">Blog</h1>
            <p class="text-xl md:text-2xl">Insights & Updates from Springfield Panel and Paint</p>
        </div>
    </div>
</section>

<!-- Blog Content -->
<section class="py-16">
    <div class="container mx-auto px-4">
        <!-- Main Content -->
        <div>
            <h2 class="text-3xl font-bold mb-8">Latest Articles</h2>
            
            <?php if (empty($blog_posts)): ?>
                <div class="bg-gray-100 p-8 rounded-lg text-center">
                    <p class="text-xl text-gray-600">No articles found. Please check back later!</p>
                </div>
            <?php else: ?>
                <!-- Blog Posts -->
                <?php foreach ($blog_posts as $post): ?>
                    <article class="blog-card bg-white rounded-lg shadow-lg overflow-hidden mb-10">
                        <div class="md:flex">
                            <!-- Blog Image -->
                            <div class="md:w-1/3 h-full">
                                <img src="<?= htmlspecialchars($post['image']) ?>" alt="<?= htmlspecialchars($post['title']) ?>" class="w-full h-full object-cover">
                            </div>
                            
                            <!-- Blog Content -->
                            <div class="md:w-2/3 p-6">
                                <h3 class="text-2xl font-bold mb-3 text-primary"><?= htmlspecialchars($post['title']) ?></h3>
                                
                                <div class="text-gray-600 text-sm mb-4">
                                    <span>
                                        <i class="far fa-calendar-alt mr-1"></i> 
                                        <?= date('F j, Y', strtotime($post['created_at'])) ?>
                                    </span>
                                </div>
                                
                                <div class="text-gray-700 mb-4">
                                    <?= truncate_text(strip_tags($post['content']), 200) ?>
                                </div>
                                
                                <a href="blog-post.php?id=<?= htmlspecialchars((string)$post['id']) ?>" class="inline-block bg-primary text-white font-semibold py-2 px-4 rounded hover:bg-red-700 transition">Read More</a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
                
                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                    <div class="mt-10 flex justify-center">
                        <div class="flex space-x-2">
                            <?php if ($current_page > 1): ?>
                                <a href="?page=<?= $current_page - 1 ?>" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition">Previous</a>
                            <?php endif; ?>
                            
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <a href="?page=<?= $i ?>" class="px-4 py-2 <?= $i === $current_page ? 'bg-primary text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?> rounded transition">
                                    <?= $i ?>
                                </a>
                            <?php endfor; ?>
                            
                            <?php if ($current_page < $total_pages): ?>
                                <a href="?page=<?= $current_page + 1 ?>" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition">Next</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php
// Include footer
require_once 'includes/footer.php';
?> 