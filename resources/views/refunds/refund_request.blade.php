@extends('layouts.app')

@section('title', 'Ajukan Refund')

@section('content')
    <div class="container py-5">
        <h2 class="fw-bold mb-4">🔄 Ajukan Refund Tiket</h2>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="fw-semibold mb-3">Order: {{ $order->barcode_code }}</h5>
                <p><strong>Event:</strong> {{ $order->event->name ?? '-' }}</p>
                <p><strong>Tipe Tiket:</strong> {{ $order->ticketType->name ?? '-' }}</p>
                <p><strong>Jumlah:</strong> {{ $order->quantity }}</p>
                <p><strong>Total:</strong> Rp{{ number_format($order->total_price, 0, ',', '.') }}</p>

                <form action="{{ route('refunds.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="order_id" value="{{ $order->id }}">

                    <div class="mb-3">
                        <label for="reason" class="form-label fw-semibold">Alasan Refund</label>
                        <textarea name="reason" id="reason" rows="4" class="form-control"
                            required>{{ old('reason') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-arrow-counterclockwise"></i> Ajukan Refund
                    </button>
                    <a href="{{ route('orders.index') }}" class="btn btn-secondary ms-2">Batal</a>
                </form>
            </div>
        </div>
    </div>
@endsection