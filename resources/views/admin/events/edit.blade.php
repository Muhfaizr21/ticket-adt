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

                        {{-- Harga Tiket --}}
                        <div class="mb-4">
                            <label class="form-label">Harga Tiket (Rp)</label>
                            <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                                value="{{ old('price', $event->price) }}" min="0" required>
                            @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Total Tiket --}}
                        <div class="mb-4">
                            <label class="form-label">Total Tiket</label>
                            <input type="number" name="total_tickets"
                                class="form-control @error('total_tickets') is-invalid @enderror"
                                value="{{ old('total_tickets', $event->total_tickets) }}" min="1" required>
                            @error('total_tickets') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- Kolom kanan --}}
                    <div class="col-md-6">
                        {{-- Tanggal Event --}}
                        <div class="mb-4">
                            <label class="form-label">Tanggal Event</label>
                            <input type="date" name="date" class="form-control"
                                value="{{ old('date', \Carbon\Carbon::parse($event->date)->format('Y-m-d')) }}" required>
                        </div>

                        {{-- Lokasi Event --}}
                        <div class="mb-4">
                            <label class="form-label">Lokasi</label>
                            <input type="text" name="location" class="form-control"
                                value="{{ old('location', $event->location) }}" required>
                        </div>

                        {{-- Poster Event --}}
                        <div class="mb-4">
                            <label class="form-label">Poster Event</label>
                            @if($event->poster)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $event->poster) }}" alt="Poster Event" class="img-thumbnail"
                                        style="max-height: 200px;">
                                </div>
                            @endif
                            <input type="file" name="poster" class="form-control">
                            <small class="text-muted">Kosongkan jika tidak ingin mengubah poster.</small>
                        </div>
                    </div>
                </div>

                {{-- Tombol Submit --}}
                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-success px-4">💾 Simpan Perubahan</button>
                    <a href="{{ route('admin.events.index') }}" class="btn btn-secondary ms-2">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection