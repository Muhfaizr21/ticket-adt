@extends('layouts.app')

@section('title', 'Detail Event')

@section('content')
<div class="container my-5">
    <div class="row">
        <!-- Kolom kiri: Detail Event -->
        <div class="col-md-8 mb-4 mb-md-0">
            <img src="{{ asset('storage/' . $event->poster) }}"
                class="img-fluid rounded mb-4 w-100"
                alt="{{ $event->name }}">

            <h1 class="fw-bold mb-3">{{ $event->name }}</h1>

            <p class="mb-2">
                <i class="bi bi-calendar-event text-primary me-2"></i>
                {{ \Carbon\Carbon::parse($event->date)->format('d M Y') }}
            </p>
            <p class="mb-2">
                <i class="bi bi-geo-alt text-danger me-2"></i>
                {{ $event->location }}
            </p>
            <p class="mt-3">{{ $event->description }}</p>
        </div>

        <!-- Kolom kanan: Pembelian Tiket -->
        <div class="col-md-4">
            <div class="card shadow-sm p-4 border-0">
                <h5 class="fw-bold mb-3">🎟️ Informasi Tiket</h5>

                <p class="mb-2">
                    <strong>Harga:</strong>
                    <span class="text-success">Rp {{ number_format($event->price, 0, ',', '.') }}</span>
                </p>

                <p class="mb-3">
                    <strong>Tersedia:</strong>
                    {{ $event->available_tickets }} tiket
                </p>

                <a href="{{ route('shop.index') }}" class="btn btn-secondary w-100 mb-3">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Shop
                </a>

                @if($event->available_tickets > 0)
                <form action="{{ route('tickets.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="event_id" value="{{ $event->id }}">

                    <div class="mb-3">
                        <label for="quantity" class="form-label fw-semibold">Jumlah Tiket</label>
                        <input type="number"
                               id="quantity"
                               name="quantity"
                               min="1"
                               max="{{ $event->available_tickets }}"
                               value="1"
                               class="form-control"
                               required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-cart-fill me-1"></i> Beli Tiket
                    </button>
                </form>
                @else
                <button class="btn btn-danger w-100" disabled>
                    <i class="bi bi-x-circle me-1"></i> Tiket Habis
                </button>
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    h1 {
        font-size: 2rem;
    }
    .card {
        border-radius: 12px;
    }
</style>
@endpush
@endsection
