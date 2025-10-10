@extends('layouts.app')

@section('title', 'Buy Tickets')

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

                            <!-- When & Where Section -->
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

                            <!-- About This Event -->
                            <div class="info-section">
                                <h2 class="section-title">About this event</h2>
                                <p>{{ $event['description'] }}</p>
                            </div>
                        </div>

                        <!-- Right Column - Ticket & Location -->
                        <div class="festival-sidebar">
                            <!-- Ticket Price Card -->
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
                                                    <span class="price discounted">{{ $ticket['final_price'] }}</span>
                                                    <span class="original">{{ $ticket['original_price'] }}</span>
                                                    <span class="promo-badge">Promo: {{ $ticket['promo_name'] }}</span>
                                                    <small class="promo-end">s.d {{ $ticket['promo_end'] }}</small>
                                                @else
                                                    <span class="price">{{ $ticket['final_price'] }}</span>
                                                @endif
                                                <button class="btn-ticket">Pilih</button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="age-restriction">
                                    <i class="bi bi-info-circle"></i>
                                    Minimal usia {{ $event['min_age'] ?? 17 }} tahun
                                </div>
                            </div>

                            <!-- Location Card -->
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

                    <!-- More Events Like This -->
                    @if(!empty($event['similar_events']))
                        <div class="more-events-section">
                            <div class="more-events-header">
                                <h2 class="section-title">Event serupa lainnya</h2>
                                <div class="events-filters">
                                    <select class="events-filter-select">
                                        <option>Hari kerja ▼</option>
                                        <option>Weekend</option>
                                    </select>
                                    <select class="events-filter-select">
                                        <option>Jenis event ▼</option>
                                        <option>Festival</option>
                                        <option>Konser</option>
                                    </select>
                                    <select class="events-filter-select">
                                        <option>Semua kategori ▼</option>
                                        <option>Musik</option>
                                        <option>Seni</option>
                                    </select>
                                </div>
                            </div>
                            <div class="events-grid">
                                @foreach ($event['similar_events'] as $sim)
                                    <div class="event-card">
                                        <div class="event-card-header">
                                            <div class="event-price-badge">
                                                <span>From</span> Rp {{ $sim['price'] ?? '450' }}k
                                            </div>
                                            <div class="event-actions">
                                                <div class="event-plus">+</div>
                                                <div class="event-favorite">
                                                    <i class="bi bi-heart"></i>
                                                </div>
                                            </div>
                                            <div class="event-image">
                                                <img src="{{ asset('storage/' . $sim['image']) }}" alt="{{ $sim['title'] }}">
                                            </div>
                                        </div>
                                        <div class="event-content">
                                            <div class="event-from">
                                                <span>From</span>
                                                <span>ID</span>
                                            </div>
                                            <div class="event-date">
                                                @php
                                                    $dateNumber = explode(' ', $sim['date'])[0];
                                                @endphp
                                                <span class="date-badge">{{ $dateNumber }}</span>
                                                <span class="date-text">{{ $sim['date'] }}</span>
                                            </div>
                                            <h3 class="event-title">{{ $sim['title'] }}</h3>
                                            <p class="event-description-short">{{ $sim['description'] }}</p>
                                            <div class="event-time">
                                                <i class="bi bi-clock"></i>
                                                <span>{{ $sim['time'] }}</span>
                                            </div>
                                            <button class="btn-buy-ticket">
                                                <i class="bi bi-ticket-perforated"></i>
                                                Beli Tiket
                                            </button>
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