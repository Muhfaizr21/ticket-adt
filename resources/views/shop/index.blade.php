@extends('layouts.app')
@php
    use Illuminate\Support\Str;
@endphp

@section('title', 'Shop - Daftar Event')

@section('content')
<div class="main-container pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">
        <div class="page-header mb-4">
            <h4>🎟️ Daftar Event</h4>
        </div>

        <div class="shop-horizontal-scroll">
            <div class="scroll-wrapper">
                @forelse($events as $event)
                    @php
                        $ticket = $event->ticketTypes->sortBy('price')->first() ?? null;
                        $finalPrice = $ticket ? $ticket->price : 0;
                        $promotion = $ticket ? $ticket->promotions->first() : null;
                        $hasPromo = (bool) $promotion;
                        $originalPrice = $hasPromo ? ($ticket->price + $promotion->value) : $finalPrice;
                    @endphp

                    <div class="event-card-mini">
                        <div class="card-photo">
                            <img src="{{ $event->poster ? asset('storage/' . $event->poster) : asset('images/default-poster.jpg') }}" alt="{{ $event->name }}">
                            @if($event->available_tickets <= 0)
                                <span class="badge bg-danger position-absolute top-0 end-0 m-1 fs-6">Sold Out</span>
                            @endif
                            @if($hasPromo)
                                <span class="badge bg-success position-absolute top-0 start-0 m-1 fs-6">{{ $promotion->name }}</span>
                            @endif
                        </div>
                        <div class="card-content p-2 d-flex flex-column">
                            <h6 class="fw-bold mb-1">{{ Str::limit($event->name, 20) }}</h6>
                            <div class="price-section mb-1">
                                @if($hasPromo)
                                    <span class="original-price me-1">Rp {{ number_format($originalPrice, 0, ',', '.') }}</span>
                                    <span class="final-price text-primary fw-bold">Rp {{ number_format($finalPrice, 0, ',', '.') }}</span>
                                @else
                                    <span class="final-price text-primary fw-bold">Rp {{ number_format($finalPrice, 0, ',', '.') }}</span>
                                @endif
                            </div>
                            <p class="text-muted small mb-2 flex-grow-1">
                                <i class="bi bi-calendar-event me-1"></i> {{ \Carbon\Carbon::parse($event->date)->format('d M Y') }}<br>
                                <i class="bi bi-geo-alt me-1"></i> {{ Str::limit($event->location, 20) }}
                            </p>
                            <a href="{{ route('shop.show', $event->id) }}" class="btn btn-outline-primary btn-sm mt-auto w-100">Pilih</a>
                        </div>
                    </div>
                @empty
                    <p class="text-muted fs-6">🚫 Tidak ada event tersedia.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
.scroll-wrapper {
    display: flex;
    gap: 15px;
    flex-wrap: nowrap;
}

.event-card-mini {
    flex: 0 0 auto;
    width: 200px; /* lebih besar */
    border-radius: 12px;
    overflow: hidden;
    background-color: #fff;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    display: flex;
    flex-direction: column;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.event-card-mini:hover {
    transform: translateY(-4px); /* naik sedikit lebih tinggi */
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.card-photo {
    height: 130px; /* lebih tinggi */
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
    transform: scale(1.08); /* scale lebih besar */
}

</style>
@endpush
