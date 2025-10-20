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

    .order-table th,
    .order-table td {
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
    .badge-failed { background-color: #dc3545; color: #fff; }
    .badge-none { background-color: #6c757d; color: #fff; }
    .badge-refund { background-color: #17a2b8; color: #fff; }

    .action-buttons {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

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
                <td>Rp{{ number_format($order->total_price, 0, ',', '.') }}</td>
                <td>
                    @php $status = $order->payment ? $order->payment->status : 'none'; @endphp
                    @if($status == 'pending')
                        <span class="badge-status badge-pending">Pending</span>
                    @elseif($status == 'verified' || $status == 'success')
                        <span class="badge-status badge-success">Success</span>
                    @elseif($status == 'failed')
                        <span class="badge-status badge-failed">Failed</span>
                    @else
                        <span class="badge-status badge-none">None</span>
                    @endif
                </td>
                <td>
                    @if($order->refund_status == 'none')
                        <span class="badge-status badge-none">None</span>
                    @else
                        <span class="badge-status badge-refund">{{ ucfirst($order->refund_status) }}</span>
                    @endif
                </td>
                <td>
                    <div class="action-buttons">
                        <!-- Lihat Detail -->
                        <a href="{{ route('orders.show', $order->id) }}" title="Lihat Detail" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i> Detail
                        </a>

                        <!-- Hapus (hanya jika pending) -->
                        @if($order->status == 'pending')
                        <form action="{{ route('orders.destroy', $order->id) }}" method="POST"
                            onsubmit="return confirm('Yakin ingin hapus pesanan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </form>
                        @endif

                        <!-- Request Refund (hanya jika paid & belum request / rejected) -->
                        @if($order->status == 'paid')
                            @if($order->refund_status == 'none' || $order->refund_status == 'rejected')
                                <a href="{{ route('refunds.create', $order->id) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-arrow-counterclockwise"></i> Refund
                                </a>
                            @elseif($order->refund_status == 'approved')
                                <a href="{{ route('refunds.create', $order->id) }}" class="btn btn-sm btn-success">
                                    <i class="bi bi-cash-stack"></i> Kirim Info Transfer
                                </a>
                            @endif
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-3">
        {{ $orders->links() }}
    </div>
</div>
@endsection
