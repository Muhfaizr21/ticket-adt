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

            {{-- Nama Event --}}
            <div class="mb-3">
                <label class="form-label">Nama Event</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $event->name) }}" required>
            </div>

            {{-- Harga Tiket --}}
            <div class="mb-3">
                <label class="form-label">Harga Tiket (Rp)</label>
                <input type="number" name="price" class="form-control" value="{{ old('price', $event->price) }}" required>
            </div>

            {{-- Total Tiket --}}
            <div class="mb-3">
                <label class="form-label">Total Tiket</label>
                <input type="number" name="total_tickets" class="form-control" value="{{ old('total_tickets', $event->total_tickets) }}" required>
            </div>

            {{-- Poster Event --}}
            <div class="mb-3">
                <label class="form-label">Poster Event</label><br>

                {{-- Poster Saat Ini --}}
                @if($event->poster)
                    <div class="mb-3">
                        <p class="text-muted mb-1">Poster saat ini:</p>
                        <img src="{{ asset('storage/' . $event->poster) }}" alt="Poster Event" class="rounded shadow-sm border" style="max-width: 250px;">
                    </div>
                @endif

                {{-- Input Upload Baru --}}
                <input type="file" name="poster" id="poster" class="form-control" accept="image/*" onchange="previewPoster(event)">
                <small class="text-muted">Kosongkan jika tidak ingin mengganti poster.</small>

                {{-- Preview Poster Baru --}}
                <div class="mt-3">
                    <img id="preview" src="#" alt="Preview Poster Baru" class="img-thumbnail d-none" style="max-height: 200px;">
                </div>
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

{{-- Preview Gambar Baru --}}
<script>
    function previewPoster(event) {
        const preview = document.getElementById('preview');
        const file = event.target.files[0];
        if (file) {
            preview.src = URL.createObjectURL(file);
            preview.classList.remove('d-none');
        } else {
            preview.src = '#';
            preview.classList.add('d-none');
        }
    }
</script>
@endsection
