@extends('admin.layouts.app')

@section('title', 'Edit Event')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-dark">Edit Event</h2>
    <a href="{{ route('admin.events.index') }}" class="btn btn-secondary shadow-sm">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-5">
        <form action="{{ route('admin.events.update', $event->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                {{-- Kolom kiri --}}
                <div class="col-md-6">
                    {{-- Nama Event --}}
                    <div class="mb-4">
                        <label class="form-label">Nama Event <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $event->name) }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Deskripsi Event --}}
                    <div class="mb-4">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" rows="4"
                            class="form-control @error('description') is-invalid @enderror">{{ old('description', $event->description) }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Total Tiket --}}
                    <div class="mb-4">
                        <label class="form-label">Total Tiket <span class="text-danger">*</span></label>
                        <input type="number" name="total_tickets"
                            class="form-control @error('total_tickets') is-invalid @enderror"
                            value="{{ old('total_tickets', $event->total_tickets) }}" min="1" required>
                        @error('total_tickets') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Available Tickets --}}
                    <div class="mb-4">
                        <label class="form-label">Tiket Tersedia</label>
                        <input type="number" class="form-control" value="{{ $event->available_tickets }}" readonly>
                    </div>

                    {{-- VIP Tickets --}}
                    <div class="mb-4">
                        <label class="form-label">VIP Tickets</label>
                        <input type="number" class="form-control" value="{{ $event->vip_tickets ?? 0 }}" readonly>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">VIP Price (Rp)</label>
                        <input type="text" class="form-control"
                            value="{{ $event->vip_price ? number_format($event->vip_price, 0, ',', '.') : '-' }}" readonly>
                    </div>

                    {{-- Reguler Tickets --}}
                    <div class="mb-4">
                        <label class="form-label">Reguler Tickets</label>
                        <input type="number" class="form-control" value="{{ $event->reguler_tickets ?? 0 }}" readonly>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Reguler Price (Rp)</label>
                        <input type="text" class="form-control"
                            value="{{ $event->reguler_price ? number_format($event->reguler_price, 0, ',', '.') : '-' }}"
                            readonly>
                    </div>
                </div>

                {{-- Kolom kanan --}}
                <div class="col-md-6">
                    {{-- Tanggal Event --}}
                    <div class="mb-4">
                        <label class="form-label">Tanggal Event</label>
                        <input type="date" name="date" class="form-control"
                            value="{{ old('date', $event->date ? \Carbon\Carbon::parse($event->date)->format('Y-m-d') : '') }}">
                    </div>

                    {{-- Lokasi Event --}}
                    <div class="mb-4">
                        <label class="form-label">Lokasi</label>
                        <input type="text" name="location" id="locationInput" class="form-control"
                            value="{{ old('location', $event->location) }}" required>
                    </div>

                    {{-- Hidden Latitude & Longitude --}}
                    <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude', $event->latitude) }}">
                    <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude', $event->longitude) }}">

                    {{-- Tombol Gunakan Lokasi Terkini --}}
                    <div class="mb-3">
                        <button type="button" id="currentLocation" class="btn btn-info btn-sm">Gunakan Lokasi Terkini</button>
                    </div>

                    {{-- Preview Maps --}}
                    <div class="mb-4">
                        <label class="form-label">Preview Lokasi</label>
                        <div id="mapPreview" style="width:100%; height:250px; border-radius:10px;">
                            <iframe id="mapIframe"
                                src="https://www.google.com/maps?q={{ urlencode(old('location', $event->location)) }}&output=embed"
                                width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                        </div>
                    </div>

                    {{-- Venue --}}
                    <div class="mb-4">
                        <label class="form-label">Venue</label>
                        <select name="venue_id" class="form-control">
                            <option value="">-- Pilih Venue --</option>
                            @foreach($venues as $venue)
                                <option value="{{ $venue->id }}"
                                    {{ old('venue_id', $event->venue_id ?? '') == $venue->id ? 'selected' : '' }}>
                                    {{ $venue->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Poster --}}
                    <div class="mb-4">
                        <label class="form-label">Poster Event</label>
                        @if($event->poster)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $event->poster) }}" alt="Poster Event" width="120"
                                    class="rounded">
                            </div>
                        @endif
                        <input type="file" name="poster" class="form-control">
                    </div>

                    {{-- Created & Updated --}}
                    <div class="mb-2">
                        <label class="form-label">Dibuat Pada</label>
                        <input type="text" class="form-control"
                            value="{{ $event->created_at ? $event->created_at->format('d-m-Y H:i') : '-' }}" readonly>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Diperbarui Pada</label>
                        <input type="text" class="form-control"
                            value="{{ $event->updated_at ? $event->updated_at->format('d-m-Y H:i') : '-' }}" readonly>
                    </div>
                </div>
            </div>

            {{-- Tombol Submit --}}
            <div class="mt-4 pt-4 border-top">
                <button type="submit" class="btn btn-primary">Update Info</button>
                <a href="{{ route('admin.events.index') }}" class="btn btn-secondary ms-2">Batal</a>
            </div>
        </form>
    </div>
</div>

{{-- Script Maps --}}
<script>
const locationInput = document.getElementById('locationInput');
const mapIframe = document.getElementById('mapIframe');

locationInput.addEventListener('input', function() {
    const address = locationInput.value;
    if(address.length > 3) {
        mapIframe.src = `https://www.google.com/maps?q=${encodeURIComponent(address)}&output=embed`;
    }
});

document.getElementById('currentLocation').addEventListener('click', function() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;

            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;

            mapIframe.src = `https://www.google.com/maps?q=${lat},${lng}&output=embed`;
        }, function(err) {
            alert('Gagal mendapatkan lokasi: ' + err.message);
        });
    } else {
        alert('Browser tidak mendukung Geolocation');
    }
});
</script>
@endsection
