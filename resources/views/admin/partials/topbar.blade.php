@php
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Str;
    use App\Models\Notification;

    $unreadCount = Notification::where('is_read', false)->count();
    $latestNotifications = Notification::orderBy('created_at', 'desc')->take(5)->get();
@endphp

<header class="topbar d-flex justify-content-between align-items-center px-3 py-2 bg-white border-bottom shadow-sm">
    <!-- Left: Menu Toggle -->
    <div class="topbar-left d-flex align-items-center">
        <button type="button" class="menu-toggle btn btn-link p-0 text-dark me-3">
            <i class="bx bx-menu fs-4"></i>
        </button>
        <span class="fs-5 fw-semibold">Admin Panel</span>
    </div>

    <!-- Right -->
    <div class="topbar-right d-flex align-items-center">

        <!-- Search -->
        <form action="{{ route('events.search') }}" method="GET" class="search-box me-3">
            <i class="bx bx-search search-icon"></i>
            <input type="text" name="q" class="search-input" placeholder="Search..." value="{{ request('q') }}">
        </form>

        <!-- Notifications -->
        <div class="dropdown me-3" data-dropdown>
            <button class="btn btn-link p-0 position-relative dropdown-toggle-js" data-target="notifMenu"
                aria-expanded="false" title="Notifications">
                <i class="bi bi-bell-fill fs-5"></i>
                @if($unreadCount > 0)
                    <span class="notification-badge">{{ $unreadCount }}</span>
                @endif
            </button>
            <ul id="notifMenu" class="dropdown-menu dropdown-menu-end shadow dropdown-animated">
                <li class="px-3 py-2 d-flex justify-content-between align-items-center">
                    <strong><i class="bi bi-bell me-2"></i> Notifikasi</strong>
                    @if($unreadCount > 0)
                        <a href="{{ route('admin.notifications.markAll') }}" class="text-primary small">Tandai semua</a>
                    @endif
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                @forelse($latestNotifications as $notif)
                    <li class="px-3 py-2 {{ $notif->is_read ? '' : 'bg-light' }}">
                        <div class="d-flex justify-content-between align-items-start">
                            <div style="word-break: break-word; white-space: normal;">
                                <div class="fw-semibold"><i class="bi bi-info-circle me-1"></i>
                                    {{ $notif->title ?? 'Notifikasi' }}</div>
                                <small class="text-muted d-block">{{ $notif->message }}</small>
                                <small class="text-muted">{{ $notif->created_at->diffForHumans() }}</small>
                            </div>
                            @if(!$notif->is_read)
                                <a href="{{ route('admin.notifications.read', $notif->id) }}" class="text-primary small ms-2"><i
                                        class="bi bi-check2-circle me-1"></i> Baca</a>
                            @endif
                        </div>
                    </li>
                @empty
                    <li class="px-3 py-2 text-center text-muted">Tidak ada notifikasi</li>
                @endforelse

                <li>
                    <hr class="dropdown-divider">
                </li>
                <li class="text-center">
                    <a href="{{ route('admin.notifications.index') }}" class="dropdown-item text-primary fw-semibold"><i
                            class="bi bi-eye me-1"></i> Lihat semua</a>
                </li>
            </ul>
        </div>

        <!-- Messages -->
        <div class="dropdown me-3" data-dropdown>
            <button class="btn btn-link p-0 position-relative dropdown-toggle-js" data-target="messageMenu"
                aria-expanded="false" title="Messages">
                <i class="bi bi-envelope-fill fs-5"></i>
                <span class="notification-badge">5</span>
            </button>
            <ul id="messageMenu" class="dropdown-menu dropdown-menu-end shadow dropdown-animated">
                <li class="px-3 py-2"><strong><i class="bi bi-envelope me-2"></i> Messages</strong></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li class="px-3 py-2 text-center text-muted">Belum ada pesan baru</li>
            </ul>
        </div>

        <!-- User -->
        <div class="dropdown" data-dropdown>
            <button class="btn btn-link p-0 d-flex align-items-center dropdown-toggle-js" data-target="userMenu"
                aria-expanded="false">
                <div class="user-avatar-sm me-2">
                    @if(Auth::user()->avatar)
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar" class="avatar-img">
                    @else
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    @endif
                </div>
                <div class="text-start">
                    <div class="user-name-sm fw-semibold">{{ Auth::user()->name }}</div>
                    <div class="user-role-sm text-muted small">{{ ucfirst(Auth::user()->role) }}</div>
                </div>
                <i class="bx bx-chevron-down ms-2"></i>
            </button>

            <ul id="userMenu" class="dropdown-menu dropdown-menu-end shadow dropdown-animated">
                <li><a href="{{ route('admin.profile.index') }}" class="dropdown-item"><i class="bx bx-user me-2"></i>
                        My Profile</a></li>
                <li><a href="{{ route('admin.settings.edit') }}" class="dropdown-item"><i class="bx bx-cog me-2"></i>
                        Settings</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item"><i class="bx bx-log-out me-2"></i> Logout</button>
                    </form>
                </li>
            </ul>
        </div>

    </div>
</header>

<style>
    .topbar {
        position: sticky;
        top: 0;
        z-index: 1050;
        background: #fff;
    }

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
        text-transform: uppercase;
        font-size: 14px;
        overflow: hidden;
    }

    .user-avatar-sm img.avatar-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }

    .notification-badge {
        position: absolute;
        top: -6px;
        right: -6px;
        background: red;
        color: #fff;
        font-size: 11px;
        border-radius: 50%;
        padding: 2px 6px;
        font-weight: bold;
    }

    .search-box {
        position: relative;
    }

    .search-box .search-icon {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #777;
    }

    .search-box .search-input {
        padding: 6px 10px 6px 34px;
        border-radius: 20px;
        border: 1px solid #ddd;
        outline: none;
    }

    /* Dropdown Fix */
    .dropdown {
        position: relative;
    }

    .dropdown-menu {
        display: none;
        position: absolute;
        top: 100%;
        right: 0;
        z-index: 2000;
        min-width: 250px;
        border-radius: 10px;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
        background: #fff;
        opacity: 0;
        transition: opacity 0.2s ease;
    }

    .dropdown-menu.show {
        display: block;
        opacity: 1;
    }

    

    .dropdown-animated {
        animation-duration: 0.25s;
        animation-fill-mode: both;
    }

    @keyframes fadeInDropdown {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .dropdown-menu.show.dropdown-animated {
        animation-name: fadeInDropdown;
    }

    @media(max-width:575px) {
        .search-box {
            display: none;
        }
    }
</style>

<script>
    (function () {
        function closeAllDropdowns() {
            document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                menu.classList.remove('show');
                const btn = document.querySelector(`[data-target="${menu.id}"]`);
                if (btn) btn.setAttribute('aria-expanded', 'false');
            });
        }

        document.querySelectorAll('.dropdown-toggle-js').forEach(button => {
            button.addEventListener('click', e => {
                e.preventDefault(); e.stopPropagation();
                const menu = document.getElementById(button.dataset.target);
                if (!menu) return;
                const isOpen = menu.classList.contains('show');
                closeAllDropdowns();
                if (!isOpen) { menu.classList.add('show'); button.setAttribute('aria-expanded', 'true'); }
            });
        });

        document.addEventListener('click', e => { if (!e.target.closest('[data-dropdown]')) closeAllDropdowns(); });
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeAllDropdowns(); });
    })();
</script>