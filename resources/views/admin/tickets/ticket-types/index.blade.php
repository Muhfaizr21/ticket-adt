@extends('admin.layouts.app')

@section('title', 'Kategori Tiket')

@section('content')
<div class="page-header mb-4 d-flex justify-content-between align-items-center">
    <h2 class="fw-semibold text-dark">🎟️ Kategori Tiket</h2>
    <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali ke Event
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card shadow-sm border-0">
    <div class="card-body">
        <table class="table table-hover align-middle text-center">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Nama Event</th>
                    <th>Kategori</th>
                    <th>Jumlah Tiket</th>
                    <th>Harga (Rp)</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($events as $index => $event)
                    {{-- VIP --}}
                    <tr>
                        <td>{{ ($index + 1) }}A</td>
                        <td>{{ $event->name }}</td>
                        <td><span class="badge bg-warning text-dark">VIP</span></td>
                        <td>{{ $event->vip_tickets ?? ceil($event->total_tickets * 0.3) }}</td>
                        <td>Rp {{ number_format($event->vip_price ?? $event->price * 1.5, 0, ',', '.') }}</td>
                        <td rowspan="2">
                            <a href="{{ route('admin.ticket-types.edit', $event->id) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil-square me-1"></i> Edit
                            </a>
                        </td>
                    </tr>

                    {{-- Reguler --}}
                    <tr>
                        <td>{{ ($index + 1) }}B</td>
                        <td>{{ $event->name }}</td>
                        <td><span class="badge bg-primary">Reguler</span></td>
                        <td>{{ $event->reguler_tickets ?? floor($event->total_tickets * 0.7) }}</td>
                        <td>Rp {{ number_format($event->reguler_price ?? $event->price, 0, ',', '.') }}</td>
                        {{-- Kolom aksi kosong karena sudah di rowspan --}}
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="bi bi-tags fs-3 d-block mb-2"></i>
                            Belum ada event yang tersedia.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
