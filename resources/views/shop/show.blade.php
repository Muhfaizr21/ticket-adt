@extends('layouts.app')

@section('title', $event->name)

@section('content')
    <!-- ============================= -->
    <!-- HERO BANNER -->
    <!-- ============================= -->
    <section class="event-hero mb-5">
        <img src="{{ $event->poster ? asset('storage/' . $event->poster) : asset('images/default-poster.jpg') }}" alt="{{ $event->name }}" class="hero-bg">
        <div class="hero-overlay"></div>
        <div class="hero-content container">
            <h1 class="fw-bold mb-3">{{ $event->name }}</h1>
            <div class="d-flex flex-wrap gap-4 text-light fs-6 justify-content-center">
                <div><i class="bi bi-calendar-event me-2"></i>{{ \Carbon\Carbon::parse($event->date)->format('d M Y') }}</div>
                <div><i class="bi bi-clock me-2"></i>{{ $event->start_time ?? '-' }} - {{ $event->end_time ?? '-' }}</div>
                <div><i class="bi bi-geo-alt me-2"></i>{{ $event->location ?? '-' }}</div>
            </div>
        </div>
    </section>

    <!-- ============================= -->
    <!-- EVENT DETAIL + TICKET SECTION -->
    <!-- ============================= -->
    <div class="container mb-5">
        <div class="row g-4">
            <!-- Deskripsi Event -->
            <div class="col-lg-8">
                <div class="card event-detail-card border-0 shadow-sm p-4 rounded-4 mb-4">
                    <h4 class="fw-bold mb-3 text-dark">Tentang Event</h4>
                    <p class="text-secondary fs-6" style="line-height: 1.8;">
                        {{ $event->description ?? 'Deskripsi tidak tersedia.' }}
                    </p>
                    <div class="mt-4 d-flex align-items-center text-muted">
                        <i class="bi bi-building me-2"></i>
                        <span>Venue ID: {{ $event->venue_id ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Tiket -->
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 p-4 rounded-4 sticky-top ticket-section-card" style="top: 100px;">
                    <h5 class="fw-bold mb-4 text-dark">🎟️ Pilih Tiketmu</h5>

                    @forelse($event->ticketTypes as $ticket)
                        @php
                            $promo = $ticket->promotions->first() ?? null;
                            $finalPrice = $ticket->price;
                            if ($promo) {
                                if ($promo->persen_diskon) {
                                    $finalPrice -= ($finalPrice * $promo->persen_diskon / 100);
                                } elseif ($promo->value) {
                                    $finalPrice -= $promo->value;
                                }
                            }
                        @endphp

                        <div class="ticket-card mb-3 p-3 rounded-3 position-relative {{ $ticket->available_tickets <= 0 ? 'sold-out' : '' }}">
                            @if($promo)
                                <div class="promo-tag"><i class="bi bi-star-fill me-1"></i> Promo</div>
                            @endif

                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="fw-semibold mb-1 text-dark">{{ $ticket->name }}</h6>
                                    @if($promo)
                                        <small class="text-muted">
                                            <i class="bi bi-tag-fill text-success me-1"></i>{{ $promo->name }}
                                            @if($promo->persen_diskon)
                                                ({{ $promo->persen_diskon }}%)
                                            @elseif($promo->value)
                                                (Rp {{ number_format($promo->value, 0, ',', '.') }})
                                            @endif
                                        </small>
                                    @endif
                                </div>
                                <div class="text-end">
                                    <span class="fw-bold text-success fs-6">Rp {{ number_format($finalPrice, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <small class="text-muted">Tersedia: {{ $ticket->available_tickets }} tiket</small>
                                @if($ticket->available_tickets > 0)
                                    <form action="{{ route('orders.store') }}" method="POST" class="d-flex gap-2 align-items-center">
                                        @csrf
                                        <input type="hidden" name="event_id" value="{{ $event->id }}">
                                        <input type="hidden" name="ticket_type_id" value="{{ $ticket->id }}">
                                        <input type="hidden" name="payment_method" value="Manual Transfer"> <!-- ✅ fix -->
                                        <input type="number" name="quantity" min="1" max="{{ $ticket->available_tickets }}" value="1" class="ticket-qty">
                                        <button type="submit" class="btn btn-success btn-sm rounded-pill px-3">
                                            <i class="bi bi-cart-fill"></i> Beli
                                        </button>
                                    </form>
                                @else
                                    <span class="sold-out-badge">Sold Out</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">Belum ada tiket tersedia.</p>
                    @endforelse

                    <a href="{{ route('shop.index') }}" class="btn btn-outline-secondary w-100 mt-3">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Shop
                    </a>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        /* =============================
           HERO SECTION
        ============================= */
        .event-hero {
            position: relative;
            height: 400px;
            border-radius: 0 0 20px 20px;
            overflow: hidden;
        }
        .hero-bg {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: brightness(60%);
            transition: transform 0.4s ease;
        }
        .event-hero:hover .hero-bg {
            transform: scale(1.05);
        }
        .hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.8), transparent);
        }
        .hero-content {
            position: absolute;
            bottom: 40px;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
            color: #fff;
        }

        /* =============================
           EVENT DETAIL CARD
        ============================= */
        .event-detail-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }

        /* =============================
           TICKET CARD
        ============================= */
        .ticket-section-card {
            background: #fff;
            border-radius: 16px;
        }
        .ticket-card {
            background: #ffffff;
            border: 1px solid #eee;
            transition: all 0.3s ease;
        }
        .ticket-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }
        .promo-tag {
            position: absolute;
            top: -12px;
            right: -12px;
            background: #28a745;
            color: #fff;
            font-size: 0.8rem;
            padding: 6px 12px;
            border-radius: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        .ticket-qty {
            width: 60px;
            text-align: center;
            border-radius: 8px;
            border: 1px solid #ccc;
            padding: 4px;
        }
        .sold-out-badge {
            background: #dc3545;
            color: #fff;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }
        .ticket-card.sold-out {
            opacity: 0.7;
        }

        /* BUTTON */
        .btn-success {
            background: linear-gradient(135deg, #28a745, #36c060);
            border: none;
            font-weight: 600;
            transition: transform 0.2s ease;
        }
        .btn-success:hover {
            background: linear-gradient(135deg, #36c060, #2ec45b);
            transform: scale(1.05);
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .event-hero { height: 260px; }
            .hero-content h1 { font-size: 1.6rem; }
        }
    </style>
    @endpush
@endsection
