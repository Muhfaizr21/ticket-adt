@extends('layouts.app')

@section('title', 'Detail Order')

@section('content')
<div class="container py-5">
    <h2 class="fw-bold mb-5 text-center">📦 Detail Order</h2>

    {{-- Flash Message --}}
    @foreach (['success', 'error'] as $msg)
        @if(session($msg))
            <div class="alert alert-{{ $msg }} alert-dismissible fade show" role="alert">
                {{ session($msg) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    @endforeach

    <div class="row g-4">
        {{-- Card Tiket Digital --}}
        <div class="col-lg-5">
            <div class="ticket-card-digital shadow-lg text-center position-relative">
                <div class="ticket-header bg-gradient text-white py-2 px-3 rounded-top">
                    <h5 class="mb-0 fw-bold">🎟️ Tiketmu</h5>
                </div>

                <div class="card-body p-4">
                    @php
                        use Milon\Barcode\DNS1D;
                        use SimpleSoftwareIO\QrCode\Facades\QrCode;
                        $barcodeImage = null;
                    @endphp

                    {{-- Cek pembayaran & refund --}}
                    @if($order->payment && $order->payment->status === 'verified' && $order->refund_status !== 'refunded' && $order->barcode_code)
                        @php
                            $barcode = new DNS1D();
                            $barcode->setStorPath(public_path('barcodes/'));
                            $barcodeImage = $barcode->getBarcodePNG($order->barcode_code, 'C39+', 2, 80, [0,0,0], true);
                        @endphp

                        {{-- Barcode --}}
                        <div class="mb-3">
                            <h6 class="fw-semibold mb-1">🔢 Barcode</h6>
                            <img src="data:image/png;base64,{{ $barcodeImage }}" class="img-fluid border rounded p-2 bg-white shadow-sm">
                        </div>

                        {{-- QR Code --}}
                        <div class="mb-3">
                            <h6 class="fw-semibold mb-1">🔲 QR Code</h6>
                            <div class="d-inline-block p-2 bg-white border rounded shadow-sm">
                                {!! QrCode::size(150)->margin(1)->generate($order->barcode_code) !!}
                            </div>
                        </div>
                    @elseif($order->refund_status === 'refunded')
                        <p class="text-danger fw-semibold">
                            ⚠️ Order ini telah di-refund. Barcode dan QR Code tidak tersedia karena dana sudah dikembalikan.
                        </p>
                    @else
                        <p class="text-muted small mt-3">
                            📌 Barcode dan QR Code akan muncul setelah pembayaran diverifikasi oleh admin.
                        </p>
                    @endif

                    <p class="text-muted small mt-3">
                        📸 Screenshot tiket ini untuk check-in di lokasi acara
                    </p>
                </div>

                <div class="ticket-footer bg-light py-2 px-3 rounded-bottom">
                    <small class="text-muted">Kode Tiket: {{ $order->barcode_code }}</small>
                </div>
            </div>
        </div>

        {{-- Card Informasi Order & Pembayaran --}}
        <div class="col-lg-7">
            <div class="card order-info-card shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold text-primary mb-4">📝 Informasi Order & Pembayaran</h5>

                    {{-- Informasi Event --}}
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <p><strong>Event:</strong> {{ $order->event->name ?? '-' }}</p>
                            <p><strong>Tipe Tiket:</strong> {{ $order->ticketType->name ?? '-' }}</p>
                            <p><strong>Jumlah Tiket:</strong> {{ $order->quantity }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Total Harga:</strong> Rp{{ number_format($order->total_price, 0, ',', '.') }}</p>
                            @if ($order->ticketType->promotions && $order->ticketType->promotions->count())
                                <p><strong>Promo:</strong>
                                    @foreach ($order->ticketType->promotions as $promo)
                                        <span class="badge bg-info me-1">
                                            {{ $promo->name }} - {{ $promo->persen_diskon ? $promo->persen_diskon.'%' : 'Rp'.number_format($promo->value,0,',','.') }}
                                        </span>
                                    @endforeach
                                </p>
                            @endif
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="mb-4 d-flex flex-wrap gap-3">
                        <p class="mb-0"><strong>Status Order:</strong>
                            <span class="badge bg-{{ $order->status === 'paid' ? 'success' : ($order->status === 'pending' ? 'warning' : 'danger') }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </p>
                        <p class="mb-0"><strong>Status Pembayaran:</strong>
                            @if($order->payment)
                                <span class="badge bg-{{ $order->payment->status === 'verified' ? 'success' : ($order->payment->status === 'rejected' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($order->payment->status) }}
                                </span>
                            @else
                                <span class="badge bg-secondary">Belum Upload</span>
                            @endif
                        </p>
                        <p class="mb-0"><strong>Status Refund:</strong>
                            <span class="badge bg-{{ $order->refund_status === 'refunded' ? 'danger' : ($order->refund_status === 'approved' ? 'info' : 'secondary') }}">
                                {{ ucfirst($order->refund_status ?? 'None') }}
                            </span>
                        </p>
                    </div>

                    {{-- Bukti Pembayaran --}}
                    <h6 class="fw-bold mb-3">💳 Bukti Pembayaran</h6>
                    <hr>
                    @if($order->payment && $order->payment->proof_image)
                        <div class="row g-4 align-items-center">
                            <div class="col-md-6">
                                <p><strong>Bank / E-Wallet:</strong> {{ $order->payment->bank_name }}</p>
                                <p><strong>Nama Akun:</strong> {{ $order->payment->account_name }}</p>
                                <p><strong>Status:</strong>
                                    <span class="badge bg-{{ $order->payment->status === 'verified' ? 'success' : ($order->payment->status === 'rejected' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($order->payment->status) }}
                                    </span>
                                </p>
                                @if($order->payment->admin_note)
                                    <p><strong>Catatan Admin:</strong> {{ $order->payment->admin_note }}</p>
                                @endif
                            </div>
                            <div class="col-md-6 text-center">
                                <img src="{{ asset('storage/' . $order->payment->proof_image) }}" class="img-fluid rounded shadow-sm border" style="max-width: 300px;" alt="Bukti Pembayaran">
                            </div>
                        </div>
                    @else
                        <p class="text-muted">Belum ada bukti pembayaran yang diunggah.</p>
                        <a href="{{ route('orders.upload', $order->id) }}" class="btn btn-primary">
                            <i class="bi bi-upload"></i> Upload Bukti Pembayaran
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Tombol Kembali --}}
    <div class="mt-4">
        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Order
        </a>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Tiket Digital */
.ticket-card-digital {
    border-radius: 1.2rem;
    overflow: hidden;
    border: 4px solid;
    border-image-slice: 1;
    border-width: 4px;
    border-image-source: linear-gradient(45deg, #ff0000, #2575fc);
    background: #f8f9fa;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.ticket-card-digital:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.25);
}

.ticket-header {
    font-size: 1.2rem;
}

.ticket-footer {
    font-size: 0.85rem;
}

.ticket-card-digital img {
    max-width: 100%;
}

/* Informasi Order */
.order-info-card {
    border-radius: 1rem;
    border: none;
    box-shadow: 0 6px 20px rgba(0,0,0,0.06);
}

.order-info-card .card-body {
    padding: 2rem;
}

.badge {
    font-size: 0.9rem;
    font-weight: 600;
}

@media (max-width: 576px) {
    .ticket-card-digital .card-body,
    .order-info-card .card-body {
        padding: 1.5rem;
    }
}
</style>
@endpush
