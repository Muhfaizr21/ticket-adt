@extends('admin.layouts.app')

@section('title', 'Edit Ticket Types')

@section('content')
<div class="page-header mb-4 d-flex justify-content-between align-items-center">
    <h2 class="fw-semibold text-dark">✏️ Edit Ticket Types: {{ $event->name }}</h2>
    <a href="{{ route('admin.ticket-types.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form action="{{ route('admin.ticket-types.update', $event->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- VIP --}}
            <h5 class="mb-3">VIP Ticket</h5>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="vip_quantity" class="form-label">Jumlah Tiket VIP</label>
                    <input type="number" name="vip_quantity" id="vip_quantity" class="form-control" 
                           value="{{ old('vip_quantity', $vipQuantity) }}" min="0" required>
                    @error('vip_quantity')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="vip_price" class="form-label">Harga Tiket VIP (Rp)</label>
                    <input type="number" name="vip_price" id="vip_price" class="form-control"
                           value="{{ old('vip_price', $vipPrice) }}" min="0" required>
                    @error('vip_price')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Reguler --}}
            <h5 class="mb-3 mt-4">Reguler Ticket</h5>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="reguler_quantity" class="form-label">Jumlah Tiket Reguler</label>
                    <input type="number" name="reguler_quantity" id="reguler_quantity" class="form-control"
                           value="{{ old('reguler_quantity', $regulerQuantity) }}" min="0" required>
                    @error('reguler_quantity')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="reguler_price" class="form-label">Harga Tiket Reguler (Rp)</label>
                    <input type="number" name="reguler_price" id="reguler_price" class="form-control"
                        value="{{ old('reguler_price', $regulerPrice) }}" min="0" required>
                    @error('reguler_price')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Tombol --}}
            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('admin.ticket-types.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i> Update Ticket Types
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
