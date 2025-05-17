<?php
declare(strict_types=1);

// Set page title
$page_title = 'Home';

// Include header
require_once 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section relative h-96 md:h-screen bg-no-repeat bg-cover bg-center" style="background-image: url('images/home-banner.jpg');">
    <div class="absolute inset-0 bg-black opacity-60"></div>
    <div class="relative z-10 w-full h-full flex flex-col justify-center items-center text-center p-4">
        <div class="container mx-auto">
            <h1 class="text-5xl md:text-7xl font-bold mb-4 text-white">EXPERT<br>AUTO BODY<br>REPAIRS</h1>
            <p class="text-xl md:text-3xl mb-10 text-white">Professional Service You Can Trust</p>
            <a href="contact.php" class="bg-primary text-white font-bold py-4 px-8 rounded-lg hover:bg-red-700 transition duration-300 pulse-button text-xl">GET A QUOTE</a>
        </div>
    </div>
</section>

<!-- WomanonFire Section -->
<section class="bg-black text-white py-16">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl md:text-4xl font-bold mb-8 text-center text-primary">WomanonFire: Igniting Excellence in Auto Body Repairs</h2>
        
        <div class="flex flex-col md:flex-row items-center gap-8">
            <!-- Left Content -->
            <div class="md:w-1/2">
                <div class="prose prose-lg text-white max-w-none">
                    <p class="mb-4">In an industry often dominated by men, we are proud to stand out as a woman-owned panel beating business committed to exceptional service and quality. With a strong focus on empowering black women, we provide a welcoming environment where skills and talent are valued over gender.</p>
                    <p>Our team of expert panel beaters brings years of experience and a passion for innovation to every project, ensuring that our customers receive the best in auto repair. We're not just fixing cars – we're changing the narrative for women in the automotive world.</p>
                </div>
                <div class="flex flex-wrap items-center gap-6">
                    <a href="woman-on-fire.php" class="inline-block mt-6 bg-primary text-white py-2 px-6 rounded hover:bg-red-700 transition duration-300">LEARN MORE</a>
                    <div class="mt-6 flex items-center">
                        <img src="images/bee level1.png" alt="BEE Level 1 Certification" class="h-16 object-contain">
                        <span class="ml-2 text-white font-medium">Proudly Level 1 B-BBEE Certified</span>
                    </div>
                </div>
            </div>
            
            <!-- Right Image -->
            <div class="md:w-1/2 mt-8 md:mt-0">
                <img src="images/Springfield P&P-16.JPG" alt="Auto Body Repair Expert" class="w-full h-auto rounded-lg shadow-xl object-cover">
            </div>
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

<!-- Mission & Vision Section -->
<section class="py-16">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl md:text-4xl font-bold mb-12 text-center">What we stand for</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Mission -->
            <div class="bg-primary text-white p-8 rounded-lg shadow-lg h-full">
                <h3 class="text-2xl font-bold mb-4">Our mission</h3>
                <p class="mb-4">
                    At Springfield Panel & Paint, our mission is to become the preferred destination for quality automotive body repairs and painting solutions. We're committed to treating each vehicle with the utmost care and providing an experience that ensures customers return time and again. We believe in delivering honest service without cutting corners—quality, integrity, expertise, and workmanship—all at competitive rates coupled with the highest standards in service and exceptional value for money.
                </p>
            </div>
            
            <!-- Vision -->
            <div class="bg-primary text-white p-8 rounded-lg shadow-lg h-full">
                <h3 class="text-2xl font-bold mb-4">Our vision</h3>
                <p class="mb-4">
                    Our vision revolves around becoming Springfield's premier auto body workshop. In achieving this goal, we commit to providing exceptional service that truly meets and exceeds our customer expectations. Each team member offers a distinctive element which ultimately contributes to our success, creating a positive environment that allows each employee to perform at their optimum level and deliver exceptional results to our customers.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Accreditation Section -->
<section class="py-16 bg-black">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-12 text-white">Proudly accredited</h2>
        <p class="text-gray-300 mb-8 max-w-4xl mx-auto">
            As one accident repair shop among many, we are proud to maintain our reputation through our accreditations and commitment to maintaining our standards of excellence by continually developing our services.
        </p>
        
        <div class="flex flex-wrap justify-center gap-8 mt-8">
            <img src="images/sambra.jpg" alt="SAMBRA Accreditation" class="h-40 rounded-lg shadow-lg">
            <img src="images/rmi.jpg" alt="RMI Accreditation" class="h-40 rounded-lg shadow-lg">
        </div>
    </div>
</section>

<!-- Manufacturer Approvals -->
<section class="py-16 bg-black">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-8 text-white">Manufacturer approvals</h2>
        <p class="text-gray-300 mb-12 max-w-4xl mx-auto">
            At our manufacturing facility, we are proud to be accredited by many of the world's leading brands in the auto industry. Our standards align with the highest benchmarks set by these manufacturers, ensuring quality and reliability across all vehicles.
        </p>
    
        <div class="mx-auto" style="max-width: 1505px;">
            <div class="bg-white py-8 px-6 rounded-xl shadow-xl overflow-hidden">
                <div class="overflow-hidden">
                    <div class="flex animate-scroll">
                        <!-- First set of logos -->
                        <div class="flex items-center gap-16 mx-12">
                            <img src="images/logos/gwm.png" alt="GWM/Haval" class="h-12 md:h-16 object-contain">
                            <img src="images/logos/peugeot.png" alt="Peugeot" class="h-12 md:h-16 object-contain">
                            <img src="images/logos/hyundai.png" alt="Hyundai" class="h-12 md:h-16 object-contain">
                            <img src="images/logos/Suzuki.png" alt="Suzuki" class="h-12 md:h-16 object-contain">
                            <img src="images/logos/ford.png" alt="Ford" class="h-12 md:h-16 object-contain">
                            <img src="images/logos/citron.png" alt="Citroen" class="h-12 md:h-16 object-contain">
                            <img src="images/logos/opel.png" alt="Opel" class="h-12 md:h-16 object-contain">
                            <img src="images/logos/renault.png" alt="Renault" class="h-12 md:h-16 object-contain">
                            <img src="images/logos/kia.png" alt="Kia" class="h-12 md:h-16 object-contain">
                            <img src="images/logos/jac.png" alt="JAC" class="h-12 md:h-16 object-contain">
                            <img src="images/logos/Chevrolet.png" alt="Chevrolet" class="h-12 md:h-16 object-contain">
                        </div>
                        
                        <!-- Duplicate set for seamless scrolling -->
                        <div class="flex items-center gap-16 mx-12">
                            <img src="images/logos/gwm.png" alt="GWM/Haval" class="h-12 md:h-16 object-contain">
                            <img src="images/logos/peugeot.png" alt="Peugeot" class="h-12 md:h-16 object-contain">
                            <img src="images/logos/hyundai.png" alt="Hyundai" class="h-12 md:h-16 object-contain">
                            <img src="images/logos/Suzuki.png" alt="Suzuki" class="h-12 md:h-16 object-contain">
                            <img src="images/logos/ford.png" alt="Ford" class="h-12 md:h-16 object-contain">
                            <img src="images/logos/citron.png" alt="Citroen" class="h-12 md:h-16 object-contain">
                            <img src="images/logos/opel.png" alt="Opel" class="h-12 md:h-16 object-contain">
                            <img src="images/logos/renault.png" alt="Renault" class="h-12 md:h-16 object-contain">
                            <img src="images/logos/kia.png" alt="Kia" class="h-12 md:h-16 object-contain">
                            <img src="images/logos/jac.png" alt="JAC" class="h-12 md:h-16 object-contain">
                            <img src="images/logos/Chevrolet.png" alt="Chevrolet" class="h-12 md:h-16 object-contain">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
@keyframes scroll {
    0% {
        transform: translateX(0);
    }
    100% {
        transform: translateX(-50%);
    }
}

.animate-scroll {
    animation: scroll 30s linear infinite;
    min-width: max-content;
}
</style>

<!-- Counter Section -->
<section class="bg-primary text-white py-12">
    <div class="container mx-auto px-4">
        <div class="flex justify-center items-center">
            <div class="text-center">
                <h3 class="text-2xl md:text-3xl font-bold mb-2">OVER 15,000 VEHICLES REPAIRED — QUALITY YOU CAN COUNT ON!</h3>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="py-16 bg-black">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl md:text-4xl font-bold mb-12 text-center text-white">What Our Clients Say</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Testimonial 1 -->
            <div class="testimonial-card bg-white p-8 rounded-xl shadow-xl transition-transform duration-300 hover:transform hover:scale-105">
                <div class="flex flex-col items-center text-center mb-6">
                    <div class="w-16 h-16 rounded-full border-2 border-red-500 flex items-center justify-center mb-4 shadow-md">
                        <i class="fas fa-user text-red-500 text-xl"></i>
                    </div>
                    <div class="text-yellow-500 mb-3">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <h4 class="font-bold text-lg text-gray-800">Sarah Thompson</h4>
                </div>
                <p class="text-gray-700 italic text-center">
                    "I had my car repaired at Springfield Panel & Paint after an accident. The team was incredibly professional and completed the work ahead of schedule. My car looks as good as new!"
                </p>
            </div>
            
            <!-- Testimonial 2 -->
            <div class="testimonial-card bg-white p-8 rounded-xl shadow-xl transition-transform duration-300 hover:transform hover:scale-105">
                <div class="flex flex-col items-center text-center mb-6">
                    <div class="w-16 h-16 rounded-full border-2 border-red-500 flex items-center justify-center mb-4 shadow-md">
                        <i class="fas fa-user text-red-500 text-xl"></i>
                    </div>
                    <div class="text-yellow-500 mb-3">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <h4 class="font-bold text-lg text-gray-800">Michael Williams</h4>
                </div>
                <p class="text-gray-700 italic text-center">
                    "The quality of workmanship at Springfield is unmatched. They took care of a major dent and repainting job on my SUV, and you can't even tell it was damaged. Excellent service and fair pricing."
                </p>
            </div>
            
            <!-- Testimonial 3 -->
            <div class="testimonial-card bg-white p-8 rounded-xl shadow-xl transition-transform duration-300 hover:transform hover:scale-105">
                <div class="flex flex-col items-center text-center mb-6">
                    <div class="w-16 h-16 rounded-full border-2 border-red-500 flex items-center justify-center mb-4 shadow-md">
                        <i class="fas fa-user text-red-500 text-xl"></i>
                    </div>
                    <div class="text-yellow-500 mb-3">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <h4 class="font-bold text-lg text-gray-800">Jennifer Rodriguez</h4>
                </div>
                <p class="text-gray-700 italic text-center">
                    "As a woman, I often feel intimidated at auto shops, but not at Springfield. Roxy and her team were welcoming, explained everything clearly, and did exceptional work on my car. I won't go anywhere else from now on."
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Get A Quote Section -->
<section class="py-16 bg-black relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0 bg-black"></div>
        <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-white/20"></div>
    </div>
    <div class="container mx-auto px-4 relative">
        <div class="max-w-4xl mx-auto text-center text-white">
            <h2 class="text-4xl md:text-5xl font-bold mb-6">Ready to Get Your Vehicle Repaired?</h2>
            <p class="text-xl mb-8 font-light">BIG OR SMALL, WE REPAIR THEM ALL!</p>
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

<!-- Map Section -->
<section class="h-96">
    <iframe src="https://www.google.com/maps/embed/v1/place?key=AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8&q=82+Intersite+Avenue,+Umgeni+Business+Park,+Durban,+KZN" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
</section>

<?php
// Include footer
require_once 'includes/footer.php';
?> 