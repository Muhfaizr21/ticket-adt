@extends('admin.layouts.app')

@section('title', 'Edit Event')

@push('styles')
<style>
    body {
        background-color: #f5f7fa;
        font-family: 'Poppins', sans-serif;
    }

    .page-header {
        background: linear-gradient(90deg, #007bff, #0056d2);
        padding: 1.2rem 1.5rem;
        border-radius: 12px;
        color: #fff;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }

    .page-header h2 {
        font-weight: 600;
        font-size: 1.5rem;
        margin: 0;
    }

    .page-header .btn {
        background-color: rgba(255, 255, 255, 0.15);
        color: #fff;
        border: none;
        transition: 0.3s ease;
    }
    .page-header .btn:hover {
        background-color: rgba(255, 255, 255, 0.3);
        transform: scale(1.05);
    }

    .card {
        border: none;
        border-radius: 14px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.08);
        transition: 0.3s ease;
    }
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.12);
    }

    label.form-label {
        font-size: 0.95rem;
        font-weight: 600;
        color: #333;
    }

    input.form-control,
    select.form-select,
    textarea.form-control {
        border-radius: 12px;
        padding: 0.75rem 1rem;
        background: #fafafa;
        transition: 0.3s ease;
        border: 1px solid #ddd;
    }

    input.form-control:focus,
    select.form-select:focus,
    textarea.form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.2);
        background-color: #fff;
    }

    .input-group-text {
        background: #f1f1f1;
        font-weight: 600;
    }

    .preview-container img {
        border-radius: 10px;
        border: 1px solid #e1e1e1;
        max-width: 100%;
        max-height: 240px;
        object-fit: cover;
    }

    .btn-primary {
        background: linear-gradient(90deg, #007bff, #0056d2);
        border: none;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: 0.3s ease;
    }

    .btn-primary:hover {
        background: linear-gradient(90deg, #0056d2, #003c9e);
        transform: translateY(-2px);
    }

    .btn-secondary {
        background: #e9ecef;
        color: #333;
        font-weight: 500;
        transition: 0.3s ease;
    }

    .btn-secondary:hover {
        background: #d6d8db;
        transform: translateY(-2px);
    }

    .ratio {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 14px rgba(0,0,0,0.05);
    }
</style>
@endpush

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-pencil-square me-2"></i> Edit Event</h2>
    <a href="{{ route('admin.events.index') }}" class="btn btn-sm px-3">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="card">
    <div class="card-body p-4 p-md-5">
        <form action="{{ route('admin.events.update', $event->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-4">
                {{-- Nama Event --}}
                <div class="col-md-6">
                    <label class="form-label">Nama Event <span class="text-danger">*</span></label>
                    <input type="text" name="name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $event->name) }}"
                        placeholder="Contoh: Seminar Digitalisasi UMKM" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Venue --}}
                <div class="col-md-6">
                    <label class="form-label">Venue (Opsional)</label>
                    <select name="venue_id" class="form-select @error('venue_id') is-invalid @enderror">
                        <option value="">-- Pilih Venue --</option>
                        @foreach($venues as $venue)
                            <option value="{{ $venue->id }}" {{ old('venue_id', $event->venue_id) == $venue->id ? 'selected' : '' }}>
                                {{ $venue->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('venue_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Deskripsi --}}
                <div class="col-md-12">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror"
                        placeholder="Tulis deskripsi singkat event...">{{ old('description', $event->description) }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Tanggal & Waktu --}}
                <div class="col-md-6">
                    <label class="form-label">Tanggal & Waktu Event</label>
                    <div class="input-group">
                        <input type="date" name="date" class="form-control @error('date') is-invalid @enderror"
                            value="{{ old('date', $event->date ? \Carbon\Carbon::parse($event->date)->format('Y-m-d') : '') }}">
                        <input type="time" name="start_time" class="form-control @error('start_time') is-invalid @enderror"
                            value="{{ old('start_time', $event->start_time) }}">
                        <span class="input-group-text">s/d</span>
                        <input type="time" name="end_time" class="form-control @error('end_time') is-invalid @enderror"
                            value="{{ old('end_time', $event->end_time) }}">
                    </div>
                    <small class="text-muted fst-italic">Contoh: 9 Oktober 2025 jam 09.00–12.00</small>
                    @error('date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    @error('start_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    @error('end_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Lokasi Event --}}
                <div class="col-md-6">
                    <label class="form-label">Lokasi Event <span class="text-danger">*</span></label>
                    <input type="text" name="location" id="locationInput"
                        class="form-control @error('location') is-invalid @enderror"
                        value="{{ old('location', $event->location) }}" placeholder="Masukkan lokasi event" required>
                    @error('location') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Preview Maps --}}
                <div class="col-md-12">
                    <label class="form-label mb-2">Preview Lokasi</label>
                    <div class="ratio ratio-16x9">
                        <iframe id="mapIframe"
                            src="https://www.google.com/maps?q={{ urlencode(old('location', $event->location)) }}&output=embed"
                            allowfullscreen loading="lazy"></iframe>
                    </div>
                </div>

                {{-- Poster Event --}}
                <div class="col-md-6">
                    <label class="form-label">Poster Event</label>
                    @if($event->poster)
                        <div class="mb-3 preview-container">
                            <img src="{{ asset('storage/' . $event->poster) }}" alt="Poster Event" class="shadow-sm border">
                        </div>
                    @endif
                    <input type="file" name="poster" class="form-control @error('poster') is-invalid @enderror"
                        accept="image/png,image/jpeg,image/jpg" onchange="previewPoster(event)">
                    <small class="text-muted d-block mt-1 fst-italic">Kosongkan jika tidak ingin mengganti.</small>
                    @error('poster') <div class="invalid-feedback">{{ $message }}</div> @enderror

                    <div class="mt-3 d-none preview-container" id="previewContainer">
                        <img id="preview" src="#" alt="Preview Poster" class="shadow-sm border">
                    </div>
                </div>
            </div>

            {{-- Tombol Simpan --}}
            <div class="text-end mt-5 pt-4 border-top">
                <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                    <i class="bi bi-save me-1"></i> Perbarui Event
                </button>
                <a href="{{ route('admin.events.index') }}" class="btn btn-secondary btn-lg ms-2 shadow-sm">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Script Preview Peta & Poster --}}
<script>
    const locationInput = document.getElementById('locationInput');
    const mapIframe = document.getElementById('mapIframe');

    locationInput.addEventListener('input', function () {
        const query = encodeURIComponent(this.value || 'Indramayu');
        mapIframe.src = `https://www.google.com/maps?q=${query}&output=embed`;
    });

    function previewPoster(event) {
        const preview = document.getElementById('preview');
        const container = document.getElementById('previewContainer');
        const file = event.target.files[0];
        if (file) {
            preview.src = URL.createObjectURL(file);
            container.classList.remove('d-none');
        } else {
            container.classList.add('d-none');
            preview.src = '#';
        }
    }
</script>
@endsection
