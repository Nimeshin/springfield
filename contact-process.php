<?php
declare(strict_types=1);

/**
 * Contact Form Processing Script
 * 
 * Handles the submission of the contact form, saves the data to the database,
 * and sends notification emails.
 */

// Include configuration and functions
require_once 'config.php';
require_once 'includes/functions.php';

// Only process POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: contact.php?error=invalid_request');
    exit;
}

// Initialize database connection
try {
    $db = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    error_log('Database connection failed: ' . $e->getMessage());
    header('Location: contact.php?error=system');
    exit;
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
    error_log('Error creating contact_messages table: ' . $e->getMessage());
    // Continue processing - don't exit here
}

// Get form data
$name = sanitize_input($_POST['name'] ?? '');
$email = sanitize_input($_POST['email'] ?? '');
$phone = isset($_POST['phone']) ? sanitize_input($_POST['phone']) : null;
$subject = sanitize_input($_POST['subject'] ?? '');
$message = sanitize_input($_POST['message'] ?? '');
$ip_address = $_SERVER['REMOTE_ADDR'] ?? '';

// Validate form data
$errors = [];

if (empty($name)) {
    $errors[] = 'Name is required';
}

if (empty($email)) {
    $errors[] = 'Email is required';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Invalid email format';
}

if (empty($subject)) {
    $errors[] = 'Subject is required';
}

if (empty($message)) {
    $errors[] = 'Message is required';
}

// If there are validation errors, redirect back with error message
if (!empty($errors)) {
    $error_string = implode(', ', $errors);
    header("Location: contact.php?error=" . urlencode($error_string));
    exit;
}

// Save message to database
try {
    $stmt = $db->prepare('
        INSERT INTO contact_messages 
        (name, email, phone, subject, message, ip_address) 
        VALUES (?, ?, ?, ?, ?, ?)
    ');
    
    $stmt->execute([$name, $email, $phone, $subject, $message, $ip_address]);
    $message_id = $db->lastInsertId();
    
} catch (PDOException $e) {
    error_log('Error saving contact message: ' . $e->getMessage());
    header('Location: contact.php?error=database');
    exit;
}

// Prepare email to site admin
$email_subject = "New Contact Form Submission: $subject";
$email_message = "
    <html>
    <head>
        <title>New Contact Form Submission</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            h2 { color: #c00000; }
            p { margin-bottom: 10px; }
            .footer { margin-top: 30px; padding-top: 15px; border-top: 1px solid #eee; font-size: 12px; color: #777; }
            .button { display: inline-block; padding: 10px 15px; background-color: #c00000; color: white; text-decoration: none; border-radius: 4px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <h2>New Contact Form Submission</h2>
            <p><strong>From:</strong> $name</p>
            <p><strong>Email:</strong> $email</p>";
            
if (!empty($phone)) {
    $email_message .= "<p><strong>Phone:</strong> $phone</p>";
}

$email_message .= "
            <p><strong>Subject:</strong> $subject</p>
            <p><strong>Message:</strong></p>
            <p>" . nl2br($message) . "</p>
            
            <p>
                <a href='" . SITE_URL . "/admin/messages.php?action=view&id=$message_id' class='button'>View in Admin Panel</a>
            </p>
            
            <div class='footer'>
                <p>This email was sent from the contact form on the Springfield Panel and Paint website.</p>
            </div>
        </div>
    </body>
    </html>
";

// Send notification email to admin
$mail_sent = send_email(SITE_EMAIL, $email_subject, $email_message);

// Send confirmation email to user
$confirmation_subject = "Thank you for contacting Springfield Panel and Paint";
$confirmation_message = "
    <html>
    <head>
        <title>Your message has been received</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            h2 { color: #c00000; }
            p { margin-bottom: 10px; }
            .footer { margin-top: 30px; padding-top: 15px; border-top: 1px solid #eee; font-size: 12px; color: #777; }
        </style>
    </head>
    <body>
        <div class='container'>
            <h2>Thank You for Contacting Us</h2>
            <p>Dear $name,</p>
            <p>Thank you for reaching out to Springfield Panel and Paint. We have received your message and will get back to you as soon as possible.</p>
            <p>For your records, here's a copy of your message:</p>
            <p><strong>Subject:</strong> $subject</p>
            <p><strong>Message:</strong></p>
            <p>" . nl2br($message) . "</p>
            
            <p>If you have any urgent matters, please feel free to call us at " . SITE_PHONE . ".</p>
            
            <p>Regards,<br>The Springfield Panel and Paint Team</p>
            
            <div class='footer'>
                <p>This is an automated response, please do not reply to this email.</p>
            </div>
        </div>
    </body>
    </html>
";

$confirmation_sent = send_email($email, $confirmation_subject, $confirmation_message);

// Redirect to thank you page
header('Location: contact.php?success=1');
exit; 