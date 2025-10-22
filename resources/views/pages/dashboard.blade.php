@extends('layouts.app')

@section('title', 'Buy Tickets')

@section('content')
    @php
        use Illuminate\Support\Str;
    @endphp

    <div class="dashboard-container">
        @foreach($festivalData as $index => $event)
            @if($index == 0)
                <!-- ================= Event Utama ================= -->
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

                <!-- ================= Detail Event ================= -->
                <section class="festival-details mt-5">
                    <div class="container">
                        <div class="details-grid">
                            <div class="festival-info">
                                <h1 class="festival-title">{{ $event['title'] }}</h1>

                                <!-- Rating -->
                                <div class="rating mb-2">
                                    <span class="text-warning">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= floor($event['rating'] ?? 4.5))
                                                <i class="bi bi-star-fill"></i>
                                            @elseif($i - 0.5 <= ($event['rating'] ?? 4.5))
                                                <i class="bi bi-star-half"></i>
                                            @else
                                                <i class="bi bi-star"></i>
                                            @endif
                                        @endfor
                                    </span>
                                    <span class="rating-text ms-2">{{ $event['rating'] ?? 4.5 }} / 5</span>
                                </div>

                                <!-- Info Singkat -->
                                <div class="info-line"><i class="bi bi-calendar-event me-2 text-primary"></i>{{ $event['date'] }} -
                                    {{ $event['time'] }} 
                                </div>
                                <div class="info-line"><i class="bi bi-geo-alt me-2 text-primary"></i>{{ $event['location'] }}</div>
                                @if(isset($event['min_age']))
                                    <div class="info-line"><i class="bi bi-info-circle me-2 text-primary"></i>Minimal usia
                                        {{ $event['min_age'] }} tahun
                                    </div>
                                @endif

                                <!-- Share Buttons -->
                                <div class="share-buttons mt-3 d-flex flex-wrap gap-1">
                                    <span class="me-2">📤 Share:</span>
                                    <a href="https://wa.me/?text={{ urlencode(route('shop.index', $event['id'])) }}" target="_blank"
                                        class="btn btn-sm btn-success"><i class="bi bi-whatsapp"></i> WA</a>
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('shop.index', $event['id'])) }}"
                                        target="_blank" class="btn btn-sm btn-primary"><i class="bi bi-facebook"></i> FB</a>
                                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('shop.index', $event['id'])) }}&text={{ urlencode($event['title']) }}"
                                        target="_blank" class="btn btn-sm btn-info"><i class="bi bi-twitter"></i> TW</a>
                                    <button class="btn btn-sm btn-secondary"
                                        data-event-url="{{ route('shop.index', $event['id']) }}" onclick="copyLink(this)"><i
                                            class="bi bi-link-45deg"></i> Copy Link</button>
                                </div>

                                <!-- Deskripsi -->
                                <div class="info-section mt-3">
                                    <h2 class="section-title">Tentang Event</h2>
                                    <p class="event-description">{{ $event['description'] }}</p>
                                </div>
                            </div>

                            <!-- Peta -->
                            <div class="festival-map">
                                <h2 class="card-title mb-2">📍 Lokasi</h2>
                                <iframe src="https://www.google.com/maps?q={{ urlencode($event['location']) }}&output=embed"
                                    width="100%" height="250" style="border:0; border-radius:10px;" allowfullscreen=""
                                    loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                            </div>
                        </div>
                    </div>
                </section>
            @endif
        @endforeach

        <!-- ================= Event Serupa ================= -->
        @if($festivalData->count() > 1)
            <section class="more-events-scroll my-5 position-relative">
                <div class="container position-relative">
                    <h2 class="section-title text-center mb-4">🎫 Event Serupa</h2>
                    <button class="scroll-btn left" id="scrollLeft"><i class="bi bi-chevron-left"></i></button>
                    <button class="scroll-btn right" id="scrollRight"><i class="bi bi-chevron-right"></i></button>
                    <div class="scroll-row" id="eventScroll">
                        @foreach($festivalData->skip(1) as $event)
                            <a href="{{ route('shop.index', $event['id']) }}" class="event-card-small-link">
                                <div class="event-card-small">
                                    <div class="event-card-image">
                                        <img src="{{ asset('storage/' . $event['poster']) }}" alt="{{ $event['title'] }}">
                                    </div>
                                    <div class="event-card-info">
                                        <h4 class="event-title">{{ $event['title'] }}</h4>
                                        <p class="event-desc">{{ Str::limit($event['description'], 60) }}</p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
    </div>

    <!-- ================= JS ================= -->
    <script>
        function copyLink(btn) {
            const url = btn.getAttribute('data-event-url');
            const dummy = document.createElement('input');
            document.body.appendChild(dummy);
            dummy.value = url;
            dummy.select();
            document.execCommand('copy');
            document.body.removeChild(dummy);
            alert('Link berhasil disalin: ' + url);
        }


        document.addEventListener("DOMContentLoaded", () => {
            const scrollRow = document.getElementById("eventScroll");
            const leftBtn = document.getElementById("scrollLeft");
            const rightBtn = document.getElementById("scrollRight");
            if (leftBtn && rightBtn && scrollRow) {
                leftBtn.addEventListener("click", () => scrollRow.scrollBy({ left: -250, behavior: 'smooth' }));
                rightBtn.addEventListener("click", () => scrollRow.scrollBy({ left: 250, behavior: 'smooth' }));
            }
        });
    </script>

    <!-- ================= CSS ================= -->
    <style>
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

        /* Info compact */
        .info-line {
            margin: 5px 0;
            font-size: 0.9rem;
        }

        /* Share Buttons */
        .share-buttons .btn {
            font-size: 0.75rem;
            padding: 4px 8px;
        }

        /* Event Serupa Scroll */
        .more-events-scroll .scroll-row {
            display: flex;
            gap: 16px;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            padding: 10px 0 15px;
            -webkit-overflow-scrolling: touch;
        }

        .event-card-small-link {
            text-decoration: none;
            color: inherit;
            flex: 0 0 220px;
        }

        .event-card-small {
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

        /* Responsive */
        @media(max-width:768px) {
            .event-main {
                flex-direction: column;
            }

            .ticket-section {
                flex: 1 1 100%;
                margin-top: 20px;
            }

            .scroll-btn {
                display: none;
            }

            .event-card-small-link {
                flex: 0 0 180px;
            }
        }
    </style>
@endsection