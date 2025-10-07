@extends('admin.layouts.app')

@section('title', 'Edit Venue')

@section('content')
<div class="page-header mb-4">
    <h2 class="fw-semibold text-dark">Edit Venue</h2>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form action="{{ route('admin.venues.update', $venue->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Nama Venue</label>
                <input type="text" name="name" class="form-control" value="{{ $venue->name }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Alamat Lengkap</label>
                <textarea name="address" class="form-control" rows="3" required>{{ $venue->address }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Kota</label>
                <input type="text" name="city" class="form-control" value="{{ $venue->city }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Kapasitas (Orang)</label>
                <input type="number" name="capacity" class="form-control" value="{{ $venue->capacity }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" rows="3">{{ $venue->description }}</textarea>
            </div>

            <button type="submit" class="btn btn-warning">Perbarui</button>
            <a href="{{ route('admin.venues.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection
