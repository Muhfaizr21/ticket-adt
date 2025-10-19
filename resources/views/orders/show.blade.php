@extends('layouts.app')

@section('title', 'Detail Order')

@section('content')
    <div class="container py-5">
        <h2 class="fw-bold mb-4">📦 Detail Order</h2>

        {{-- Flash Message --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Informasi Order --}}
        <div class="card order-card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <h5 class="fw-bold text-primary m-0">📝 Informasi Order</h5>
                    <span class="badge bg-dark px-3 py-2 fs-6">{{ $order->barcode_code }}</span>
                </div>
                <hr>

                {{-- Barcode & QR Code --}}
                <div class="text-center my-4">
                    @php
                        use Milon\Barcode\DNS1D;
                        use SimpleSoftwareIO\QrCode\Facades\QrCode;

                        $barcode = new DNS1D();
                        $barcode->setStorPath(public_path('barcodes/'));
                        $barcodeImage = $barcode->getBarcodePNG($order->barcode_code, 'C39+', 2, 80, [0, 0, 0], true);
                    @endphp

                    <div class="d-flex flex-column flex-md-row justify-content-center align-items-center gap-4">
                        {{-- Barcode --}}
                        <div>
                            <h6 class="fw-semibold text-dark mb-2">🔢 Barcode</h6>
                            <img src="data:image/png;base64,{{ $barcodeImage }}" alt="Barcode"
                                class="img-fluid border rounded shadow-sm p-2 bg-white" style="max-width: 300px;">
                        </div>

                        {{-- QR Code --}}
                        <div>
                            <h6 class="fw-semibold text-dark mb-2">🔲 QR Code</h6>
                            <div class="bg-white border rounded p-2 shadow-sm d-inline-block">
                                {!! QrCode::size(150)->margin(1)->generate($order->barcode_code) !!}
                            </div>
                        </div>
                    </div>

                    <p class="mt-3 text-muted">
                        📸 <strong>Simpan barcode & QR ini untuk proses check-in di lokasi acara.</strong>
                    </p>

                    @if ($order->payment && $order->payment->status === 'verified')
                        <a href="{{ route('orders.downloadBarcode', $order->id) }}" class="btn btn-success mt-2">
                            <i class="bi bi-download"></i> Download Barcode
                        </a>
                    @endif
                </div>

                {{-- Informasi Event --}}
                <div class="row g-3">
                    <div class="col-md-6">
                        <p><strong>Event:</strong> {{ $order->event->name ?? '-' }}</p>
                        <p><strong>Tipe Tiket:</strong> {{ $order->ticketType->name ?? '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Jumlah Tiket:</strong> {{ $order->quantity }}</p>
                        <p><strong>Total Harga:</strong> Rp{{ number_format($order->total_price, 0, ',', '.') }}</p>
                    </div>
                </div>

                @if ($order->ticketType->promotions && $order->ticketType->promotions->count() > 0)
                    <p class="mt-2"><strong>Promo:</strong>
                        @foreach ($order->ticketType->promotions as $promo)
                            <span class="badge bg-info me-1">
                                {{ $promo->name }} -
                                {{ $promo->persen_diskon ? $promo->persen_diskon . '%' : 'Rp' . number_format($promo->value, 0, ',', '.') }}
                            </span>
                        @endforeach
                    </p>
                @endif

                {{-- Status --}}
                <div class="mt-3">
                    <p><strong>Status Order:</strong>
                        <span
                            class="badge bg-{{ $order->status === 'paid' ? 'success' : ($order->status === 'pending' ? 'warning' : 'danger') }} px-3 py-2">
                            {{ ucfirst($order->status) }}
                        </span>
                    </p>
                    <p><strong>Status Pembayaran:</strong>
                        @if ($order->payment)
                            <span
                                class="badge bg-{{ $order->payment->status === 'verified' ? 'success' : ($order->payment->status === 'rejected' ? 'danger' : 'warning') }} px-3 py-2">
                                {{ $order->payment->status_label }}
                            </span>
                        @else
                            <span class="badge bg-secondary px-3 py-2">Belum Upload</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        {{-- Bukti Pembayaran --}}
        <div class="card payment-card mb-4">
            <div class="card-body">
                <h5 class="fw-bold text-primary mb-3">💳 Bukti Pembayaran</h5>
                <hr>
                @if ($order->payment && $order->payment->proof_image)
                    <div class="row g-4">
                        <div class="col-md-6">
                            <p><strong>Bank / E-Wallet:</strong> {{ $order->payment->bank_name }}</p>
                            <p><strong>Nama Akun:</strong> {{ $order->payment->account_name }}</p>
                            <p><strong>Status:</strong>
                                <span
                                    class="badge bg-{{ $order->payment->status === 'verified' ? 'success' : ($order->payment->status === 'rejected' ? 'danger' : 'warning') }} px-3 py-2">
                                    {{ $order->payment->status_label }}
                                </span>
                            </p>
                            @if ($order->payment->admin_note)
                                <p><strong>Catatan Admin:</strong> {{ $order->payment->admin_note }}</p>
                            @endif
                        </div>
                        <div class="col-md-6 text-center">
                            <img src="{{ asset('storage/' . $order->payment->proof_image) }}" alt="Bukti Pembayaran"
                                class="img-fluid rounded shadow-sm border" style="max-width: 300px;">
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

        {{-- Tombol Kembali --}}
        <div class="d-flex justify-content-start">
            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali ke Daftar Order
            </a>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .order-card,
        .payment-card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .order-card .card-body,
        .payment-card .card-body {
            padding: 2rem;
        }

        .badge {
            font-size: 0.9rem;
            font-weight: 600;
        }

        @media (max-width: 576px) {
            .order-card .card-body,
            .payment-card .card-body {
                padding: 1.5rem;
            }
        }
    </style>
@endpush
