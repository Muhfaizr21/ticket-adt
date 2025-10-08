@extends('layouts.app')

@section('title', 'Buy Tickets')

@section('content')
    <div class="dashboard-container">

        @foreach($festivalData as $event)
            <!-- Image Gallery Section -->
            <section class="image-gallery mb-5">
                <div class="container">
                    <div class="gallery-grid">
                        <div class="main-image">
                            <img src="{{ asset('storage/' . $event['poster']) }}" alt="{{ $event['title'] }}"
                                style="width:100%; border-radius:10px;">
                        </div>
                        <!-- Optional: side images jika ada -->
                    </div>
                </div>
            </section>

            <!-- Festival Details Section -->
            <section class="festival-details mb-5">
                <div class="container">
                    <div class="details-grid">

                        <!-- Left Column - Festival Info -->
                        <div class="festival-info">
                            <div class="festival-header mb-3">
                                <h1 class="festival-title">{{ $event['title'] }}</h1>
                                <div class="rating">
                                    <div class="stars">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= floor($event['rating']))
                                                <i class="bi bi-star-fill"></i>
                                            @elseif($i - 0.5 <= $event['rating'])
                                                <i class="bi bi-star-half"></i>
                                            @else
                                                <i class="bi bi-star"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="rating-text">{{ $event['rating'] }} Ratings</span>
                                </div>
                            </div>

                            <div class="info-section mb-3">
                                <div class="info-grid">
                                    <div class="info-item">
                                        <div class="info-header">
                                            <i class="bi bi-calendar-event"></i>
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
                                            <i class="bi bi-geo-alt"></i>
                                            <h3 class="info-title">Where</h3>
                                        </div>
                                        <div class="info-content">
                                            <div class="info-location">{{ $event['location'] }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="info-section mb-3">
                                <h2 class="section-title">About this event</h2>
                                <p>{{ $event['description'] }}</p>
                            </div>
                        </div>

                        <!-- Right Column - Ticket & Location -->
                        <div class="festival-sidebar">
                            <div class="sidebar-card mb-3">
                                <h2 class="card-title">Harga Tiket</h2>
                                <div class="ticket-options">
                                    @foreach ($event['ticket_prices'] as $ticket)
                                        <div class="ticket-option">
                                            <div class="ticket-info">
                                                <h4 class="ticket-type">{{ $ticket['type'] }}</h4>
                                                <p class="ticket-desc">{{ $ticket['description'] }}</p>
                                            </div>
                                            <div class="ticket-price">
                                                <span class="price">{{ $ticket['price'] }}</span>
                                                <button class="btn-ticket">Pilih</button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="age-restriction">
                                    <i class="bi bi-info-circle"></i>
                                    Minimal usia {{ $event['min_age'] }} tahun
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
        @endforeach
    </div>
@endsection