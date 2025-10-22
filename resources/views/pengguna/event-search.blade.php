@extends('layouts.app')

@section('title', 'Search Events')
@section('page-title', 'Search Results')

@section('content')
<div class="container py-5">
    <h2 class="mb-4 text-center">
        🔍 Hasil Pencarian untuk: 
        <span class="text-primary">{{ $search ?? 'Semua Event' }}</span>
    </h2>

    @if ($events->count() > 0)
        <div class="event-grid">
            @foreach ($events as $event)
                <div class="event-card">
                    <div class="event-image">
                        <img src="{{ $event->poster ? asset('storage/' . $event->poster) : asset('images/default-poster.jpg') }}" 
                             alt="{{ $event->name }}">
                    </div>
                    <div class="event-info">
                        <h5 class="event-title">{{ $event->name }}</h5>
                        <p class="event-location">
                            <i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ $event->location }}
                        </p>
                        <p class="event-date">
                            <i class="bi bi-calendar-event text-primary me-1"></i>
                            {{ \Carbon\Carbon::parse($event->date)->format('d M Y') }}
                        </p>
                        
                        {{-- Tombol Lihat Detail mengarah ke shop.show --}}
                        <a href="{{ route('shop.show', $event->id) }}" class="btn btn-outline-primary w-100 mt-2">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-warning text-center mt-5">
            🚫 Tidak ada event ditemukan untuk pencarian ini.
        </div>
    @endif
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
/* ===== GRID WRAPPER ===== */
.event-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 25px;
}

/* ===== CARD STYLE ===== */
.event-card {
    background: #fff;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
}

.event-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

/* ===== IMAGE ===== */
.event-image {
    height: 180px;
    overflow: hidden;
    border-bottom: 3px solid #ffd700;
}

.event-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.event-card:hover .event-image img {
    transform: scale(1.1);
}

/* ===== INFO ===== */
.event-info {
    padding: 15px 18px;
    flex-grow: 1;
}

.event-title {
    font-weight: 700;
    color: #03346E;
    font-size: 1.1rem;
    margin-bottom: 8px;
}

.event-location, .event-date {
    font-size: 0.9rem;
    color: #555;
    margin-bottom: 4px;
}

.btn-outline-primary {
    border-radius: 8px;
    font-weight: 600;
    transition: 0.3s;
}

.btn-outline-primary:hover {
    background-color: #03346E;
    color: #fff;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .event-image {
        height: 150px;
    }
    .event-title {
        font-size: 1rem;
    }
}
</style>
@endpush
