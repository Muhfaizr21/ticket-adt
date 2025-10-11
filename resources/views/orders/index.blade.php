@extends('layouts.app')
@section('title', 'Daftar Pesanan Saya')

@section('content')
    <div class="container py-5">
        <h2 class="fw-bold mb-4">
            🧾 Daftar Pesanan Saya
        </h2>

        {{-- ✅ Notifikasi --}}
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

        {{-- ✅ Jika belum ada order --}}
        @if ($orders->isEmpty())
            <div class="text-center py-5">
                <img src="{{ asset('images/empty-cart.svg') }}" alt="No Orders" style="width: 150px;" class="mb-3">
                <h5 class="fw-semibold text-secondary mb-2">
                    Belum ada pesanan yang dibuat.
                </h5>
                <p class="text-muted mb-4">
                    Yuk, lihat event menarik dan beli tiket sekarang!
                </p>
                <a href="{{ route('shop.index') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-cart-plus"></i> Order Sekarang
                </a>
            </div>
        @else
            {{-- ✅ Tabel Daftar Order --}}
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle shadow-sm border rounded-3">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Event</th>
                            <th>Tipe Tiket</th>
                            <th>Jumlah</th>
                            <th>Total</th>
                            <th>Status Pembayaran</th>
                            <th>Status Refund</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="fw-semibold text-primary">
                                        {{ $order->event->name ?? '-' }}
                                    </div>
                                    <small class="text-muted">
                                        {{ $order->event->date ? \Carbon\Carbon::parse($order->event->date)->format('d M Y') : '' }}
                                    </small>
                                </td>
                                <td>{{ $order->ticketType->name ?? '-' }}</td>
                                <td>{{ $order->quantity }}</td>
                                <td>Rp{{ number_format($order->total_price, 0, ',', '.') }}</td>
                                <td>
                                    @php
                                        $badge = match ($order->status) {
                                            'paid' => 'success',
                                            'cancelled' => 'danger',
                                            default => 'warning'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $badge }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $refundBadge = match ($order->refund_status) {
                                            'approved' => 'success',
                                            'requested' => 'warning',
                                            'rejected' => 'danger',
                                            default => 'secondary'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $refundBadge }}">
                                        {{ ucfirst($order->refund_status) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-info me-1">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>

                                    {{-- Jika order masih pending, tampilkan tombol hapus --}}
                                    @if ($order->status === 'pending')
                                        <form action="{{ route('orders.destroy', $order->id) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Yakin ingin menghapus pesanan ini?')">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- ✅ Pagination --}}
            <div class="d-flex justify-content-center mt-4">
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endsection

@push('styles')
    <style>
        table th,
        table td {
            vertical-align: middle !important;
        }

        .table thead th {
            font-weight: 600;
        }

        .badge {
            font-size: 0.9rem;
            padding: .5em .8em;
            border-radius: .5rem;
        }

        .btn-lg {
            padding: 0.7rem 1.5rem;
            font-size: 1rem;
            border-radius: 0.5rem;
        }
    </style>
@endpush