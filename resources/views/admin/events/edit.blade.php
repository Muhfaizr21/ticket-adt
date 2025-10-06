@extends('admin.layouts.app')

@section('title', 'Edit Event')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-semibold text-dark">Edit Event</h2>
    <a href="{{ route('admin.events.index') }}" class="btn btn-secondary shadow-sm">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form action="{{ route('admin.events.update', $event->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Nama Event</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $event->name) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Harga Tiket</label>
                <input type="number" name="price" class="form-control" value="{{ old('price', $event->price) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Total Tiket</label>
                <input type="number" name="total_tickets" class="form-control" value="{{ old('total_tickets', $event->total_tickets) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Poster Event</label><br>
                @if($event->poster)
                    <img src="{{ asset('storage/'.$event->poster) }}" width="120" class="rounded mb-2">
                @endif
                <input type="file" name="poster" class="form-control">
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-success px-4">
                    <i class="bi bi-save me-1"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- SweetAlert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Berhasil!',
    text: '{{ session('success') }}',
    timer: 2000,
    showConfirmButton: false
});
</script>
@endif
@endsection
