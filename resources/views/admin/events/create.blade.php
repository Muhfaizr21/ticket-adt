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
                        <input type="text" name="name" id="name" class="form-control" placeholder="Masukkan nama event..." required>
                    </div>

                    {{-- Harga Tiket --}}
                    <div class="col-md-6">
                        <label for="price" class="form-label fw-semibold">Harga Tiket (Rp)</label>
                        <input type="number" name="price" id="price" class="form-control" placeholder="Contoh: 50000" required>
                    </div>

                    {{-- Deskripsi Event --}}
                    <div class="col-md-12">
                        <label for="description" class="form-label fw-semibold">Deskripsi Event</label>
                        <textarea name="description" id="description" rows="3" class="form-control" placeholder="Tulis deskripsi singkat event..." required></textarea>
                    </div>

                    {{-- Jumlah Tiket --}}
                    <div class="col-md-6">
                        <label for="total_tickets" class="form-label fw-semibold">Jumlah Tiket</label>
                        <input type="number" name="total_tickets" id="total_tickets" class="form-control" placeholder="Contoh: 1000" required>
                    </div>

                    {{-- Poster Event --}}
                    <div class="col-md-6">
                        <label for="poster" class="form-label fw-semibold">Poster Event</label>
                        <input type="file" name="poster" id="poster" class="form-control" accept="image/*" required onchange="previewPoster(event)">
                        <small class="text-muted">Format: JPG, JPEG, PNG</small>
                        <div class="mt-3">
                            <img id="preview" src="#" alt="Preview Poster" class="img-thumbnail d-none" style="max-height: 200px;">
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
@endsection
