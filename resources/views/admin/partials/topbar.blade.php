<header class="topbar">
    <div class="topbar-left">
        <button class="menu-toggle">
            <i class='bx bx-menu'></i>
        </button>
    </div>

    <div class="topbar-right d-flex align-items-center">
        <!-- Search -->
        <div class="search-box me-3">
            <i class='bx bx-search search-icon'></i>
            <input type="text" class="search-input" placeholder="Search...">
        </div>

        <!-- Theme Toggle -->
        <div class="theme-toggle me-3">
            <label class="form-check form-switch mb-0">
                <input class="form-check-input" type="checkbox" id="theme-toggle-switch" 
                    {{ auth()->user()->theme === 'dark' ? 'checked' : '' }}>
                <span class="form-check-label">Mode Gelap</span>
            </label>
        </div>

        <!-- Notifications & Messages -->
        <button class="action-btn me-2" title="Notifications">
            <i class="bi bi-bell-fill"></i>
            <span class="notification-badge">3</span>
        </button>

        <button class="action-btn me-3" title="Messages">
            <i class="bi bi-envelope-fill"></i>
            <span class="notification-badge">5</span>
        </button>

        <!-- User Menu -->
        <div class="user-menu">
            <div class="user-avatar-sm">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
            <div class="user-details-sm">
                <div class="user-name-sm">{{ Auth::user()->name }}</div>
                <div class="user-role-sm">Administrator</div>
            </div>
            <i class='bx bx-chevron-down'></i>

            <div class="dropdown-menu">
                <!-- My Profile -->
                <a href="{{ route('admin.profile.index') }}" class="dropdown-item">
                    <i class='bx bx-user'></i>
                    <span>My Profile</span>
                </a>

                <!-- Settings -->
                <a href="{{ route('admin.settings.edit') }}" class="dropdown-item">
                    <i class='bx bx-cog'></i>
                    <span>Settings</span>
                </a>

                <div class="dropdown-divider"></div>

                <!-- Logout -->
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="dropdown-item"
                        style="background: none; border: none; width: 100%; text-align: left;">
                        <i class='bx bx-log-out'></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Theme toggle JS
        document.addEventListener('DOMContentLoaded', function() {
            const themeSwitch = document.getElementById('theme-toggle-switch');
            const body = document.body;
            if(themeSwitch){
                themeSwitch.addEventListener('change', function(){
                    if(this.checked){
                        body.classList.add('dark'); body.classList.remove('light');
                    } else {
                        body.classList.add('light'); body.classList.remove('dark');
                    }

                    // Simpan tema via AJAX
                    fetch("{{ route('admin.settings.update') }}", {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ theme: this.checked ? 'dark' : 'light' })
                    });
                });
            }
        });
    </script>
</header>
