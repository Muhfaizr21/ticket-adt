<header class="topbar">
    <div class="container">
        <div class="topbar-content">
            <!-- Logo -->
            <div class="logo">
                <a href="{{ route('pengguna.dashboard') }}">
                    <img src="{{ asset('images/ikon.png') }}" alt="MyApp Logo" class="logo-image">
                </a>
            </div>
            <!-- Navigation Menu -->
            <nav class="nav-menu">
                <ul class="nav-list">
                    <li class="nav-item">
                        <a href="{{ route('pengguna.dashboard') }}" class="nav-link {{ request()->routeIs('pengguna.dashboard') ? 'active' : '' }}">
                            Buy Tickets
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('shop') }}" class="nav-link {{ request()->routeIs('shop') ? 'active' : '' }}">
                            Shop
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('help') }}" class="nav-link {{ request()->routeIs('help') ? 'active' : '' }}">
                            Help
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('news') }}" class="nav-link {{ request()->routeIs('news') ? 'active' : '' }}">
                            News
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('contact') }}" class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">
                            Contact
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- Right Side Actions -->
            <div class="topbar-actions">
                <!-- My Basket -->
                <div class="basket">
                    <a href="#" class="basket-link">
                        <i class="bi bi-bag"></i>
                        <span class="basket-count">0</span>
                    </a>
                </div>

                <!-- User Menu -->
                <div class="user-menu">
                    <button class="user-menu-toggle">
                        <i class="bi bi-person-circle"></i>
                        <span>Account</span>
                        <i class="bi bi-chevron-down"></i>
                    </button>
                    <div class="dropdown-menu">
                        @auth
                            <a href="{{ route('profile') }}" class="dropdown-item">
                                <i class="bi bi-person"></i>
                                My Profile
                            </a>
                            <a href="#" class="dropdown-item">
                                <i class="bi bi-ticket-perforated"></i>
                                My Tickets
                            </a>
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item logout-btn">
                                    <i class="bi bi-box-arrow-right"></i>
                                    Sign Out
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="dropdown-item">
                                <i class="bi bi-box-arrow-in-right"></i>
                                Sign In
                            </a>
                            <a href="{{ route('register') }}" class="dropdown-item">
                                <i class="bi bi-person-plus"></i>
                                Sign Up
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- Mobile Menu Toggle -->
                <button class="mobile-menu-toggle">
                    <i class="bi bi-list"></i>
                </button>
            </div>
        </div>
    </div>
</header>