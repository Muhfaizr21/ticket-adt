<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - MyApp</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/topbar.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    <!-- Topbar -->
    @include('partials.topbar')
    
    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>

    <script>
        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.querySelector('.mobile-menu-toggle');
            const navMenu = document.querySelector('.nav-menu');
            
            if (menuToggle) {
                menuToggle.addEventListener('click', function() {
                    navMenu.classList.toggle('mobile-open');
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
        });
    </script>
    
    @stack('scripts')
</body>
</html>