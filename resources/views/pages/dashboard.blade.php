@extends('layouts.app')

@section('title', 'Buy Tickets')

@section('content')
@php
use Illuminate\Support\Str;
@endphp

<div class="dashboard-container">
    @foreach($festivalData as $index => $event)
        @if($index == 0)
            <!-- ================= Event Pertama: Full Detail ================= -->
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

            <section class="festival-details">
                <div class="container">
                    <div class="details-grid">
                        <!-- Left Column -->
                        <div class="festival-info">
                            <div class="festival-header">
                                <h1 class="festival-title">{{ $event['title'] }}</h1>
                                <div class="festival-actions">
                                    <button class="btn-action"><i class="bi bi-heart"></i> Add to favorites</button>
                                    <button class="btn-action"><i class="bi bi-share"></i> Share this event</button>
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

                        <!-- Right Column -->
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
                                    <i class="bi bi-info-circle"></i> Minimal usia {{ $event['min_age'] ?? 17 }} tahun
                                </div>
                            </div>

                            <div class="sidebar-card">
                                <h2 class="card-title">Lokasi di peta</h2>
                                <div class="location-map">
                                    <iframe src="https://www.google.com/maps?q={{ urlencode($event['location']) }}&output=embed"
                                        width="100%" height="250" style="border:0; border-radius:10px;" allowfullscreen=""
                                        loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                                    </iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif
    @endforeach

    <!-- ================= Event Kedua dst: Card Horizontal Profesional ================= -->
    @if($festivalData->count() > 1)
    <section class="more-events-scroll my-4">
        <div class="container">
            <h2 class="section-title">Event Lainnya</h2>
            <div class="scroll-row">
                @foreach($festivalData->skip(1) as $event)
                    <div class="event-card-small">
                        <div class="event-card-image">
                            <img src="{{ asset('storage/' . $event['poster']) }}" alt="{{ $event['title'] }}">
                        </div>
                        <div class="event-card-info">
                            <h3>{{ $event['title'] }}</h3>
                            <p>{{ Str::limit($event['description'], 80) }}</p>
                            <a href="{{ route('shop.index', $event['id']) }}" class="btn btn-primary btn-block">
                                Lihat Event
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
</div>

<!-- ================= CSS Internal ================= -->
<style>
.more-events-scroll .scroll-row {
    display: flex;
    gap: 20px;
    overflow-x: auto;
    padding-bottom: 10px;
    scroll-snap-type: x mandatory;
}

.more-events-scroll .scroll-row::-webkit-scrollbar {
    height: 8px;
}

.more-events-scroll .scroll-row::-webkit-scrollbar-thumb {
    background-color: #888;
    border-radius: 4px;
}

.event-card-small {
    min-width: 250px;
    max-width: 250px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.15);
    flex-shrink: 0;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    scroll-snap-align: start;
    transition: transform 0.3s;
}

.event-card-small:hover {
    transform: translateY(-5px);
}

.event-card-small img {
    width: 100%;
    height: 150px;
    object-fit: cover;
}

.event-card-info {
    padding: 15px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    flex-grow: 1;
}

.event-card-info h3 {
    font-size: 16px;
    margin-bottom: 5px;
}

.event-card-info p {
    font-size: 14px;
    margin-bottom: 10px;
    color: #555;
}

.event-card-info .btn-block {
    margin-top: auto;
    width: 100%;
}
</style>
@endsection
