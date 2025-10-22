@extends('layouts.app')
@php
    use Illuminate\Support\Str;
@endphp

@section('title', $event->name)

@section('content')
<!-- HERO BANNER -->
<section class="event-hero mx-auto">
    <div class="hero-container">
        <img src="{{ $event->poster ? asset('storage/' . $event->poster) : asset('images/default-poster.jpg') }}" 
             alt="{{ $event->name }}" class="hero-bg">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1 class="fw-bold mb-1">{{ $event->name }}</h1>
            <div class="d-flex flex-wrap gap-2 text-light fs-6 justify-content-center">
                <div><i class="bi bi-calendar-event me-1"></i>{{ $event->date ? \Carbon\Carbon::parse($event->date)->format('d M Y') : '-' }}</div>
                <div><i class="bi bi-clock me-1"></i>{{ $event->start_time ?? '-' }} - {{ $event->end_time ?? '-' }}</div>
                <div><i class="bi bi-geo-alt me-1"></i>{{ $event->location ?? '-' }}</div>
            </div>
        </div>
    </div>
</section>

<!-- EVENT DETAIL + TICKET -->
<div class="container py-4 px-3 px-md-4">
    <div class="row g-3">
        <!-- Deskripsi Event -->
        <div class="col-lg-8">
            <div class="card event-detail-card p-3 rounded-3 shadow-sm">
                <h4 class="fw-bold mb-2 text-dark">Tentang Event</h4>
                <p class="text-secondary fs-6" style="line-height:1.6;">{{ $event->description ?? 'Deskripsi tidak tersedia.' }}</p>
                <div class="d-flex align-items-center text-muted small mt-2">
                    <i class="bi bi-building me-1"></i>Venue ID: {{ $event->venue_id ?? '-' }}
                </div>
            </div>
        </div>

        <!-- Tiket -->
        <div class="col-lg-4">
            <div class="card ticket-section-card p-3 rounded-3 shadow-sm sticky-top">
                <h5 class="fw-bold mb-3 text-dark">🎟️ Pilih Tiketmu</h5>
                @forelse($event->ticketTypes as $ticket)
                    @php
                        $promo = $ticket->promotions->first() ?? null;
                        $finalPrice = $ticket->price;
                        if ($promo) {
                            if ($promo->persen_diskon) $finalPrice -= ($finalPrice * $promo->persen_diskon / 100);
                            elseif ($promo->value) $finalPrice -= $promo->value;
                        }
                    @endphp
                    <div class="ticket-card mb-2 p-2 rounded-2 {{ $ticket->available_tickets <= 0 ? 'sold-out' : '' }}">
                        @if($promo)<div class="promo-tag"><i class="bi bi-star-fill me-1"></i> Promo</div>@endif
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="fw-semibold mb-1 text-dark small">{{ $ticket->name }}</h6>
                                @if($promo)
                                    <small class="text-muted"><i class="bi bi-tag-fill text-success me-1"></i>{{ $promo->name }}
                                        @if($promo->persen_diskon) ({{ $promo->persen_diskon }}%) 
                                        @elseif($promo->value) (Rp {{ number_format($promo->value,0,',','.') }}) 
                                        @endif
                                    </small>
                                @endif
                            </div>
                            <div class="text-end"><span class="fw-bold text-success fs-7">Rp {{ number_format($finalPrice,0,',','.') }}</span></div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <small class="text-muted">Tersedia: {{ $ticket->available_tickets }} tiket</small>
                            @if($ticket->available_tickets > 0)
                                <form action="{{ route('orders.create', $event->id) }}" method="GET" class="d-flex gap-1 align-items-center">
                                    <input type="hidden" name="ticket_type_id" value="{{ $ticket->id }}">
                                    <input type="number" name="quantity" min="1" max="{{ $ticket->available_tickets }}" value="1" class="ticket-qty small-input">
                                    <button type="submit" class="btn btn-success btn-sm rounded-pill px-2 py-0"><i class="bi bi-cart-fill"></i> Beli</button>
                                </form>
                            @else
                                <span class="sold-out-badge small">Sold Out</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-muted mb-1 small">Belum ada tiket tersedia.</p>
                @endforelse
                <a href="{{ route('shop.index') }}" class="btn btn-outline-secondary w-100 mt-2 py-1 small"><i class="bi bi-arrow-left me-1"></i> Kembali ke Shop</a>
            </div>
        </div>
    </div>

    <!-- EVENT SERUPA -->
    @if($relatedEvents && $relatedEvents->count())
    <div class="more-events-wrapper mt-4">
        <h4 class="fw-bold mb-3 text-dark text-center small">🎫 Event Serupa</h4>
        <div class="more-events-grid">
            @foreach($relatedEvents as $relEvent)
                @php
                    $ticket = $relEvent->ticketTypes->sortBy('price')->first() ?? null;
                    $finalPrice = $ticket ? $ticket->price : 0;
                    $promo = $ticket ? $ticket->promotions->first() : null;
                    $hasPromo = (bool) $promo;
                    $originalPrice = $hasPromo ? ($ticket->price + $promo->value) : $finalPrice;
                @endphp

                <a href="{{ route('shop.show', $relEvent->id) }}" class="event-link">
                    <div class="event-card-mini">
                        <div class="card-photo">
                            <img src="{{ $relEvent->poster ? asset('storage/' . $relEvent->poster) : asset('images/default-poster.jpg') }}" 
                                 alt="{{ $relEvent->name }}">
                            @if($relEvent->available_tickets <= 0)
                                <span class="badge bg-danger position-absolute top-0 end-0 m-1 fs-7">Sold Out</span>
                            @endif
                            @if($hasPromo)
                                <span class="badge bg-success position-absolute top-0 start-0 m-1 fs-7">{{ $promo->name }}</span>
                            @endif
                        </div>
                        <div class="card-content d-flex flex-column p-1">
                            <h6 class="fw-bold mb-1 small">{{ Str::limit($relEvent->name, 20) }}</h6>
                            <div class="price-section mb-1">
                                @if($hasPromo)
                                    <span class="original-price me-1">Rp {{ number_format($originalPrice, 0, ',', '.') }}</span>
                                    <span class="final-price text-primary fw-bold">Rp {{ number_format($finalPrice, 0, ',', '.') }}</span>
                                @else
                                    <span class="final-price text-primary fw-bold">Rp {{ number_format($finalPrice, 0, ',', '.') }}</span>
                                @endif
                            </div>
                            <p class="text-muted small mb-1 flex-grow-1">
                                <i class="bi bi-calendar-event me-1"></i> {{ \Carbon\Carbon::parse($relEvent->date)->format('d M Y') }}<br>
                                <i class="bi bi-geo-alt me-1"></i> {{ Str::limit($relEvent->location, 20) }}
                            </p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    @endif
</div>

@push('styles')
<style>
.container { max-width: 1200px; }

/* HERO */
.event-hero { position: relative; margin: 20px auto; max-width: calc(100% - 40px); border-radius: 12px; overflow: hidden; }
.hero-container { position: relative; width: 100%; height: 320px; border-radius:12px; overflow:hidden; }
.hero-bg { width:100%; height:100%; object-fit:cover; filter:brightness(60%); transition: transform 0.3s ease; border-radius:12px; }
.event-hero:hover .hero-bg { transform: scale(1.03); }
.hero-overlay { position:absolute; inset:0; background:linear-gradient(to top, rgba(0,0,0,0.7), transparent); border-radius:12px; }
.hero-content { position:absolute; bottom:10px; left:50%; transform:translateX(-50%); text-align:center; color:#fff; padding:0 15px; max-width: calc(100% - 40px); }

/* EVENT SERUPA */
.more-events-wrapper { margin-top:30px; margin-bottom:30px; }
.more-events-grid { display:grid; grid-template-columns:repeat(auto-fit, minmax(180px,1fr)); gap:12px; }

.event-link { text-decoration:none; color:inherit; display:block; }

.event-card-mini {
    display:flex;
    flex-direction:column;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    font-size:0.85rem;
    border-radius:10px;
    background-color:#fff;
    box-shadow:0 2px 10px rgba(0,0,0,0.08);
    overflow:hidden;

    /* border  */
    border:2px solid transparent;
    background-image:linear-gradient(#fff,#fff),linear-gradient(135deg,#000000,#c0c0c0,#1c3b63);
    background-origin:border-box;
    background-clip:content-box,border-box;
}

.event-card-mini:hover {
    transform:translateY(-3px);
    box-shadow:0 8px 20px rgba(255,215,0,0.3);
    background-image:linear-gradient(#fff,#fff),linear-gradient(135deg,#000000,#FFD700,#FFB300);
}

.card-photo { height:130px; overflow:hidden; position:relative; }
.card-photo img { width:100%; height:100%; object-fit:cover; transition:transform 0.3s ease; }
.event-card-mini:hover img { transform:scale(1.05); }

.card-content { padding:8px; }
.price-section { font-size:0.8rem; }
.original-price { text-decoration:line-through; color:#888; font-size:0.75rem; }
.final-price { font-size:0.85rem; font-weight:bold; color:#03346E; }

/* RESPONSIVE */
@media (max-width:992px){ .ticket-section-card{ position:relative!important; top:0!important; margin-bottom:20px; } }
@media (max-width:768px){ .event-hero{ height:200px; } .hero-content h1{ font-size:1.4rem; } }
</style>
@endpush
