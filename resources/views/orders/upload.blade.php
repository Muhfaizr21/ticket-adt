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
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Detail Order --}}
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-body">
            <h5 class="fw-bold text-primary">📦 Detail Order</h5>
            <hr>
            <p><strong>Kode Order:</strong> {{ $order->barcode_code }}</p>
            <p><strong>Event:</strong> {{ $order->event->name ?? '-' }}</p>
            <p><strong>Tipe Tiket:</strong> {{ $order->ticketType->name ?? '-' }}</p>
            <p><strong>Jumlah Tiket:</strong> {{ $order->quantity }}</p>
            <p><strong>Total Harga:</strong> Rp{{ number_format($order->total_price,0,',','.') }}</p>
            <p><strong>Status Pembayaran:</strong>
                @if($order->payment)
                    <span class="badge bg-{{ $order->payment->status === 'verified' ? 'success' : ($order->payment->status === 'rejected' ? 'danger' : 'warning') }}">
                        {{ $order->payment->status_label }}
                    </span>
                @else
                    <span class="badge bg-secondary">Belum Upload</span>
                @endif
            </p>

            {{-- Info Metode Pembayaran --}}
            @if($order->payment_method)
                <p><strong>Metode Pembayaran:</strong> {{ $order->payment_method }}</p>
                @if($order->payment_method_type === 'bank')
                    <p><strong>No. Rekening:</strong> {{ $order->payment_method_info['account_number'] }}</p>
                    <p><strong>Atas Nama:</strong> {{ $order->payment_method_info['account_name'] }}</p>
                @elseif($order->payment_method_type === 'qris')
                    <p><strong>Scan QRIS berikut untuk membayar:</strong></p>
                    <img src="{{ asset('storage/' . $order->payment_method_info['qr_code_image']) }}" alt="QRIS" style="max-width:200px;">
                @endif
            @endif
        </div>
    </div>

    {{-- Form Upload Bukti Pembayaran --}}
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('orders.upload.store', $order->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Nama Bank / E-Wallet --}}
                <div class="mb-3">
                    <label for="bank_name" class="form-label fw-semibold">Nama Bank / E-Wallet</label>
                    <input type="text" name="bank_name" id="bank_name" class="form-control"
                        value="{{ old('bank_name', $order->payment->bank_name ?? $order->payment_method) }}" readonly>
                </div>

                {{-- Nama Pemilik Rekening / Akun --}}
                <div class="mb-3">
                    <label for="account_name" class="form-label fw-semibold">Nama Pemilik Akun / Rekening</label>
                    <input type="text" name="account_name" id="account_name" class="form-control"
                        value="{{ old('account_name', $order->payment->account_name ?? Auth::user()->name) }}" readonly>
                </div>

                {{-- Upload Bukti Pembayaran --}}
                <div class="mb-3">
                    <label for="proof_image" class="form-label fw-semibold">Upload Bukti Pembayaran</label>
                    <input type="file" name="proof_image" id="proof_image" class="form-control" accept="image/*" required>
                    <small class="text-muted">Format: JPG, PNG, JPEG. Maksimal 2MB.</small>
                </div>

                {{-- Preview Bukti Pembayaran Lama --}}
                @if($order->payment && $order->payment->proof_image)
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Bukti Pembayaran Sebelumnya:</label><br>
                        <img src="{{ asset('storage/' . $order->payment->proof_image) }}" alt="Bukti Pembayaran"
                             class="img-fluid rounded shadow-sm" style="max-width:300px;">
                    </div>
                @endif

                {{-- Tombol --}}
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali ke Daftar Order
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-upload"></i> Upload Bukti Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
