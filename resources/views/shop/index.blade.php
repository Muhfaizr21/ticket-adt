@extends('layouts.app')
@php
    use Illuminate\Support\Str;
@endphp

@section('title', 'Shop - Daftar Event')

@section('content')
<div class="main-container pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">
        
        {{-- === WRAPPER UTAMA DENGAN MARGIN SAMPING === --}}
        <div class="event-wrapper">
            <div class="page-header text-center mb-5 mt-4">
                <h4 class="fw-bold text-dark">🎟️ Daftar Event</h4>
            </div>

            {{-- === GRID EVENT === --}}
            <div class="event-grid">
                @forelse($events as $event)
                    @php
                        $ticket = $event->ticketTypes->sortBy('price')->first() ?? null;
                        $finalPrice = $ticket ? $ticket->price : 0;
                        $promotion = $ticket ? $ticket->promotions->first() : null;
                        $hasPromo = (bool) $promotion;
                        $originalPrice = $hasPromo ? ($ticket->price + $promotion->value) : $finalPrice;
                    @endphp

                    <div class="event-card-mini">
                        <a href="{{ route('shop.show', $event->id) }}" class="event-link">
                            <div class="card-photo">
                                <img src="{{ $event->poster ? asset('storage/' . $event->poster) : asset('images/default-poster.jpg') }}" 
                                     alt="{{ $event->name }}">
                                @if($event->available_tickets <= 0)
                                    <span class="badge bg-danger position-absolute top-0 end-0 m-1 fs-6">Sold Out</span>
                                @endif
                                @if($hasPromo)
                                    <span class="badge bg-success position-absolute top-0 start-0 m-1 fs-6">{{ $promotion->name }}</span>
                                @endif
                            </div>
                            <div class="card-content d-flex flex-column p-2">
                                <h6 class="fw-bold mb-1">{{ Str::limit($event->name, 20) }}</h6>
                                <div class="price-section mb-1">
                                    @if($hasPromo)
                                        <span class="original-price me-1">Rp {{ number_format($originalPrice, 0, ',', '.') }}</span>
                                        <span class="final-price text-primary fw-bold">Rp {{ number_format($finalPrice, 0, ',', '.') }}</span>
                                    @else
                                        <span class="final-price text-primary fw-bold">Rp {{ number_format($finalPrice, 0, ',', '.') }}</span>
                                    @endif
                                </div>
                                <p class="text-muted small mb-0">
                                    <i class="bi bi-calendar-event me-1"></i> {{ \Carbon\Carbon::parse($event->date)->format('d M Y') }}<br>
                                    <i class="bi bi-geo-alt me-1"></i> {{ Str::limit($event->location, 20) }}
                                </p>
                            </div>
                        </a>
                    </div>
                @empty
                    <p class="text-muted fs-6 text-center mt-5">🚫 Tidak ada event tersedia.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
/* ======== WRAPPER UTAMA ======== */
.event-wrapper {
    max-width: 100%;
    padding-left: 6.5cm;
    padding-right: 6.5cm;
    margin-top: 30px;
    margin-bottom: 60px;
}

/* ======== GRID EVENT ======== */
.event-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
    gap: 25px;
}

/* ======== LINK CARD ======== */
.event-link {
    color: inherit;
    text-decoration: none;
    display: block;
}

/* ======== KARTU EVENT ======== */
.event-card-mini {
    border-radius: 12px;
    overflow: hidden;
    background-color: #fff;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    display: flex;
    flex-direction: column;
    transition: transform 0.3s ease, box-shadow 0.3s ease, border 0.3s ease;
    
    /* Tambahan border gold elegan */ border: 2px solid transparent; background-image: linear-gradient(#fff, #fff), linear-gradient(135deg, #274b74, #d4d4d4, #000000); background-origin: border-box; background-clip: content-box, border-box; } .event-card-mini:hover { transform: translateY(-4px); box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15); border: 2px solid transparent; background-image: linear-gradient(#fff, #fff), linear-gradient(135deg, #000000, #ffffff, #006eff); }

/* ======== GAMBAR EVENT ======== */
.card-photo {
    height: 150px;
    overflow: hidden;
    position: relative;
}

.card-photo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.event-card-mini:hover img {
    transform: scale(1.08);
}

/* ======== KONTEN KARTU ======== */
.card-content {
    padding: 12px;
}

.price-section {
    font-size: 0.9rem;
}

.original-price {
    text-decoration: line-through;
    color: #888;
    font-size: 0.85rem;
}

.final-price {
    font-size: 1rem;
    font-weight: bold;
    color: #03346E;
}

/* ======== RESPONSIVE ======== */
@media (max-width: 1400px) {
    .event-wrapper {
        padding-left: 4cm;
        padding-right: 4cm;
    }
}

@media (max-width: 992px) {
    .event-wrapper {
        padding-left: 2cm;
        padding-right: 2cm;
    }

    .event-grid {
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    }
}

@media (max-width: 768px) {
    .event-wrapper {
        padding-left: 1cm;
        padding-right: 1cm;
    }

    .event-grid {
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    }

    .card-photo {
        height: 120px;
    }

    .final-price {
        font-size: 0.9rem;
    }
}
</style>
@endpush
