@extends('admin.layouts.app')

@section('content')
    <div class="container py-5">
        <h1 class="mb-4">📦 Detail Order</h1>

        {{-- Alert --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{-- Info User --}}
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Info User</h5>
                <p><strong>Nama:</strong> {{ $order->user->name }}</p>
                <p><strong>Email:</strong> {{ $order->user->email }}</p>
                <p><strong>Tanggal Order:</strong> {{ $order->created_at->format('d M Y H:i') }}</p>
            </div>
        </div>

        {{-- Info Event & Tiket --}}
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Info Event & Tiket</h5>
                <p><strong>Event:</strong> {{ $order->event->name }}</p>
                <p><strong>Tipe Tiket:</strong> {{ $order->ticketType->name }}</p>
                <p><strong>Jumlah Tiket:</strong> {{ $order->quantity }}</p>
                <p><strong>Total Harga:</strong> Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                <p><strong>Status Order:</strong>
                    <span
                        class="badge bg-{{ $order->status == 'paid' ? 'success' : ($order->status == 'cancelled' ? 'danger' : 'warning') }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </p>
                <p><strong>Kode Unik Tiket:</strong> <code>{{ $order->barcode_code }}</code></p>
                <p><strong>QR Code Tiket:</strong></p>
                <div>{!! QrCode::size(200)->generate($order->barcode_code) !!}</div>
            </div>
        </div>


        {{-- Bukti Pembayaran --}}
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Bukti Pembayaran</h5>
                @if($order->payment && $order->payment->proof_image)
                    <p><strong>Status Pembayaran:</strong>
                        <span
                            class="badge bg-{{ $order->payment->status == 'verified' ? 'success' : ($order->payment->status == 'rejected' ? 'danger' : 'warning') }}">
                            {{ ucfirst($order->payment->status) }}
                        </span>
                    </p>
                    <p><strong>Nama Pemilik Rekening:</strong> {{ $order->payment->account_name ?? '-' }}</p>
                    <p><strong>Bank / QRIS:</strong> {{ $order->payment->bank_name ?? '-' }}</p>
                    <p><strong>Bukti:</strong></p>
                    <img src="{{ asset('storage/' . $order->payment->proof_image) }}" alt="Bukti Pembayaran"
                        style="max-width:300px;">
                @else
                    <p class="text-muted">Belum ada bukti pembayaran.</p>
                @endif
            </div>
        </div>

        {{-- Verifikasi Pembayaran --}}
        @if($order->status == 'pending' && $order->payment)
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Verifikasi Pembayaran</h5>
                    <form action="{{ route('admin.orders.verify-payment', $order->id) }}" method="POST" class="d-flex gap-2">
                        @csrf
                        <button type="submit" name="status" value="verified" class="btn btn-success">Verifikasi</button>
                        <button type="submit" name="status" value="rejected" class="btn btn-danger">Tolak</button>
                    </form>
                </div>
            </div>
        @endif

        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Kembali ke Daftar Order</a>
    </div>
@endsection