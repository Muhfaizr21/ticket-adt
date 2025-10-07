@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">➕ Tambah Event Baru</h5>
            <a href="{{ route('admin.events.index') }}" class="btn btn-light btn-sm">← Kembali</a>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-4">
                    {{-- Nama Event --}}
                    <div class="col-md-6">
                        <label for="name" class="form-label fw-semibold">Nama Event</label>
                        <input type="text" name="name" id="name"
                            class="form-control @error('name') is-invalid @enderror"
                            placeholder="Masukkan nama event..." value="{{ old('name') }}" required>
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Harga Tiket Default --}}
                    <div class="col-md-6">
                        <label for="price" class="form-label fw-semibold">Harga Tiket Default (Rp)</label>
                        <input type="number" name="price" id="price"
                            class="form-control @error('price') is-invalid @enderror"
                            placeholder="Contoh: 50000" value="{{ old('price') }}" required>
                        @error('price')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Deskripsi Event --}}
                    <div class="col-md-12">
                        <label for="description" class="form-label fw-semibold">Deskripsi Event</label>
                        <textarea name="description" id="description" rows="3"
                            class="form-control @error('description') is-invalid @enderror"
                            placeholder="Tulis deskripsi singkat event..." required>{{ old('description') }}</textarea>
                        @error('description')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Tanggal & Lokasi --}}
                    <div class="col-md-6">
                        <label for="date" class="form-label fw-semibold">Tanggal Event</label>
                        <input type="date" name="date" id="date"
                            class="form-control @error('date') is-invalid @enderror" value="{{ old('date') }}" required>
                        @error('date')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="location" class="form-label fw-semibold">Lokasi Event</label>
                        <input type="text" name="location" id="location"
                            class="form-control @error('location') is-invalid @enderror"
                            placeholder="Contoh: Stadion Indramayu" value="{{ old('location') }}" required>
                        @error('location')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Total & Available Tickets --}}
                    <div class="col-md-6">
                        <label for="total_tickets" class="form-label fw-semibold">Total Tiket</label>
                        <input type="number" name="total_tickets" id="total_tickets"
                            class="form-control @error('total_tickets') is-invalid @enderror"
                            placeholder="Contoh: 1000" value="{{ old('total_tickets') }}" required>
                        @error('total_tickets')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="available_tickets" class="form-label fw-semibold">Tiket Tersedia</label>
                        <input type="number" name="available_tickets" id="available_tickets"
                            class="form-control @error('available_tickets') is-invalid @enderror"
                            placeholder="Contoh: 1000" value="{{ old('available_tickets') }}" required>
                        @error('available_tickets')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- VIP Tickets --}}
                    <div class="col-md-6">
                        <label for="vip_tickets" class="form-label fw-semibold">Tiket VIP (Jumlah)</label>
                        <input type="number" name="vip_tickets" id="vip_tickets"
                            class="form-control @error('vip_tickets') is-invalid @enderror"
                            placeholder="Contoh: 300" value="{{ old('vip_tickets') }}">
                        @error('vip_tickets')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="vip_price" class="form-label fw-semibold">Harga Tiket VIP (Rp)</label>
                        <input type="number" name="vip_price" id="vip_price"
                            class="form-control @error('vip_price') is-invalid @enderror"
                            placeholder="Contoh: 75000" value="{{ old('vip_price') }}">
                        @error('vip_price')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Reguler Tickets --}}
                    <div class="col-md-6">
                        <label for="reguler_tickets" class="form-label fw-semibold">Tiket Reguler (Jumlah)</label>
                        <input type="number" name="reguler_tickets" id="reguler_tickets"
                            class="form-control @error('reguler_tickets') is-invalid @enderror"
                            placeholder="Contoh: 700" value="{{ old('reguler_tickets') }}">
                        @error('reguler_tickets')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="reguler_price" class="form-label fw-semibold">Harga Tiket Reguler (Rp)</label>
                        <input type="number" name="reguler_price" id="reguler_price"
                            class="form-control @error('reguler_price') is-invalid @enderror"
                            placeholder="Contoh: 50000" value="{{ old('reguler_price') }}">
                        @error('reguler_price')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Poster Event --}}
                    <div class="col-md-12">
                        <label for="poster" class="form-label fw-semibold">Poster Event</label>
                        <input type="file" name="poster" id="poster"
                            class="form-control @error('poster') is-invalid @enderror" accept="image/*"
                            onchange="previewPoster(event)">
                        <small class="text-muted">Format: JPG, JPEG, PNG (maks. 2MB)</small>
                        @error('poster')
                            <small class="text-danger d-block">{{ $message }}</small>
                        @enderror

                        <div class="mt-3">
                            <img id="preview" src="#" alt="Preview Poster" class="img-thumbnail d-none"
                                 style="max-height: 200px;">
                        </div>
                    </div>

                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-success px-4">💾 Simpan Event</button>
                </div>
            </form>
        </div>
    </div>
</div>

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
