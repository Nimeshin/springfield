<?php
/**
 * Admin header template
 *
 * Provides consistent navigation and branding across all admin pages.
 */

// Default page title if not set
$pageTitle = $pageTitle ?? 'Admin Panel';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> - Springfield Panel and Paint</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/custom.css">
    <script src="js/text-formatter.js"></script>
</head>
<body class="flex flex-col min-h-screen bg-gray-100">
    <!-- Top Navigation -->
    <header class="bg-white shadow-md">
        <div class="container mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center">
                <button id="toggle-sidebar" class="mr-3 text-gray-600 hover:text-gray-900 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <span class="text-xl font-semibold text-gray-800">Admin Panel</span>
            </div>
            
            <div class="flex items-center">
                <div class="relative inline-block mr-4">
                    <button id="notif-button" class="text-gray-600 hover:text-gray-900 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                    </button>
                </div>
                
                <div class="relative inline-block">
                    <button id="user-menu-button" class="flex items-center text-gray-600 hover:text-gray-900 focus:outline-none">
                        <img class="h-8 w-8 rounded-full object-cover" src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['admin_username'] ?? 'Admin') ?>&background=0D8ABC&color=fff" alt="Profile Image">
                        <span class="ml-2 text-sm font-medium hidden md:block"><?= htmlspecialchars($_SESSION['admin_username'] ?? 'Admin') ?></span>
                    </button>
                </div>
            </div>
        </div>
    </header>
    
    <div class="flex flex-1">
        <!-- Sidebar -->
        <aside id="sidebar" class="w-64 bg-gray-800 text-white h-screen sticky top-0">
            <div class="p-4 flex items-center justify-center">
                <a href="../index.php" target="_blank" class="block text-center">
                    <div class="h-12 w-12 mx-auto rounded-full bg-gray-600 flex items-center justify-center">
                        <span class="text-xl font-bold">SP</span>
                    </div>
                    <span class="mt-2 block text-sm font-medium">Springfield</span>
                </a>
            </div>
            
            <nav class="mt-6">
                <div class="px-4 py-2">
                    <h5 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">MAIN</h5>
                </div>
                
                <a href="dashboard.php" class="px-4 py-3 hover:bg-gray-700 flex items-center transition-colors duration-200">
                    <div class="mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                    </div>
                    <span>Dashboard</span>
                </a>
                
                <a href="blog_posts.php" class="px-4 py-3 hover:bg-gray-700 flex items-center transition-colors duration-200">
                    <div class="mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                        </svg>
                    </div>
                    <span>Blog Posts</span>
                </a>
                
                <a href="testimonials.php" class="px-4 py-3 hover:bg-gray-700 flex items-center transition-colors duration-200">
                    <div class="mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                        </svg>
                    </div>
                    <span>Testimonials</span>
                </a>
                
                <a href="messages.php" class="px-4 py-3 hover:bg-gray-700 flex items-center transition-colors duration-200">
                    <div class="mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <span>Messages</span>
                </a>
                
                <div class="px-4 py-2 mt-4">
                    <h5 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">TOOLS</h5>
                </div>
                
                <a href="gallery.php" class="px-4 py-3 hover:bg-gray-700 flex items-center transition-colors duration-200">
                    <div class="mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <span>Gallery</span>
                </a>
                
                <a href="generate_sitemap.php" class="px-4 py-3 hover:bg-gray-700 flex items-center transition-colors duration-200">
                    <div class="mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                        </svg>
                    </div>
                    <span>Sitemap</span>
                </a>
                
                <a href="check_links.php" class="px-4 py-3 hover:bg-gray-700 flex items-center transition-colors duration-200">
                    <div class="mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                        </svg>
                    </div>
                    <span>Check Links</span>
                </a>
                
                <div class="px-4 py-2 mt-4">
                    <h5 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">SETTINGS</h5>
                </div>
                
                <a href="users.php" class="px-4 py-3 hover:bg-gray-700 flex items-center transition-colors duration-200">
                    <div class="mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <span>Users</span>
                </a>
                
                <a href="settings.php" class="px-4 py-3 hover:bg-gray-700 flex items-center transition-colors duration-200">
                    <div class="mr-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <span>Settings</span>
                </a>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <main class="flex-1 p-6">