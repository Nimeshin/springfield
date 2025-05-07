<?php
declare(strict_types=1);
// Include site configuration
require_once __DIR__ . '/../config.php';

// Determine current page for active navigation links
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= SITE_NAME ?> - <?= isset($page_title) ? $page_title : 'Auto Body Repairs' ?></title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#e11d24',
                        secondary: '#1e293b',
                        dark: '#0f172a',
                    }
                }
            }
        }
    </script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/css/style.css">
    
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* Custom gradient for footer */
        .footer-gradient {
            background: linear-gradient(to bottom, #000000, #990000);
        }
        
        /* Navigation styles */
        .nav-link {
            position: relative;
            padding: 0.5rem 0;
            margin: 0 1rem;
            font-weight: 500;
            letter-spacing: 0.5px;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 0;
            background-color: #e11d24;
            transition: width 0.3s ease;
        }
        
        .nav-link:hover::after {
            width: 100%;
        }
        
        .nav-link.active::after {
            width: 100%;
        }
        
        .nav-link.active {
            font-weight: 600;
        }
    </style>
</head>
<body class="bg-black text-white font-sans">
    <!-- Header Section -->
    <header class="bg-white shadow-md">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center">
                <a href="index.php" class="flex items-center">
                    <img src="images/logo.png" alt="<?= SITE_NAME ?>" class="h-20 w-auto">
                </a>
            </div>
            
            <!-- Desktop Navigation -->
            <nav class="hidden md:flex">
                <a href="index.php" class="nav-link text-secondary hover:text-primary transition-colors <?= $current_page === 'index.php' ? 'active text-primary' : '' ?>">Home</a>
                <a href="about.php" class="nav-link text-secondary hover:text-primary transition-colors <?= $current_page === 'about.php' ? 'active text-primary' : '' ?>">About us</a>
                <a href="services.php" class="nav-link text-secondary hover:text-primary transition-colors <?= $current_page === 'services.php' ? 'active text-primary' : '' ?>">Services</a>
                <a href="woman-on-fire.php" class="nav-link text-secondary hover:text-primary transition-colors <?= $current_page === 'woman-on-fire.php' ? 'active text-primary' : '' ?>">Woman on Fire</a>
                <a href="blog.php" class="nav-link text-secondary hover:text-primary transition-colors <?= $current_page === 'blog.php' ? 'active text-primary' : '' ?>">Blog</a>
                <a href="contact.php" class="nav-link text-secondary hover:text-primary transition-colors <?= $current_page === 'contact.php' ? 'active text-primary' : '' ?>">Contact</a>
            </nav>
            
            <!-- Phone Number (Desktop) -->
            <div class="hidden md:flex items-center">
                <a href="tel:<?= SITE_PHONE ?>" class="flex items-center bg-primary text-white px-4 py-2 rounded-full hover:bg-red-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                    </svg>
                    <span class="font-medium"><?= SITE_PHONE ?></span>
                </a>
            </div>
            
            <!-- Mobile Menu Button -->
            <button id="mobile-menu-button" class="md:hidden text-gray-600 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
        
        <!-- Mobile Navigation Menu (Hidden by default) -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-200">
            <div class="container mx-auto px-4 py-3 space-y-1">
                <a href="index.php" class="block py-3 px-3 rounded <?= $current_page === 'index.php' ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100' ?>">Home</a>
                <a href="about.php" class="block py-3 px-3 rounded <?= $current_page === 'about.php' ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100' ?>">About us</a>
                <a href="services.php" class="block py-3 px-3 rounded <?= $current_page === 'services.php' ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100' ?>">Services</a>
                <a href="woman-on-fire.php" class="block py-3 px-3 rounded <?= $current_page === 'woman-on-fire.php' ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100' ?>">Woman on Fire</a>
                <a href="blog.php" class="block py-3 px-3 rounded <?= $current_page === 'blog.php' ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100' ?>">Blog</a>
                <a href="contact.php" class="block py-3 px-3 rounded <?= $current_page === 'contact.php' ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100' ?>">Contact</a>
                <a href="tel:<?= SITE_PHONE ?>" class="flex items-center py-3 px-3 rounded my-2 bg-primary text-white">
                    <i class="fas fa-phone-alt mr-2"></i><?= SITE_PHONE ?>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="min-h-screen"> 