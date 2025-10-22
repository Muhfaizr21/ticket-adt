@extends('layouts.app')

@section('title', $event['title'])
@section('page-title', $event['title'])

@section('content')
<div class="container py-5">
    <div class="event-detail-box shadow-lg rounded-4 p-4 bg-white">
        <div class="row g-4 align-items-center">

            <!-- Poster -->
            <div class="col-md-6">
                <div class="event-image-wrapper rounded-3 overflow-hidden">
                    <img src="{{ $event['poster'] ? asset('storage/' . $event['poster']) : asset('images/default-poster.jpg') }}" 
                         class="img-fluid w-100" alt="{{ $event['title'] }}">
                </div>
            </div>

            <!-- Detail -->
            <div class="col-md-6">
                <h2 class="fw-bold text-primary mb-3">{{ $event['title'] }}</h2>

                <p class="text-muted mb-2">
                    <i class="bi bi-calendar-event text-primary me-2"></i>
                    {{ $event['date'] ?? '-' }}
                </p>

                <p class="text-muted mb-3">
                    <i class="bi bi-geo-alt-fill text-danger me-2"></i>
                    {{ $event['location'] }}
                </p>

                <p class="mb-3">{{ $event['description'] }}</p>

                <p class="fw-semibold mb-4">
                    🎟️ <strong>Tiket Tersedia:</strong> {{ count($event['tickets'] ?? []) }}
                </p>

                <!-- Tombol Aksi -->
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('pengguna.dashboard') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>

                <!-- Share Buttons -->
                <div class="share-buttons mt-4 d-flex flex-wrap gap-2 align-items-center">
                    <span class="fw-semibold me-2">📤 Bagikan:</span>

                    <a href="https://wa.me/?text={{ urlencode(route('events.show', $event['id'])) }}" 
                       target="_blank" class="btn btn-success btn-sm">
                        <i class="bi bi-whatsapp"></i> WhatsApp
                    </a>

                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('events.show', $event['id'])) }}" 
                       target="_blank" class="btn btn-primary btn-sm">
                        <i class="bi bi-facebook"></i> Facebook
                    </a>

                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('events.show', $event['id'])) }}&text={{ urlencode($event['title']) }}" 
                       target="_blank" class="btn btn-info btn-sm text-white">
                        <i class="bi bi-twitter-x"></i> X (Twitter)
                    </a>

                    <button class="btn btn-outline-secondary btn-sm" 
                            data-event-url="{{ route('events.show', $event['id']) }}" 
                            onclick="copyLink(this)">
                        <i class="bi bi-link-45deg"></i> Copy Link
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
