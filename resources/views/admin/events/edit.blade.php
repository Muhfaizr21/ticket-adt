@extends('admin.layouts.app')

@section('title', 'Edit Event')

@section('content')
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">✏️ Edit Event</h2>
        <a href="{{ route('admin.events.index') }}" class="btn btn-secondary shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-5">
            <form action="{{ route('admin.events.update', $event->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    {{-- Nama Event --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nama Event <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $event->name) }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Venue --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Venue (Opsional)</label>
                        <select name="venue_id" class="form-select @error('venue_id') is-invalid @enderror">
                            <option value="">-- Pilih Venue --</option>
                            @foreach($venues as $venue)
                                <option value="{{ $venue->id }}"
                                    {{ old('venue_id', $event->venue_id) == $venue->id ? 'selected' : '' }}>
                                    {{ $venue->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('venue_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <textarea name="description" rows="3"
                            class="form-control @error('description') is-invalid @enderror"
                            placeholder="Tulis deskripsi singkat event...">{{ old('description', $event->description) }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Tanggal Event --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tanggal Event</label>
                        <input type="date" name="date" class="form-control @error('date') is-invalid @enderror"
                            value="{{ old('date', $event->date ? \Carbon\Carbon::parse($event->date)->format('Y-m-d') : '') }}">
                        @error('date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Lokasi Event --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Lokasi Event <span class="text-danger">*</span></label>
                        <input type="text" name="location" id="locationInput"
                            class="form-control @error('location') is-invalid @enderror"
                            value="{{ old('location', $event->location) }}" required>
                        @error('location') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Preview Maps --}}
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Preview Lokasi</label>
                        <div style="width:100%; height:250px; border-radius:10px;">
                            <iframe id="mapIframe"
                                src="https://www.google.com/maps?q={{ urlencode(old('location', $event->location)) }}&output=embed"
                                width="100%" height="250" style="border:0; border-radius:10px;" allowfullscreen
                                loading="lazy">
                            </iframe>
                        </div>
                    </div>

                    {{-- Poster Event --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Poster Event</label>
                        @if($event->poster)
                            <div class="mb-3">
                                <img src="{{ asset('storage/' . $event->poster) }}" alt="Poster Event" width="150"
                                    class="rounded shadow-sm">
                            </div>
                        @endif
                        <input type="file" name="poster" class="form-control @error('poster') is-invalid @enderror"
                            accept="image/png,image/jpeg,image/jpg" onchange="previewPoster(event)">
                        <small class="text-muted d-block">Biarkan kosong jika tidak ingin mengganti.</small>
                        @error('poster') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="mt-3">
                            <img id="preview" src="#" alt="Preview Poster" class="img-thumbnail d-none"
                                style="max-height:200px;">
                        </div>
                    </div>

                    {{-- Waktu Pembuatan & Update --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Dibuat Pada</label>
                        <input type="text" class="form-control mb-3"
                            value="{{ $event->created_at ? $event->created_at->format('d-m-Y H:i') : '-' }}" readonly>
                        <label class="form-label fw-semibold">Diperbarui Pada</label>
                        <input type="text" class="form-control"
                            value="{{ $event->updated_at ? $event->updated_at->format('d-m-Y H:i') : '-' }}" readonly>
                    </div>
                </div>

                {{-- Tombol Simpan --}}
                <div class="text-end mt-4 pt-4 border-top">
                    <button type="submit" class="btn btn-primary px-4 fw-semibold">💾 Perbarui Event</button>
                    <a href="{{ route('admin.events.index') }}" class="btn btn-secondary ms-2">Batal</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Script Preview Peta & Poster --}}
    <script>
        const locationInput = document.getElementById('locationInput');
        const mapIframe = document.getElementById('mapIframe');

        locationInput.addEventListener('input', function () {
            const query = encodeURIComponent(this.value || 'Indramayu, Gor Wiralodra');
            mapIframe.src = `https://www.google.com/maps?q=${query}&output=embed`;
        });

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