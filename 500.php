<?php
declare(strict_types=1);

// Set page title
$page_title = 'Server Error';

// Include header
require_once 'includes/header.php';
?>

<!-- 500 Error Section -->
<section class="py-20">
    <div class="container mx-auto px-4 text-center">
        <div class="bg-white rounded-lg shadow-lg p-12 max-w-2xl mx-auto">
            <div class="mb-8">
                <span class="text-primary text-8xl font-bold">500</span>
            </div>
            
            <h1 class="text-3xl font-bold mb-6">Server Error</h1>
            
            <p class="text-gray-700 mb-8">
                We're sorry, but something went wrong on our server. Our technical team has been notified and is working to fix the issue.
            </p>
            
            <div class="flex flex-col md:flex-row justify-center gap-4">
                <a href="index.php" class="bg-primary text-white font-bold py-3 px-6 rounded-lg hover:bg-red-700 transition duration-300">
                    <i class="fas fa-home mr-2"></i> Go to Homepage
                </a>
                
                <a href="contact.php" class="bg-gray-200 text-gray-800 font-bold py-3 px-6 rounded-lg hover:bg-gray-300 transition duration-300">
                    <i class="fas fa-envelope mr-2"></i> Report the Issue
                </a>
            </div>
        </div>
        
        <div class="mt-12">
            <h2 class="text-2xl font-bold mb-4">While you wait, you might explore:</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto">
                <a href="services.php" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition">
                    <i class="fas fa-tools text-primary text-3xl mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Our Services</h3>
                    <p class="text-gray-700">Explore our range of auto body repair services.</p>
                </a>
                
                <a href="woman-on-fire.php" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition">
                    <i class="fas fa-fire text-primary text-3xl mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Woman on Fire</h3>
                    <p class="text-gray-700">Learn about our women empowerment initiative.</p>
                </a>
                
                <a href="blog.php" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition">
                    <i class="fas fa-newspaper text-primary text-3xl mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Our Blog</h3>
                    <p class="text-gray-700">Read our latest articles and updates.</p>
                </a>
            </div>
        </div>
    </div>
</section>

<?php
// Include footer
require_once 'includes/footer.php';
?> 