<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - MyApp</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/admin/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/admin/sidebar.css') }}" rel="stylesheet">
    <link href="{{ asset('css/admin/topbar.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        @include('admin.partials.sidebar')
        
        <!-- Main Content -->
        <div class="main-content">
            <!-- Topbar -->
            @include('admin.partials.topbar')
            
            <!-- Content Wrapper -->
            <div class="content-wrapper">
                @yield('content')
            </div>
        </div>
    </div>

    <script>
        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.querySelector('.menu-toggle');
            const sidebar = document.querySelector('.sidebar');
            
            if (menuToggle) {
                menuToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('mobile-open');
                });
            }
            
            // User dropdown
            const userMenu = document.querySelector('.user-menu');
            const dropdownMenu = document.querySelector('.dropdown-menu');
            
            if (userMenu && dropdownMenu) {
                userMenu.addEventListener('click', function(e) {
                    e.stopPropagation();
                    dropdownMenu.classList.toggle('show');
                });
                
                // Close dropdown when clicking outside
                document.addEventListener('click', function() {
                    dropdownMenu.classList.remove('show');
                });
            }
            
            // Close mobile sidebar when clicking on menu item
            const menuLinks = document.querySelectorAll('.menu-link');
            menuLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 768) {
                        sidebar.classList.remove('mobile-open');
                    }
                });
            });

            // Add active state animation
            menuLinks.forEach(link => {
                link.addEventListener('click', function() {
                    // Remove active class from all links
                    menuLinks.forEach(l => l.classList.remove('active'));
                    // Add active class to clicked link
                    this.classList.add('active');
                });
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>