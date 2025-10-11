@extends('admin.layouts.app')

@section('content')
    <div class="container py-5">
        <h1 class="mb-4">📋 Daftar Pesanan</h1>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Event</th>
                        <th>Jumlah Tiket</th>
                        <th>Total Harga</th>
                        <th>Status Order / Pembayaran</th>
                        <th>Metode Pembayaran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td>{{ $loop->iteration + ($orders->currentPage() - 1) * $orders->perPage() }}</td>
                            <td>{{ $order->user->name }}</td>
                            <td>{{ $order->event->name }}</td>
                            <td>{{ $order->quantity }}</td>
                            <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                            <td>
                                <span
                                    class="badge bg-{{ $order->status == 'paid' ? 'success' : ($order->status == 'pending' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                                @if($order->payment)
                                    <br>
                                    <small class="text-muted">
                                        Status Pembayaran:
                                        <span
                                            class="badge bg-{{ $order->payment->status == 'verified' ? 'success' : ($order->payment->status == 'rejected' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($order->payment->status) }}
                                        </span>
                                    </small>
                                @endif
                            </td>
                            <td>
                                @if($order->payment)
                                    {{-- Bank --}}
                                    @if($order->payment->bank_name)
                                        <strong>Bank:</strong> {{ $order->payment->bank_name }}<br>
                                        <strong>No. Rek:</strong> {{ $order->payment->account_number ?? '-' }}<br>
                                        <strong>Atas Nama:</strong> {{ $order->payment->account_name ?? '-' }}
                                    @endif

                                    {{-- QRIS --}}
                                    @if($order->payment->qr_code_image)
                                        <strong>QRIS:</strong><br>
                                        <img src="{{ asset('storage/' . $order->payment->qr_code_image) }}" alt="QRIS"
                                            style="max-width:100px;">
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order->id) }}"
                                    class="btn btn-sm btn-primary mb-1 w-100">Detail</a>

                                {{-- Verifikasi / Tolak pembayaran --}}
                                @if($order->status == 'pending' && $order->payment)
                                    <form action="{{ route('admin.orders.verify-payment', $order->id) }}" method="POST"
                                        class="d-flex gap-1 mt-1">
                                        @csrf
                                        <button type="submit" name="status" value="verified"
                                            class="btn btn-sm btn-success flex-fill">Verifikasi</button>
                                        <button type="submit" name="status" value="rejected"
                                            class="btn btn-sm btn-danger flex-fill">Tolak</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Belum ada pesanan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-3">
            {{ $orders->links() }}

        </div>
    </div>
@endsection