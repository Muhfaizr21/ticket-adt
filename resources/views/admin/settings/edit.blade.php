@extends('admin.layouts.app')

@section('title', 'Edit Settings')

@section('content')
<div class="page-header mb-4 d-flex justify-content-between align-items-center">
    <h2 class="fw-bold text-dark">Edit Pengaturan Admin</h2>
    <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card shadow-sm border-0 p-4">
    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <h5 class="mb-3">Informasi Akun</h5>
        <div class="mb-3">
            <label for="name" class="form-label">Nama</label>
            <input type="text" name="name" id="name" value="{{ old('name', $admin->name) }}" class="form-control">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email', $admin->email) }}" class="form-control">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password Baru (kosongkan jika tidak ingin diubah)</label>
            <input type="password" name="password" id="password" class="form-control">
        </div>
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
        </div>

        <h5 class="mb-3">Preferensi Sistem</h5>
        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" id="theme-toggle-switch" name="theme" value="dark" {{ $admin->theme == 'dark' ? 'checked' : '' }}>
            <label class="form-check-label" for="theme-toggle-switch">Mode Gelap / Dark Mode</label>
        </div>

        <div class="mb-3">
            <label for="language" class="form-label">Bahasa / Locale</label>
            <select name="language" id="language" class="form-select">
                <option value="id" {{ $admin->language == 'id' ? 'selected' : '' }}>Indonesia</option>
                <option value="en" {{ $admin->language == 'en' ? 'selected' : '' }}>English</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Nomor Telepon / WhatsApp</label>
            <input type="text" name="phone" id="phone" value="{{ old('phone', $admin->phone) }}" class="form-control">
        </div>

        <div class="mb-3">
            <label for="position" class="form-label">Jabatan / Departemen</label>
            <input type="text" name="position" id="position" value="{{ old('position', $admin->position) }}" class="form-control">
        </div>

        <div class="mb-3">
            <label for="avatar" class="form-label">Foto Profil</label>
            <input type="file" name="avatar" id="avatar" class="form-control">
            @if($admin->avatar)
            <img src="{{ asset('storage/' . $admin->avatar) }}" alt="Avatar" class="rounded-circle mt-2" width="50">
            @endif
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const themeSwitch = document.getElementById('theme-toggle-switch');
        const body = document.getElementById('app-body');

        themeSwitch.addEventListener('change', function () {
            if (this.checked) {
                body.classList.remove('light'); body.classList.add('dark');
            } else {
                body.classList.remove('dark'); body.classList.add('light');
            }

            // Simpan ke DB via AJAX tanpa reload
            fetch("{{ route('admin.settings.update') }}", {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ theme: this.checked ? 'dark' : 'light' })
            });
        });
    });
</script>
@endsection
