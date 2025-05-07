<?php
declare(strict_types=1);

// Set page title
$page_title = 'Contact Us';

// Include header and functions
require_once 'includes/header.php';
require_once 'includes/functions.php';

// Initialize variables
$name = $email = $subject = $message = $phone = '';
$success_message = $error_message = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Redirect to the processing script
    header('Location: contact-process.php');
    exit;
}

// Get success/error messages from URL parameters
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $success_message = 'Thank you for your message! We will get back to you soon.';
}

if (isset($_GET['error'])) {
    $error_message = urldecode($_GET['error']);
}
?>

<!-- Hero Section -->
<section class="hero-section h-72" style="background-image: url('images/Springfield P&P-5.JPG');">
    <div class="hero-overlay w-full h-full flex flex-col justify-center items-center text-center p-4">
        <div class="container mx-auto">
            <h1 class="text-4xl md:text-6xl font-bold mb-4">Contact Us</h1>
            <p class="text-xl md:text-2xl">We're here to help with all your auto body repair needs</p>
        </div>
    </div>
</section>

<!-- Contact Info & Form Section -->
<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Contact Information -->
            <div class="text-white">
                <h2 class="text-3xl font-bold mb-8 text-primary">Get In Touch</h2>
                
                <div class="space-y-8">
                    <!-- Address -->
                    <div class="flex items-start">
                        <div class="bg-primary rounded-full w-12 h-12 flex items-center justify-center flex-shrink-0 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold mb-2">Our Location</h3>
                            <address class="not-italic text-white">
                                82 Intersite Avenue,<br>
                                Unit 5 The Wolds "A",<br>
                                Umgeni Business Park,<br>
                                Durban, KZN
                            </address>
                        </div>
                    </div>
                    
                    <!-- Phone -->
                    <div class="flex items-start">
                        <div class="bg-primary rounded-full w-12 h-12 flex items-center justify-center flex-shrink-0 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold mb-2">Phone Number</h3>
                            <p class="text-white">
                                <a href="tel:<?= SITE_PHONE ?>" class="hover:text-primary transition"><?= SITE_PHONE ?></a>
                            </p>
                        </div>
                    </div>
                    
                    <!-- Email -->
                    <div class="flex items-start">
                        <div class="bg-primary rounded-full w-12 h-12 flex items-center justify-center flex-shrink-0 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold mb-2">Email Address</h3>
                            <p class="text-white">
                                <a href="mailto:<?= SITE_EMAIL ?>" class="hover:text-primary transition"><?= SITE_EMAIL ?></a>
                            </p>
                        </div>
                    </div>
                    
                    <!-- Business Hours -->
                    <div class="flex items-start">
                        <div class="bg-primary rounded-full w-12 h-12 flex items-center justify-center flex-shrink-0 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold mb-2">Business Hours</h3>
                            <ul class="text-white space-y-1">
                                <li>Monday - Friday: 8:00 - 17:00</li>
                                <li>Saturday: 8:00 - 13:00</li>
                                <li>Sundays: Closed</li>
                                <li>Public Holidays: 8:00 - 13:00</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Social Media Links -->
                <div class="mt-10">
                    <h3 class="text-xl font-bold mb-4">Follow Us</h3>
                    <div class="flex space-x-4">
                        <a href="#" class="bg-blue-600 text-white w-10 h-10 rounded-full flex items-center justify-center hover:bg-blue-700 transition">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="bg-pink-600 text-white w-10 h-10 rounded-full flex items-center justify-center hover:bg-pink-700 transition">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Contact Form -->
            <div>
                <h2 class="text-3xl font-bold mb-8 text-primary">Send Us a Message</h2>
                
                <?php if (!empty($success_message)): ?>
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                        <p><?= $success_message ?></p>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($error_message)): ?>
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                        <p><?= $error_message ?></p>
                    </div>
                <?php endif; ?>
                
                <form action="contact-process.php" method="POST" class="contact-form bg-white rounded-lg shadow-lg p-8">
                    <div class="mb-6">
                        <label for="name" class="block text-gray-700 font-semibold mb-2">Your Name</label>
                        <input type="text" id="name" name="name" value="<?= htmlspecialchars($name) ?>" required class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:border-primary" placeholder="Enter your name">
                    </div>
                    
                    <div class="mb-6">
                        <label for="email" class="block text-gray-700 font-semibold mb-2">Your Email</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:border-primary" placeholder="Enter your email address">
                    </div>
                    
                    <div class="mb-6">
                        <label for="phone" class="block text-gray-700 font-semibold mb-2">Phone Number (Optional)</label>
                        <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($phone ?? '') ?>" class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:border-primary" placeholder="Enter your phone number">
                    </div>
                    
                    <div class="mb-6">
                        <label for="subject" class="block text-gray-700 font-semibold mb-2">Subject</label>
                        <input type="text" id="subject" name="subject" value="<?= htmlspecialchars($subject) ?>" required class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:border-primary" placeholder="Enter subject">
                    </div>
                    
                    <div class="mb-6">
                        <label for="message" class="block text-gray-700 font-semibold mb-2">Your Message</label>
                        <textarea id="message" name="message" required class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:border-primary h-40" placeholder="Enter your message"><?= htmlspecialchars($message) ?></textarea>
                    </div>
                    
                    <div>
                        <button type="submit" class="bg-primary text-white font-bold py-3 px-8 rounded-lg hover:bg-red-700 transition duration-300">Send Message</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="h-96">
    <iframe src="https://www.google.com/maps/embed/v1/place?key=AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8&q=82+Intersite+Avenue,+Umgeni+Business+Park,+Durban,+KZN" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
</section>

<?php
// Include footer
require_once 'includes/footer.php';
?> 