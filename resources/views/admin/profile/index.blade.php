@extends('admin.layouts.app')

@section('title', 'Profile Admin')

@section('content')
<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h2 class="fw-bold text-dark"><i class="fas fa-user-cog me-2"></i>Profile Admin</h2>
        </div>
    </div>
</div>

{{-- Alert --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-triangle me-2"></i>
    <ul class="mb-0">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="row">
    {{-- Form Update Profile --}}
    <div class="col-lg-8">
        <div class="card card-profile animate__animated animate__fadeInUp">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0"><i class="fas fa-user-edit me-2"></i>Informasi Profil</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" id="profileForm">
                    @csrf
                    @method('PUT')

                    <div class="row align-items-center mb-4">
                        <div class="col-md-4 text-center">
                            <div class="profile-avatar-wrapper">
                                <div class="avatar-container">
                                    <img src="{{ $admin->avatar ? asset('storage/'.$admin->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($admin->name).'&size=150&background=4361ee&color=fff' }}" 
                                         class="profile-avatar" id="profileImage">
                                    <div class="avatar-overlay" onclick="document.getElementById('avatar').click()">
                                        <i class="fas fa-camera"></i>
                                        <span>Ubah Foto</span>
                                    </div>
                                </div>

                                @if($admin->avatar)
                                <button type="button" class="btn btn-danger btn-sm mt-2" onclick="deleteAvatar()">
                                    <i class="fas fa-trash-alt"></i> Hapus Foto
                                </button>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="avatar" class="form-label">Upload Foto Profil</label>
                                <input type="file" name="avatar" id="avatar" class="form-control" accept="image/*" onchange="previewImage(event)">
                                <div class="form-text">Format: JPG, PNG, GIF. Maksimal 2MB. Ukuran disarankan 150x150px.</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-user text-primary"></i></span>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $admin->name) }}" required placeholder="Masukkan nama lengkap">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Alamat Email <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-envelope text-primary"></i></span>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $admin->email) }}" required placeholder="Masukkan alamat email">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i> Field dengan tanda <span class="text-danger">*</span> wajib diisi
                        </div>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Sidebar Password & Info --}}
    <div class="col-lg-4">
        {{-- Form Update Password --}}
        <div class="card card-security animate__animated animate__fadeInUp animate__delay-1s">
            <div class="card-header bg-warning text-dark">
                <h5 class="card-title mb-0"><i class="fas fa-key me-2"></i>Keamanan Akun</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.profile.password.update') }}" method="POST" id="passwordForm">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="current_password" class="form-label">Password Saat Ini <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-lock text-warning"></i></span>
                            <input type="password" name="current_password" id="current_password" class="form-control" required placeholder="Masukkan password saat ini">
                            <button type="button" class="btn btn-outline-secondary toggle-password" data-target="current_password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password Baru <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-key text-warning"></i></span>
                            <input type="password" name="password" id="password" class="form-control" required placeholder="Masukkan password baru">
                            <button type="button" class="btn btn-outline-secondary toggle-password" data-target="password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="form-text">Minimal 8 karakter dengan kombinasi huruf dan angka</div>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-check-circle text-warning"></i></span>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required placeholder="Konfirmasi password baru">
                            <button type="button" class="btn btn-outline-secondary toggle-password" data-target="password_confirmation">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-warning w-100">
                        <i class="fas fa-sync-alt me-2"></i>Perbarui Password
                    </button>
                </form>
            </div>
        </div>

        {{-- Info Box --}}
        <div class="card card-info animate__animated animate__fadeInUp animate__delay-2s mt-4">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0"><i class="fas fa-info-circle me-2"></i>Informasi</h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="flex-shrink-0">
                        <i class="fas fa-user-shield fa-2x text-info"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-1">Keamanan Akun</h6>
                        <p class="text-muted small mb-0">Pastikan password Anda kuat dan tidak dibagikan kepada siapapun.</p>
                    </div>
                </div>
                <div class="d-flex align-items-center mb-3">
                    <div class="flex-shrink-0">
                        <i class="fas fa-clock fa-2x text-info"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-1">Terakhir Diperbarui</h6>
                        <p class="text-muted small mb-0">{{ $admin->updated_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-calendar-alt fa-2x text-info"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-1">Bergabung Sejak</h6>
                        <p class="text-muted small mb-0">{{ $admin->created_at->format('d M Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<style>
.profile-avatar { width: 150px; height: 150px; object-fit: cover; border-radius: 50%; border: 3px solid #fff; }
.avatar-container { position: relative; display: inline-block; }
.avatar-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; display:flex; justify-content:center; align-items:center; flex-direction:column; background: rgba(0,0,0,0.5); color:#fff; border-radius:50%; opacity:0; cursor:pointer; transition:0.3s; }
.avatar-container:hover .avatar-overlay { opacity:1; }
.avatar-overlay i { font-size: 24px; }
.avatar-overlay span { font-size: 12px; }
</style>
@endpush

@push('scripts')
<script>
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function() {
        const output = document.getElementById('profileImage');
        output.src = reader.result;
        output.classList.add('animate__animated','animate__pulse');
        setTimeout(() => output.classList.remove('animate__animated','animate__pulse'),500);
    };
    reader.readAsDataURL(event.target.files[0]);
}

document.querySelectorAll('.toggle-password').forEach(button => {
    button.addEventListener('click', function() {
        const target = document.getElementById(this.dataset.target);
        const icon = this.querySelector('i');
        if(target.type==='password'){target.type='text'; icon.classList.replace('fa-eye','fa-eye-slash');}
        else {target.type='password'; icon.classList.replace('fa-eye-slash','fa-eye');}
    });
});

['profileForm','passwordForm'].forEach(id=>{
    document.getElementById(id).addEventListener('submit',function(){
        const btn=this.querySelector('button[type="submit"]');
        btn.disabled=true;
        btn.innerHTML='<i class="fas fa-spinner fa-spin me-2"></i>'+btn.textContent.trim();
        this.classList.add('animate__animated','animate__pulse');
    });
});

document.addEventListener('DOMContentLoaded', function(){
    document.querySelectorAll('.alert').forEach(alert=>setTimeout(()=>new bootstrap.Alert(alert).close(),5000));
});

function deleteAvatar(){
    if(confirm('Apakah Anda yakin ingin menghapus foto profil?')){
        fetch("{{ route('admin.profile.avatar.destroy') }}",{
            method:'DELETE',
            headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'}
        }).then(res=>{
            if(res.ok) location.reload();
            else alert('Gagal menghapus foto profil.');
        }).catch(()=>alert('Terjadi kesalahan.'));
    }
}
</script>
@endpush
