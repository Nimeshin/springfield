<?php
declare(strict_types=1);

/**
 * Admin Panel - Testimonials Management
 * 
 * Handles listing, creating, editing, and deleting testimonials.
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

// Create testimonials table if it doesn't exist
try {
    $db->exec('
        CREATE TABLE IF NOT EXISTS testimonials (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            position VARCHAR(100),
            company VARCHAR(100),
            content TEXT NOT NULL,
            rating TINYINT UNSIGNED,
            is_featured BOOLEAN DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    ');
} catch (PDOException $e) {
    die('Error creating testimonials table: ' . $e->getMessage());
}

// Handle actions (create, edit, delete)
$action = $_GET['action'] ?? 'list';
$testimonial_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$messages = [];

// Delete testimonial
if ($action === 'delete' && $testimonial_id > 0) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
        try {
            // Get testimonial info for activity log
            $stmt = $db->prepare('SELECT name FROM testimonials WHERE id = ?');
            $stmt->execute([$testimonial_id]);
            $testimonial = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Delete the testimonial
            $stmt = $db->prepare('DELETE FROM testimonials WHERE id = ?');
            $stmt->execute([$testimonial_id]);
            
            // Log the action
            $stmt = $db->prepare('INSERT INTO admin_activity_logs (user_id, username, action, details) VALUES (?, ?, ?, ?)');
            $stmt->execute([
                $_SESSION['admin_id'] ?? null,
                $_SESSION['admin_username'] ?? 'Unknown',
                'delete_testimonial',
                'Deleted testimonial from: ' . ($testimonial['name'] ?? 'Unknown person')
            ]);
            
            $messages[] = [
                'type' => 'success',
                'text' => 'Testimonial deleted successfully.'
            ];
            
            // Redirect to list
            header('Location: testimonials.php?deleted=1');
            exit;
        } catch (PDOException $e) {
            $messages[] = [
                'type' => 'error',
                'text' => 'Error deleting testimonial: ' . $e->getMessage()
            ];
        }
    }
}

// Save testimonial (create or edit)
if (($action === 'new' || $action === 'edit') && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $position = $_POST['position'] ?? '';
    $company = $_POST['company'] ?? '';
    $content = $_POST['content'] ?? '';
    $rating = isset($_POST['rating']) ? (int)$_POST['rating'] : 5;
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    
    $errors = [];
    
    // Validate input
    if (empty($name)) {
        $errors[] = 'Name is required.';
    }
    
    if (empty($content)) {
        $errors[] = 'Testimonial content is required.';
    }
    
    if ($rating < 1 || $rating > 5) {
        $errors[] = 'Rating must be between 1 and 5.';
    }
    
    if (empty($errors)) {
        try {
            if ($action === 'edit' && $testimonial_id > 0) {
                // Update existing testimonial
                $stmt = $db->prepare('UPDATE testimonials SET name = ?, position = ?, company = ?, content = ?, rating = ?, is_featured = ? WHERE id = ?');
                $stmt->execute([$name, $position, $company, $content, $rating, $is_featured, $testimonial_id]);
                
                // Log the action
                $stmt = $db->prepare('INSERT INTO admin_activity_logs (user_id, username, action, details) VALUES (?, ?, ?, ?)');
                $stmt->execute([
                    $_SESSION['admin_id'] ?? null,
                    $_SESSION['admin_username'] ?? 'Unknown',
                    'edit_testimonial',
                    'Edited testimonial from: ' . $name
                ]);
                
                $messages[] = [
                    'type' => 'success',
                    'text' => 'Testimonial updated successfully.'
                ];
            } else {
                // Create new testimonial
                $stmt = $db->prepare('INSERT INTO testimonials (name, position, company, content, rating, is_featured) VALUES (?, ?, ?, ?, ?, ?)');
                $stmt->execute([$name, $position, $company, $content, $rating, $is_featured]);
                
                // Get the new testimonial ID
                $testimonial_id = $db->lastInsertId();
                
                // Log the action
                $stmt = $db->prepare('INSERT INTO admin_activity_logs (user_id, username, action, details) VALUES (?, ?, ?, ?)');
                $stmt->execute([
                    $_SESSION['admin_id'] ?? null,
                    $_SESSION['admin_username'] ?? 'Unknown',
                    'create_testimonial',
                    'Created new testimonial from: ' . $name
                ]);
                
                $messages[] = [
                    'type' => 'success',
                    'text' => 'Testimonial created successfully.'
                ];
                
                // Redirect to edit mode
                header('Location: testimonials.php?action=edit&id=' . $testimonial_id . '&created=1');
                exit;
            }
        } catch (PDOException $e) {
            $messages[] = [
                'type' => 'error',
                'text' => 'Error saving testimonial: ' . $e->getMessage()
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

// Get testimonial for editing
$testimonial = null;
if ($action === 'edit' && $testimonial_id > 0) {
    try {
        $stmt = $db->prepare('SELECT * FROM testimonials WHERE id = ?');
        $stmt->execute([$testimonial_id]);
        $testimonial = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$testimonial) {
            $messages[] = [
                'type' => 'error',
                'text' => 'Testimonial not found.'
            ];
            $action = 'list'; // Fall back to list view
        }
    } catch (PDOException $e) {
        $messages[] = [
            'type' => 'error',
            'text' => 'Error fetching testimonial: ' . $e->getMessage()
        ];
        $action = 'list'; // Fall back to list view
    }
}

// Get all testimonials for listing
$testimonials = [];
if ($action === 'list') {
    try {
        $stmt = $db->query('SELECT id, name, company, rating, is_featured, created_at FROM testimonials ORDER BY created_at DESC');
        $testimonials = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $messages[] = [
            'type' => 'error',
            'text' => 'Error fetching testimonials: ' . $e->getMessage()
        ];
    }
}

// Set page title based on action
$pageTitle = match($action) {
    'new' => 'Add New Testimonial',
    'edit' => 'Edit Testimonial',
    'delete' => 'Delete Testimonial',
    default => 'Testimonials'
};

// Include admin header
include 'includes/admin_header.php';
?>

<div class="container px-6 py-8 mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800"><?= htmlspecialchars($pageTitle) ?></h1>
        <?php if ($action === 'list'): ?>
            <a href="testimonials.php?action=new" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                Add New Testimonial
            </a>
        <?php else: ?>
            <a href="testimonials.php" class="px-4 py-2 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                Back to All Testimonials
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
            Testimonial added successfully.
        </div>
    <?php endif; ?>
    
    <?php if (isset($_GET['deleted']) && $_GET['deleted'] == 1): ?>
        <div class="mb-6 p-4 rounded-lg bg-green-100 text-green-700">
            Testimonial deleted successfully.
        </div>
    <?php endif; ?>
    
    <?php if ($action === 'list'): ?>
        <!-- Testimonials List -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">All Testimonials</h2>
            </div>
            <div class="overflow-x-auto">
                <?php if (empty($testimonials)): ?>
                    <div class="p-6 text-center text-gray-500">
                        No testimonials found. <a href="testimonials.php?action=new" class="text-green-600">Add your first testimonial</a>.
                    </div>
                <?php else: ?>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Featured</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($testimonials as $testimonial): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($testimonial['name']) ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500"><?= htmlspecialchars($testimonial['company'] ?? '-') ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex text-yellow-400">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <?php if ($i <= $testimonial['rating']): ?>
                                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path>
                                                    </svg>
                                                <?php else: ?>
                                                    <svg class="w-4 h-4 text-gray-300 fill-current" viewBox="0 0 24 24">
                                                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path>
                                                    </svg>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php if ($testimonial['is_featured']): ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Featured
                                            </span>
                                        <?php else: ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                Not Featured
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500"><?= date('M d, Y', strtotime($testimonial['created_at'])) ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="testimonials.php?action=edit&id=<?= $testimonial['id'] ?>" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                        <a href="testimonials.php?action=delete&id=<?= $testimonial['id'] ?>" class="text-red-600 hover:text-red-900">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    <?php elseif ($action === 'new' || $action === 'edit'): ?>
        <!-- Testimonial Form -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">
                    <?= $action === 'new' ? 'Add New Testimonial' : 'Edit Testimonial' ?>
                </h2>
            </div>
            <div class="p-6">
                <form method="post" action="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>">
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name *</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="<?= htmlspecialchars($testimonial['name'] ?? '') ?>"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                            required
                        >
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="position" class="block text-sm font-medium text-gray-700 mb-2">Position</label>
                            <input
                                type="text"
                                id="position"
                                name="position"
                                value="<?= htmlspecialchars($testimonial['position'] ?? '') ?>"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                            >
                        </div>
                        
                        <div>
                            <label for="company" class="block text-sm font-medium text-gray-700 mb-2">Company</label>
                            <input
                                type="text"
                                id="company"
                                name="company"
                                value="<?= htmlspecialchars($testimonial['company'] ?? '') ?>"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                            >
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Testimonial *</label>
                        <textarea
                            id="content"
                            name="content"
                            rows="6"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 formatted-textarea"
                            required
                        ><?= htmlspecialchars($testimonial['content'] ?? '') ?></textarea>
                        <p class="mt-1 text-sm text-gray-500">
                            Select text and use the formatting buttons above to apply bold, italic, or underline formatting.
                        </p>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                        <div class="flex space-x-2">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <label class="inline-flex items-center">
                                    <input 
                                        type="radio" 
                                        name="rating" 
                                        value="<?= $i ?>" 
                                        <?= (isset($testimonial['rating']) && $testimonial['rating'] == $i) || (!isset($testimonial['rating']) && $i == 5) ? 'checked' : '' ?>
                                        class="form-radio h-4 w-4 text-yellow-500"
                                    >
                                    <span class="ml-1"><?= $i ?></span>
                                </label>
                            <?php endfor; ?>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="inline-flex items-center">
                            <input 
                                type="checkbox" 
                                name="is_featured" 
                                value="1" 
                                <?= isset($testimonial['is_featured']) && $testimonial['is_featured'] ? 'checked' : '' ?>
                                class="form-checkbox h-4 w-4 text-green-500"
                            >
                            <span class="ml-2 text-sm text-gray-700">Featured on homepage</span>
                        </label>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <a href="testimonials.php" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Cancel
                        </a>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md text-sm font-medium hover:bg-green-700">
                            <?= $action === 'new' ? 'Add Testimonial' : 'Update Testimonial' ?>
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
                    Are you sure you want to delete this testimonial? This action cannot be undone.
                </p>
                
                <form method="post" action="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>">
                    <input type="hidden" name="confirm_delete" value="1">
                    <div class="flex justify-end space-x-3">
                        <a href="testimonials.php" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Cancel
                        </a>
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md text-sm font-medium hover:bg-red-700">
                            Delete Testimonial
                        </button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php
// Include admin footer
include 'includes/admin_footer.php';
?> 