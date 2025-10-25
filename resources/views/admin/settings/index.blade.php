@extends('admin.layouts.app')

@section('title', 'Settings')

@section('content')
<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h2 class="fw-bold text-dark"><i class="fas fa-cog me-2"></i>Pengaturan Admin</h2>
            <p class="text-muted mb-0">Kelola akun dan tampilan profil administrator</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.settings.edit') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-edit me-2"></i> Edit Pengaturan
            </a>
        </div>
    </div>
</div>

{{-- Flash Messages --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show animate__animated animate__fadeIn" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show animate__animated animate__fadeIn" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-4">
    {{-- Kartu Profil --}}
    <div class="col-lg-4">
        <div class="card card-profile animate__animated animate__fadeInUp">
            <div class="card-header bg-gradient-primary text-white text-center">
                <h5 class="card-title mb-0"><i class="fas fa-user me-2"></i>Profil Admin</h5>
            </div>
            <div class="card-body text-center">
                <div class="avatar-container mx-auto mb-3">
                    <img src="{{ $admin->avatar ? asset('storage/' . $admin->avatar) : asset('images/default-avatar.png') }}" 
                         alt="Avatar" class="profile-avatar" id="profileAvatar">
                    <div class="avatar-overlay" onclick="document.getElementById('avatarUpload').click()">
                        <i class="fas fa-camera"></i>
                        <span>Ubah Foto</span>
                    </div>
                </div>

                <h5 class="mb-1">{{ $admin->name }}</h5>
                <p class="text-muted mb-3">{{ $admin->email }}</p>

                <form action="{{ route('admin.profile.avatar.update') }}" method="POST" enctype="multipart/form-data" id="avatarForm">
                    @csrf
                    @method('PUT')
                    <input type="file" name="avatar" id="avatarUpload" class="d-none" accept="image/*" onchange="document.getElementById('avatarForm').submit()">
                </form>

                <button type="button" class="btn btn-outline-primary btn-sm" onclick="document.getElementById('avatarUpload').click()">
                    <i class="fas fa-camera me-1"></i> Ganti Foto
                </button>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="card card-quick-actions animate__animated animate__fadeInUp animate__delay-1s mt-4">
            <div class="card-header bg-light">
                <h6 class="card-title mb-0"><i class="fas fa-bolt me-2"></i>Aksi Cepat</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.profile.index') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-user-edit me-1"></i> Edit Profil
                    </a>
                    <a href="{{ route('admin.profile.index') }}" class="btn btn-outline-warning btn-sm">
                        <i class="fas fa-key me-1"></i> Ganti Password
                    </a>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-tachometer-alt me-1"></i> Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Informasi Sistem --}}
    <div class="col-lg-8">
        <div class="card card-system-info animate__animated animate__fadeInUp">
            <div class="card-header bg-dark text-white">
                <h5 class="card-title mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Akun & Sistem</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3 col-6 mb-3">
                        <div class="system-stat">
                            <i class="fas fa-calendar-alt fa-2x text-primary mb-2"></i>
                            <h6 class="mb-1">Bergabung</h6>
                            <p class="text-muted small mb-0">{{ $admin->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="system-stat">
                            <i class="fas fa-sync-alt fa-2x text-success mb-2"></i>
                            <h6 class="mb-1">Terakhir Update</h6>
                            <p class="text-muted small mb-0">{{ $admin->updated_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="system-stat">
                            <i class="fas fa-shield-alt fa-2x text-warning mb-2"></i>
                            <h6 class="mb-1">Status Akun</h6>
                            <span class="badge bg-success">Aktif</span>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="system-stat">
                            <i class="fas fa-user-tie fa-2x text-info mb-2"></i>
                            <h6 class="mb-1">Role</h6>
                            <p class="text-muted small mb-0">Administrator</p>
                        </div>
                    </div>
                </div>

                {{-- Info tambahan --}}
                <hr>
                <div class="text-center text-muted small mt-3">
                    <i class="fas fa-code-branch me-1"></i> Versi Sistem: <strong>v1.0.0</strong> <br>
                    <i class="fas fa-server me-1"></i> Laravel: {{ app()->version() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .page-header {
        background: #fff;
        padding: 1.5rem;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }
    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.15);
    }
    .card-header {
        border-radius: 12px 12px 0 0 !important;
        border-bottom: none;
        font-weight: 600;
    }
    .bg-gradient-primary {
        background: linear-gradient(135deg, #4361ee, #3a56d4) !important;
    }
    .avatar-container {
        position: relative;
        display: inline-block;
        width: 150px;
        height: 150px;
    }
    .profile-avatar {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #e9ecef;
    }
    .avatar-overlay {
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.7);
        border-radius: 50%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: white;
        opacity: 0;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .avatar-overlay:hover { opacity: 1; }
    .system-stat {
        padding: 1rem;
        border-radius: 10px;
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
    }
    .system-stat:hover {
        background-color: #f8f9fa;
        transform: translateY(-2px);
    }
    .btn {
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .btn-primary {
        background: linear-gradient(135deg, #4361ee, #3a56d4);
        border: none;
    }
    .btn-primary:hover {
        background: linear-gradient(135deg, #3a56d4, #3046c4);
        transform: translateY(-2px);
    }
    @media (max-width: 768px) {
        .profile-avatar, .avatar-container {
            width: 120px;
            height: 120px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Pratinjau foto profil
    document.getElementById('avatarUpload')?.addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                document.getElementById('profileAvatar').src = e.target.result;
            };
            reader.readAsDataURL(this.files[0]);
        }
    });

    // Auto close alert
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.alert').forEach(alert => {
            setTimeout(() => {
                if (alert.classList.contains('show')) new bootstrap.Alert(alert).close();
            }, 5000);
        });
    });
</script>
@endpush
