<header class="topbar">
    <div class="topbar-left">
        <button class="menu-toggle">
            <i class='bx bx-menu'></i>
        </button>
    </div>

    <div class="topbar-right">
        <div class="search-box">
            <i class='bx bx-search search-icon'></i>
            <input type="text" class="search-input" placeholder="Search...">
        </div>

        <div class="topbar-actions">
            <button class="action-btn" title="Notifications">
                <i class="bi bi-bell-fill"></i>
                <span class="notification-badge">3</span>
            </button>

            <button class="action-btn" title="Messages">
                <i class="bi bi-envelope-fill"></i>
                <span class="notification-badge">5</span>
            </button>

            <div class="user-menu">
                <div class="user-avatar-sm">
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </div>
                <div class="user-details-sm">
                    <div class="user-name-sm">{{ Auth::user()->name }}</div>
                    <div class="user-role-sm">Administrator</div>
                </div>
                <i class='bx bx-chevron-down'></i>

                <div class="dropdown-menu">
                    <a href="#" class="dropdown-item">
                        <i class='bx bx-user'></i>
                        <span>My Profile</span>
                    </a>
                    <a href="#" class="dropdown-item">
                        <i class='bx bx-cog'></i>
                        <span>Settings</span>
                    </a>
                    <div class="dropdown-divider"></div>
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
    </div>
</header>
