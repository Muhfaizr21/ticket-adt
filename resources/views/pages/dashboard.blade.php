@extends('layouts.app')

@section('title', 'Buy Tickets')

@push('styles')
<style>
    body {
        background: #f8f9fa;
        font-family: 'Poppins', sans-serif;
    }

    .dashboard-container {
        padding: 40px 0;
    }

    /* ===========================
       IMAGE GALLERY
    =========================== */
    .image-gallery .container {
        max-width: 1200px;
        margin: 0 auto 40px auto;
        padding: 0 20px;
    }
    .gallery-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
    }
    .main-image img {
        width: 100%;
        border-radius: 15px;
        object-fit: cover;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .side-images {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .small-image img {
        width: 100%;
        border-radius: 10px;
        object-fit: cover;
        box-shadow: 0 4px 8px rgba(0,0,0,0.08);
        transition: 0.3s;
    }
    .small-image img:hover {
        transform: scale(1.05);
    }

    /* ===========================
       FESTIVAL DETAILS
    =========================== */
    .festival-details .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }
    .details-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 30px;
    }
    .festival-info {
        background: #fff;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    .festival-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 15px;
    }
    .festival-actions {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
    }
    .btn-action {
        background: #f1f1f1;
        border: none;
        padding: 8px 15px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 5px;
        transition: 0.3s;
    }
    .btn-action:hover {
        background: #e9ecef;
    }

    .rating {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.9rem;
    }
    .stars i {
        color: #ffc107;
    }

    /* ===========================
       INFO SECTIONS
    =========================== */
    .info-section {
        margin-top: 25px;
    }
    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    .info-item {
        display: flex;
        flex-direction: column;
        background: #f8f9fa;
        padding: 15px;
        border-radius: 10px;
    }
    .info-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 8px;
    }
    .info-icon i {
        font-size: 1.5rem;
        color: #0d6efd;
    }
    .info-title {
        font-size: 1rem;
        font-weight: 600;
    }
    .section-title {
        font-size: 1.4rem;
        margin-bottom: 10px;
        font-weight: 600;
    }

    /* ===========================
       SIDEBAR
    =========================== */
    .festival-sidebar {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    .sidebar-card {
        background: #fff;
        padding: 20px;
        border-radius: 15px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    .card-title {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 15px;
    }
    .ticket-option {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }
    .ticket-info h4 {
        font-size: 1rem;
        font-weight: 600;
    }
    .ticket-price .price {
        font-weight: 700;
        color: #0d6efd;
    }
    .ticket-price .original {
        text-decoration: line-through;
        color: #999;
        font-size: 0.85rem;
        margin-left: 5px;
    }
    .promo-badge {
        display: inline-block;
        background: #d1e7dd;
        color: #0f5132;
        font-size: 0.8rem;
        padding: 2px 6px;
        border-radius: 6px;
        margin-top: 4px;
    }

    /* ===========================
       SIMILAR EVENTS
    =========================== */
    .more-events-section {
        margin-top: 50px;
    }
    .events-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
    }
    .event-card {
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        overflow: hidden;
        transition: 0.3s;
    }
    .event-card:hover {
        transform: translateY(-5px);
    }
    .event-card-header {
        position: relative;
    }
    .event-price-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        background: #0d6efd;
        color: #fff;
        padding: 5px 10px;
        border-radius: 8px;
        font-size: 0.85rem;
    }
    .event-image img {
        width: 100%;
        height: 180px;
        object-fit: cover;
    }
    .event-content {
        padding: 15px;
    }
    .event-title {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 5px;
    }
    .event-description-short {
        font-size: 0.9rem;
        color: #555;
        margin-bottom: 10px;
    }
    .btn-buy-ticket {
        width: 100%;
        margin-top: 10px;
    }

    @media (max-width: 768px) {
        .gallery-grid,
        .details-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
    <div class="dashboard-container">

        @foreach($festivalData as $event)
            <!-- Image Gallery Section -->
            <section class="image-gallery">
                <div class="container">
                    <div class="gallery-grid">
                        <div class="main-image">
                            <img src="{{ asset('storage/' . $event['poster']) }}" alt="{{ $event['title'] }}">
                        </div>
                        <div class="side-images">
                            @if(!empty($event['side_images']))
                                @foreach($event['side_images'] as $side)
                                    <div class="small-image">
                                        <img src="{{ asset('storage/' . $side) }}" alt="Side image">
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </section>

            <!-- Festival Details Section -->
            <section class="festival-details">
                <div class="container">
                    <div class="details-grid">

                        <!-- Left Column - Festival Info -->
                        <div class="festival-info">
                            <div class="festival-header">
                                <h1 class="festival-title">{{ $event['title'] }}</h1>
                                <div class="festival-actions">
                                    <button class="btn-action">
                                        <i class="bi bi-heart"></i>
                                        Add to favorites
                                    </button>
                                    <button class="btn-action">
                                        <i class="bi bi-share"></i>
                                        Share this event
                                    </button>
                                    <div class="rating">
                                        <div class="stars">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= floor($event['rating'] ?? 4.5))
                                                    <i class="bi bi-star-fill"></i>
                                                @elseif($i - 0.5 <= ($event['rating'] ?? 4.5))
                                                    <i class="bi bi-star-half"></i>
                                                @else
                                                    <i class="bi bi-star"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="rating-text">{{ $event['rating'] ?? 4.5 }} Ratings</span>
                                    </div>
                                </div>
                            </div>

                            <div class="info-section">
                                <div class="info-grid">
                                    <div class="info-item">
                                        <div class="info-header">
                                            <div class="info-icon"><i class="bi bi-calendar-event"></i></div>
                                            <h3 class="info-title">When</h3>
                                        </div>
                                        <div class="info-content">
                                            <div class="info-date">{{ $event['date'] }}</div>
                                            <div class="info-time">{{ $event['time'] }}</div>
                                            <div class="info-timezone">(WIB)</div>
                                        </div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-header">
                                            <div class="info-icon"><i class="bi bi-geo-alt"></i></div>
                                            <h3 class="info-title">Where</h3>
                                        </div>
                                        <div class="info-content">
                                            <div class="info-location">{{ $event['location'] }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="info-section">
                                <h2 class="section-title">About this event</h2>
                                <p>{{ $event['description'] }}</p>
                            </div>
                        </div>

                        <!-- Right Column - Ticket & Location -->
                        <div class="festival-sidebar">
                            <div class="sidebar-card">
                                <h2 class="card-title">Harga Tiket</h2>
                                <div class="ticket-options">
                                    @foreach ($event['tickets'] as $ticket)
                                        <div class="ticket-option">
                                            <div class="ticket-info">
                                                <h4 class="ticket-type">{{ $ticket['type'] }}</h4>
                                                <p class="ticket-desc">{{ $ticket['available'] }}</p>
                                            </div>
                                            <div class="ticket-price">
                                                @if($ticket['has_promo'])
                                                    <span class="price discounted">
                                                        Rp {{ number_format((float) ($ticket['final_price'] ?? 0), 0, ',', '.') }}
                                                    </span>
                                                    <span class="original">
                                                        Rp {{ number_format((float) ($ticket['original_price'] ?? 0), 0, ',', '.') }}
                                                    </span>
                                                    <span class="promo-badge">Promo: {{ $ticket['promo_name'] }}</span>
                                                    <small class="promo-end">s.d {{ $ticket['promo_end'] }}</small>
                                                @else
                                                    <span class="price">
                                                        Rp {{ number_format((float) ($ticket['final_price'] ?? 0), 0, ',', '.') }}
                                                    </span>
                                                @endif

                                                <a href="{{ route('orders.create', ['event' => $event['id'], 'ticket_type' => $ticket['id']]) }}"
                                                    class="btn btn-primary btn-ticket mt-2">
                                                    Pilih
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="age-restriction">
                                    <i class="bi bi-info-circle"></i>
                                    Minimal usia {{ $event['min_age'] ?? 17 }} tahun
                                </div>
                            </div>

                            <div class="sidebar-card">
                                <h2 class="card-title">Lokasi di peta</h2>
                                <div class="location-map">
                                    <iframe src="https://www.google.com/maps?q={{ urlencode($event['location']) }}&output=embed"
                                        width="100%" height="250" style="border:0; border-radius:10px;" allowfullscreen=""
                                        loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- More Events Like This -->
                    @if(!empty($event['similar_events']))
                        <div class="more-events-section">
                            <div class="more-events-header">
                                <h2 class="section-title">Event serupa lainnya</h2>
                            </div>
                            <div class="events-grid">
                                @foreach ($event['similar_events'] as $sim)
                                    <div class="event-card">
                                        <div class="event-card-header">
                                            <div class="event-price-badge">
                                                <span>From</span> Rp {{ $sim['price'] ?? '450' }}k
                                            </div>
                                            <div class="event-image">
                                                <img src="{{ asset('storage/' . $sim['image']) }}" alt="{{ $sim['title'] }}">
                                            </div>
                                        </div>
                                        <div class="event-content">
                                            <h3 class="event-title">{{ $sim['title'] }}</h3>
                                            <p class="event-description-short">{{ $sim['description'] }}</p>
                                            <div class="event-time">
                                                <i class="bi bi-clock"></i>
                                                <span>{{ $sim['time'] }}</span>
                                            </div>
                                            <a href="{{ route('tickets.show', $sim['id']) }}"
                                                class="btn btn-outline-primary btn-buy-ticket">
                                                <i class="bi bi-ticket-perforated"></i> Beli Tiket
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>
            </section>
        @endforeach

    </div>
@endsection

