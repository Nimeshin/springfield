<?php
declare(strict_types=1);

/**
 * Sanitize user input
 * 
 * @param string $data Input data to sanitize
 * @return string Sanitized data
 */
function sanitize_input(string $data): string
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * Format date in a readable format
 * 
 * @param string $date Date string
 * @param string $format Optional format (default: 'F j, Y')
 * @return string Formatted date
 */
function format_date(string $date, string $format = 'F j, Y'): string
{
    return date($format, strtotime($date));
}

/**
 * Generate a slug from a string
 * 
 * @param string $string Input string
 * @return string Sanitized slug
 */
function generate_slug(string $string): string
{
    // Replace non letter or digits by -
    $string = preg_replace('~[^\pL\d]+~u', '-', $string);
    // Transliterate
    $string = iconv('utf-8', 'us-ascii//TRANSLIT', $string);
    // Remove unwanted characters
    $string = preg_replace('~[^-\w]+~', '', $string);
    // Trim
    $string = trim($string, '-');
    // Remove duplicate -
    $string = preg_replace('~-+~', '-', $string);
    // Lowercase
    $string = strtolower($string);
    
    return empty($string) ? 'n-a' : $string;
}

/**
 * Truncate text to a specific length
 * 
 * @param string $text Text to truncate
 * @param int $length Maximum length
 * @param string $append String to append if truncated (default: '...')
 * @return string Truncated text
 */
function truncate_text(string $text, int $length = 150, string $append = '...'): string
{
    if (strlen($text) <= $length) {
        return $text;
    }
    
    $text = substr($text, 0, $length);
    $text = substr($text, 0, strrpos($text, ' '));
    
    return $text . $append;
}

/**
 * Send email using PHP mail function
 * 
 * @param string $to Recipient email
 * @param string $subject Email subject
 * @param string $message Email message
 * @param array $headers Additional headers
 * @return bool Success status
 */
function send_email(string $to, string $subject, string $message, array $headers = []): bool
{
    $default_headers = [
        'MIME-Version: 1.0',
        'Content-type: text/html; charset=utf-8',
        'From: ' . SITE_NAME . ' <' . SITE_EMAIL . '>'
    ];
    
    $all_headers = array_merge($default_headers, $headers);
    $header_string = implode("\r\n", $all_headers);
    
    return mail($to, $subject, $message, $header_string);
}

/**
 * Check if string starts with a specific substring
 * 
 * @param string $haystack String to search in
 * @param string $needle String to search for
 * @return bool True if haystack starts with needle
 */
function starts_with(string $haystack, string $needle): bool
{
    return strpos($haystack, $needle) === 0;
}

/**
 * Get current page URL
 * 
 * @return string Current URL
 */
function get_current_url(): string
{
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $uri = $_SERVER['REQUEST_URI'];
    
    return $protocol . '://' . $host . $uri;
}

/**
 * Format phone number for display
 * 
 * @param string $phone Phone number
 * @return string Formatted phone number
 */
function format_phone(string $phone): string
{
    // Remove all non-numeric characters
    $phone = preg_replace('/[^0-9]/', '', $phone);
    
    // Format based on length
    $length = strlen($phone);
    
    if ($length == 10) {
        return substr($phone, 0, 3) . '-' . substr($phone, 3, 3) . '-' . substr($phone, 6);
    } elseif ($length == 11) {
        return substr($phone, 0, 1) . '-' . substr($phone, 1, 3) . '-' . substr($phone, 4, 3) . '-' . substr($phone, 7);
    }
    
    return $phone;
}

/**
 * Simple CSRF token generation and validation
 * 
 * @param string $action Form action to generate token for
 * @return string CSRF token
 */
function generate_csrf_token(string $action): string
{
    $token = bin2hex(random_bytes(32));
    $_SESSION['csrf_tokens'][$action] = $token;
    return $token;
}

/**
 * Verify CSRF token
 * 
 * @param string $token Token to verify
 * @param string $action Form action associated with token
 * @return bool Token is valid
 */
function verify_csrf_token(string $token, string $action): bool
{
    if (!isset($_SESSION['csrf_tokens'][$action])) {
        return false;
    }
    
    $stored_token = $_SESSION['csrf_tokens'][$action];
    unset($_SESSION['csrf_tokens'][$action]); // Use token only once
    
    return hash_equals($stored_token, $token);
}

/**
 * Safely display HTML content while filtering out dangerous elements
 * 
 * @param string $html HTML content to purify
 * @return string Filtered HTML content
 */
function safe_html(string $html): string
{
    // List of allowed HTML tags
    $allowedTags = '<p><br><b><strong><i><em><u><h1><h2><h3><h4><h5><h6><ul><ol><li><a><img><blockquote><pre><code><span><div>';
    
    // Strip all tags except allowed ones
    $html = strip_tags($html, $allowedTags);
    
    // Remove potentially harmful attributes (basic protection)
    $html = preg_replace('/(<[^>]+)(?:on\w+|javascript:|onclick|ondblclick|onmousedown|onmouseup|onmouseover|onmousemove|onmouseout|onkeypress|onkeydown|onkeyup)=[^>]*/i', '$1', $html);
    
    // Remove script tags and their content
    $html = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $html);
    
    return $html;
}
?> 