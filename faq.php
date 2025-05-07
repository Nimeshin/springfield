<?php
declare(strict_types=1);

// Set page title
$page_title = 'FAQ';

// Include header and functions
require_once 'includes/header.php';
require_once 'includes/functions.php';
?>

<!-- Hero Section -->
<section class="hero-section h-72" style="background-image: url('images/Springfield P&P-21.JPG');">
    <div class="hero-overlay w-full h-full flex flex-col justify-center items-center text-center p-4">
        <div class="container mx-auto">
            <h1 class="text-4xl md:text-6xl font-bold mb-4">Frequently Asked Questions</h1>
            <p class="text-xl md:text-2xl">Everything you need to know about our services</p>
        </div>
    </div>
</section>

<!-- FAQ Content -->
<section class="py-16 bg-black text-white">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto">
            <p class="text-lg text-center mb-12">
                Find answers to the most common questions about our services and procedures. If you can't find the information you're looking for, please <a href="contact.php" class="text-primary font-semibold hover:underline">contact us</a>.
            </p>
            
            <!-- FAQ Accordion -->
            <div class="space-y-6" id="faq-accordion">
                
                <!-- FAQ Item 1 -->
                <div class="faq-item border border-gray-700 rounded-lg overflow-hidden">
                    <button class="faq-question w-full bg-gray-900 px-6 py-4 text-left font-semibold text-lg flex justify-between items-center hover:bg-gray-800 transition" onclick="toggleFaq(this)">
                        <span>TURN AROUND TIME</span>
                        <svg class="w-5 h-5 transform transition-transform text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="faq-answer px-6 py-4 hidden bg-gray-800 text-gray-200">
                        <p>We usually tell all our customers that our average turn around time is 5-7 working days if there is no extensive work being done.</p>
                        <p class="mt-2">Anything that requires cutting and welding takes 14-21 working days subject to parts availability.</p>
                    </div>
                </div>
                
                <!-- FAQ Item 2 -->
                <div class="faq-item border border-gray-700 rounded-lg overflow-hidden">
                    <button class="faq-question w-full bg-gray-900 px-6 py-4 text-left font-semibold text-lg flex justify-between items-center hover:bg-gray-800 transition" onclick="toggleFaq(this)">
                        <span>COURTESY VEHICLES</span>
                        <svg class="w-5 h-5 transform transition-transform text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="faq-answer px-6 py-4 hidden bg-gray-800 text-gray-200">
                        <p>We offer courtesy vehicles for clients who may not have car hire on their policies, subject to availability.</p>
                    </div>
                </div>
                
                <!-- FAQ Item 3 -->
                <div class="faq-item border border-gray-700 rounded-lg overflow-hidden">
                    <button class="faq-question w-full bg-gray-900 px-6 py-4 text-left font-semibold text-lg flex justify-between items-center hover:bg-gray-800 transition" onclick="toggleFaq(this)">
                        <span>ARE ALTERNATE OR SECOND HAND PARTS USED ON VEHICLES</span>
                        <svg class="w-5 h-5 transform transition-transform text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="faq-answer px-6 py-4 hidden bg-gray-800 text-gray-200">
                        <p>If the vehicle is out of warranty, we are generally required to fit alternate/used parts in order to repair within a reasonable cost.</p>
                        <p class="mt-2">We also take instruction from clients if parts are not OEM. We are transparent with all our repairs.</p>
                    </div>
                </div>
                
                <!-- FAQ Item 4 -->
                <div class="faq-item border border-gray-700 rounded-lg overflow-hidden">
                    <button class="faq-question w-full bg-gray-900 px-6 py-4 text-left font-semibold text-lg flex justify-between items-center hover:bg-gray-800 transition" onclick="toggleFaq(this)">
                        <span>WARRANTIES</span>
                        <svg class="w-5 h-5 transform transition-transform text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="faq-answer px-6 py-4 hidden bg-gray-800 text-gray-200">
                        <p>We have a 1 year workmanship warranty and a lifetime paint warranty.</p>
                        <p class="mt-2">Parts warranties are as per the suppliers' warranties.</p>
                    </div>
                </div>
                
                <!-- FAQ Item 5 -->
                <div class="faq-item border border-gray-700 rounded-lg overflow-hidden">
                    <button class="faq-question w-full bg-gray-900 px-6 py-4 text-left font-semibold text-lg flex justify-between items-center hover:bg-gray-800 transition" onclick="toggleFaq(this)">
                        <span>ARE WE DRIVING YOUR VEHICLES</span>
                        <svg class="w-5 h-5 transform transition-transform text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="faq-answer px-6 py-4 hidden bg-gray-800 text-gray-200">
                        <p>We do not drive customer vehicles unless we are requested to do so.</p>
                    </div>
                </div>
                
                <!-- FAQ Item 6 -->
                <div class="faq-item border border-gray-700 rounded-lg overflow-hidden">
                    <button class="faq-question w-full bg-gray-900 px-6 py-4 text-left font-semibold text-lg flex justify-between items-center hover:bg-gray-800 transition" onclick="toggleFaq(this)">
                        <span>PAINT</span>
                        <svg class="w-5 h-5 transform transition-transform text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="faq-answer px-6 py-4 hidden bg-gray-800 text-gray-200">
                        <p>We use a spectra to match the paint color on your vehicle to get it to a 100% match.</p>
                        <p class="mt-2">We also use the highest quality water-bourne paint.</p>
                    </div>
                </div>
                
                <!-- FAQ Item 7 -->
                <div class="faq-item border border-gray-700 rounded-lg overflow-hidden">
                    <button class="faq-question w-full bg-gray-900 px-6 py-4 text-left font-semibold text-lg flex justify-between items-center hover:bg-gray-800 transition" onclick="toggleFaq(this)">
                        <span>THE STORAGE OF YOUR VEHICLES OVER NIGHT</span>
                        <svg class="w-5 h-5 transform transition-transform text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="faq-answer px-6 py-4 hidden bg-gray-800 text-gray-200">
                        <p>All cars are parked inside the workshop in order to make sure your vehicles are safe and secure over night.</p>
                    </div>
                </div>
                
                <!-- FAQ Item 8 -->
                <div class="faq-item border border-gray-700 rounded-lg overflow-hidden">
                    <button class="faq-question w-full bg-gray-900 px-6 py-4 text-left font-semibold text-lg flex justify-between items-center hover:bg-gray-800 transition" onclick="toggleFaq(this)">
                        <span>RELEASING OF VEHICLES</span>
                        <svg class="w-5 h-5 transform transition-transform text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="faq-answer px-6 py-4 hidden bg-gray-800 text-gray-200">
                        <p>When you book in the vehicle, we take a copy of your ID to ensure that when you pick it up, it is you.</p>
                        <p class="mt-2">We do not release any vehicles to unidentified persons to ensure your vehicle is picked up and to avoid theft.</p>
                        <p class="mt-2">If you are sending someone else to collect your vehicle, we need written authorization via email.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- JavaScript for Accordion -->
<script>
    function toggleFaq(button) {
        // Toggle active class on button
        button.classList.toggle('active');
        
        // Get the answer element
        const answer = button.nextElementSibling;
        
        // Toggle display of answer
        if (answer.classList.contains('hidden')) {
            answer.classList.remove('hidden');
            button.querySelector('svg').classList.add('rotate-180');
        } else {
            answer.classList.add('hidden');
            button.querySelector('svg').classList.remove('rotate-180');
        }
    }
    
    // Open the first FAQ item by default
    document.addEventListener('DOMContentLoaded', function() {
        const firstFaqQuestion = document.querySelector('.faq-question');
        if (firstFaqQuestion) {
            toggleFaq(firstFaqQuestion);
        }
    });
</script>

<?php
// Include footer
require_once 'includes/footer.php';
?> 