@extends('admin.layouts.app')

@section('title', 'Tambah Berita')

@section('content')
<div class="container py-4">
    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
        <div class="card-header bg-primary text-white py-3">
            <h1 class="h4 mb-0 fw-bold">
                <i class="bi bi-newspaper me-2"></i> Tambah Berita Baru
            </h1>
        </div>

        <div class="card-body p-5 bg-light">
            <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data" class="row g-4">
                @csrf

                {{-- Judul --}}
                <div class="col-12">
                    <label class="form-label fw-semibold mb-2">Judul Berita</label>
                    <div class="input-box rounded-3 shadow-sm bg-white">
                        <input type="text" name="title"
                            class="form-control border-0 py-3 px-3 bg-transparent @error('title') is-invalid @enderror"
                            placeholder="Masukkan judul berita..."
                            required>
                    </div>
                    @error('title')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Konten --}}
                <div class="col-12">
                    <label class="form-label fw-semibold mb-2">Konten Berita</label>
                    <div class="input-box rounded-3 shadow-sm bg-white">
                        <textarea name="content"
                            class="form-control border-0 py-3 px-3 bg-transparent @error('content') is-invalid @enderror"
                            rows="8"
                            placeholder="Tulis isi berita..." required></textarea>
                    </div>
                    @error('content')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Gambar --}}
                <div class="col-12">
                    <label class="form-label fw-semibold mb-2">Gambar (Opsional)</label>
                    <div class="input-box rounded-3 shadow-sm bg-white">
                        <input type="file"
                            name="image"
                            class="form-control border-0 py-3 px-3 bg-transparent @error('image') is-invalid @enderror"
                            accept="image/*"
                            onchange="previewImage(event)">
                    </div>
                    @error('image')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror

                    <div class="mt-3 d-none" id="imagePreviewWrapper">
                        <p class="fw-semibold mb-2">Preview Gambar:</p>
                        <img id="imagePreview" src="#" alt="Preview"
                            class="img-fluid rounded-4 border shadow-sm"
                            style="max-height: 250px; object-fit: cover;">
                    </div>
                </div>

                {{-- Tombol --}}
                <div class="col-12 d-flex justify-content-end gap-3 mt-4">
                    <a href="{{ route('admin.news.index') }}" class="btn btn-secondary px-4 py-2 rounded-3">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <button class="btn btn-primary px-4 py-2 rounded-3">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    .input-box {
        border: 2px solid transparent;
        transition: all 0.3s ease;
    }
    .input-box:hover {
        border: 2px solid #0d6efd;
        box-shadow: 0 0 10px rgba(13,110,253,0.1);
    }
    .form-control:focus {
        outline: none;
        box-shadow: none;
    }
    .card {
        border-radius: 16px;
    }
    label.form-label {
        font-size: 0.95rem;
    }
    textarea.form-control {
        resize: none;
    }
</style>
@endpush

@push('scripts')
<script>
    function previewImage(event) {
        const preview = document.getElementById('imagePreview');
        const wrapper = document.getElementById('imagePreviewWrapper');
        const file = event.target.files[0];
        if (file) {
            preview.src = URL.createObjectURL(file);
            wrapper.classList.remove('d-none');
        } else {
            preview.src = "#";
            wrapper.classList.add('d-none');
        }
    }
</script>
@endpush
@endsection
