@extends('layouts.app')

@section('title', 'My Profile')
@section('page-title', 'My Profile')

@push('styles')
<link href="{{ asset('css/profile.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="profile-container">
    <div class="profile-grid">
        <!-- Profile Card -->
        <div class="profile-card">
            <div class="profile-avatar">
                @if($userData['user']['avatar'])
                    <img src="{{ $userData['user']['avatar'] }}" alt="Profile Avatar" class="avatar-image">
                @else
                    <div class="avatar-initials">
                        {{ substr($userData['user']['name'], 0, 1) }}
                    </div>
                @endif
            </div>
            
            <h2 class="profile-name">{{ $userData['user']['name'] }}</h2>
            <p class="profile-email">{{ $userData['user']['email'] }}</p>
            
            <div class="profile-stats">
                <div class="stat-item">
                    <span class="stat-number">{{ $userData['user']['total_events'] }}</span>
                    <span class="stat-label">Events</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">{{ $userData['user']['total_tickets'] }}</span>
                    <span class="stat-label">Tickets</span>
                </div>
            </div>
        </div>

        <!-- Tabs Container -->
        <div class="tabs-container">
            <!-- Tabs Navigation -->
            <div class="tabs-nav">
                <button class="tab-btn active" data-tab="edit-profile">
                    <i class="bi bi-person"></i>
                    Edit Profile
                </button>
                <button class="tab-btn" data-tab="edit-password">
                    <i class="bi bi-shield-lock"></i>
                    Ubah Password
                </button>
                <button class="tab-btn" data-tab="recent-events">
                    <i class="bi bi-ticket-perforated"></i>
                    Event Terbaru
                </button>
            </div>

            <!-- Tabs Content -->
            <div class="tab-content">
                <!-- Edit Profile Tab -->
                <div class="tab-pane active" id="edit-profile">
                    <div class="section-header">
                        <h2 class="section-title">Edit Profile</h2>
                        <p class="section-subtitle">Perbarui informasi profil dan alamat email Anda</p>
                    </div>

                    <form class="profile-form" method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="name">Nama Lengkap</label>
                                <input type="text" class="form-input" id="name" name="name" value="{{ $userData['user']['name'] }}" required>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label" for="email">Email</label>
                                <input type="email" class="form-input" id="email" name="email" value="{{ $userData['user']['email'] }}" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="phone">Nomor Telepon</label>
                                <input type="tel" class="form-input" id="phone" name="phone" value="{{ $userData['user']['phone'] }}">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label" for="joined_date">Tanggal Bergabung</label>
                                <input type="text" class="form-input" id="joined_date" value="{{ date('d F Y', strtotime($userData['user']['joined_date'])) }}" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="bio">Bio</label>
                            <textarea class="form-input" id="bio" name="bio" rows="4" placeholder="Ceritakan sedikit tentang diri Anda..."></textarea>
                        </div>

                        <button type="submit" class="btn-save">
                            <i class="bi bi-check-circle"></i>
                            Simpan Perubahan
                        </button>
                    </form>
                </div>

                <!-- Edit Password Tab -->
                <div class="tab-pane" id="edit-password">
                    <div class="section-header">
                        <h2 class="section-title">Ubah Password</h2>
                        <p class="section-subtitle">Perbarui password Anda untuk menjaga keamanan akun</p>
                    </div>

                    <form class="profile-form password-form" method="POST" action="{{ route('profile.password.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label class="form-label" for="current_password">Password Saat Ini</label>
                            <input type="password" class="form-input" id="current_password" name="current_password" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="new_password">Password Baru</label>
                            <input type="password" class="form-input" id="new_password" name="new_password" required>
                            <div class="password-strength strength-weak" id="password-strength">
                                Kekuatan password: Lemah
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="confirm_password">Konfirmasi Password Baru</label>
                            <input type="password" class="form-input" id="confirm_password" name="confirm_password" required>
                        </div>

                        <button type="submit" class="btn-save">
                            <i class="bi bi-shield-check"></i>
                            Update Password
                        </button>
                    </form>
                </div>

                <!-- Recent Events Tab -->
                <div class="tab-pane" id="recent-events">
                    <div class="section-header">
                        <h2 class="section-title">Event Terbaru</h2>
                        <p class="section-subtitle">Riwayat event dan tiket yang telah Anda beli</p>
                    </div>

                    @if(count($userData['recent_events']) > 0)
                        <div class="events-list">
                            @foreach($userData['recent_events'] as $event)
                            <div class="event-item">
                                <div class="event-icon">
                                    <i class="bi bi-ticket-perforated"></i>
                                </div>
                                <div class="event-details">
                                    <div class="event-name">{{ $event['event_name'] }}</div>
                                    <div class="event-meta">
                                        <span><i class="bi bi-calendar"></i> {{ date('d F Y', strtotime($event['date'])) }}</span>
                                        <span><i class="bi bi-tag"></i> {{ $event['ticket_type'] }}</span>
                                    </div>
                                </div>
                                <div class="event-status status-{{ $event['status'] }}">
                                    {{ ucfirst($event['status']) }}
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div style="margin-top: 1.5rem;">
                            <a href="#" class="btn-edit">
                                <i class="bi bi-eye"></i>
                                Lihat Semua Event
                            </a>
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="bi bi-ticket-perforated"></i>
                            <h3>Belum Ada Event</h3>
                            <p>Anda belum memiliki tiket event apapun.</p>
                            <a href="#" class="btn-save">
                                <i class="bi bi-search"></i>
                                Cari Event
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab functionality
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabPanes = document.querySelectorAll('.tab-pane');

    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');
            
            // Remove active class from all buttons and panes
            tabBtns.forEach(b => b.classList.remove('active'));
            tabPanes.forEach(p => p.classList.remove('active'));
            
            // Add active class to current button and pane
            this.classList.add('active');
            document.getElementById(targetTab).classList.add('active');
        });
    });

    // Form submission handling for profile
    const profileForm = document.querySelector('#edit-profile .profile-form');
    if (profileForm) {
        profileForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = profileForm.querySelector('.btn-save');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.innerHTML = '<i class="bi bi-arrow-repeat spin"></i> Menyimpan...';
            submitBtn.disabled = true;
            
            setTimeout(() => {
                submitBtn.innerHTML = '<i class="bi bi-check-circle"></i> Berhasil Disimpan!';
                submitBtn.style.background = 'var(--success-color)';
                
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                    submitBtn.style.background = '';
                }, 2000);
            }, 1500);
        });
    }

    // Form submission handling for password
    const passwordForm = document.querySelector('#edit-password .profile-form');
    if (passwordForm) {
        passwordForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = passwordForm.querySelector('.btn-save');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.innerHTML = '<i class="bi bi-arrow-repeat spin"></i> Mengupdate...';
            submitBtn.disabled = true;
            
            setTimeout(() => {
                submitBtn.innerHTML = '<i class="bi bi-check-circle"></i> Password Diupdate!';
                submitBtn.style.background = 'var(--success-color)';
                
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                    submitBtn.style.background = '';
                    passwordForm.reset();
                }, 2000);
            }, 1500);
        });
    }

    // Password strength indicator
    const passwordInput = document.getElementById('new_password');
    const strengthText = document.getElementById('password-strength');
    
    if (passwordInput && strengthText) {
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 'Lemah';
            let strengthClass = 'strength-weak';
            
            if (password.length >= 8) {
                strength = 'Sedang';
                strengthClass = 'strength-medium';
            }
            if (password.length >= 12 && /[A-Z]/.test(password) && /[0-9]/.test(password) && /[^A-Za-z0-9]/.test(password)) {
                strength = 'Kuat';
                strengthClass = 'strength-strong';
            }
            
            strengthText.textContent = `Kekuatan password: ${strength}`;
            strengthText.className = `password-strength ${strengthClass}`;
        });
    }

    // Add spin animation
    const style = document.createElement('style');
    style.textContent = `
        .spin {
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    `;
    document.head.appendChild(style);
});
</script>
@endpush