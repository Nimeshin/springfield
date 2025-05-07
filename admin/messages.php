<?php
declare(strict_types=1);

/**
 * Admin Panel - Contact Messages Management
 * 
 * Handles viewing, responding to, and deleting contact messages.
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

// Create contact_messages table if it doesn't exist
try {
    $db->exec('
        CREATE TABLE IF NOT EXISTS contact_messages (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(255) NOT NULL,
            phone VARCHAR(20),
            subject VARCHAR(255) NOT NULL,
            message TEXT NOT NULL,
            is_read BOOLEAN DEFAULT 0,
            is_replied BOOLEAN DEFAULT 0,
            reply_text TEXT,
            reply_date DATETIME,
            replied_by INT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            ip_address VARCHAR(45)
        )
    ');
} catch (PDOException $e) {
    die('Error creating messages table: ' . $e->getMessage());
}

// Handle actions (view, reply, delete)
$action = $_GET['action'] ?? 'list';
$message_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$messages = [];

// Mark as read when viewing
if ($action === 'view' && $message_id > 0) {
    try {
        $stmt = $db->prepare('UPDATE contact_messages SET is_read = 1 WHERE id = ?');
        $stmt->execute([$message_id]);
    } catch (PDOException $e) {
        error_log('Error marking message as read: ' . $e->getMessage());
    }
}

// Delete message
if ($action === 'delete' && $message_id > 0) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
        try {
            // Get message info for activity log
            $stmt = $db->prepare('SELECT name, email FROM contact_messages WHERE id = ?');
            $stmt->execute([$message_id]);
            $contact = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Delete the message
            $stmt = $db->prepare('DELETE FROM contact_messages WHERE id = ?');
            $stmt->execute([$message_id]);
            
            // Log the action
            $stmt = $db->prepare('INSERT INTO admin_activity_logs (user_id, username, action, details) VALUES (?, ?, ?, ?)');
            $stmt->execute([
                $_SESSION['admin_id'] ?? null,
                $_SESSION['admin_username'] ?? 'Unknown',
                'delete_message',
                'Deleted message from: ' . ($contact['name'] ?? 'Unknown') . ' (' . ($contact['email'] ?? 'no email') . ')'
            ]);
            
            $alert = [
                'type' => 'success',
                'text' => 'Message deleted successfully.'
            ];
            
            // Redirect to list
            header('Location: messages.php?deleted=1');
            exit;
        } catch (PDOException $e) {
            $alert = [
                'type' => 'error',
                'text' => 'Error deleting message: ' . $e->getMessage()
            ];
        }
    }
}

// Handle reply
if ($action === 'reply' && $message_id > 0 && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $reply_text = $_POST['reply_text'] ?? '';
    
    if (empty($reply_text)) {
        $alert = [
            'type' => 'error',
            'text' => 'Reply text cannot be empty.'
        ];
    } else {
        try {
            // Get contact details
            $stmt = $db->prepare('SELECT name, email, subject FROM contact_messages WHERE id = ?');
            $stmt->execute([$message_id]);
            $contact = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$contact) {
                throw new Exception('Message not found');
            }
            
            // Update message with reply information
            $stmt = $db->prepare('
                UPDATE contact_messages 
                SET is_replied = 1, reply_text = ?, reply_date = NOW(), replied_by = ? 
                WHERE id = ?
            ');
            $stmt->execute([$reply_text, $_SESSION['admin_id'] ?? null, $message_id]);
            
            // Send email (placeholder - would need proper email configuration)
            $to = $contact['email'];
            $subject = 'RE: ' . $contact['subject'];
            $headers = 'From: Springfield Panel and Paint <info@springfieldpanelandpaint.co.za>' . "\r\n";
            
            $email_sent = mail($to, $subject, $reply_text, $headers);
            
            // Log the action
            $stmt = $db->prepare('INSERT INTO admin_activity_logs (user_id, username, action, details) VALUES (?, ?, ?, ?)');
            $stmt->execute([
                $_SESSION['admin_id'] ?? null,
                $_SESSION['admin_username'] ?? 'Unknown',
                'reply_message',
                'Replied to message from: ' . $contact['name'] . ' (' . $contact['email'] . ')'
            ]);
            
            $alert = [
                'type' => 'success',
                'text' => 'Reply sent successfully' . ($email_sent ? '' : ' but email delivery failed')
            ];
            
            // Redirect to prevent resubmission
            header('Location: messages.php?action=view&id=' . $message_id . '&replied=1');
            exit;
        } catch (Exception $e) {
            $alert = [
                'type' => 'error',
                'text' => 'Error sending reply: ' . $e->getMessage()
            ];
        }
    }
}

// Get message for viewing/replying
$contact_message = null;
if (($action === 'view' || $action === 'reply') && $message_id > 0) {
    try {
        $stmt = $db->prepare('SELECT * FROM contact_messages WHERE id = ?');
        $stmt->execute([$message_id]);
        $contact_message = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$contact_message) {
            $alert = [
                'type' => 'error',
                'text' => 'Message not found.'
            ];
            $action = 'list'; // Fall back to list view
        }
    } catch (PDOException $e) {
        $alert = [
            'type' => 'error',
            'text' => 'Error fetching message: ' . $e->getMessage()
        ];
        $action = 'list'; // Fall back to list view
    }
}

// Get all messages for listing
$contact_messages = [];
if ($action === 'list') {
    try {
        // Handle filtering and pagination
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $per_page = 15;
        $offset = ($page - 1) * $per_page;
        
        // Base query
        $query = 'SELECT id, name, email, subject, is_read, is_replied, created_at FROM contact_messages';
        $count_query = 'SELECT COUNT(*) FROM contact_messages';
        
        // Add filters if present
        $filters = [];
        $filter_values = [];
        
        if (isset($_GET['read']) && $_GET['read'] !== '') {
            $filters[] = 'is_read = ?';
            $filter_values[] = (int)$_GET['read'];
        }
        
        if (isset($_GET['replied']) && $_GET['replied'] !== '') {
            $filters[] = 'is_replied = ?';
            $filter_values[] = (int)$_GET['replied'];
        }
        
        if (!empty($filters)) {
            $query .= ' WHERE ' . implode(' AND ', $filters);
            $count_query .= ' WHERE ' . implode(' AND ', $filters);
        }
        
        // Add order and limit
        $query .= ' ORDER BY created_at DESC LIMIT ? OFFSET ?';
        $filter_values[] = $per_page;
        $filter_values[] = $offset;
        
        // Execute queries
        $stmt = $db->prepare($query);
        $stmt->execute($filter_values);
        $contact_messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Get total count for pagination
        $count_stmt = $db->prepare($count_query);
        $count_stmt->execute(array_slice($filter_values, 0, -2)); // Remove limit and offset
        $total_messages = (int)$count_stmt->fetchColumn();
        $total_pages = ceil($total_messages / $per_page);
        
    } catch (PDOException $e) {
        $alert = [
            'type' => 'error',
            'text' => 'Error fetching messages: ' . $e->getMessage()
        ];
        $contact_messages = [];
        $total_pages = 1;
    }
}

// Set page title based on action
$pageTitle = match($action) {
    'view' => 'View Message',
    'reply' => 'Reply to Message',
    'delete' => 'Delete Message',
    default => 'Contact Messages'
};

// Include admin header
include 'includes/admin_header.php';
?>

<div class="container px-6 py-8 mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-semibold text-gray-800"><?= htmlspecialchars($pageTitle) ?></h1>
        
        <?php if ($action !== 'list'): ?>
            <a href="messages.php" class="px-4 py-2 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                Back to All Messages
            </a>
        <?php endif; ?>
    </div>
    
    <!-- Messages/Alerts -->
    <?php if (isset($alert)): ?>
        <div class="mb-6 p-4 rounded-lg <?= $alert['type'] === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
            <?= htmlspecialchars($alert['text']) ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_GET['deleted']) && $_GET['deleted'] == 1): ?>
        <div class="mb-6 p-4 rounded-lg bg-green-100 text-green-700">
            Message deleted successfully.
        </div>
    <?php endif; ?>
    
    <?php if (isset($_GET['replied']) && $_GET['replied'] == 1): ?>
        <div class="mb-6 p-4 rounded-lg bg-green-100 text-green-700">
            Reply sent successfully.
        </div>
    <?php endif; ?>
    
    <?php if ($action === 'list'): ?>
        <!-- Messages List -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800">All Contact Messages</h2>
                
                <!-- Filters -->
                <form method="get" action="messages.php" class="flex space-x-4">
                    <div class="flex items-center">
                        <label for="read" class="mr-2 text-sm text-gray-700">Status:</label>
                        <select id="read" name="read" class="text-sm border border-gray-300 rounded px-2 py-1">
                            <option value="">All</option>
                            <option value="0" <?= isset($_GET['read']) && $_GET['read'] === '0' ? 'selected' : '' ?>>Unread</option>
                            <option value="1" <?= isset($_GET['read']) && $_GET['read'] === '1' ? 'selected' : '' ?>>Read</option>
                        </select>
                    </div>
                    
                    <div class="flex items-center">
                        <label for="replied" class="mr-2 text-sm text-gray-700">Reply:</label>
                        <select id="replied" name="replied" class="text-sm border border-gray-300 rounded px-2 py-1">
                            <option value="">All</option>
                            <option value="0" <?= isset($_GET['replied']) && $_GET['replied'] === '0' ? 'selected' : '' ?>>Not Replied</option>
                            <option value="1" <?= isset($_GET['replied']) && $_GET['replied'] === '1' ? 'selected' : '' ?>>Replied</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="text-sm bg-blue-100 text-blue-700 px-3 py-1 rounded hover:bg-blue-200">
                        Filter
                    </button>
                    
                    <?php if (isset($_GET['read']) || isset($_GET['replied'])): ?>
                        <a href="messages.php" class="text-sm text-gray-600 hover:text-gray-800">
                            Clear Filters
                        </a>
                    <?php endif; ?>
                </form>
            </div>
            
            <div class="overflow-x-auto">
                <?php if (empty($contact_messages)): ?>
                    <div class="p-6 text-center text-gray-500">
                        No messages found.
                    </div>
                <?php else: ?>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">From</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($contact_messages as $msg): ?>
                                <tr class="<?= !$msg['is_read'] ? 'bg-blue-50' : '' ?>">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php if (!$msg['is_read']): ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                New
                                            </span>
                                        <?php elseif ($msg['is_replied']): ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Replied
                                            </span>
                                        <?php else: ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                Read
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($msg['name']) ?></div>
                                        <div class="text-sm text-gray-500"><?= htmlspecialchars($msg['email']) ?></div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 truncate max-w-xs">
                                            <?= htmlspecialchars($msg['subject']) ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">
                                            <?= date('M d, Y - H:i', strtotime($msg['created_at'])) ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="messages.php?action=view&id=<?= $msg['id'] ?>" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
                                        <a href="messages.php?action=delete&id=<?= $msg['id'] ?>" class="text-red-600 hover:text-red-900">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
            
            <!-- Pagination -->
            <?php if (isset($total_pages) && $total_pages > 1): ?>
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            Showing page <?= $page ?> of <?= $total_pages ?>
                        </div>
                        <div class="flex space-x-2">
                            <?php if ($page > 1): ?>
                                <a href="messages.php?page=<?= $page - 1 ?><?= isset($_GET['read']) ? '&read=' . htmlspecialchars($_GET['read']) : '' ?><?= isset($_GET['replied']) ? '&replied=' . htmlspecialchars($_GET['replied']) : '' ?>" class="px-3 py-1 text-sm bg-gray-100 text-gray-700 rounded hover:bg-gray-200">
                                    Previous
                                </a>
                            <?php endif; ?>
                            
                            <?php if ($page < $total_pages): ?>
                                <a href="messages.php?page=<?= $page + 1 ?><?= isset($_GET['read']) ? '&read=' . htmlspecialchars($_GET['read']) : '' ?><?= isset($_GET['replied']) ? '&replied=' . htmlspecialchars($_GET['replied']) : '' ?>" class="px-3 py-1 text-sm bg-gray-100 text-gray-700 rounded hover:bg-gray-200">
                                    Next
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    <?php elseif ($action === 'view' && $contact_message): ?>
        <!-- View Message -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Message Details</h2>
            </div>
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">From</h3>
                        <p class="mt-1 text-sm text-gray-900"><?= htmlspecialchars($contact_message['name']) ?></p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Email</h3>
                        <p class="mt-1 text-sm text-gray-900"><?= htmlspecialchars($contact_message['email']) ?></p>
                    </div>
                    <?php if (!empty($contact_message['phone'])): ?>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Phone</h3>
                            <p class="mt-1 text-sm text-gray-900"><?= htmlspecialchars($contact_message['phone']) ?></p>
                        </div>
                    <?php endif; ?>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Date</h3>
                        <p class="mt-1 text-sm text-gray-900"><?= date('F d, Y - H:i', strtotime($contact_message['created_at'])) ?></p>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Subject</h3>
                    <p class="mt-1 text-sm text-gray-900"><?= htmlspecialchars($contact_message['subject']) ?></p>
                </div>
                
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Message</h3>
                    <div class="mt-1 p-4 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-900 whitespace-pre-line"><?= htmlspecialchars($contact_message['message']) ?></p>
                    </div>
                </div>
                
                <?php if ($contact_message['is_replied']): ?>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Your Reply (<?= date('F d, Y - H:i', strtotime($contact_message['reply_date'])) ?>)</h3>
                        <div class="mt-1 p-4 bg-green-50 rounded-lg">
                            <p class="text-sm text-gray-900 whitespace-pre-line"><?= htmlspecialchars($contact_message['reply_text']) ?></p>
                        </div>
                    </div>
                <?php else: ?>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Reply</h3>
                        <form method="post" action="messages.php?action=reply&id=<?= $contact_message['id'] ?>">
                            <textarea
                                name="reply_text"
                                rows="5"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                placeholder="Enter your reply here..."
                                required
                            ></textarea>
                            <div class="mt-3 flex justify-end">
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700">
                                    Send Reply
                                </button>
                            </div>
                        </form>
                    </div>
                <?php endif; ?>
                
                <div class="flex justify-between pt-4 border-t border-gray-200">
                    <a href="messages.php" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md text-sm font-medium hover:bg-gray-200">
                        Back to Messages
                    </a>
                    <a href="messages.php?action=delete&id=<?= $contact_message['id'] ?>" class="px-4 py-2 bg-red-100 text-red-700 rounded-md text-sm font-medium hover:bg-red-200">
                        Delete Message
                    </a>
                </div>
            </div>
        </div>
    <?php elseif ($action === 'delete' && $message_id): ?>
        <!-- Delete Confirmation -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 bg-red-50 border-b border-red-200">
                <h2 class="text-lg font-semibold text-red-800">Confirm Deletion</h2>
            </div>
            <div class="p-6">
                <p class="text-gray-700 mb-6">
                    Are you sure you want to delete this message? This action cannot be undone.
                </p>
                
                <form method="post" action="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>">
                    <input type="hidden" name="confirm_delete" value="1">
                    <div class="flex justify-end space-x-3">
                        <a href="messages.php" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Cancel
                        </a>
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md text-sm font-medium hover:bg-red-700">
                            Delete Message
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