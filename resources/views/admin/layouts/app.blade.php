<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - MyApp</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/admin/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/admin/sidebar.css') }}" rel="stylesheet">
    <link href="{{ asset('css/admin/topbar.css') }}" rel="stylesheet">
    @stack('styles')
</head>

<body id="app-body" class="{{ auth()->user()->theme ?? 'light' }}">
    <div class="admin-layout">
        @include('admin.partials.sidebar')
        <div class="main-content">
            @include('admin.partials.topbar')
            <div class="content-wrapper">
                @yield('content')
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const menuToggle = document.querySelector('.menu-toggle');
            const sidebar = document.querySelector('.sidebar');
            if (menuToggle) menuToggle.addEventListener('click', () => sidebar.classList.toggle('mobile-open'));

            const userMenu = document.querySelector('.user-menu');
            const dropdownMenu = document.querySelector('.dropdown-menu');
            if (userMenu && dropdownMenu) {
                userMenu.addEventListener('click', e => {
                    e.stopPropagation();
                    dropdownMenu.classList.toggle('show');
                });
                document.addEventListener('click', () => dropdownMenu.classList.remove('show'));
            }

            const menuLinks = document.querySelectorAll('.menu-link');
            menuLinks.forEach(link => link.addEventListener('click', function () {
                menuLinks.forEach(l => l.classList.remove('active'));
                this.classList.add('active');
            }));

            // THEME TOGGLE
            const themeSwitch = document.getElementById('theme-toggle-switch');
            const body = document.getElementById('app-body');

            if (themeSwitch) {
                // Set toggle sesuai DB saat halaman load
                themeSwitch.checked = body.classList.contains('dark');

                themeSwitch.addEventListener('change', function () {
                    const newTheme = this.checked ? 'dark' : 'light';

                    // Update body class
                    body.classList.remove('light', 'dark');
                    body.classList.add(newTheme);

                    // Kirim AJAX ke controller untuk simpan ke DB
                    fetch("{{ route('admin.settings.edit') }}", {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ theme: newTheme })
                    }).then(res => res.json())
                        .then(data => console.log(data.message));
                });
            }
        });
    </script>

    @stack('scripts')
</body>

</html>