@extends('admin.layouts.app')

@section('title', 'Laporan Penjualan Tiket')

@section('content')
<div class="container mt-4">
    <h3 class="fw-bold mb-4">📊 Laporan Penjualan Tiket</h3>

    {{-- Filter --}}
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-3">
            <label class="form-label">Tanggal Mulai</label>
            <input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control">
        </div>
        <div class="col-md-3">
            <label class="form-label">Tanggal Akhir</label>
            <input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control">
        </div>
        <div class="col-md-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="all">Semua</option>
                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Lunas</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
            </select>
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button class="btn btn-primary me-2">Filter</button>
            <a href="{{ route('admin.reports.pdf', request()->all()) }}" class="btn btn-danger">Export PDF</a>
        </div>
    </form>

    {{-- Statistik --}}
    <div class="row text-center mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 p-3">
                <h6 class="text-muted">Total Pendapatan</h6>
                <h4 class="fw-bold text-success">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</h4>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0 p-3">
                <h6 class="text-muted">Total Tiket Terjual</h6>
                <h4 class="fw-bold text-primary">{{ $totalTickets }}</h4>
            </div>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <table class="table table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Event</th>
                        <th>Venue</th>
                        <th>User</th>
                        <th>Tiket</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $order->event->name ?? '-' }}</td>
                            <td>{{ $order->event->venue->name ?? '-' }}</td>
                            <td>{{ $order->user->name ?? '-' }}</td>
                            <td>{{ $order->quantity }}</td>
                            <td>Rp{{ number_format($order->total_price, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge bg-{{ $order->status == 'paid' ? 'success' : 'warning' }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>{{ $order->created_at->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-3">Tidak ada data laporan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="d-flex justify-content-center">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
