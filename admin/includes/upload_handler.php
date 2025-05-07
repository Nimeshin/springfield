<?php
declare(strict_types=1);

/**
 * Image Upload Handler
 * Handles file uploads with validation and security measures
 */

function handleImageUpload(array $file): array {
    $result = [
        'success' => false,
        'message' => '',
        'path' => ''
    ];

    // Validate file presence
    if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
        $result['message'] = 'No file uploaded';
        return $result;
    }

    // Validate file type
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($fileInfo, $file['tmp_name']);
    finfo_close($fileInfo);

    if (!in_array($mimeType, $allowedTypes)) {
        $result['message'] = 'Invalid file type. Only JPG, PNG, GIF, and WebP images are allowed.';
        return $result;
    }

    // Validate file size (max 5MB)
    if ($file['size'] > 5 * 1024 * 1024) {
        $result['message'] = 'File is too large. Maximum size is 5MB.';
        return $result;
    }

    // Create upload directory if it doesn't exist
    $uploadDir = __DIR__ . '/../../images/blog';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Generate unique filename
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $filename = uniqid('blog_', true) . '.' . $extension;
    $uploadPath = $uploadDir . '/' . $filename;

    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
        $result['success'] = true;
        $result['path'] = 'images/blog/' . $filename;
        $result['message'] = 'File uploaded successfully';
    } else {
        $result['message'] = 'Failed to upload file';
    }

    return $result;
}

/**
 * Gallery Image Upload Handler
 * Handles gallery image uploads with validation and security measures
 */
function handleGalleryImageUpload(array $file): array {
    $result = [
        'success' => false,
        'message' => '',
        'path' => ''
    ];

    // Validate file presence
    if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
        $result['message'] = 'No file uploaded';
        return $result;
    }

    // Validate file type
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($fileInfo, $file['tmp_name']);
    finfo_close($fileInfo);

    if (!in_array($mimeType, $allowedTypes)) {
        $result['message'] = 'Invalid file type. Only JPG, PNG, GIF, and WebP images are allowed.';
        return $result;
    }

    // Validate file size (max 5MB)
    if ($file['size'] > 5 * 1024 * 1024) {
        $result['message'] = 'File is too large. Maximum size is 5MB.';
        return $result;
    }

    // Create upload directory if it doesn't exist
    $uploadDir = __DIR__ . '/../../uploads/gallery';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Generate unique filename
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $filename = uniqid('gallery_', true) . '.' . $extension;
    $uploadPath = $uploadDir . '/' . $filename;

    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
        $result['success'] = true;
        $result['path'] = 'uploads/gallery/' . $filename;
        $result['message'] = 'Gallery image uploaded successfully';
    } else {
        $result['message'] = 'Failed to upload gallery image';
    }

    return $result;
} 