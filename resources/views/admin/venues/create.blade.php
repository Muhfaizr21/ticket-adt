@extends('admin.layouts.app')

@section('title', 'Tambah Venue')

@push('styles')
<style>
    .form-container {
        max-width: 650px;
        margin: 0 auto;
    }
    .form-floating > label {
        font-size: 0.85rem;
        color: #6c757d;
    }
    .form-floating .form-control {
        border-radius: 10px;
        padding: 1rem 0.75rem 0.25rem 0.75rem;
        font-size: 0.95rem;
    }
    .card {
        border-radius: 12px;
    }
    .btn {
        border-radius: 8px;
        padding: 8px 18px;
    }
    textarea.form-control {
        min-height: 100px;
        resize: vertical;
    }
</style>
@endpush

@section('content')
<div class="page-header mb-4">
    <h2 class="fw-semibold text-dark">Tambah Venue Baru</h2>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body form-container">
        <form action="{{ route('admin.venues.store') }}" method="POST">
            @csrf

            <div class="form-floating mb-3">
                <input type="text" name="name" class="form-control" id="name" placeholder="Nama Venue" required>
                <label for="name">Nama Venue</label>
            </div>

            <div class="form-floating mb-3">
                <textarea name="address" class="form-control" placeholder="Alamat Lengkap" id="address" required></textarea>
                <label for="address">Alamat Lengkap</label>
            </div>

            <div class="form-floating mb-3">
                <input type="text" name="city" class="form-control" id="city" placeholder="Kota" required>
                <label for="city">Kota</label>
            </div>

            <div class="form-floating mb-3">
                <input type="number" name="capacity" class="form-control" id="capacity" placeholder="Kapasitas" required>
                <label for="capacity">Kapasitas (Orang)</label>
            </div>

            <div class="form-floating mb-4">
                <textarea name="description" class="form-control" placeholder="Deskripsi" id="description"></textarea>
                <label for="description">Deskripsi</label>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.venues.index') }}" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-success">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
