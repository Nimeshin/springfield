<?php
declare(strict_types=1);

/**
 * Gallery Management Page
 * 
 * Allows administrators to add, edit, and delete gallery images that
 * are displayed on the About page.
 */

// Initialize session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

// Include database connection
require_once '../config.php';

// Include upload handler
require_once 'includes/upload_handler.php';

// Set page title for header
$pageTitle = 'Gallery Management';

// Process form submissions
$alertMessage = '';
$alertType = '';

// Handle image uploads
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    // Add new gallery image
    if ($_POST['action'] === 'add_image' && isset($_FILES['image'])) {
        $displayOrder = (int)($_POST['display_order'] ?? 0);
        
        // Upload the image
        $uploadResult = handleGalleryImageUpload($_FILES['image']);
        
        if ($uploadResult['success']) {
            // Insert into database
            $stmt = $conn->prepare("INSERT INTO gallery_images (image_path, display_order) VALUES (?, ?)");
            $stmt->bind_param("si", $uploadResult['path'], $displayOrder);
            
            if ($stmt->execute()) {
                $alertMessage = 'Gallery image added successfully.';
                $alertType = 'success';
            } else {
                $alertMessage = 'Failed to add gallery image: ' . $conn->error;
                $alertType = 'error';
            }
            
            $stmt->close();
        } else {
            $alertMessage = 'Failed to upload image: ' . $uploadResult['message'];
            $alertType = 'error';
        }
    }
    
    // Update gallery image
    if ($_POST['action'] === 'update_image') {
        $imageId = (int)$_POST['image_id'];
        $displayOrder = (int)($_POST['display_order'] ?? 0);
        
        if ($imageId <= 0) {
            $alertMessage = 'Invalid image ID.';
            $alertType = 'error';
        } else {
            $updateQuery = "UPDATE gallery_images SET display_order = ? WHERE id = ?";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param("ii", $displayOrder, $imageId);
            
            if ($stmt->execute()) {
                // Handle image file update if new image was uploaded
                if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
                    $uploadResult = handleGalleryImageUpload($_FILES['image']);
                    
                    if ($uploadResult['success']) {
                        // Update the image path in the database
                        $updateImgQuery = "UPDATE gallery_images SET image_path = ? WHERE id = ?";
                        $imgStmt = $conn->prepare($updateImgQuery);
                        $imgStmt->bind_param("si", $uploadResult['path'], $imageId);
                        $imgStmt->execute();
                        $imgStmt->close();
                    } else {
                        $alertMessage = 'Image updated but failed to update the image file: ' . $uploadResult['message'];
                        $alertType = 'warning';
                    }
                }
                
                if (empty($alertMessage)) {
                    $alertMessage = 'Gallery image updated successfully.';
                    $alertType = 'success';
                }
            } else {
                $alertMessage = 'Failed to update gallery image: ' . $conn->error;
                $alertType = 'error';
            }
            
            $stmt->close();
        }
    }
    
    // Delete gallery image
    if ($_POST['action'] === 'delete_image') {
        $imageId = (int)$_POST['image_id'];
        
        if ($imageId <= 0) {
            $alertMessage = 'Invalid image ID.';
            $alertType = 'error';
        } else {
            // First get the image path to delete the file
            $getPathQuery = "SELECT image_path FROM gallery_images WHERE id = ?";
            $pathStmt = $conn->prepare($getPathQuery);
            $pathStmt->bind_param("i", $imageId);
            $pathStmt->execute();
            $pathStmt->bind_result($imagePath);
            $pathStmt->fetch();
            $pathStmt->close();
            
            // Delete from database
            $deleteQuery = "DELETE FROM gallery_images WHERE id = ?";
            $stmt = $conn->prepare($deleteQuery);
            $stmt->bind_param("i", $imageId);
            
            if ($stmt->execute()) {
                // Try to delete the file if it exists
                if (!empty($imagePath) && file_exists('../' . $imagePath)) {
                    unlink('../' . $imagePath);
                }
                
                $alertMessage = 'Gallery image deleted successfully.';
                $alertType = 'success';
            } else {
                $alertMessage = 'Failed to delete gallery image: ' . $conn->error;
                $alertType = 'error';
            }
            
            $stmt->close();
        }
    }
}

// Fetch gallery images from database
$galleryImages = [];
$query = "SELECT id, image_path, display_order, created_at FROM gallery_images ORDER BY display_order ASC, created_at DESC";
$result = $conn->query($query);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $galleryImages[] = $row;
    }
    $result->free();
}

// Include header
require_once 'includes/admin_header.php';
?>

<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Gallery Management</h1>
        <button id="add-image-btn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add New Image
        </button>
    </div>
    
    <?php if (!empty($alertMessage)): ?>
        <div class="mb-6 p-4 rounded-lg <?= $alertType === 'success' ? 'bg-green-100 text-green-800' : ($alertType === 'warning' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') ?>">
            <?= htmlspecialchars($alertMessage) ?>
        </div>
    <?php endif; ?>
    
    <!-- Add Image Form (Hidden by default) -->
    <div id="add-image-form" class="mb-8 p-6 bg-white rounded-lg shadow-md hidden">
        <h2 class="text-xl font-semibold mb-4">Add New Gallery Image</h2>
        <form action="gallery.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="add_image">
            
            <div class="mb-4">
                <label for="display_order" class="block text-sm font-medium text-gray-700 mb-1">Display Order</label>
                <input type="number" id="display_order" name="display_order" min="0" value="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-sm text-gray-500 mt-1">Lower numbers display first. Images with the same order are sorted by date.</p>
            </div>
            
            <div class="mb-6">
                <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Image File *</label>
                <input type="file" id="image" name="image" required accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-sm text-gray-500 mt-1">Maximum file size: 5MB. Supported formats: JPG, PNG, GIF, WebP</p>
            </div>
            
            <div class="flex justify-end space-x-3">
                <button type="button" id="cancel-add" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-500">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Add Image
                </button>
            </div>
        </form>
    </div>
    
    <!-- Gallery Images List -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Image
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Order
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Date Added
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (empty($galleryImages)): ?>
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                            No gallery images found. Add your first image using the button above.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($galleryImages as $image): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <img src="../<?= htmlspecialchars($image['image_path']) ?>" alt="Gallery Image" class="h-16 w-16 object-cover rounded">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?= htmlspecialchars($image['display_order']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= date('M j, Y', strtotime($image['created_at'])) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button 
                                    class="edit-image text-blue-600 hover:text-blue-900 mr-3"
                                    data-id="<?= $image['id'] ?>"
                                    data-order="<?= $image['display_order'] ?>"
                                    data-path="<?= htmlspecialchars($image['image_path']) ?>"
                                >
                                    Edit
                                </button>
                                <button 
                                    class="delete-image text-red-600 hover:text-red-900"
                                    data-id="<?= $image['id'] ?>"
                                >
                                    Delete
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Edit Image Modal (Hidden by default) -->
    <div id="edit-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-lg max-w-2xl w-full mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold">Edit Gallery Image</h3>
                    <button id="close-edit-modal" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form id="edit-form" action="gallery.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_image">
                    <input type="hidden" id="edit-image-id" name="image_id" value="">
                    
                    <div class="mb-4">
                        <label for="edit-display-order" class="block text-sm font-medium text-gray-700 mb-1">Display Order</label>
                        <input type="number" id="edit-display-order" name="display_order" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Current Image</label>
                        <img id="current-image" src="" alt="Current Image" class="h-32 object-contain mb-2">
                    </div>
                    
                    <div class="mb-6">
                        <label for="edit-image" class="block text-sm font-medium text-gray-700 mb-1">Replace Image</label>
                        <input type="file" id="edit-image" name="image" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <p class="text-sm text-gray-500 mt-1">Leave empty to keep current image</p>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" id="close-edit-form" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-500">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Update Image
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Delete Confirmation Modal (Hidden by default) -->
    <div id="delete-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4">
            <div class="p-6">
                <div class="mb-4">
                    <h3 class="text-xl font-semibold text-red-600">Confirm Deletion</h3>
                    <p class="mt-2">Are you sure you want to delete this gallery image? This action cannot be undone.</p>
                </div>
                
                <form id="delete-form" action="gallery.php" method="POST">
                    <input type="hidden" name="action" value="delete_image">
                    <input type="hidden" id="delete-image-id" name="image_id" value="">
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" id="cancel-delete" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-500">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                            Delete
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Add Image Form Toggle
    const addImageBtn = document.getElementById('add-image-btn');
    const addImageForm = document.getElementById('add-image-form');
    const cancelAddBtn = document.getElementById('cancel-add');
    
    addImageBtn.addEventListener('click', () => {
        addImageForm.classList.remove('hidden');
    });
    
    cancelAddBtn.addEventListener('click', () => {
        addImageForm.classList.add('hidden');
    });
    
    // Edit Image Modal
    const editButtons = document.querySelectorAll('.edit-image');
    const editModal = document.getElementById('edit-modal');
    const closeEditModal = document.getElementById('close-edit-modal');
    const closeEditForm = document.getElementById('close-edit-form');
    const editImageId = document.getElementById('edit-image-id');
    const editDisplayOrder = document.getElementById('edit-display-order');
    const currentImage = document.getElementById('current-image');
    
    editButtons.forEach(button => {
        button.addEventListener('click', () => {
            const id = button.getAttribute('data-id');
            const order = button.getAttribute('data-order');
            const path = button.getAttribute('data-path');
            
            editImageId.value = id;
            editDisplayOrder.value = order;
            currentImage.src = '../' + path;
            
            editModal.classList.remove('hidden');
        });
    });
    
    closeEditModal.addEventListener('click', () => {
        editModal.classList.add('hidden');
    });
    
    closeEditForm.addEventListener('click', () => {
        editModal.classList.add('hidden');
    });
    
    // Delete Confirmation Modal
    const deleteButtons = document.querySelectorAll('.delete-image');
    const deleteModal = document.getElementById('delete-modal');
    const cancelDelete = document.getElementById('cancel-delete');
    const deleteImageId = document.getElementById('delete-image-id');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', () => {
            const id = button.getAttribute('data-id');
            deleteImageId.value = id;
            deleteModal.classList.remove('hidden');
        });
    });
    
    cancelDelete.addEventListener('click', () => {
        deleteModal.classList.add('hidden');
    });
    
    // Close modals when clicking outside
    window.addEventListener('click', (e) => {
        if (e.target === editModal) {
            editModal.classList.add('hidden');
        }
        if (e.target === deleteModal) {
            deleteModal.classList.add('hidden');
        }
    });
</script>

<?php
// Include footer
require_once 'includes/admin_footer.php';
?> 