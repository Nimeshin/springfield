<?php
declare(strict_types=1);

/**
 * Link Checker
 * 
 * This script scans the website for broken links and generates a report.
 * It helps maintain website health and improve SEO.
 */

// Include configuration
require_once 'config.php';

// Set default timezone and execution time
date_default_timezone_set('Africa/Johannesburg');
set_time_limit(300); // 5 minutes max execution time

/**
 * Link class to store link information
 */
class Link
{
    public string $url;
    public string $source;
    public int $status;
    public string $message;
    
    public function __construct(string $url, string $source)
    {
        $this->url = $url;
        $this->source = $source;
        $this->status = 0;
        $this->message = '';
    }
}

/**
 * Link Checker class
 */
class LinkChecker
{
    private array $scannedUrls = [];
    private array $links = [];
    private array $pages = [];
    private string $baseUrl;
    private array $ignoredExtensions;
    private array $ignoredUrls;
    
    public function __construct(string $baseUrl)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        // File extensions to ignore
        $this->ignoredExtensions = [
            'jpg', 'jpeg', 'png', 'gif', 'svg', 'pdf', 'doc', 'docx', 
            'xls', 'xlsx', 'zip', 'tar', 'gz', 'mp3', 'mp4', 'wav'
        ];
        // URLs to ignore
        $this->ignoredUrls = [
            '#', 'javascript:void(0)', 'tel:', 'mailto:', 'sms:', 'whatsapp:'
        ];
    }
    
    /**
     * Add a page to scan
     */
    public function addPage(string $url): void
    {
        $absoluteUrl = $this->getAbsoluteUrl($url);
        if (!in_array($absoluteUrl, $this->pages)) {
            $this->pages[] = $absoluteUrl;
        }
    }
    
    /**
     * Get absolute URL
     */
    private function getAbsoluteUrl(string $url): string
    {
        // If already absolute
        if (strpos($url, 'http') === 0) {
            return $url;
        }
        
        // Remove fragment
        $url = preg_replace('/#.*$/', '', $url);
        
        // If root-relative
        if (strpos($url, '/') === 0) {
            return $this->baseUrl . $url;
        }
        
        // Otherwise, assume site-relative
        return $this->baseUrl . '/' . $url;
    }
    
    /**
     * Add static pages to scan
     */
    public function addStaticPages(): void
    {
        // Add main pages
        $this->addPage('index.php');
        $this->addPage('about.php');
        $this->addPage('services.php');
        $this->addPage('woman-on-fire.php');
        $this->addPage('blog.php');
        $this->addPage('contact.php');
    }
    
    /**
     * Add blog posts from database
     */
    public function addBlogPosts(): void
    {
        try {
            // Connect to database
            $db = new PDO(
                'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
                DB_USER,
                DB_PASS,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            
            // Query to get all published blog posts
            $stmt = $db->query("SELECT id FROM blog_posts WHERE status = 'published' ORDER BY id DESC");
            $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Add each post to pages to scan
            foreach ($posts as $post) {
                $this->addPage("blog-post.php?id={$post['id']}");
            }
        } catch (PDOException $e) {
            error_log("Database error in link checker: " . $e->getMessage());
            // If we can't connect to database, we'll just skip the blog posts
        }
    }
    
    /**
     * Check if URL should be ignored
     */
    private function shouldIgnoreUrl(string $url): bool
    {
        // Check if it's in ignored URLs list
        foreach ($this->ignoredUrls as $ignoredUrl) {
            if (strpos($url, $ignoredUrl) === 0) {
                return true;
            }
        }
        
        // Check extensions
        $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);
        if (in_array(strtolower($extension), $this->ignoredExtensions)) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Extract links from HTML content
     */
    private function extractLinks(string $content, string $sourceUrl): array
    {
        $links = [];
        $dom = new DOMDocument();
        
        // Suppress warnings for malformed HTML
        libxml_use_internal_errors(true);
        $dom->loadHTML($content);
        libxml_clear_errors();
        
        // Get all anchor tags
        $anchors = $dom->getElementsByTagName('a');
        foreach ($anchors as $anchor) {
            if ($anchor->hasAttribute('href')) {
                $href = $anchor->getAttribute('href');
                
                // Skip if should be ignored
                if ($this->shouldIgnoreUrl($href)) {
                    continue;
                }
                
                // Get absolute URL
                $absoluteUrl = $this->getAbsoluteUrl($href);
                
                // Add to links array
                $links[] = new Link($absoluteUrl, $sourceUrl);
            }
        }
        
        return $links;
    }
    
    /**
     * Check URL status
     */
    private function checkUrl(Link $link): void
    {
        // If already scanned, skip
        if (isset($this->scannedUrls[$link->url])) {
            $link->status = $this->scannedUrls[$link->url]['status'];
            $link->message = $this->scannedUrls[$link->url]['message'];
            return;
        }
        
        // Check if internal or external link
        $isInternalLink = strpos($link->url, $this->baseUrl) === 0;
        
        // Initialize cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $link->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; Springfield Link Checker/1.0)');
        
        // Execute request
        curl_exec($ch);
        
        // Check for errors
        if (curl_errno($ch)) {
            $link->status = 0;
            $link->message = curl_error($ch);
        } else {
            $link->status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $link->message = $this->getStatusMessage($link->status);
        }
        
        // Close cURL handle
        curl_close($ch);
        
        // Store result for future reference
        $this->scannedUrls[$link->url] = [
            'status' => $link->status,
            'message' => $link->message
        ];
        
        // If internal link and this is a page to scan, get its links
        if ($isInternalLink && $link->status >= 200 && $link->status < 300) {
            if (in_array($link->url, $this->pages) && !isset($this->links[$link->url])) {
                $this->scanPage($link->url);
            }
        }
    }
    
    /**
     * Scan a page for links
     */
    private function scanPage(string $url): void
    {
        // Initialize cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; Springfield Link Checker/1.0)');
        
        // Execute request
        $content = curl_exec($ch);
        
        // Check for errors
        if (!curl_errno($ch) && is_string($content)) {
            // Extract links
            $pageLinks = $this->extractLinks($content, $url);
            
            // Add to links array
            foreach ($pageLinks as $link) {
                $this->links[] = $link;
            }
        }
        
        // Close cURL handle
        curl_close($ch);
    }
    
    /**
     * Get status message for HTTP code
     */
    private function getStatusMessage(int $status): string
    {
        $messages = [
            200 => 'OK',
            201 => 'Created',
            301 => 'Moved Permanently',
            302 => 'Found',
            307 => 'Temporary Redirect',
            308 => 'Permanent Redirect',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            500 => 'Internal Server Error',
            503 => 'Service Unavailable'
        ];
        
        return $messages[$status] ?? "Unknown Status: $status";
    }
    
    /**
     * Run the link checker
     */
    public function run(): array
    {
        // Scan each page
        foreach ($this->pages as $page) {
            $this->scanPage($page);
        }
        
        // Check each link
        foreach ($this->links as $link) {
            $this->checkUrl($link);
        }
        
        return $this->links;
    }
    
    /**
     * Generate HTML report
     */
    public function generateReport(): string
    {
        $brokenLinks = array_filter($this->links, function($link) {
            return $link->status < 200 || $link->status >= 400;
        });
        
        $html = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Link Checker Report</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; margin: 0; padding: 20px; color: #333; }
        h1, h2 { color: #444; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
        tr:hover { background-color: #f5f5f5; }
        .good { color: green; }
        .bad { color: red; }
        .warning { color: orange; }
        .summary { background-color: #f9f9f9; padding: 15px; margin-bottom: 20px; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>Link Checker Report</h1>
    <div class="summary">
        <p>Generated: ' . date('Y-m-d H:i:s') . '</p>
        <p>Total Links Checked: ' . count($this->links) . '</p>
        <p>Broken Links Found: ' . count($brokenLinks) . '</p>
    </div>';
    
        if (count($brokenLinks) > 0) {
            $html .= '
    <h2>Broken Links</h2>
    <table>
        <tr>
            <th>URL</th>
            <th>Status</th>
            <th>Message</th>
            <th>Source Page</th>
        </tr>';
            
            foreach ($brokenLinks as $link) {
                $statusClass = 'bad';
                
                $html .= '
        <tr>
            <td>' . htmlspecialchars($link->url) . '</td>
            <td class="' . $statusClass . '">' . $link->status . '</td>
            <td>' . htmlspecialchars($link->message) . '</td>
            <td>' . htmlspecialchars($link->source) . '</td>
        </tr>';
            }
            
            $html .= '
    </table>';
        } else {
            $html .= '
    <h2>No Broken Links Found</h2>
    <p class="good">All links are working correctly.</p>';
        }
        
        $html .= '
    <h2>All Links</h2>
    <table>
        <tr>
            <th>URL</th>
            <th>Status</th>
            <th>Message</th>
            <th>Source Page</th>
        </tr>';
        
        foreach ($this->links as $link) {
            if ($link->status >= 200 && $link->status < 300) {
                $statusClass = 'good';
            } elseif ($link->status >= 300 && $link->status < 400) {
                $statusClass = 'warning';
            } else {
                $statusClass = 'bad';
            }
            
            $html .= '
        <tr>
            <td>' . htmlspecialchars($link->url) . '</td>
            <td class="' . $statusClass . '">' . $link->status . '</td>
            <td>' . htmlspecialchars($link->message) . '</td>
            <td>' . htmlspecialchars($link->source) . '</td>
        </tr>';
        }
        
        $html .= '
    </table>
</body>
</html>';
        
        return $html;
    }
}

// Check if this script is being run from the command line
$isCli = php_sapi_name() === 'cli';

// Base URL of the website
$baseUrl = SITE_URL ?? 'https://springfieldpanelandpaint.co.za';

// Create link checker
$checker = new LinkChecker($baseUrl);

// Add pages to scan
$checker->addStaticPages();
$checker->addBlogPosts();

// Run the checker
$links = $checker->run();

// Generate report
$report = $checker->generateReport();

// Write report to file
$reportFile = __DIR__ . '/link_report.html';
file_put_contents($reportFile, $report);

// Output message
if ($isCli) {
    echo "Link check completed. Report saved to {$reportFile}\n";
    echo "Total Links: " . count($links) . "\n";
    
    $brokenLinks = array_filter($links, function($link) {
        return $link->status < 200 || $link->status >= 400;
    });
    
    echo "Broken Links: " . count($brokenLinks) . "\n";
    
    if (count($brokenLinks) > 0) {
        echo "\nBroken Links:\n";
        foreach ($brokenLinks as $link) {
            echo "{$link->url} ({$link->status}: {$link->message}) - Found in: {$link->source}\n";
        }
    }
    
    exit(0);
} else {
    // Redirect to the report if accessed via browser
    header('Location: link_report.html');
    exit;
} 