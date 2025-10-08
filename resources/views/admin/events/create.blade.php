@extends('admin.layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">➕ Tambah Event Baru</h5>
                <a href="{{ route('admin.events.index') }}" class="btn btn-light btn-sm">← Kembali</a>
            </div>

            <div class="card-body">
                <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                    @csrf
                    <div class="row g-4">

                        {{-- Nama Event --}}
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-semibold">Nama Event <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="name" id="name"
                                class="form-control @error('name') is-invalid @enderror"
                                placeholder="Masukkan nama event..." value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Harga Tiket --}}
                        <div class="col-md-6">
                            <label for="price" class="form-label fw-semibold">Harga Tiket (Rp) <span
                                    class="text-danger">*</span></label>
                            <input type="number" name="price" id="price"
                                class="form-control @error('price') is-invalid @enderror" placeholder="Contoh: 50000"
                                value="{{ old('price') }}" min="0" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Deskripsi --}}
                        <div class="col-md-12">
                            <label for="description" class="form-label fw-semibold">Deskripsi Event <span
                                    class="text-danger">*</span></label>
                            <textarea name="description" id="description" rows="3"
                                class="form-control @error('description') is-invalid @enderror"
                                placeholder="Tulis deskripsi singkat event..." required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Tanggal --}}
                        <div class="col-md-6">
                            <label for="date" class="form-label fw-semibold">Tanggal Event <span
                                    class="text-danger">*</span></label>
                            <input type="date" name="date" id="date"
                                class="form-control @error('date') is-invalid @enderror"
                                value="{{ old('date', date('Y-m-d')) }}" required>
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Lokasi --}}
                        {{-- Lokasi --}}
                        <div class="col-md-6 mb-3">
                            <label for="location" class="form-label fw-semibold">Lokasi Event <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="location" id="location"
                                class="form-control @error('location') is-invalid @enderror"
                                placeholder="Contoh: Stadion Indramayu" value="{{ old('location') }}" required>
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Peta Lokasi Dinamis --}}
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-semibold">Preview Lokasi di Peta</label>
                            <div id="map-container" style="height:250px; border-radius:10px;">
                                <iframe id="map-frame"
                                    src="https://www.google.com/maps?q={{ urlencode(old('location', 'Indramayu, Gor Wiralodra')) }}&output=embed"
                                    width="100%" height="250" style="border:0; border-radius:10px;" allowfullscreen=""
                                    loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                                </iframe>
                            </div>
                        </div>

                        <script>
                            const locationInput = document.getElementById('location');
                            const mapFrame = document.getElementById('map-frame');

                            locationInput.addEventListener('input', function () {
                                const query = encodeURIComponent(this.value || 'Indramayu, Gor Wiralodra');
                                mapFrame.src = `https://www.google.com/maps?q=${query}&output=embed`;
                            });
                        </script>

                        {{-- Total Tiket --}}
                        <div class="col-md-6">
                            <label for="total_tickets" class="form-label fw-semibold">Total Tiket <span
                                    class="text-danger">*</span></label>
                            <input type="number" name="total_tickets" id="total_tickets"
                                class="form-control @error('total_tickets') is-invalid @enderror" placeholder="Contoh: 1000"
                                value="{{ old('total_tickets') }}" min="1" required>
                            @error('total_tickets')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Tiket Tersedia --}}
                        <div class="col-md-6">
                            <label for="available_tickets" class="form-label fw-semibold">Tiket Tersedia <span
                                    class="text-danger">*</span></label>
                            <input type="number" name="available_tickets" id="available_tickets"
                                class="form-control @error('available_tickets') is-invalid @enderror"
                                placeholder="Contoh: 1000" value="{{ old('available_tickets', old('total_tickets')) }}"
                                min="1" required>
                            @error('available_tickets')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Venue --}}
                        <div class="col-md-6">
                            <label for="venue_id" class="form-label fw-semibold">Venue (Opsional)</label>
                            <select name="venue_id" id="venue_id"
                                class="form-select @error('venue_id') is-invalid @enderror">
                                <option value="">-- Pilih Venue --</option>
                                @foreach($venues as $venue)
                                    <option value="{{ $venue->id }}" {{ old('venue_id') == $venue->id ? 'selected' : '' }}>
                                        {{ $venue->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('venue_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Poster --}}
                        <div class="col-md-6">
                            <label for="poster" class="form-label fw-semibold">Poster Event</label>
                            <input type="file" name="poster" id="poster"
                                class="form-control @error('poster') is-invalid @enderror"
                                accept="image/png,image/jpeg,image/jpg" onchange="previewPoster(event)">
                            <small class="text-muted d-block">Format: JPG, JPEG, PNG (maks. 2MB)</small>
                            @error('poster')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div class="mt-3">
                                <img id="preview" src="#" alt="Preview Poster" class="img-thumbnail d-none"
                                    style="max-height: 200px;">
                            </div>
                        </div>

                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-success px-4 fw-semibold">💾 Simpan Event</button>
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