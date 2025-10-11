@extends('layouts.app')

@section('title', 'Detail Event')

@section('content')
    <div class="container my-5">
        <div class="row g-4">
            <!-- Kolom kiri: Detail Event -->
            <div class="col-md-7">
                <div class="card border-0 shadow-sm overflow-hidden">
                    <div class="poster-container">
                        <img src="{{ $event->poster ? asset('storage/' . $event->poster) : asset('images/default-poster.jpg') }}"
                            class="poster-img" alt="{{ $event->name }}">
                    </div>
                    <div class="card-body p-4">
                        <h1 class="fw-bold mb-3 text-dark">{{ $event->name }}</h1>

                        <div class="d-flex flex-column gap-2 mb-3 text-muted">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-calendar-event text-primary me-2 fs-5"></i>
                                <span>{{ \Carbon\Carbon::parse($event->date)->format('d M Y') }}</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-clock text-info me-2 fs-5"></i>
                                <span>{{ $event->start_time ?? '-' }} - {{ $event->end_time ?? '-' }}</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-geo-alt text-danger me-2 fs-5"></i>
                                <span>{{ $event->location ?? '-' }}</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-building text-secondary me-2 fs-5"></i>
                                <span>Venue ID: {{ $event->venue_id ?? '-' }}</span>
                            </div>
                        </div>

                        <hr>

                        <p class="text-secondary" style="line-height: 1.7;">
                            {{ $event->description ?? 'Deskripsi tidak tersedia.' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Kolom kanan: Pembelian Tiket -->
            <div class="col-md-5">
                <div class="card shadow-sm border-0 p-4 sticky-top" style="top: 100px;">
                    <h5 class="fw-bold mb-4 text-dark">🎟️ Informasi Tiket</h5>

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

                        <div class="ticket-card mb-4 p-3 border rounded-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="fw-semibold">{{ $ticket->name }}</span>
                                <span class="text-success fw-bold">Rp {{ number_format($finalPrice, 0, ',', '.') }}</span>
                            </div>

                            @if($promo)
                                <div class="mb-2">
                                    <span class="badge bg-success">
                                        Promo: {{ $promo->name }}
                                        @if($promo->persen_diskon)
                                            ({{ $promo->persen_diskon }}%)
                                        @elseif($promo->value)
                                            (Rp {{ number_format($promo->value, 0, ',', '.') }})
                                        @endif
                                    </span>
                                </div>
                            @endif

                            <div class="d-flex justify-content-between align-items-center">
                                <span>Tersedia: {{ $ticket->available_tickets }} tiket</span>
                                @if($ticket->available_tickets > 0)
                                    <div class="d-flex gap-2 align-items-center">
                                        <form action="{{ route('orders.store') }}" method="POST"
                                            class="d-flex gap-2 align-items-center">
                                            @csrf
                                            <input type="hidden" name="event_id" value="{{ $event->id }}">
                                            <input type="hidden" name="ticket_type_id" value="{{ $ticket->id }}">
                                            <input type="number" name="quantity" min="1" max="{{ $ticket->available_tickets }}"
                                                value="1" class="form-control form-control-sm" style="width: 70px;">
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="bi bi-cart-fill"></i> Beli
                                            </button>
                                        </form>

                                        <a href="{{ route('orders.create', $event->id) }}" class="btn btn-primary btn-sm">
                                            <i class="bi bi-bag-check"></i> Order Sekarang
                                        </a>
                                    </div>
                                @else
                                    <span class="badge bg-danger">Sold Out</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">Tidak ada tipe tiket tersedia.</p>
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

            /* Ticket card */
            .ticket-card {
                background: #f9f9f9;
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
                padding: .4rem .6rem;
            }
        </style>
    @endpush
@endsection