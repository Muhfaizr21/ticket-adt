@extends('layouts.app')
@php
    use Illuminate\Support\Str;
@endphp

@section('title', 'Shop - Daftar Event')

@section('content')
<div class="main-container pd-ltr-20 xs-pd-20-10">
    <div class="min-height-200px">
        <div class="page-header mb-4">
            <div class="row">
                <div class="col-12">
                    <div class="title">
                        <h4>🎟️ Daftar Event</h4>
                    </div>
                    <nav aria-label="breadcrumb" role="navigation">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active" aria-current="page">Shop</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <div class="product-wrap">
            {{-- Menggunakan clearfix dan row untuk layout grid --}}
            <div class="row clearfix">
                @forelse($events as $event)
                    @php
                        // Ambil tiket termurah atau yang pertama untuk ditampilkan harganya
                        $ticket = $event->ticketTypes->sortBy('price')->first() ?? null;
                        $finalPrice = $ticket ? $ticket->price : 0;
                        $promotion = $ticket ? $ticket->promotions->first() : null;
                        $hasPromo = (bool) $promotion;
                        
                        // Hitung harga asli sebelum diskon jika ada promo
                        $originalPrice = $hasPromo ? ($ticket->price + $promotion->value) : $finalPrice;
                    @endphp

                    {{-- Setiap event kini berada di dalam col-lg-3 dengan margin bawah --}}
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
                        <div class="da-card box-shadow h-100">
                            {{-- Foto Event --}}
                            <div class="da-card-photo position-relative">
                                <img src="{{ $event->poster ? asset('storage/' . $event->poster) : asset('images/default-poster.jpg') }}" alt="{{ $event->name }}">
                                @if($event->available_tickets <= 0)
                                    <span class="badge bg-danger position-absolute top-0 end-0 m-2">Sold Out</span>
                                @endif
                                @if($hasPromo)
                                    <span class="badge bg-success position-absolute top-0 start-0 m-2">
                                        {{ $promotion->name }}
                                    </span>
                                @endif
                            </div>

                            {{-- Detail Event --}}
                            <div class="da-card-content p-3 d-flex flex-column">
                                <h5 class="font-weight-bold mb-1 text-dark">
                                    {{ Str::limit($event->name, 35) }}
                                </h5>
                                
                                {{-- Harga --}}
                                <div class="price-section mb-2">
                                    @if($hasPromo)
                                        <span class="original-price me-2">
                                            Rp {{ number_format($originalPrice, 0, ',', '.') }}
                                        </span>
                                        <span class="final-price text-primary fw-bold">
                                            Rp {{ number_format($finalPrice, 0, ',', '.') }}
                                        </span>
                                    @else
                                        <span class="final-price text-primary fw-bold">
                                            Rp {{ number_format($finalPrice, 0, ',', '.') }}
                                        </span>
                                    @endif
                                </div>
                                
                                {{-- Lokasi & Tanggal --}}
                                <p class="text-muted small mb-3 flex-grow-1">
                                    <i class="icon-copy bi bi-calendar-event me-1"></i> 
                                    {{ \Carbon\Carbon::parse($event->date)->format('d M Y') }}<br>
                                    <i class="icon-copy bi bi-geo-alt me-1"></i> 
                                    {{ Str::limit($event->location, 30) }}
                                </p>

                                {{-- Tombol Pilih Tiket --}}
                                <a href="{{ route('shop.show', $event->id) }}" class="btn btn-outline-primary btn-sm w-100 mt-auto">
                                    Pilih Tiket
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-muted fs-5">🚫 Tidak ada event tersedia saat ini.</p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="blog-pagination mb-30">
                <div class="btn-toolbar justify-content-center">
                    {{ $events->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
/* =========================
    DeskApp Style Cards
========================= */

/* Perbaikan untuk masalah floating dan clearance pada DeskApp */
.product-wrap {
    overflow: hidden; /* Tambahkan ini untuk menampung floating child (kolom) */
    padding: 10px; /* Sedikit padding pada container utama */
}

.row.clearfix {
    clear: both;
}

.da-card {
    /* Mengikuti gaya kartu Help Center Anda: rounded, white background, subtle shadow */
    border-radius: 12px;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    cursor: pointer;
    background-color: #fff;
    border: none; /* Hilangkan border, hanya pakai shadow */
    box-shadow: 0 4px 15px rgba(0,0,0,0.05); /* Shadow seperti Help Card */
    display: flex; 
    flex-direction: column;
    height: 100%; /* Penting: Pastikan card mengisi tinggi kolom */
    box-sizing: border-box; 
}

.da-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1); /* Shadow saat hover, seperti Help Card */
}

.da-card-photo {
    height: 180px;
    overflow: hidden;
    position: relative;
    border-top-left-radius: 11px;
    border-top-right-radius: 11px;
}

.da-card-photo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.da-card:hover img {
    transform: scale(1.03); 
}

/* Bagian konten/text di bawah foto */
.da-card-content {
    flex-grow: 1;
    padding: 1rem;
}

/* Gaya Harga */
.price-section .final-price {
    font-size: 1.15rem;
    color: #2980b9; /* Menggunakan warna biru dari Help Center Anda */
    font-weight: 700;
}

.price-section .original-price {
    font-size: 0.9rem;
    color: #999;
    text-decoration: line-through;
    font-weight: 400;
}

/* Responsif */
@media (max-width: 991.98px) {
    .da-card-photo { height: 160px; }
}

@media (max-width: 575.98px) {
    .da-card-photo { height: 140px; }
}
</style>
@endpush
