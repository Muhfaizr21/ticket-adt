@extends('admin.layouts.app')

@section('title', 'Tambah Event Baru')

@push('styles')
<style>
    body {
        background-color: #f4f6f9;
        font-family: 'Poppins', sans-serif;
    }

    .event-card {
        background: #fff;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 6px 20px rgba(0,0,0,0.08);
    }

    .event-card-header {
        background: linear-gradient(90deg, #007bff, #0056b3);
        color: white;
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .event-card-header h5 {
        font-weight: 600;
        margin: 0;
    }

    .event-card-body {
        padding: 2rem;
    }

    .form-label {
        font-weight: 600;
        font-size: 0.95rem;
        color: #333;
    }

    .form-control, .form-select {
        border-radius: 12px;
        padding: 0.7rem 1rem;
        border: 1px solid #ddd;
        transition: all 0.25s ease;
        background-color: #fdfdfd;
    }

    .form-control:focus, .form-select:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.2);
        background-color: #fff;
    }

    .btn-success {
        background-color: #28a745;
        border: none;
        padding: 0.7rem 1.5rem;
        font-weight: 600;
        border-radius: 10px;
        transition: background-color 0.3s;
    }

    .btn-success:hover {
        background-color: #218838;
    }

    .map-preview, .poster-preview {
        background: #f8f9fa;
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        padding: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    #poster-preview {
        max-height: 240px;
        border-radius: 12px;
        object-fit: cover;
    }

    .section-title {
        font-size: 1.05rem;
        font-weight: 600;
        color: #444;
        margin-bottom: 0.5rem;
    }

    @media (max-width: 992px) {
        .event-card-body {
            padding: 1.25rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container my-4">
    <div class="event-card">
        <div class="event-card-header">
            <h5><i class="bi bi-calendar-plus me-2"></i> Tambah Event Baru</h5>
            <a href="{{ route('admin.events.index') }}" class="btn btn-light btn-sm rounded-pill">
                <i class="bi bi-arrow-left-circle me-1"></i> Kembali
            </a>
        </div>

        <div class="event-card-body">
            <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-4">

                    {{-- Nama Event --}}
                    <div class="col-lg-6">
                        <label for="name" class="form-label">Nama Event <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Masukkan nama event..." required>
                    </div>

                    {{-- Venue --}}
                    <div class="col-lg-6">
                        <label for="venue_id" class="form-label">Venue (Opsional)</label>
                        <select name="venue_id" id="venue_id" class="form-select">
                            <option value="">-- Pilih Venue --</option>
                            @foreach($venues as $venue)
                                <option value="{{ $venue->id }}">{{ $venue->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Lokasi --}}
                    <div class="col-lg-6">
                        <label for="location" class="form-label">Lokasi Event <span class="text-danger">*</span></label>
                        <input type="text" name="location" id="location" class="form-control" placeholder="Contoh: Stadion Indramayu" required>
                    </div>

                    {{-- Preview Map --}}
                    <div class="col-lg-6">
                        <label class="form-label section-title">Preview Lokasi</label>
                        <div class="map-preview">
                            <iframe id="map-frame" src="https://www.google.com/maps?q=Indramayu&output=embed" width="100%" height="250" style="border:0;" allowfullscreen></iframe>
                        </div>
                    </div>

                    {{-- Deskripsi --}}
                    <div class="col-lg-12">
                        <label for="description" class="form-label">Deskripsi Event</label>
                        <textarea name="description" id="description" rows="4" class="form-control" placeholder="Tulis deskripsi singkat event..."></textarea>
                    </div>

                    {{-- Tanggal & Waktu --}}
                    <div class="col-lg-6">
                        <label class="form-label">Tanggal & Waktu Event</label>
                        <div class="input-group">
                            <input type="date" name="date" class="form-control">
                            <input type="time" name="start_time" class="form-control">
                            <span class="input-group-text">s/d</span>
                            <input type="time" name="end_time" class="form-control">
                        </div>
                        <small class="text-muted d-block mt-1">Contoh: 9 Oktober 2025 jam 09.00–12.00</small>
                    </div>

                    {{-- Poster --}}
                    <div class="col-lg-6">
                        <label for="poster" class="form-label">Poster Event</label>
                        <input type="file" name="poster" id="poster" class="form-control" accept="image/png,image/jpeg" onchange="previewPoster(event)">
                        <small class="text-muted d-block mt-1">Format: JPG, JPEG, PNG (maks. 2MB)</small>
                        <div class="poster-preview mt-2">
                            <img id="poster-preview" src="#" alt="Poster Preview" class="w-100 d-none">
                        </div>
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save me-1"></i> Simpan Event
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Ganti peta saat lokasi diketik
    document.getElementById('location').addEventListener('input', function() {
        const loc = encodeURIComponent(this.value || 'Indramayu');
        document.getElementById('map-frame').src = `https://www.google.com/maps?q=${loc}&output=embed`;
    });

    // Preview poster
    function previewPoster(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('poster-preview');
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
