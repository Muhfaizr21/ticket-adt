@extends('admin.layouts.app')

@section('title', 'Tambah Venue')

@section('content')
<div class="page-header mb-4">
    <h2 class="fw-semibold text-dark">Tambah Venue Baru</h2>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form action="{{ route('admin.venues.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Nama Venue</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Alamat Lengkap</label>
                <textarea name="address" class="form-control" rows="3" required></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Kota</label>
                <input type="text" name="city" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Kapasitas (Orang)</label>
                <input type="number" name="capacity" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>

            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="{{ route('admin.venues.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection
