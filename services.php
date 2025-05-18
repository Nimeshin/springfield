<?php
declare(strict_types=1);

// Set page title
$page_title = 'Our Services';

// Include header
require_once 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section relative h-96" style="background-image: url('images/services-header.JPG'); background-repeat: no-repeat; background-size: cover; background-position: center;">
    <div class="absolute inset-0 bg-black opacity-60"></div>
    <div class="relative z-10 w-full h-full flex flex-col justify-center items-center text-center p-4">
        <div class="container mx-auto">
            <h1 class="text-4xl md:text-6xl font-bold mb-4">Expert Autobody Repair & Refinishing</h1>
            <p class="text-xl md:text-2xl mb-8">Quality repairs you can trust</p>
            <a href="contact.php" class="bg-primary text-white font-bold py-3 px-6 rounded-lg hover:bg-red-700 transition duration-300 pulse-button">QUOTE REQUEST</a>
        </div>
    </div>
</section>

<!-- Services Introduction -->
<section class="py-10 bg-black">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto text-center">
            <h3 class="text-red-500 text-xl md:text-2xl text-center mb-12 font-normal">
                At Springfield Panel & Paint, we specialize in high-quality autobody repairs and refinishing to restore your vehicle to its original condition. Our skilled technicians use state-of-the-art equipment and premium materials to deliver flawless results.
            </h3>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="py-16 bg-black">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl md:text-4xl font-bold mb-12 text-center text-white">Services Offered</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Service 1 -->
            <div class="service-card bg-white rounded-lg overflow-hidden shadow-lg">
                <div class="h-56 overflow-hidden">
                    <img src="images/frame and structural repairs.jpg" alt="Frame & Structural Repairs" class="w-full h-full object-cover">
                </div>
                <div class="bg-primary text-white py-3">
                    <h3 class="text-xl font-bold text-center">Frame & Structural Repairs</h3>
                </div>
            </div>
            
            <!-- Service 2 -->
            <div class="service-card bg-white rounded-lg overflow-hidden shadow-lg">
                <div class="h-56 overflow-hidden">
                    <img src="images/dent repairs.jpg" alt="Oil Spot Repairs" class="w-full h-full object-cover">
                </div>
                <div class="bg-primary text-white py-3">
                    <h3 class="text-xl font-bold text-center">Oil Spot Repairs</h3>
                </div>
            </div>
            
            <!-- Service 3 -->
            <div class="service-card bg-white rounded-lg overflow-hidden shadow-lg">
                <div class="h-56 overflow-hidden">
                    <img src="images/auto body painting.jpg" alt="Auto Body Painting" class="w-full h-full object-cover">
                </div>
                <div class="bg-primary text-white py-3">
                    <h3 class="text-xl font-bold text-center">Auto Body Painting</h3>
                </div>
            </div>
            
            <!-- Service 4 -->
            <div class="service-card bg-white rounded-lg overflow-hidden shadow-lg">
                <div class="h-56 overflow-hidden">
                    <img src="images/suspension and alignment.jpg" alt="Suspension & Alignment" class="w-full h-full object-cover">
                </div>
                <div class="bg-primary text-white py-3">
                    <h3 class="text-xl font-bold text-center">Suspension & Alignment</h3>
                </div>
            </div>
            
            <!-- Service 5 -->
            <div class="service-card bg-white rounded-lg overflow-hidden shadow-lg">
                <div class="h-56 overflow-hidden">
                    <img src="images/complimentary towing.jpg" alt="Complimentary Towing" class="w-full h-full object-cover">
                </div>
                <div class="bg-primary text-white py-3">
                    <h3 class="text-xl font-bold text-center">Complimentary Towing</h3>
                </div>
            </div>
            
            <!-- Service 6 -->
            <div class="service-card bg-white rounded-lg overflow-hidden shadow-lg">
                <div class="h-56 overflow-hidden">
                    <img src="images/insurance claim.jpg" alt="Insurance Claim Assist" class="w-full h-full object-cover">
                </div>
                <div class="bg-primary text-white py-3">
                    <h3 class="text-xl font-bold text-center">Insurance Claim Assist</h3>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section class="py-16 bg-black text-white">
    <div class="container mx-auto px-4">
        <h2 class="text-4xl font-bold mb-16 text-center text-white">Why choose us?</h2>
        
        <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Item 1 -->
            <div class="bg-gray-900 rounded-lg p-6 border-l-4 border-green-500 transform transition-transform hover:scale-105 duration-300">
                <div class="flex items-center mb-4">
                    <div class="bg-green-500 rounded-full p-2 mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold">Certified and Experienced Technicians</h3>
                </div>
                <p class="text-gray-300 ml-16">Our team consists of industry-certified professionals with years of experience in auto body repair.</p>
            </div>
            
            <!-- Item 2 -->
            <div class="bg-gray-900 rounded-lg p-6 border-l-4 border-green-500 transform transition-transform hover:scale-105 duration-300">
                <div class="flex items-center mb-4">
                    <div class="bg-green-500 rounded-full p-2 mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold">Advanced Repair Technology</h3>
                </div>
                <p class="text-gray-300 ml-16">We use state-of-the-art equipment and modern techniques for precise and efficient repairs.</p>
            </div>
            
            <!-- Item 3 -->
            <div class="bg-gray-900 rounded-lg p-6 border-l-4 border-green-500 transform transition-transform hover:scale-105 duration-300">
                <div class="flex items-center mb-4">
                    <div class="bg-green-500 rounded-full p-2 mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold">Fast Turnaround and Competitive Pricing</h3>
                </div>
                <p class="text-gray-300 ml-16">We value your time and budget, offering quick service at fair and transparent rates.</p>
            </div>
            
            <!-- Item 4 -->
            <div class="bg-gray-900 rounded-lg p-6 border-l-4 border-green-500 transform transition-transform hover:scale-105 duration-300">
                <div class="flex items-center mb-4">
                    <div class="bg-green-500 rounded-full p-2 mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold">Customer Satisfaction Guaranteed</h3>
                </div>
                <p class="text-gray-300 ml-16">Your satisfaction is our priority, backed by our quality guarantees and warranties.</p>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-16 bg-black text-white">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl md:text-4xl font-bold mb-6 text-center text-white">Frequently Asked Questions</h2>
        <p class="text-xl md:text-2xl mb-12 text-center">Everything you need to know about our services</p>
        
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

                <!-- FAQ Item 9 - Free Towing -->
                <div class="faq-item border border-gray-700 rounded-lg overflow-hidden">
                    <button class="faq-question w-full bg-gray-900 px-6 py-4 text-left font-semibold text-lg flex justify-between items-center hover:bg-gray-800 transition" onclick="toggleFaq(this)">
                        <span>FREE TOWING SERVICE</span>
                        <svg class="w-5 h-5 transform transition-transform text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="faq-answer px-6 py-4 hidden bg-gray-800 text-gray-200">
                        <p>We offer free trade tow within 40km radius if the vehicle is repaired by us.</p>
                        <p class="mt-2">This service helps ensure your vehicle gets to our workshop safely and conveniently.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Free Towing Service Section -->
<section class="py-16 bg-black text-white">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-4xl md:text-5xl font-bold mb-6">Ready to Get Your Vehicle Repaired?</h2>
            <p class="text-xl mb-4 font-light">BIG OR SMALL, WE REPAIR THEM ALL!</p>
            <p class="text-xl mb-8 font-light">We offer free trade tow within 40km radius IF the vehicle is repaired by us</p>
            
            <div class="flex flex-wrap justify-center gap-6 mb-12">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Professional Service</span>
                </div>
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Quick Turnaround</span>
                </div>
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Quality Guaranteed</span>
                </div>
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Free Towing Within 40km Radius</span>
                </div>
            </div>
            
            <div class="flex justify-center">
                <a href="contact.php" class="inline-flex items-center bg-primary text-white font-bold py-4 px-8 rounded-lg hover:bg-red-700 transition duration-300 group shadow-lg">
                    GET A QUOTE
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2 group-hover:translate-x-1 transition-transform" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
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