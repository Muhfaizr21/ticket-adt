@php
    use Illuminate\Support\Facades\Auth;
@endphp

@extends('layouts.app')

@section('title', 'Upload Bukti Pembayaran')

@section('content')
<div class="container py-5">
    <h2 class="fw-bold mb-4">💳 Upload Bukti Pembayaran</h2>

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Detail Order --}}
    <div class="card order-card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <h5 class="fw-bold text-primary m-0">📦 Detail Order</h5>
                <span class="badge bg-dark px-3 py-2 fs-6">{{ $order->barcode_code }}</span>
            </div>
            <hr>
            <div class="row g-3">
                <div class="col-md-6">
                    <p><strong>Event:</strong> {{ $order->event->name ?? '-' }}</p>
                    <p><strong>Tipe Tiket:</strong> {{ $order->ticketType->name ?? '-' }}</p>
                    <p><strong>Jumlah Tiket:</strong> {{ $order->quantity }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Total Harga:</strong> Rp{{ number_format($order->total_price,0,',','.') }}</p>
                    <p><strong>Status Pembayaran:</strong>
                        @if($order->payment)
                            <span class="badge bg-{{ $order->payment->status === 'verified' ? 'success' : ($order->payment->status === 'rejected' ? 'danger' : 'warning') }} px-3 py-2">
                                {{ $order->payment->status_label }}
                            </span>
                        @else
                            <span class="badge bg-secondary px-3 py-2">Belum Upload</span>
                        @endif
                    </p>
                </div>
            </div>

            {{-- Info Metode Pembayaran --}}
            @if($order->payment_method)
                <div class="mt-3">
                    <h6 class="fw-bold text-primary mb-2">💰 Metode Pembayaran</h6>
                    <p><strong>Metode:</strong> {{ $order->payment_method }}</p>
                    @if($order->payment_method_type === 'bank')
                        <p><strong>No. Rekening:</strong> {{ $order->payment_method_info['account_number'] }}</p>
                        <p><strong>Atas Nama:</strong> {{ $order->payment_method_info['account_name'] }}</p>
                    @elseif($order->payment_method_type === 'qris')
                        <p><strong>Scan QRIS berikut untuk membayar:</strong></p>
                        <div class="bg-white border rounded shadow-sm p-2 d-inline-block">
                            <img src="{{ asset('storage/' . $order->payment_method_info['qr_code_image']) }}" alt="QRIS" class="img-fluid" style="max-width: 200px;">
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- Form Upload Bukti Pembayaran --}}
    <div class="card payment-card mb-4">
        <div class="card-body">
            <h5 class="fw-bold text-primary mb-3">📤 Form Upload Bukti Pembayaran</h5>
            <hr>
            <form action="{{ route('orders.upload.store', $order->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">
                    {{-- Nama Bank / E-Wallet --}}
                    <div class="col-md-6">
                        <label for="bank_name" class="form-label fw-semibold">Nama Bank / E-Wallet</label>
                        <input type="text" name="bank_name" id="bank_name" class="form-control"
                            value="{{ old('bank_name', $order->payment->bank_name ?? $order->payment_method) }}" readonly>
                    </div>

                    {{-- Nama Pemilik Akun --}}
                    <div class="col-md-6">
                        <label for="account_name" class="form-label fw-semibold">Nama Pemilik Akun / Rekening</label>
                        <input type="text" name="account_name" id="account_name" class="form-control"
                            value="{{ old('account_name', $order->payment->account_name ?? Auth::user()->name) }}" readonly>
                    </div>
                </div>

                {{-- Upload Bukti --}}
                <div class="mt-3">
                    <label for="proof_image" class="form-label fw-semibold">Upload Bukti Pembayaran</label>
                    <input type="file" name="proof_image" id="proof_image" class="form-control" accept="image/*" required>
                    <small class="text-muted">Format: JPG, PNG, JPEG — Maksimal 2MB.</small>
                </div>

                {{-- Preview Bukti Sebelumnya --}}
                @if($order->payment && $order->payment->proof_image)
                    <div class="mt-3">
                        <label class="form-label fw-semibold">Bukti Pembayaran Sebelumnya:</label><br>
                        <img src="{{ asset('storage/' . $order->payment->proof_image) }}" alt="Bukti Pembayaran"
                             class="img-fluid rounded shadow-sm border" style="max-width:300px;">
                    </div>
                @endif

                {{-- Tombol --}}
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-upload"></i> Upload
                    </button>
                </div>
            </form>
        </div>
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

    label.form-label {
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
