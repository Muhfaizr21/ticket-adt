@php
    use Illuminate\Support\Str;
@endphp
@extends('layouts.app')

@section('title', 'Shop - Event List')

@section('content')
    <div class="container my-5">
        <h1 class="mb-4 text-center fw-bold" style="font-size: 2rem; color: #1b1b1b;">
            Daftar Event
        </h1>

        <div class="row g-3 justify-content-center">
            @forelse($events as $event)
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="card h-100 shadow-sm border-0 position-relative">

                        {{-- Poster --}}
                        <img src="{{ $event->poster ? asset('storage/' . $event->poster) : asset('images/default-poster.jpg') }}"
                            class="card-img-top" alt="{{ $event->name }}" style="height: 180px; object-fit: cover;">

                        {{-- Badges --}}
                        @if(\Carbon\Carbon::parse($event->created_at)->gt(now()->subDays(7)))
                            <span class="badge bg-primary badge-top-left">🆕 Baru</span>
                        @endif
                        @if($event->available_tickets > 50)
                            <span class="badge bg-warning text-dark badge-top-right">🔥 Trending</span>
                        @endif
                        @if($event->available_tickets <= 0)
                            <span class="badge bg-danger badge-bottom-right">Sold Out</span>
                        @endif

                        {{-- Body --}}
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-truncate">{{ $event->name }}</h5>
                            <p class="card-text mb-1">
                                <i class="bi bi-calendar-event me-1"></i>
                                {{ \Carbon\Carbon::parse($event->date)->format('d M Y') }}
                            </p>
                            <p class="card-text mb-2">
                                <i class="bi bi-geo-alt me-1"></i>
                                {{ Str::limit($event->location, 20) }}
                            </p>

                            {{-- Harga tiket + promo --}}
                            @php
                                $ticket = $event->ticketTypes->first(); // ambil ticket pertama
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

                            <p class="card-text fw-bold text-success mb-3">
                                Rp {{ number_format($finalPrice, 0, ',', '.') }}
                                @if($promo)
                                    <span class="badge bg-success ms-2">{{ $promo->name }}</span>
                                @endif
                            </p>

                            {{-- Action Buttons --}}
                            <div class="mt-auto d-grid gap-2">
                                <a href="{{ route('shop.show', $event->id) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-eye me-1"></i> Lihat Detail
                                </a>

                                @if($event->available_tickets > 0 && $ticket)
                                    <form action="{{ route('orders.store') }}" method="POST" class="d-grid">
                                        @csrf
                                        <input type="hidden" name="event_id" value="{{ $event->id }}">
                                        <input type="hidden" name="ticket_type_id" value="{{ $ticket->id }}">
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="bi bi-cart-fill me-1"></i> Add to Cart
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-danger btn-sm" disabled>
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
        <div class="d-flex justify-content-center mt-4">
            {{ $events->links() }}
        </div>
    </div>

    @push('styles')
        <style>
            body {
                background: #f7f8fa;
            }

            .card {
                border-radius: 12px;
                transition: all 0.3s ease;
            }

            .card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            }

            .badge-top-left {
                position: absolute;
                top: 10px;
                left: 10px;
                font-size: 0.7rem;
                padding: 4px 7px;
                border-radius: 6px;
            }

            .badge-top-right {
                position: absolute;
                top: 10px;
                right: 10px;
                font-size: 0.7rem;
                padding: 4px 7px;
                border-radius: 6px;
            }

            .badge-bottom-right {
                position: absolute;
                bottom: 10px;
                right: 10px;
                font-size: 0.7rem;
                padding: 4px 7px;
                border-radius: 6px;
            }

            .card-body .btn {
                font-size: 0.8rem;
                padding: 4px 6px;
            }

            @media (max-width: 576px) {
                .card img {
                    height: 150px;
                }
            }
        </style>
    @endpush
@endsection