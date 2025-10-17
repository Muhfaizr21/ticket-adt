@php
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Str;
    use App\Models\Notification;

    $unreadCount = Notification::where('is_read', false)->count();
    $latestNotifications = Notification::orderBy('created_at', 'desc')->take(5)->get();
@endphp

<header class="topbar">
    <div class="topbar-left">
        <button class="menu-toggle">
            <i class='bx bx-menu'></i>
        </button>
    </div>

    <div class="topbar-right d-flex align-items-center">
        <!-- 🔍 Search -->
        <form action="{{ route('events.search') }}" method="GET" class="search-box me-3">
            <i class='bx bx-search search-icon'></i>
            <input type="text" name="q" class="search-input" placeholder="Search...">
        </form>

        <!-- 🔔 Notifications -->
        <div class="dropdown me-2">
            <button class="action-btn position-relative" title="Notifications" id="notifDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-bell-fill"></i>
                @if($unreadCount > 0)
                    <span class="notification-badge">{{ $unreadCount }}</span>
                @endif
            </button>

            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="notifDropdown" style="width: 300px; max-height: 300px; overflow-y: auto;">
                <li class="px-3 py-2 d-flex justify-content-between align-items-center">
                    <strong>Notifikasi</strong>
                    @if($unreadCount > 0)
                        <a href="{{ route('admin.notifications.markAll') }}" class="text-primary small">Tandai semua</a>
                    @endif
                </li>
                <li><hr class="dropdown-divider"></li>

                @forelse($latestNotifications as $notif)
                    <li class="px-3 py-2 {{ $notif->is_read ? '' : 'bg-light' }}">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="fw-semibold">{{ $notif->title ?? 'Notifikasi' }}</div>
                                <small class="text-muted">{{ Str::limit($notif->message, 40) }}</small><br>
                                <small class="text-muted">{{ $notif->created_at->diffForHumans() }}</small>
                            </div>
                            @if(!$notif->is_read)
                                <a href="{{ route('admin.notifications.read', $notif->id) }}" class="text-primary small ms-2">Baca</a>
                            @endif
                        </div>
                    </li>
                @empty
                    <li class="px-3 py-2 text-center text-muted">
                        Tidak ada notifikasi
                    </li>
                @endforelse

                <li><hr class="dropdown-divider"></li>
                <li class="text-center">
                    <a href="{{ route('admin.notifications.index') }}" class="dropdown-item text-primary fw-semibold">Lihat semua</a>
                </li>
            </ul>
        </div>

        <!-- ✉️ Messages (dummy, nanti bisa kamu sambungin ke fitur pesan) -->
        <button class="action-btn me-3" title="Messages">
            <i class="bi bi-envelope-fill"></i>
            <span class="notification-badge">5</span>
        </button>

        <!-- 👤 User Menu -->
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
                <div class="user-role-sm">{{ ucfirst(Auth::user()->role) }}</div>
            </div>
            <i class='bx bx-chevron-down'></i>

            <div class="dropdown-menu">
                <a href="{{ route('admin.profile.index') }}" class="dropdown-item">
                    <i class='bx bx-user'></i> My Profile
                </a>

                <a href="{{ route('admin.settings.edit') }}" class="dropdown-item">
                    <i class='bx bx-cog'></i> Settings
                </a>

                <div class="dropdown-divider"></div>

                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="dropdown-item" style="background: none; border: none; width: 100%; text-align: left;">
                        <i class='bx bx-log-out'></i> Logout
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

.notification-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background: red;
    color: white;
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
    padding: 5px 10px 5px 30px;
    border-radius: 20px;
    border: 1px solid #ddd;
    outline: none;
}
</style>
