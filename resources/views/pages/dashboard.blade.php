@extends('layouts.app')

@section('title', 'Pestapora 2024 - Buy Tickets')

@section('content')
    <div class="dashboard-container">
        <!-- Image Gallery Section -->
        <section class="image-gallery">
            <div class="container">
                <div class="gallery-grid">
                    <div class="main-image">
                        <img src="{{ asset('images/pestapora.jpg') }}" alt="Pestapora Main Image">
                    </div>
                    <div class="side-images">
                        <div class="small-image">
                            <img src="{{ asset('images/pestapora1.jpg') }}" alt="Pestapora Stage">
                        </div>
                        <div class="small-image">
                            <img src="{{ asset('images/pestapora2.jpg') }}" alt="Pestapora Crowd">
                        </div>
                        <div class="small-image">
                            <img src="{{ asset('images/pestapora3.jpg') }}" alt="Pestapora Performance">
                        </div>
                        <div class="small-image">
                            <img src="{{ asset('images/pestapora4.jpg') }}" alt="Pestapora Atmosphere">
                        </div>
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
                        <!-- Festival Title and Actions -->
                        <div class="festival-header">
                            <h1 class="festival-title">{{ $festivalData['title'] }}</h1>
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
                                            @if ($i <= floor($festivalData['rating']))
                                                <i class="bi bi-star-fill"></i>
                                            @elseif($i - 0.5 <= $festivalData['rating'])
                                                <i class="bi bi-star-half"></i>
                                            @else
                                                <i class="bi bi-star"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="rating-text">{{ $festivalData['rating'] }} Ratings</span>
                                </div>
                            </div>
                        </div>

                        <!-- When & Where Section -->
                        <div class="info-section">
                            <div class="info-grid">
                                <div class="info-item">
                                    <div class="info-header">
                                        <div class="info-icon">
                                            <i class="bi bi-calendar-event"></i>
                                        </div>
                                        <h3 class="info-title">When</h3>
                                    </div>
                                    <div class="info-content">
                                        <div class="info-date">{{ $festivalData['date'] }}</div>
                                        <div class="info-time">{{ $festivalData['time'] }}</div>
                                        <div class="info-timezone">(WIB - Western Indonesia Time)</div>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <div class="info-header">
                                        <div class="info-icon">
                                            <i class="bi bi-geo-alt"></i>
                                        </div>
                                        <h3 class="info-title">Where</h3>
                                    </div>
                                    <div class="info-content">
                                        <div class="info-location">{{ $festivalData['location'] }}</div>
                                        <div class="info-address">Senayan, Jakarta Selatan</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- About This Event -->
                        <div class="info-section">
                            <h2 class="section-title">About this event</h2>
                            <div class="event-description">
                                <p>{{ $festivalData['description'] }}</p>
                                <p><strong>Lineup Artist:</strong> Hindia, Tulus, Niki, Rich Brian, Reality Club, .Feast,
                                    Sheila on 7, Noah, dan puluhan artis lainnya.</p>
                                <p><strong>Fasilitas:</strong> 4 stage utama, food court kuliner Indonesia, merchandise
                                    store, charging station, prayer room, dan medical center.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Ticket & Location -->
                    <div class="festival-sidebar">
                        <!-- Ticket Price Card -->
                        <div class="sidebar-card">
                            <h2 class="card-title">Harga Tiket</h2>
                            <div class="ticket-options">
                                @foreach ($festivalData['ticket_prices'] as $ticket)
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
                                Minimal usia {{ $festivalData['min_age'] }} tahun untuk mengakses event ini
                            </div>
                        </div>

                        <!-- Location Card -->
                        <div class="sidebar-card">
                            <h2 class="card-title">Lokasi di peta</h2>
                            <div class="location-map">
                                <iframe
                                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.493008918007!2d106.80240277499543!3d-6.218558593769214!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f155502c1827%3A0x8a6c33f6794a1c8!2sGelora%20Bung%20Karno%20Stadium!5e0!3m2!1sen!2sid!4v1695988898430!5m2!1sen!2sid"
                                    width="100%" height="250" style="border:0; border-radius:10px;" allowfullscreen=""
                                    loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                                </iframe>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- More Events Like This -->
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
                        @foreach ($festivalData['similar_events'] as $event)
                            <div class="event-card">
                                <div class="event-card-header">
                                    <div class="event-price-badge">
                                        <span>From</span> Rp {{ $event['price'] ?? '450' }}k
                                    </div>
                                    <div class="event-actions">
                                        <div class="event-plus">+</div>
                                        <div class="event-favorite">
                                            <i class="bi bi-heart"></i>
                                        </div>
                                    </div>
                                    <div class="event-image">
                                        <img src="{{ asset('images/' . $event['image']) }}" alt="{{ $event['title'] }}">
                                    </div>
                                </div>
                                <div class="event-content">
                                    <div class="event-from">
                                        <span>From</span>
                                        <span>ID</span>
                                    </div>
                                    <div class="event-date">
                                        @php
                                            $dateNumber = explode(' ', $event['date'])[0];
                                        @endphp
                                        <span class="date-badge">{{ $dateNumber }}</span>
                                        <span class="date-text">{{ $event['date'] }}</span>
                                    </div>
                                    <h3 class="event-title">{{ $event['title'] }}</h3>
                                    <p class="event-description-short">
                                        {{ $event['description'] }}
                                    </p>
                                    <div class="event-time">
                                        <i class="bi bi-clock"></i>
                                        <span>{{ $event['time'] }}</span>
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
            </div>
        </section>
    </div>
@endsection
