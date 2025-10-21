@extends('layouts.app')

@section('title', 'Buy Tickets')

@section('content')
    @php
        use Illuminate\Support\Str;
    @endphp

    <div class="dashboard-container">
        @foreach($festivalData as $index => $event)
            @if($index == 0)
                <!-- ================= Event Utama (Poster + Tiket di Samping) ================= -->
                <section class="event-details-container">
                    <div class="container">
                        <div class="event-main">
                            <!-- Poster -->
                            <div class="event-poster">
                                <img src="{{ asset('storage/' . $event['poster']) }}" alt="{{ $event['title'] }}">
                            </div>

                            <!-- Tiket -->
                            <div class="ticket-section">
                                <h3 class="section-title">🎟️ Pilih Tiket</h3>

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

                                <div class="age-restriction">
                                    <i class="bi bi-info-circle"></i> Minimal usia {{ $event['min_age'] ?? 17 }} tahun
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- ================= Detail & Lokasi ================= -->
                <section class="festival-details mt-5">
                    <div class="container">
                        <div class="details-grid">
                            <div class="festival-info">
                                <h1 class="festival-title">{{ $event['title'] }}</h1>
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

                                <div class="info-section">
                                    <h3><i class="bi bi-calendar-event"></i> {{ $event['date'] }} - {{ $event['time'] }} WIB</h3>
                                    <h3><i class="bi bi-geo-alt"></i> {{ $event['location'] }}</h3>
                                </div>

                                <div class="info-section">
                                    <h2 class="section-title">Tentang Event</h2>
                                    <p>{{ $event['description'] }}</p>
                                </div>
                            </div>

                            <div class="festival-map">
                                <h2 class="card-title">📍 Lokasi di Peta</h2>
                                <iframe src="https://www.google.com/maps?q={{ urlencode($event['location']) }}&output=embed"
                                    width="100%" height="250" style="border:0; border-radius:10px;" allowfullscreen=""
                                    loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                                </iframe>
                            </div>
                        </div>
                    </div>
                </section>
            @endif
        @endforeach


        <!-- ================= Event Lainnya: Scroll Horizontal ================= -->
        @if($festivalData->count() > 1)
            <section class="more-events-scroll my-5 position-relative">
                <div class="container position-relative">
                    <h2 class="section-title text-center mb-4">🎫 Event Lainnya</h2>

                    <!-- Tombol Navigasi -->
                    <button class="scroll-btn left" id="scrollLeft"><i class="bi bi-chevron-left"></i></button>
                    <button class="scroll-btn right" id="scrollRight"><i class="bi bi-chevron-right"></i></button>

                    <div class="scroll-row" id="eventScroll">
                        @foreach($festivalData->skip(1) as $event)
                            <div class="event-card-small">
                                <div class="event-card-image">
                                    <img src="{{ asset('storage/' . $event['poster']) }}" alt="{{ $event['title'] }}">
                                </div>
                                <div class="event-card-info">
                                    <h4 class="event-title">{{ $event['title'] }}</h4>
                                    <p class="event-desc">{{ Str::limit($event['description'], 60) }}</p>
                                    <a href="{{ route('shop.index', $event['id']) }}" class="btn btn-sm btn-primary">
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

    <!-- ================= CSS ================= -->
    <style>
        /* Poster + Tiket Samping */
        .event-details-container {
            margin-top: 40px;
        }

        .event-main {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 30px;
        }

        .event-poster {
            flex: 1 1 55%;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .event-poster img {
            width: 100%;
            height: auto;
            object-fit: cover;
            border-radius: 16px;
        }

        .ticket-section {
            flex: 1 1 40%;
            background: #fff;
            padding: 25px;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .ticket-option {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background: #f8f9fa;
            transition: 0.3s;
        }

        .ticket-option:hover {
            background: #eaf2ff;
            border-color: #03346E;
        }

        .ticket-price .original {
            text-decoration: line-through;
            color: #999;
            font-size: 0.85rem;
        }

        .ticket-price .discounted {
            color: #03346E;
            font-weight: bold;
        }

        .ticket-price .promo-badge {
            display: inline-block;
            background: #ffce00;
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 12px;
            margin-top: 4px;
        }

        .btn-ticket {
            background: #03346E;
            color: #fff;
            border: none;
            padding: 8px 14px;
            border-radius: 8px;
        }

        .btn-ticket:hover {
            background: #021f47;
        }

        /* Detail & Map */
        .details-grid {
            display: flex;
            gap: 40px;
            flex-wrap: wrap;
        }

        .festival-info {
            flex: 1 1 55%;
        }

        .festival-map {
            flex: 1 1 40%;
        }

        /* Event Lainnya Scroll */
        /* Event Lainnya Scroll (Compact & Clean) */
        .more-events-scroll .scroll-row {
            display: flex;
            gap: 16px;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            padding: 10px 0 15px;
            -webkit-overflow-scrolling: touch;
        }

        .event-card-small {
            flex: 0 0 220px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            scroll-snap-align: start;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .event-card-small:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.12);
        }

        .event-card-image img {
            width: 100%;
            height: 140px;
            object-fit: cover;
            display: block;
        }

        .event-card-info {
            padding: 12px;
            text-align: center;
        }

        .event-title {
            font-size: 1rem;
            font-weight: 600;
            color: #03346E;
            margin-bottom: 6px;
        }

        .event-desc {
            font-size: 0.85rem;
            color: #555;
            margin-bottom: 10px;
            height: 38px;
            overflow: hidden;
        }

        .btn.btn-sm {
            font-size: 0.8rem;
            padding: 5px 10px;
            border-radius: 6px;
        }

        /* Scroll Buttons */
        .scroll-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            width: 36px;
            height: 36px;
            border-radius: 50%;
            cursor: pointer;
            z-index: 10;
            transition: all 0.3s ease;
        }

        .scroll-btn:hover {
            background: #03346E;
            color: #fff;
        }

        .scroll-btn.left {
            left: -8px;
        }

        .scroll-btn.right {
            right: -8px;
        }

        @media (max-width: 768px) {
            .scroll-btn {
                display: none;
            }
        }
    </style>

    <!-- ================= JS ================= -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const scrollRow = document.getElementById("eventScroll");
            const leftBtn = document.getElementById("scrollLeft");
            const rightBtn = document.getElementById("scrollRight");

            leftBtn.addEventListener("click", () => scrollRow.scrollBy({ left: -300, behavior: 'smooth' }));
            rightBtn.addEventListener("click", () => scrollRow.scrollBy({ left: 300, behavior: 'smooth' }));
        });
    </script>
@endsection