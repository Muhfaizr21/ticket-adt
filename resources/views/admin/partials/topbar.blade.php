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
            <div class="user-avatar-sm">
                @if(Auth::user()->avatar)
                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar" class="avatar-img">
                @else
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                @endif
            </div>
            <div class="user-details-sm">
                <div class="user-name-sm">{{ Auth::user()->name }}</div>
                <div class="user-role-sm">
                    {{ ucfirst(Auth::user()->role) }}
                </div>
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
</header>

<style>
.user-avatar-sm {
    width: 40px;
    height: 40px;
    background: #4f46e5;
    color: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    overflow: hidden;
    text-transform: uppercase;
    font-size: 14px;
}

.user-avatar-sm img.avatar-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
}
</style>
