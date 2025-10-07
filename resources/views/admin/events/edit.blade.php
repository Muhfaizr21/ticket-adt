@extends('admin.layouts.app')

@section('title', 'Edit Event')

@section('content')
<style>
    .poster-preview {
        width: 100%;
        max-height: 200px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #eee;
    }
    .btn-submit {
        background-color: #198754;
        color: white;
        font-weight: 500;
        padding: 10px 20px;
        border-radius: 6px;
        transition: 0.2s;
    }
    .btn-submit:hover {
        background-color: #157347;
    }
    .required-star {
        color: red;
        font-weight: bold;
    }
</style>

<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-dark">✏️ Edit Event</h2>
    <a href="{{ route('admin.events.index') }}" class="btn btn-secondary shadow-sm">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-5">
        <form action="{{ route('admin.events.update', $event->id) }}" method="POST" enctype="multipart/form-data" id="eventForm">
            @csrf
            @method('PUT')

            <div class="row">
                {{-- Kiri: Nama, Deskripsi, Harga --}}
                <div class="col-md-6">
                    {{-- Nama Event --}}
                    <div class="mb-4">
                        <label class="form-label">Nama Event <span class="required-star">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $event->name) }}" required placeholder="Masukkan Nama Event">
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Deskripsi Event --}}
                    <div class="mb-4">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" rows="5" class="form-control @error('description') is-invalid @enderror"
                                  placeholder="Deskripsi lengkap tentang event">{{ old('description', $event->description) }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Harga Tiket Default --}}
                    <div class="mb-4">
                        <label class="form-label">Harga Tiket Default (Rp)</label>
                        <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                               value="{{ old('price', $event->price) }}" min="0">
                        @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- VIP --}}
                    <h5 class="mb-3 mt-4 text-primary">VIP Ticket</h5>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Jumlah Tiket VIP</label>
                            <input type="number" name="vip_tickets" class="form-control"
                                   value="{{ old('vip_tickets', $event->vip_tickets ?? ceil($event->total_tickets * 0.3)) }}" min="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Harga Tiket VIP (Rp)</label>
                            <input type="number" name="vip_price" class="form-control"
                                   value="{{ old('vip_price', $event->vip_price ?? $event->price * 1.5) }}" min="0">
                        </div>
                    </div>

                    {{-- Reguler --}}
                    <h5 class="mb-3 mt-4 text-primary">Reguler Ticket</h5>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Jumlah Tiket Reguler</label>
                            <input type="number" name="reguler_tickets" class="form-control"
                                   value="{{ old('reguler_tickets', $event->reguler_tickets ?? floor($event->total_tickets * 0.7)) }}" min="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Harga Tiket Reguler (Rp)</label>
                            <input type="number" name="reguler_price" class="form-control"
                                   value="{{ old('reguler_price', $event->reguler_price ?? $event->price) }}" min="0">
                        </div>
                    </div>
                </div>

                {{-- Kanan: Tanggal, Lokasi, Poster --}}
                <div class="col-md-6">
                    {{-- Tanggal Event --}}
                    <div class="mb-4">
                        <label class="form-label">Tanggal Event</label>
                        <input type="date" name="date" class="form-control"
                               value="{{ old('date', $event->date ? \Carbon\Carbon::parse($event->date)->format('Y-m-d') : '') }}">
                    </div>

                    {{-- Lokasi --}}
                    <div class="mb-4">
                        <label class="form-label">Lokasi</label>
                        <input type="text" name="location" class="form-control"
                               value="{{ old('location', $event->location) }}" placeholder="Contoh: Balai Kota">
                    </div>

                    {{-- Poster --}}
                    <div class="mb-4">
                        <label class="form-label">Poster Event</label>
                        @if($event->poster)
                            <div class="poster-preview-container mb-2">
                                <img id="preview" src="{{ asset('storage/' . $event->poster) }}" alt="Poster Event" class="poster-preview">
                            </div>
                        @else
                            <img id="preview" src="#" class="poster-preview d-none" alt="Preview Poster">
                        @endif
                        <input type="file" name="poster" class="form-control" accept="image/*" onchange="previewPoster(event)">
                        <small class="text-muted">Format: JPG, JPEG, PNG (maks. 2MB)</small>
                    </div>
                </div>
            </div>

            {{-- Tombol Submit --}}
            <div class="mt-4 pt-4 border-top text-end">
                <button type="submit" class="btn btn-submit">💾 Update Info</button>
                <a href="{{ route('admin.events.index') }}" class="btn btn-secondary ms-2">Batal</a>
            </div>

        </form>
    </div>
</div>

{{-- Script Preview Poster --}}
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

{{-- SweetAlert Notifikasi --}}
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
@if(session('error'))
<script>
Swal.fire({
    icon: 'error',
    title: 'Gagal!',
    text: '{{ session('error') }}',
    timer: 2500,
    showConfirmButton: false
});
</script>
@endif

@endsection
