<?php
declare(strict_types=1);

// Set page title
$page_title = 'Woman on Fire';

// Include header
require_once 'includes/header.php';
?>

<!-- Hero Section with Logo -->
<section class="relative bg-white py-12 md:py-20">
    <div class="container mx-auto px-4">
        <div class="flex flex-col items-center text-center">
            <h1 class="text-5xl md:text-6xl font-bold mb-4 text-red-600">Welcome to WomanonFire (PTY)Ltd</h1>
            <p class="text-xl md:text-2xl mb-8 text-black">Empowering Women in the Autobody Repair Industry</p>
            <a href="contact.php" class="bg-red-600 text-white font-medium py-3 px-12 rounded hover:bg-red-700 transition duration-300 mb-8">CONTACT US</a>
            <div class="flex justify-center">
                <img src="images/wof logo.png" alt="Woman on Fire Logo" class="w-48 md:w-56 h-auto">
            </div>
        </div>
    </div>
</section>

<!-- Main Content Section -->
<section class="bg-black text-white py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-5xl mx-auto">
            <h3 class="text-red-500 text-xl md:text-2xl text-center mb-12 font-normal">
                Founded by visionary leader Rosy Manikam, WomanonFire (Pty) Ltd is a proudly South African non-profit organization dedicated to breaking barriers and creating opportunities for women in the automotive industry. Our mission is to light the way for women to not only enter but thrive in a sector traditionally dominated by men.
            </h3>

            <div class="grid md:grid-cols-2 gap-8 items-center mb-16">
                <div>
                    <img src="images/mix paint.JPG" alt="Woman working with paint mixing" class="rounded-lg w-full">
                </div>
                <div class="text-center">
                    <h2 class="text-2xl md:text-3xl font-bold mb-6">Igniting Passion. Driving Change.<br>Empowering Women.</h2>
                    <p class="mb-6">
                        We are a passionate, non-profit organization committed to breaking down barriers, providing support, and creating opportunities for women to thrive in this dynamic and rewarding industry.
                    </p>
                    <p>
                        We envision a world where women are equally represented in every aspect of the autobody repair industry, from technical roles to leadership positions. We aim to foster an environment where women not only succeed but excel, creating a lasting impact in a field that requires skill, innovation, and creativity.
                    </p>
                </div>
            </div>

            <!-- What We Do Section -->
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold mb-12">What we do</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- Service 1 -->
                    <div class="flex flex-col items-center">
                        <div class="bg-white rounded-full p-4 w-24 h-24 flex items-center justify-center mb-6">
                            <img src="images/icons/empower.jpg" alt="Empowerment Icon" class="w-16 h-16">
                        </div>
                        <div class="bg-red-600 p-6 rounded text-center h-[180px] flex items-center justify-center">
                            <p class="text-white text-base">
                                Empowering women to break into and succeed in the autobody repair industry through education, training, and mentorship.
                            </p>
                        </div>
                    </div>

                    <!-- Service 2 -->
                    <div class="flex flex-col items-center">
                        <div class="bg-white rounded-full p-4 w-24 h-24 flex items-center justify-center mb-6">
                            <img src="images/icons/resources.jpg" alt="Resources Icon" class="w-16 h-16">
                        </div>
                        <div class="bg-red-600 p-6 rounded text-center h-[180px] flex items-center justify-center">
                            <p class="text-white text-base">
                                Offering resources to help women gain the technical expertise and confidence they need to pursue careers in this field.
                            </p>
                        </div>
                    </div>

                    <!-- Service 3 -->
                    <div class="flex flex-col items-center">
                        <div class="bg-white rounded-full p-4 w-24 h-24 flex items-center justify-center mb-6">
                            <img src="images/icons/community.jpg" alt="Community Icon" class="w-16 h-16">
                        </div>
                        <div class="bg-red-600 p-6 rounded text-center h-[180px] flex items-center justify-center">
                            <p class="text-white text-base">
                                Building a supportive community that uplifts women in their personal and professional journeys, encouraging collaboration, and sharing of knowledge.
                            </p>
                        </div>
                    </div>

                    <!-- Service 4 -->
                    <div class="flex flex-col items-center">
                        <div class="bg-white rounded-full p-4 w-24 h-24 flex items-center justify-center mb-6">
                            <img src="images/icons/gender.jpg" alt="Diversity Icon" class="w-16 h-16">
                        </div>
                        <div class="bg-red-600 p-6 rounded text-center h-[180px] flex items-center justify-center">
                            <p class="text-white text-base">
                                Advocating for gender diversity and inclusion within the industry, ensuring equal opportunities for women at every level of the profession.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Industry Context -->
            <div class="grid md:grid-cols-2 gap-8 items-center mb-16">
                <div class="text-lg">
                    <p class="mb-4">
                        The autobody repair industry has been historically underrepresented by women, yet it is a field full of untapped potential and opportunity. By empowering women to take on roles that range from technicians to shop owners, we're not only changing the industry but also giving women a platform to showcase their talent, strength, and determination.
                    </p>
                    <p>
                        At WomanonFire (PTY) Ltd, we believe in creating a supportive and inclusive culture where women can confidently build fulfilling careers, break down gender barriers, and make their mark on an industry that is constantly evolving.
                    </p>
                </div>
                <div>
                    <img src="images/women mechanic at work.jpg" alt="Woman mechanic at work" class="rounded-lg w-full">
                </div>
            </div>

            <!-- Why It Matters -->
            <div class="mb-16">
                <h2 class="text-3xl md:text-4xl font-bold mb-8 text-center">Why it matters</h2>
                <div class="bg-red-600 p-6 md:p-8 rounded-lg text-center">
                    <p class="text-lg md:text-xl">
                        In a country where unemployment and inequality continue to impact women disproportionately, we believe economic empowerment begins with access and opportunity. By equipping women with technical skills, confidence, and a strong support system, we're not just fixing cars — we're driving transformation.
                    </p>
                </div>
            </div>

            <!-- Join The Movement -->
            <div class="mb-16">
                <h2 class="text-3xl md:text-4xl font-bold mb-8 text-center">Join The Movement</h2>
                <div class="grid md:grid-cols-2 gap-8 items-center">
                    <div class="text-center md:text-left">
                        <p class="mb-6">
                            Whether you are a woman looking to start or advance your career in autobody repair, or you are an ally who wants to support women in the industry, WomanonFire (PTY) Ltd welcomes you to be part of our community.
                        </p>
                        <p class="mb-8 font-bold">
                            Let's work together to create a more diverse, inclusive, and empowered future for women in the trades.
                        </p>
                        <div class="bg-white text-black p-6 rounded-lg">
                            <p class="font-bold mb-4">For training inquiries, partnership opportunities, or to support our cause:</p>
                            <p class="mb-2 flex items-center justify-center md:justify-start">
                                <i class="fas fa-envelope text-red-600 mr-2"></i>
                                <a href="mailto:rosy.womanonfire@gmail.com" class="text-red-600 hover:underline">rosy.womanonfire@gmail.com</a>
                            </p>
                            <p class="flex items-center justify-center md:justify-start">
                                <i class="fas fa-phone text-red-600 mr-2"></i>
                                <a href="tel:031-8279977" class="text-red-600 hover:underline">031-8279977</a>
                            </p>
                        </div>
                    </div>
                    <div>
                        <img src="images/women working on car.jpg" alt="Woman working on car" class="rounded-lg w-full">
                    </div>
                </div>
            </div>

            <!-- Final Call to Action -->
            <div class="text-center">
                <h2 class="text-2xl md:text-3xl font-bold text-red-500">
                    Together, we can break the barrier and show the world that women are on fire in the autobody repair industry!
                </h2>
            </div>
        </div>
    </div>
</section>

<?php
// Include footer
require_once 'includes/footer.php';
?> 