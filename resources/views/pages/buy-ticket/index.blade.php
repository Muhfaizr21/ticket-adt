@extends('layouts.app')
@section('title', 'Buat Order Tiket')

@section('content')
    <div class="container py-5">
        <h2 class="fw-bold mb-4">🎟 Pembelian Tiket Event</h2>

        {{-- Flash Message --}}
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Informasi Event --}}
        <div class="card shadow-sm mb-4 border-0">
            <div class="row g-0">
                {{-- Gambar Event --}}
                <div class="col-md-5">
                    <img src="{{ asset('storage/' . ($event->image ?? 'default.jpg')) }}"
                        class="img-fluid rounded-start w-100 h-100 object-fit-cover" alt="{{ $event->title }}">
                </div>

                {{-- Detail Event --}}
                <div class="col-md-7">
                    <div class="card-body">
                        <h3 class="fw-bold text-primary mb-2">{{ $event->title }}</h3>
                        <p class="mb-1"><i class="bi bi-geo-alt text-danger"></i>
                            {{ $event->venue->name ?? 'Venue belum ditentukan' }}</p>
                        <p class="mb-1"><i class="bi bi-calendar-event text-success"></i>
                            {{ $event->start_date ? \Carbon\Carbon::parse($event->start_date)->format('d M Y H:i') : 'Tanggal belum ditentukan' }}
                        </p>
                        <p class="mb-1"><i class="bi bi-tag text-warning"></i> Kategori: {{ $event->category ?? 'Umum' }}
                        </p>
                        <p class="text-muted mt-2">{{ \Illuminate\Support\Str::limit($event->description, 250) }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form Pembelian --}}
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form action="{{ route('orders.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="event_id" value="{{ $event->id }}">

                    {{-- Pilih Tipe Tiket --}}
                    <div class="mb-3">
                        <label for="ticket_type_id" class="form-label fw-semibold">Pilih Tipe Tiket</label>
                        <select name="ticket_type_id" id="ticket_type_id" class="form-select" required>
                            <option value="">-- Pilih Tipe Tiket --</option>
                            @foreach($event->ticketTypes as $ticket)
                                @php
                                    $originalPrice = $ticket->price;
                                @endphp
                                <option value="{{ $ticket->id }}">
                                    {{ $ticket->name }}
                                    — Rp{{ number_format($originalPrice, 0, ',', '.') }}
                                    — Sisa {{ $ticket->available_tickets }} tiket
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Kode Promo Manual --}}
                    <div class="mb-3">
                        <label for="promo_code" class="form-label fw-semibold">Kode Promo (opsional)</label>
                        <input type="text" name="promo_code" id="promo_code" class="form-control"
                            placeholder="Masukkan kode promo jika ada">
                        <small class="text-muted">Masukkan kode promosi yang valid untuk mendapatkan potongan harga.</small>
                    </div>

                    {{-- Jumlah Pembelian --}}
                    <div class="mb-3">
                        <label for="quantity" class="form-label fw-semibold">Jumlah Tiket</label>
                        <input type="number" name="quantity" id="quantity" class="form-control" value="1" min="1" required>
                    </div>

                    {{-- Tombol --}}
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('shop.show', $event->id) }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-cart-check"></i> Buat Order
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection