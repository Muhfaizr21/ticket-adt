@php
    use Illuminate\Support\Str;
@endphp
@extends('layouts.app')

@section('title', 'Shop - Event List')

@section('content')
    <div class="container my-5">
        <h1 class="mb-5 text-center fw-bold" style="font-size: 2rem; color: #1b1b1b;">
            🎟️ Daftar Event
        </h1>

        <div class="row g-4 justify-content-center">
            @forelse($events as $event)
                @php
                    $ticket = $event->ticketTypes->first();
                    $promo = $ticket && $ticket->promotions->first() ? $ticket->promotions->first() : null;
                    $finalPrice = $ticket ? $ticket->price : 0;

                    if ($promo) {
                        if ($promo->persen_diskon) {
                            $finalPrice -= ($finalPrice * $promo->persen_diskon / 100);
                        } elseif ($promo->value) {
                            $finalPrice -= $promo->value;
                        }
                    }
                @endphp

                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="event-card shadow-sm position-relative h-100">

                        {{-- Poster --}}
                        <div class="poster-wrapper">
                            <img src="{{ $event->poster ? asset('storage/' . $event->poster) : asset('images/default-poster.jpg') }}"
                                 alt="{{ $event->name }}">

                            @if(\Carbon\Carbon::parse($event->created_at)->gt(now()->subDays(7)))
                                <span class="badge bg-primary badge-top-left">🆕 Baru</span>
                            @endif
                            @if($event->available_tickets > 50)
                                <span class="badge bg-warning text-dark badge-top-right">🔥 Trending</span>
                            @endif
                            @if($event->available_tickets <= 0)
                                <span class="badge bg-danger badge-top-right" style="right: 80px;">Sold Out</span>
                            @endif
                        </div>

                        {{-- Body --}}
                        <div class="p-3 d-flex flex-column gap-1">
                            <h5 class="fw-bold text-truncate mb-1">{{ $event->name }}</h5>
                            <div class="text-muted small mb-2 d-flex flex-column gap-1">
                                <div>
                                    <i class="bi bi-calendar-event me-1"></i>
                                    {{ \Carbon\Carbon::parse($event->date)->format('d M Y') }}
                                </div>
                                <div>
                                    <i class="bi bi-geo-alt me-1"></i>
                                    {{ Str::limit($event->location, 25) }}
                                </div>
                            </div>

                            {{-- Harga tiket + promo --}}
                            <div class="mb-3 mt-1">
                                @if($ticket)
                                    <span class="fw-bold text-success fs-6">
                                        Rp {{ number_format($finalPrice, 0, ',', '.') }}
                                    </span>
                                    @if($promo)
                                        <span class="badge bg-success ms-1">{{ $promo->name }}</span>
                                    @endif
                                @else
                                    <span class="text-danger fw-semibold">Tiket belum tersedia</span>
                                @endif
                            </div>

                            {{-- Action Buttons --}}
                            <div class="mt-auto d-grid gap-2">
                                <a href="{{ route('shop.show', $event->id) }}" class="btn btn-primary btn-sm rounded-pill">
                                    <i class="bi bi-eye me-1"></i> Lihat Detail
                                </a>

                                @if($event->available_tickets > 0 && $ticket)
                                    <form action="{{ route('orders.store') }}" method="POST" class="d-grid">
                                        @csrf
                                        <input type="hidden" name="event_id" value="{{ $event->id }}">
                                        <input type="hidden" name="ticket_type_id" value="{{ $ticket->id }}">
                                        <button type="submit" class="btn btn-outline-success btn-sm rounded-pill">
                                            <i class="bi bi-cart-fill me-1"></i> Add to Cart
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-secondary btn-sm rounded-pill" disabled>
                                        <i class="bi bi-x-circle me-1"></i> Sold Out
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted fs-5">🚫 Tidak ada event tersedia saat ini.</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-center mt-5">
            {{ $events->links() }}
        </div>
    </div>
@endsection

@push('styles')
<style>
    body {
        background: #f5f6fa;
    }

    .event-card {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .event-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
    }

    .poster-wrapper {
        position: relative;
        height: 200px;
        overflow: hidden;
    }

    .poster-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .event-card:hover .poster-wrapper img {
        transform: scale(1.05);
    }

    .badge-top-left,
    .badge-top-right {
        position: absolute;
        top: 10px;
        font-size: 0.75rem;
        padding: 5px 8px;
        border-radius: 6px;
    }

    .badge-top-left { left: 10px; }
    .badge-top-right { right: 10px; }

    .card-body .btn {
        font-size: 0.85rem;
    }

    .btn-sm {
        padding: 6px 12px;
    }

    @media (max-width: 576px) {
        .poster-wrapper {
            height: 150px;
        }
        .event-card {
            border-radius: 12px;
        }
    }
</style>
@endpush
