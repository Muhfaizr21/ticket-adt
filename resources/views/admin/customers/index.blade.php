@extends('admin.layouts.app')

@section('title', 'Customers')

@section('content')
<div class="page-header mb-4">
    <h2 class="fw-semibold text-dark">👥 Customers</h2>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body">
        <table class="table table-hover align-middle text-center">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Jumlah Order</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $customer->name }}</td>
                    <td>{{ $customer->email }}</td>
                    <td>{{ $customer->orders_count }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted py-4">
                        <i class="bi bi-people-x fs-3 d-block mb-2"></i>
                        Belum ada pelanggan yang membeli tiket.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
