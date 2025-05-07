<?php
declare(strict_types=1);

/**
 * Sitemap Generator
 * 
 * This script generates a sitemap.xml file by scanning the website structure
 * and database content. It can be run manually or as a scheduled task.
 */

// Include configuration
require_once 'config.php';

// Set default timezone
date_default_timezone_set('Africa/Johannesburg');

/**
 * Sitemap URL class
 */
class SitemapUrl
{
    public string $loc;
    public string $lastmod;
    public string $changefreq;
    public float $priority;

    public function __construct(string $loc, string $lastmod, string $changefreq, float $priority)
    {
        $this->loc = $loc;
        $this->lastmod = $lastmod;
        $this->changefreq = $changefreq;
        $this->priority = $priority;
    }

    public function toXml(): string
    {
        return "    <url>\n" .
               "        <loc>" . htmlspecialchars($this->loc) . "</loc>\n" .
               "        <lastmod>" . $this->lastmod . "</lastmod>\n" .
               "        <changefreq>" . $this->changefreq . "</changefreq>\n" .
               "        <priority>" . number_format($this->priority, 1) . "</priority>\n" .
               "    </url>\n";
    }
}

/**
 * Sitemap Generator class
 */
class SitemapGenerator
{
    private array $urls = [];
    private string $baseUrl;
    
    public function __construct(string $baseUrl)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
    }
    
    /**
     * Add a URL to the sitemap
     */
    public function addUrl(string $path, string $lastmod, string $changefreq, float $priority): void
    {
        $url = $this->baseUrl . '/' . ltrim($path, '/');
        $this->urls[] = new SitemapUrl($url, $lastmod, $changefreq, $priority);
    }
    
    /**
     * Add static pages to the sitemap
     */
    public function addStaticPages(): void
    {
        // Get current date in ISO 8601 format
        $today = date('Y-m-d');
        
        // Add main pages
        $this->addUrl('', $today, 'daily', 1.0);
        $this->addUrl('about.php', $today, 'monthly', 0.8);
        $this->addUrl('services.php', $today, 'monthly', 0.8);
        $this->addUrl('woman-on-fire.php', $today, 'monthly', 0.8);
        $this->addUrl('blog.php', $today, 'weekly', 0.8);
        $this->addUrl('contact.php', $today, 'monthly', 0.7);
    }
    
    /**
     * Add blog posts from database to the sitemap
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
            $stmt = $db->query("SELECT id, title, updated_at FROM blog_posts WHERE status = 'published' ORDER BY id DESC");
            $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Add each post to sitemap
            foreach ($posts as $post) {
                $slug = $this->generateSlug($post['title']);
                $lastmod = date('Y-m-d', strtotime($post['updated_at']));
                $this->addUrl("blog-post.php?id={$post['id']}", $lastmod, 'monthly', 0.6);
            }
        } catch (PDOException $e) {
            error_log("Database error in sitemap generation: " . $e->getMessage());
            // If we can't connect to database, we'll just skip the blog posts
        }
    }
    
    /**
     * Generate a URL-friendly slug from a string
     */
    private function generateSlug(string $text): string
    {
        // Replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        // Transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        // Remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);
        // Trim
        $text = trim($text, '-');
        // Remove duplicate -
        $text = preg_replace('~-+~', '-', $text);
        // Lowercase
        $text = strtolower($text);
        
        return $text;
    }
    
    /**
     * Generate the XML sitemap
     */
    public function generateSitemap(): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        
        foreach ($this->urls as $url) {
            $xml .= $url->toXml();
        }
        
        $xml .= '</urlset>';
        
        return $xml;
    }
    
    /**
     * Write sitemap to file
     */
    public function writeSitemap(string $filename): bool
    {
        try {
            file_put_contents($filename, $this->generateSitemap());
            return true;
        } catch (Exception $e) {
            error_log("Error writing sitemap: " . $e->getMessage());
            return false;
        }
    }
}

// Base URL of the website
$baseUrl = SITE_URL ?? 'https://springfieldpanelandpaint.co.za';

// Create sitemap generator
$generator = new SitemapGenerator($baseUrl);

// Add pages to sitemap
$generator->addStaticPages();
$generator->addBlogPosts();

// Write sitemap to file
$sitemapFile = __DIR__ . '/sitemap.xml';
if ($generator->writeSitemap($sitemapFile)) {
    echo "Sitemap generated successfully at " . date('Y-m-d H:i:s') . "\n";
} else {
    echo "Failed to generate sitemap\n";
}

// If this script is run from the command line, exit with appropriate code
if (php_sapi_name() === 'cli') {
    exit(0);
} 