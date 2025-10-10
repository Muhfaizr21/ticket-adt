@extends('layouts.app')

@section('title', 'Detail Event')

@section('content')
<div class="container my-5">
    <div class="row g-4">
        <!-- Kolom kiri: Detail Event -->
        <div class="col-md-7">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="poster-container">
                    <img src="{{ asset('storage/' . $event->poster) }}"
                        class="poster-img"
                        alt="{{ $event->name }}">
                </div>
                <div class="card-body p-4">
                    <h1 class="fw-bold mb-3 text-dark">{{ $event->name }}</h1>

                    <div class="d-flex flex-column gap-2 mb-3">
                        <div class="d-flex align-items-center text-muted">
                            <i class="bi bi-calendar-event text-primary me-2 fs-5"></i>
                            <span>{{ \Carbon\Carbon::parse($event->date)->format('d M Y') }}</span>
                        </div>
                        <div class="d-flex align-items-center text-muted">
                            <i class="bi bi-geo-alt text-danger me-2 fs-5"></i>
                            <span>{{ $event->location }}</span>
                        </div>
                    </div>

                    <hr>

                    <p class="text-secondary" style="line-height: 1.7;">
                        {{ $event->description }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Kolom kanan: Pembelian Tiket -->
        <div class="col-md-5">
            <div class="card shadow-sm border-0 p-4 sticky-top" style="top: 100px;">
                <h5 class="fw-bold mb-4 text-dark">🎟️ Informasi Tiket</h5>

                <div class="d-flex justify-content-between mb-3">
                    <span class="fw-semibold">Harga</span>
                    <span class="text-success fw-bold">Rp {{ number_format($event->price, 0, ',', '.') }}</span>
                </div>

                <div class="d-flex justify-content-between mb-4">
                    <span class="fw-semibold">Tersedia</span>
                    <span>{{ $event->available_tickets }} tiket</span>
                </div>

                <a href="{{ route('shop.index') }}" class="btn btn-outline-secondary w-100 mb-3">
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
                            class="form-control form-control-lg rounded-3"
                            required>
                    </div>

                    <button type="submit" class="btn btn-success w-100 py-2 fw-semibold">
                        <i class="bi bi-cart-fill me-1"></i> Beli Tiket Sekarang
                    </button>
                </form>
                @else
                <button class="btn btn-danger w-100 py-2" disabled>
                    <i class="bi bi-x-circle me-1"></i> Tiket Habis
                </button>
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Poster styling */
    .poster-container {
        width: 100%;
        height: 320px;
        overflow: hidden;
        background: #f5f5f5;
    }

    .poster-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform .4s ease;
    }

    .poster-container:hover .poster-img {
        transform: scale(1.05);
    }

    /* Card styling */
    .card {
        border-radius: 14px;
        background: #fff;
    }

    h1 {
        font-size: 1.8rem;
    }

    @media (max-width: 768px) {
        .poster-container {
            height: 220px;
        }
        h1 {
            font-size: 1.5rem;
        }
    }

    .btn-success {
        background: linear-gradient(135deg, #28a745, #36c060);
        border: none;
        font-weight: 600;
        transition: transform .2s ease;
    }

    .btn-success:hover {
        transform: scale(1.03);
        background: linear-gradient(135deg, #36c060, #2ec45b);
    }

    .btn-outline-secondary {
        font-weight: 500;
        border-radius: 8px;
    }

    .form-control {
        border-radius: 8px;
        padding: .6rem .75rem;
    }
</style>
@endpush
@endsection
