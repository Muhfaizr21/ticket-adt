@extends('layouts.app')

@section('title', 'Daftar Pesanan Saya')

@push('styles')
<style>
    .order-container {
        max-width: 1000px;
        margin: 30px auto;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        padding: 20px;
    }

    .order-container h2 {
        font-weight: 600;
        margin-bottom: 20px;
    }

    .order-table {
        width: 100%;
        border-collapse: collapse;
    }

    .order-table thead {
        background: #f8f9fa;
    }

    .order-table th, .order-table td {
        padding: 12px 15px;
        text-align: left;
        font-size: 14px;
    }

    .order-table tbody tr:hover {
        background: #f5f5f5;
        transition: 0.2s ease;
    }

    .order-table td i {
        cursor: pointer;
        font-size: 18px;
    }

    .badge-status {
        padding: 4px 8px;
        border-radius: 5px;
        font-size: 12px;
    }

    .badge-pending { background-color: #ffc107; color: #000; }
    .badge-success { background-color: #28a745; color: #fff; }
    .badge-none { background-color: #6c757d; color: #fff; }

    @media (max-width: 768px) {
        .order-table {
            display: block;
            overflow-x: auto;
        }
    }
</style>
@endpush

@section('content')
<div class="order-container">
    <h2>📄 Daftar Pesanan Saya</h2>
    <table class="order-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Event</th>
                <th>Tipe Tiket</th>
                <th>Jumlah</th>
                <th>Total</th>
                <th>Status Pembayaran</th>
                <th>Status Refund</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $index => $order)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $order->event->name }}</td>
                    <td>{{ $order->ticketType->name }}</td>
                    <td>{{ $order->quantity }}</td>
                    <td>Rp{{ number_format($order->total, 0, ',', '.') }}</td>
                    <td>
                        @if($order->payment_status == 'Pending')
                            <span class="badge-status badge-pending">Pending</span>
                        @elseif($order->payment_status == 'Success')
                            <span class="badge-status badge-success">Success</span>
                        @else
                            <span class="badge-status badge-none">None</span>
                        @endif
                    </td>
                    <td>{{ $order->refund_status ?? 'None' }}</td>
                    <td class="d-flex gap-2">
                        <!-- Tombol Lihat -->
                        <a href="{{ route('orders.show', $order->id) }}" title="Lihat Detail">
                            <i class="bi bi-eye text-primary"></i>
                        </a>
                        <!-- Tombol Hapus -->
                        <form action="{{ route('orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Yakin ingin hapus pesanan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="border:none;background:none;padding:0;">
                                <i class="bi bi-trash text-danger"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
