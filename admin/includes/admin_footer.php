            <!-- Footer -->
            <footer class="mt-auto py-4 bg-white border-t border-gray-200">
                <div class="container mx-auto px-4">
                    <div class="flex flex-col md:flex-row justify-between items-center">
                        <div class="mb-4 md:mb-0">
                            <p class="text-sm text-gray-600">&copy; <?= date('Y') ?> Springfield Panel and Paint. All rights reserved.</p>
                        </div>
                        <div class="flex space-x-4">
                            <a href="../index.php" target="_blank" class="text-sm text-gray-600 hover:text-gray-900">View Website</a>
                            <a href="#" class="text-sm text-gray-600 hover:text-gray-900">Documentation</a>
                        </div>
                    </div>
                </div>
            </footer>
        </main>
    </div>
    
    <script>
        // Simple sidebar toggle
        document.getElementById('toggle-sidebar').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('hidden');
            
            // Adjust main content when sidebar is toggled
            const mainContent = document.querySelector('main');
            mainContent.classList.toggle('w-full');
        });
        
        // Log when page is loaded
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Admin panel loaded successfully');
        });
    </script>
</body>
</html>