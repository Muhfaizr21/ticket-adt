@extends('layouts.app')

@section('title', 'Shop - Event List')

@section('content')
<div class="container my-5">
    <h1 class="mb-5 text-center fw-bold" style="font-size: 2.2rem; color: #1b1b1b;">
    Beberpa Event yang Tersedia
    </h1>

    <div class="row justify-content-center g-4">
        @forelse($events as $event)
        @php
            $isNew = \Carbon\Carbon::parse($event->created_at)->gt(now()->subDays(7));
            $isTrending = $event->available_tickets > 50;
        @endphp

        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <div class="card event-card border-0 position-relative">

                <!-- Poster -->
                <div class="poster-wrapper position-relative">
                    <img src="{{ asset('storage/' . $event->poster) }}"
                        class="card-img-top poster-img"
                        alt="{{ $event->name }}">

                    @if($isNew)
                        <span class="badge bg-primary badge-custom">🆕 Baru</span>
                    @endif
                    @if($isTrending)
                        <span class="badge bg-warning text-dark badge-custom" style="right:10px;left:auto;">🔥 Trending</span>
                    @endif
                    @if($event->available_tickets <= 0)
                        <span class="badge bg-danger sold-out-badge">Sold Out</span>
                    @endif
                </div>

                <!-- Body -->
                <div class="card-body d-flex flex-column p-3">
                    <h6 class="card-title fw-bold text-dark mb-2 text-truncate">
                        {{ $event->name }}
                    </h6>

                    <div class="info mb-3">
                        <div class="info-item">
                            <i class="bi bi-calendar-event text-primary me-2"></i>
                            <span>{{ \Carbon\Carbon::parse($event->date)->format('d M Y') }}</span>
                        </div>
                        <div class="info-item">
                            <i class="bi bi-geo-alt text-danger me-2"></i>
                            <span class="text-truncate" style="max-width: 150px;">{{ $event->location }}</span>
                        </div>
                        <div class="info-item price">
                            <i class="bi bi-cash-coin text-success me-2"></i>
                            <span>Rp {{ number_format($event->price, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="mt-auto">
                        <a href="{{ route('shop.show', $event->id) }}" class="btn btn-detail w-100 mb-2">
                            <i class="bi bi-eye me-1"></i> Lihat Detail
                        </a>

                        @if($event->available_tickets > 0)
                        <form action="{{ route('tickets.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="event_id" value="{{ $event->id }}">
                            <button type="submit" class="btn btn-buy w-100">
                                <i class="bi bi-cart-fill me-1"></i> Beli Tiket
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <p class="text-muted fs-5">Tidak ada event yang tersedia saat ini.</p>
        </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $events->links() }}
    </div>
</div>

@push('styles')
<style>
    /* Card Styling */
    .event-card {
        border-radius: 16px;
        overflow: hidden;
        background: #fff;
        transition: all 0.4s ease;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
    }
    .event-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
    }

    /* Poster */
    .poster-wrapper {
        position: relative;
        overflow: hidden;
        height: 200px;
        background: #f5f5f5;
    }
    .poster-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }
    .event-card:hover .poster-img {
        transform: scale(1.08);
    }

    /* Badges */
    .badge-custom {
        position: absolute;
        top: 10px;
        left: 10px;
        padding: 6px 10px;
        font-size: 0.8rem;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }
    .sold-out-badge {
        position: absolute;
        bottom: 10px;
        right: 10px;
        padding: 6px 12px;
        font-size: 0.8rem;
        border-radius: 8px;
    }

    /* Text & Info */
    .card-title {
        font-size: 1.05rem;
        line-height: 1.4;
    }
    .info .info-item {
        display: flex;
        align-items: center;
        margin-bottom: 6px;
        font-size: 0.9rem;
        color: #555;
    }
    .info .price {
        font-weight: 600;
        font-size: 1rem;
        color: #28a745;
    }

    /* Buttons */
    .btn-detail {
        background: transparent;
        border: 2px solid #0d6efd;
        color: #0d6efd;
        border-radius: 10px;
        transition: all 0.3s ease;
        font-weight: 600;
    }
    .btn-detail:hover {
        background: linear-gradient(135deg, #0d6efd, #4a8cff);
        color: #fff;
        transform: scale(1.03);
    }

    .btn-buy {
        background: linear-gradient(135deg, #28a745, #36c060);
        color: #fff;
        border: none;
        transition: all 0.3s ease;
        border-radius: 10px;
        font-weight: 600;
    }
    .btn-buy:hover {
        background: linear-gradient(135deg, #36c060, #2ec45b);
        transform: scale(1.03);
    }

    /* Responsive */
    @media (max-width: 576px) {
        .poster-wrapper { height: 170px; }
        .card-title { font-size: 1rem; }
    }
</style>
@endpush
@endsection
