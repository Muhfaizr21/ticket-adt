<aside class="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <i class="bi bi-ticket-perforated"></i>
        </div>
        <div>
            <div class="sidebar-title">TicketMaster</div>
            <div class="sidebar-subtitle">Admin Panel</div>
        </div>
    </div>

    <nav class="sidebar-menu">
        <div class="menu-section">
            <div class="menu-title">Main Menu</div>
            <ul class="menu-items">
                <li class="menu-item">
                    <a href="{{ route('admin.dashboard') }}"
                        class="menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2 menu-icon"></i>
                        <span class="menu-text">Dashboard</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('admin.events.index') }}"
                        class="menu-link {{ request()->is('admin/events*') ? 'active' : '' }}">

                        <i class="bi bi-calendar-event menu-icon"></i>
                        <span class="menu-text">Events</span>
                        {{-- <span class="menu-badge">12</span> --}}
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('admin.orders.index') }}"
                        class="menu-link {{ request()->is('admin/orders*') ? 'active' : '' }}">
                        <i class="bi bi-ticket-perforated menu-icon"></i>
                        <span class="menu-text">Ticket Orders</span>
                        {{-- <span class="menu-badge">8</span> --}}
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('admin.customers.index') }}"
                        class="menu-link {{ request()->is('admin/customers*') ? 'active' : '' }}">
                        <i class="bi bi-people-fill menu-icon"></i>
                        <span class="menu-text">Customers</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="menu-section">
            <div class="menu-title">Management</div>
            <ul class="menu-items">
                <li class="menu-item">
                    <a href="{{ route('admin.ticket-types.index') }}"
                        class="menu-link {{ request()->is('admin/ticket-types*') ? 'active' : '' }}">
                        <i class="bi bi-tags-fill menu-icon"></i>
                        <span class="menu-text">Ticket Types</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('admin.promotions.index') }}"
                        class="menu-link {{ request()->is('admin/promotions*') ? 'active' : '' }}">
                        <i class="bi bi-percent menu-icon"></i>
                        <span class="menu-text">Promotions</span>
                        {{-- <span class="menu-badge"></span> --}}
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('admin.venues.index') }}"
                        class="menu-link {{ request()->is('admin/venues*') ? 'active' : '' }}">
                        <i class="bi bi-geo-alt-fill menu-icon"></i>
                        <span class="menu-text">Venues</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('admin.reports.index') }}"
                        class="menu-link {{ request()->is('admin/reports*') ? 'active' : '' }}">
                        <i class="bi bi-bar-chart-fill menu-icon"></i>
                        <span class="menu-text">Reports</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="menu-section">
            <div class="menu-title">System</div>
            <ul class="menu-items">
                <li class="menu-item">
                    <a href="{{ route('admin.settings.index') }}"
                        class="menu-link {{ request()->is('admin/settings*') ? 'active' : '' }}">
                        <i class="bi bi-gear-fill menu-icon"></i>
                        <span class="menu-text">Settings</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#" class="menu-link {{ request()->is('admin/notifications*') ? 'active' : '' }}">
                        <i class="bi bi-bell-fill menu-icon"></i>
                        <span class="menu-text">Notifications</span>
                        {{-- <span class="menu-badge">7</span> --}}
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#" class="menu-link {{ request()->is('admin/support*') ? 'active' : '' }}">
                        <i class="bi bi-question-circle-fill menu-icon"></i>
                        <span class="menu-text">Help & Support</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</aside>