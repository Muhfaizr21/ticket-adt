@extends('layouts.app')

@section('title', 'Buy Tickets')

@section('content')
<div class="dashboard-container">
    <section class="festival-details">
        <div class="container">
            <div class="details-grid">

                <!-- Left Column - Event List -->
                <div class="festival-info">
                    <h1 class="section-title">Daftar Event</h1>

                    @forelse ($events as $event)
                        <div class="event-card mb-4">
                            <div class="event-image">
                                <img src="{{ asset('storage/' . $event->poster) }}" alt="{{ $event->name }}" style="width:100%; max-height:300px; object-fit:cover; border-radius:10px;">
                            </div>
                            <div class="event-content p-3 border rounded mt-2">
                                <h3 class="event-title">{{ $event->name }}</h3>
                                <p class="event-description">{{ Str::limit($event->description, 200) }}</p>
                                <p><i class="bi bi-calendar-event"></i> {{ \Carbon\Carbon::parse($event->date)->format('d M Y') }}</p>
                                <p><i class="bi bi-geo-alt"></i> {{ $event->location }}</p>
                                <div class="ticket-prices mt-2">
                                    @if($event->vip_tickets)
                                        <p>VIP: Rp {{ number_format($event->vip_price, 0, ',', '.') }} ({{ $event->vip_tickets }} tiket)</p>
                                    @endif
                                    @if($event->reguler_tickets)
                                        <p>Regular: Rp {{ number_format($event->reguler_price, 0, ',', '.') }} ({{ $event->reguler_tickets }} tiket)</p>
                                    @endif
                                    <p>Harga Standar: Rp {{ number_format($event->price, 0, ',', '.') }}</p>
                                </div>
                                <a href="{{ route('shop') }}" class="btn btn-primary mt-2">
                                    <i class="bi bi-ticket-perforated"></i> Beli Tiket
                                </a>
                            </div>
                        </div>
                    @empty
                        <p>Tidak ada event tersedia saat ini.</p>
                    @endforelse
                </div>

                <!-- Right Column - Event Filters / Info (Opsional) -->
                <div class="festival-sidebar">
                    <div class="sidebar-card">
                        <h2 class="card-title">Filter Event</h2>
                        <form method="GET" action="{{ route('pengguna.dashboard') }}">
                            <div class="mb-2">
                                <label>Jenis Tiket</label>
                                <select name="ticket_type" class="form-select">
                                    <option value="">Semua</option>
                                    <option value="vip">VIP</option>
                                    <option value="regular">Regular</option>
                                </select>
                            </div>
                            <div class="mb-2">
                                <label>Lokasi</label>
                                <input type="text" name="location" class="form-control" placeholder="Cari lokasi...">
                            </div>
                            <button type="submit" class="btn btn-secondary w-100">Filter</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>
@endsection
