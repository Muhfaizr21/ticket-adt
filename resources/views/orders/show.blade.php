@extends('layouts.app')

@section('title', 'Detail Order')

@section('content')
    <div class="container py-5">
        <h2 class="fw-bold mb-4">📦 Detail Order</h2>

        {{-- Flash Message --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{-- Informasi Order --}}
        <div class="card shadow-sm mb-4 border-0">
            <div class="card-body">
                <h5 class="fw-bold text-primary">📝 Informasi Order</h5>
                <hr>
                <p><strong>Kode Order:</strong> {{ $order->barcode_code }}</p>

                {{-- Barcode dan QR Code --}}
                <div class="my-4 text-center">
                    @php
                        use Milon\Barcode\DNS1D;
                        use SimpleSoftwareIO\QrCode\Facades\QrCode;

                        // Barcode (1D)
                        $barcode = new DNS1D();
                        $barcode->setStorPath(public_path('barcodes/'));
                        $barcodeImage = $barcode->getBarcodePNG($order->barcode_code, 'C39+', 2, 80, [0, 0, 0], true);
                    @endphp

                    {{-- Barcode --}}
                    <div class="mb-4">
                        <h6 class="fw-semibold text-dark">🔢 Barcode</h6>
                        <img src="data:image/png;base64,{{ $barcodeImage }}" alt="Barcode"
                            class="img-fluid border rounded shadow-sm" style="max-width: 300px;">
                    </div>

                    {{-- QR Code --}}
                    <div>
                        <h6 class="fw-semibold text-dark">🔲 QR Code</h6>
                        {!! QrCode::size(200)->margin(1)->generate($order->barcode_code) !!}
                    </div>

                    <p class="mt-3 text-muted">
                        📸 <strong>Screenshot atau simpan kode ini untuk proses check-in di lokasi acara.</strong>
                    </p>
                </div>

                {{-- Tombol Download Barcode --}}
                @if ($order->payment && $order->payment->status === 'verified')
                    <a href="{{ route('orders.downloadBarcode', $order->id) }}" class="btn btn-success mb-3">
                        <i class="bi bi-download"></i> Download Barcode
                    </a>
                @endif

                <p><strong>Event:</strong> {{ $order->event->name ?? '-' }}</p>
                <p><strong>Tipe Tiket:</strong> {{ $order->ticketType->name ?? '-' }}</p>
                <p><strong>Jumlah Tiket:</strong> {{ $order->quantity }}</p>
                <p><strong>Total Harga:</strong> Rp{{ number_format($order->total_price, 0, ',', '.') }}</p>

                @if ($order->ticketType->promotions && $order->ticketType->promotions->count() > 0)
                    <p><strong>Promo:</strong>
                        @foreach ($order->ticketType->promotions as $promo)
                            <span class="badge bg-info">
                                {{ $promo->name }} -
                                {{ $promo->persen_diskon ? $promo->persen_diskon . '%' : 'Rp' . number_format($promo->value, 0, ',', '.') }}
                            </span>
                        @endforeach
                    </p>
                @endif

                <p><strong>Status Order:</strong>
                    <span
                        class="badge bg-{{ $order->status === 'paid' ? 'success' : ($order->status === 'pending' ? 'warning' : 'danger') }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </p>

                <p><strong>Status Pembayaran:</strong>
                    @if ($order->payment)
                        <span
                            class="badge bg-{{ $order->payment->status === 'verified' ? 'success' : ($order->payment->status === 'rejected' ? 'danger' : 'warning') }}">
                            {{ $order->payment->status_label }}
                        </span>
                    @else
                        <span class="badge bg-secondary">Belum Upload</span>
                    @endif
                </p>
            </div>
        </div>

        {{-- Bukti Pembayaran --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <h5 class="fw-bold text-primary">💳 Bukti Pembayaran</h5>
                <hr>
                @if ($order->payment && $order->payment->proof_image)
                    <p><strong>Bank / E-Wallet:</strong> {{ $order->payment->bank_name }}</p>
                    <p><strong>Nama Akun / Rekening:</strong> {{ $order->payment->account_name }}</p>
                    <p><strong>Status:</strong>
                        <span
                            class="badge bg-{{ $order->payment->status === 'verified' ? 'success' : ($order->payment->status === 'rejected' ? 'danger' : 'warning') }}">
                            {{ $order->payment->status_label }}
                        </span>
                    </p>
                    @if ($order->payment->admin_note)
                        <p><strong>Catatan Admin:</strong> {{ $order->payment->admin_note }}</p>
                    @endif
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Bukti Pembayaran:</label><br>
                        <img src="{{ asset('storage/' . $order->payment->proof_image) }}" alt="Bukti Pembayaran"
                            class="img-fluid rounded shadow-sm" style="max-width: 300px;">
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