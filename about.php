<?php
declare(strict_types=1);

// Set page title
$page_title = 'About Us';

// Include header
require_once 'includes/header.php';

// Fetch gallery images
$galleryImages = [];
$galleryQuery = "SELECT id, image_path, display_order FROM gallery_images ORDER BY display_order ASC, created_at DESC";
$galleryResult = $conn->query($galleryQuery);

if ($galleryResult && $galleryResult->num_rows > 0) {
    while ($row = $galleryResult->fetch_assoc()) {
        $galleryImages[] = $row;
    }
    $galleryResult->free();
}
?>

<!-- Hero Section -->
<section class="hero-section relative h-96" style="background-image: url('images/about-banner.JPG'); background-repeat: no-repeat; background-size: cover; background-position: center;">
    <div class="absolute inset-0 bg-black opacity-60"></div>
    <div class="relative z-10 w-full h-full flex flex-col justify-center items-center text-center p-4">
        <div class="container mx-auto">
            <h1 class="text-3xl md:text-4xl font-bold mb-4">Where Expertise Meets Integrity — Your Trusted Automotive Partner</h1>
            <p class="text-lg md:text-xl mb-8">Caring for cars since 2013</p>
            <a href="contact.php" class="bg-primary text-white font-bold py-3 px-6 rounded-lg hover:bg-red-700 transition duration-300 text-lg">QUOTE REQUEST</a>
        </div>
    </div>
</section>

<!-- Company Info Section -->
<section class="py-16 bg-black text-white">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto text-center">
            <h3 class="text-red-500 text-xl md:text-2xl text-center mb-12 font-normal">WomanOnFire (PTY) LTD, trading as Springfield Panel and Paint, made its debut in 2013 as a dynamic force in the autobody repair industry.</h3>
            
            <div class="prose prose-lg max-w-none prose-invert text-center">
                <p class="mb-6 text-lg">
                    Proudly holding a Level 1 BBBEE certification, our woman-led team is dedicated to hiring based on skill and expertise, without regard to race, gender, or age. We are committed to delivering exceptional service and outstanding quality that consistently exceeds our customers' expectations.
                </p>
                <p class="mb-6 text-lg">
                    With a focus on empowering black women and youth in our community, our team of automotive professionals is passionate about revitalizing vehicles. From minor repairs to complete paint jobs, we specialize in returning cars to pristine condition.
                </p>
                <p class="mb-6 text-lg">
                    Our success is driven by our talented panel beaters and painters, who work seamlessly together to ensure that every client leaves with a smile and a vehicle that looks as good as new.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="bg-black py-16 text-white">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl md:text-4xl font-bold mb-8 text-center">MEET THE TEAM</h2>
        
        <div class="max-w-6xl mx-auto mb-12 text-center">
            <p class="text-lg">
                Led by a trailblazing female entrepreneur, we're on a mission to redefine the auto repair industry by offering superior panel beating services while empowering women to break into automotive careers. Our focus on precision and customer satisfaction is matched by our commitment to fostering a diverse, inclusive, and supportive environment for all.
            </p>
        </div>
        
        <div class="grid grid-cols-1 gap-8">
            <!-- Team Member 1 -->
            <div class="bg-gray-900 rounded-lg overflow-hidden shadow-lg border border-gray-800">
                <div class="flex flex-col md:flex-row md:h-[400px]">
                    <div class="w-full md:w-1/3">
                        <div class="aspect-w-3 aspect-h-3 md:aspect-none h-full">
                            <img src="images/Rosy Manikam.JPG" alt="Rosy Manikam" class="w-full h-full object-cover object-center object-top">
                        </div>
                    </div>
                    <div class="w-full md:w-2/3 p-6 overflow-y-auto">
                        <div class="text-center mb-6">
                            <h3 class="text-2xl font-bold mb-2">ROSY MANIKAM</h3>
                            <h4 class="text-lg font-semibold text-red-600">FOUNDER & CEO</h4>
                        </div>
                        <p class="text-gray-300 mb-6 text-base">
                            Rosy Manikam began her journey in the auto body repair industry as a teenager, working alongside her stepfather in a panel shop. In 2013, she realized her dream of owning a business by opening Springfield Panel and Paint in Durban, which has since expanded from a small rented space to a 1,000m2 owned facility. Today, her workshop employs 23 staff, with half being women and youth, reflecting her commitment to empowering underrepresented groups. Rosy is also a member of the South African Motor Body Repair Association (SAMBRA) and co-founded the WomanOnFire co-op to support other women in the industry. Despite challenges, she remains dedicated to mentoring, closing the skills gap, and advancing the sector, encouraging women to pursue their dreams with courage and determination.
                        </p>
                        <div class="text-center">
                            <a href="contact.php" class="inline-block bg-red-600 text-white font-semibold py-2 px-8 rounded hover:bg-red-700 transition text-base uppercase">Contact Us</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Team Member 2 -->
            <div class="bg-gray-900 rounded-lg overflow-hidden shadow-lg border border-gray-800">
                <div class="flex flex-col md:flex-row md:h-[400px]">
                    <div class="w-full md:w-1/3">
                        <div class="aspect-w-3 aspect-h-3 md:aspect-none h-full">
                            <img src="images/Yastiel.jpg" alt="Yastiel Manikam" class="w-full h-full object-cover object-center object-top">
                        </div>
                    </div>
                    <div class="w-full md:w-2/3 p-6 overflow-y-auto">
                        <div class="text-center mb-6">
                            <h3 class="text-2xl font-bold mb-2">YASTIEL MANIKAM</h3>
                            <h4 class="text-lg font-semibold text-red-600">GENERAL MANAGER</h4>
                        </div>
                        <p class="text-gray-300 mb-6 text-base">
                            Meet our General Manager, Yastiel, a young and dynamic leader following in the footsteps of her mother, Rosy Manikam. From a young age, Yastiel has been mentored and taught by Rosy, learning the ins and outs of running a successful auto body repair shop. Growing up in the industry, she has witnessed firsthand how dedication and hard work can shape a thriving business, and now, she's carrying on that legacy with pride. Yastiel is also a professional VDQ assessor, bringing a sharp eye for detail and expertise to the shop's daily operations. As the face of Springfield Panel and Paint, she manages the day-to-day activities of the shop while ensuring that every customer receives top-notch service. Alongside a talented team of service advisors, Yastiel is always ready to assist and guide you as you step through our doors, making your experience as seamless and positive as possible.
                        </p>
                        <div class="text-center">
                            <a href="contact.php" class="inline-block bg-red-600 text-white font-semibold py-2 px-8 rounded hover:bg-red-700 transition text-base uppercase">Contact Us</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Team Group Photo -->
        <div class="mt-16">
            <div class="bg-gray-900 rounded-lg overflow-hidden shadow-lg border border-gray-800">
                <div class="md:flex">
                    <div class="md:w-1/2">
                        <img src="images/team.JPG" alt="Springfield Team" class="w-full h-full object-cover">
                    </div>
                    <div class="md:w-1/2 flex flex-col justify-center p-6 bg-black">
                        <p class="text-lg text-gray-300 mb-8">
                            At Springfield Panel and Paint, our team is the heart of everything we do. We're a diverse group of skilled professionals with a true passion for precision and commitment to delivering the quality auto body repairs. From expert panel beaters and painters to our front-of-house staff, every member of our team plays a crucial role in ensuring that your vehicle is in the best hands.
                        </p>
                        <div class="bg-primary text-white p-6 rounded-lg text-center">
                            <h3 class="text-2xl font-bold mb-4">Certified Repairs, Guaranteed Satisfaction</h3>
                            <a href="contact.php" class="inline-block bg-white text-primary font-bold py-3 px-6 rounded-lg hover:bg-gray-200 transition duration-300 text-lg">GET A QUOTE</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Insurance Partners Section -->
<section class="py-16 bg-black text-white">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-8">INSURANCE PANELS</h2>
        <p class="text-lg text-gray-300 mb-12 max-w-6xl mx-auto">
            At Springfield Panel and Paint, we work with a wide range of insurance companies to provide seamless repairs and ensure the highest regard for your vehicle. As an approved repair center for major insurers, we are proud to be recognized for our high-quality workmanship and professional service.
        </p>
        
        <!-- Insurance logo strip organized in 3 rows of 4 logos each -->
        <div class="max-w-6xl mx-auto">
            <div class="bg-white px-4 py-8 rounded-xl shadow-lg">
                <!-- First row -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-8 mb-6">
                    <div class="flex justify-center items-center">
                        <img src="images/Insurance logo/guardrisk.png" alt="GuardRisk" class="h-10 md:h-12 lg:h-14 object-contain">
                    </div>
                    <div class="flex justify-center items-center">
                        <img src="images/Insurance logo/discovery.png" alt="Discovery Insurance" class="h-10 md:h-12 lg:h-14 object-contain">
                    </div>
                    <div class="flex justify-center items-center">
                        <img src="images/Insurance logo/momentum.png" alt="Momentum" class="h-10 md:h-12 lg:h-14 object-contain">
                    </div>
                    <div class="flex justify-center items-center">
                        <img src="images/Insurance logo/bryte.png" alt="Bryte Insurance" class="h-10 md:h-12 lg:h-14 object-contain">
                    </div>
                </div>
                
                <!-- Second row -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-8 mb-6">
                    <div class="flex justify-center items-center">
                        <img src="images/Insurance logo/miway.png" alt="MiWay Insurance" class="h-12 md:h-16 lg:h-20 object-contain">
                    </div>
                    <div class="flex justify-center items-center">
                        <img src="images/Insurance logo/sanatam.png" alt="Santam Insurance" class="h-10 md:h-12 lg:h-14 object-contain">
                    </div>
                    <div class="flex justify-center items-center">
                        <img src="images/Insurance logo/old mutual.png" alt="Old Mutual Insurance" class="h-10 md:h-12 lg:h-14 object-contain">
                    </div>
                    <div class="flex justify-center items-center">
                        <img src="images/Insurance logo/standard bank.png" alt="Standard Bank Insurance" class="h-12 md:h-16 lg:h-20 object-contain">
                    </div>
                </div>
                
                <!-- Third row -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-8">
                    <div class="flex justify-center items-center">
                        <img src="images/Insurance logo/hollard.png" alt="Hollard Insurance" class="h-10 md:h-12 lg:h-14 object-contain">
                    </div>
                    <div class="flex justify-center items-center">
                        <img src="images/Insurance logo/telesure.png" alt="Telesure" class="h-10 md:h-12 lg:h-14 object-contain">
                    </div>
                    <div class="flex justify-center items-center">
                        <img src="images/Insurance logo/outsurance.png" alt="Outsurance" class="h-12 md:h-16 lg:h-20 object-contain">
                    </div>
                    <div class="flex justify-center items-center">
                        <img src="images/Insurance logo/CIB.png" alt="CIB" class="h-10 md:h-12 lg:h-14 object-contain">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Gallery Section -->
<section class="py-16 bg-black text-white">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl md:text-4xl font-bold mb-12 text-center">GALLERY</h2>
        
        <?php if (empty($galleryImages)): ?>
            <!-- Default gallery images if no images are added in the admin panel -->
            <div class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-1 gap-6">
                <!-- Main Facility Image -->
                <div class="overflow-hidden rounded-lg shadow-lg">
                    <img src="images/Springfield P&P-1.JPG" alt="Springfield Facility" class="w-full h-auto object-cover">
                </div>
                
                <!-- Service Images -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                    <div class="overflow-hidden rounded-lg shadow-lg">
                        <img src="images/Springfield P&P-2.JPG" alt="Repair Services" class="w-full h-64 object-cover">
                        <div class="p-4 bg-primary text-white">
                            <h3 class="font-bold text-center text-lg">Precise mechanics to maintain your car's high performance</h3>
                        </div>
                    </div>
                    
                    <div class="overflow-hidden rounded-lg shadow-lg">
                        <img src="images/Springfield P&P-3.JPG" alt="Painting Services" class="w-full h-64 object-cover">
                        <div class="p-4 bg-primary text-white">
                            <h3 class="font-bold text-center text-lg">Quality work for great shine and durability on your vehicle</h3>
                        </div>
                    </div>
                    
                    <div class="overflow-hidden rounded-lg shadow-lg">
                        <img src="images/Springfield P&P-4.JPG" alt="Specialist Services" class="w-full h-64 object-cover">
                        <div class="p-4 bg-primary text-white">
                            <h3 class="font-bold text-center text-lg">Senior staff and premium equipment for service excellence</h3>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- Display gallery images from database -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($galleryImages as $image): ?>
                    <div class="overflow-hidden rounded-lg shadow-lg cursor-pointer gallery-item" onclick="openLightbox('<?= htmlspecialchars($image['image_path']) ?>')">
                        <img src="<?= htmlspecialchars($image['image_path']) ?>" alt="Gallery Image" class="w-full h-64 object-cover">
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Lightbox Modal -->
<div id="lightbox" class="fixed inset-0 bg-black bg-opacity-90 flex items-center justify-center z-50 hidden">
    <div class="relative w-full h-full flex flex-col items-center justify-center">
        <!-- Close Button -->
        <button id="close-lightbox" class="absolute top-4 right-4 text-white hover:text-gray-300 z-10">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        
        <!-- Navigation Buttons -->
        <button id="prev-image" class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white hover:text-gray-300 z-10">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>
        <button id="next-image" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-white hover:text-gray-300 z-10">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>
        
        <!-- Image Container -->
        <div class="flex items-center justify-center w-full h-full px-4 md:px-16">
            <img id="lightbox-image" src="" alt="Gallery Image" class="max-w-full max-h-full object-contain">
        </div>
    </div>
</div>

<!-- Lightbox JavaScript -->
<script>
    const lightbox = document.getElementById('lightbox');
    const lightboxImage = document.getElementById('lightbox-image');
    const closeLightbox = document.getElementById('close-lightbox');
    const prevButton = document.getElementById('prev-image');
    const nextButton = document.getElementById('next-image');
    
    // Gallery images data
    const galleryImages = [
        <?php foreach ($galleryImages as $image): ?>
            '<?= htmlspecialchars($image['image_path']) ?>',
        <?php endforeach; ?>
    ];
    
    let currentImageIndex = 0;
    
    // Open lightbox with specific image
    function openLightbox(imagePath) {
        lightboxImage.src = imagePath;
        lightbox.classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Prevent scrolling
        
        // Set current image index
        currentImageIndex = galleryImages.indexOf(imagePath);
    }
    
    // Close lightbox
    closeLightbox.addEventListener('click', () => {
        lightbox.classList.add('hidden');
        document.body.style.overflow = ''; // Enable scrolling
    });
    
    // Navigate to previous image
    prevButton.addEventListener('click', () => {
        currentImageIndex = (currentImageIndex - 1 + galleryImages.length) % galleryImages.length;
        lightboxImage.src = galleryImages[currentImageIndex];
    });
    
    // Navigate to next image
    nextButton.addEventListener('click', () => {
        currentImageIndex = (currentImageIndex + 1) % galleryImages.length;
        lightboxImage.src = galleryImages[currentImageIndex];
    });
    
    // Close lightbox when clicking outside the image
    lightbox.addEventListener('click', (e) => {
        if (e.target === lightbox) {
            lightbox.classList.add('hidden');
            document.body.style.overflow = '';
        }
    });
    
    // Keyboard navigation
    document.addEventListener('keydown', (e) => {
        if (lightbox.classList.contains('hidden')) return;
        
        if (e.key === 'Escape') {
            lightbox.classList.add('hidden');
            document.body.style.overflow = '';
        } else if (e.key === 'ArrowLeft') {
            currentImageIndex = (currentImageIndex - 1 + galleryImages.length) % galleryImages.length;
            lightboxImage.src = galleryImages[currentImageIndex];
        } else if (e.key === 'ArrowRight') {
            currentImageIndex = (currentImageIndex + 1) % galleryImages.length;
            lightboxImage.src = galleryImages[currentImageIndex];
        }
    });
</script>

<?php
// Include footer
require_once 'includes/footer.php';
?> 