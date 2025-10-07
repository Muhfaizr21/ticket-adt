@extends('admin.layouts.app')

@section('title', 'Edit Jenis Tiket')

@section('content')
<div class="page-header mb-4 d-flex justify-content-between align-items-center">
    <h2 class="fw-semibold text-dark">✏️ Edit Jenis Tiket: {{ $event->name }}</h2>
    <a href="{{ route('admin.ticket-types.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form action="{{ route('admin.ticket-types.update', $event->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Tiket VIP --}}
            <h5 class="mb-3">🎟️ Tiket VIP</h5>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="vip_tickets" class="form-label">Jumlah Tiket VIP</label>
                    <input type="number" 
                           name="vip_tickets" 
                           id="vip_tickets" 
                           class="form-control" 
                           value="{{ old('vip_tickets', $event->vip_tickets) }}" 
                           min="0" 
                           required>
                    @error('vip_tickets')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="vip_price" class="form-label">Harga Tiket VIP (Rp)</label>
                    <input type="number" 
                           name="vip_price" 
                           id="vip_price" 
                           class="form-control"
                           value="{{ old('vip_price', $event->vip_price) }}" 
                           min="0" 
                           required>
                    @error('vip_price')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Tiket Reguler --}}
            <h5 class="mb-3 mt-4">🎟️ Tiket Reguler</h5>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="reguler_tickets" class="form-label">Jumlah Tiket Reguler</label>
                    <input type="number" 
                           name="reguler_tickets" 
                           id="reguler_tickets" 
                           class="form-control"
                           value="{{ old('reguler_tickets', $event->reguler_tickets) }}" 
                           min="0" 
                           required>
                    @error('reguler_tickets')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="reguler_price" class="form-label">Harga Tiket Reguler (Rp)</label>
                    <input type="number" 
                           name="reguler_price" 
                           id="reguler_price" 
                           class="form-control"
                           value="{{ old('reguler_price', $event->reguler_price) }}" 
                           min="0" 
                           required>
                    @error('reguler_price')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('admin.ticket-types.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
