@extends('admin.layouts.app')

@section('title', 'Refund Requests')

@section('content')
<div class="page-header mb-4">
    <h2 class="fw-semibold text-dark">🔄 Semua Pengajuan Refund</h2>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <table class="table table-hover align-middle text-center">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Event</th>
                    <th>Tipe Tiket</th>
                    <th>Jumlah</th>
                    <th>Alasan Refund</th>
                    <th>Status</th>
                    <th>Bank Info</th>
                    <th>Pengajuan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($refunds as $refund)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $refund->user->name }}</td>
                        <td>{{ $refund->user->email }}</td>
                        <td>{{ $refund->event->name ?? '-' }}</td>
                        <td>{{ $refund->ticketType->name ?? '-' }}</td>
                        <td>{{ $refund->quantity }}</td>
                        <td>{{ $refund->refund_reason ?? '-' }}</td>
                        <td>
                            @php
                                $badge = match ($refund->refund_status) {
                                    'requested' => 'warning',
                                    'approved' => 'success',
                                    'rejected' => 'danger',
                                    default => 'secondary',
                                };
                            @endphp
                            <span class="badge bg-{{ $badge }}">{{ ucfirst($refund->refund_status) }}</span>
                        </td>
                        <td>{{ $refund->bank_info ?? '-' }}</td>
                        <td>{{ $refund->updated_at->format('d M Y H:i') }}</td>
                        <td>
                            @if($refund->refund_status === 'requested')
                                {{-- Tombol Setuju --}}
                                <form
                                    action="{{ route('admin.customers.refunds.update', ['order' => $refund->id, 'status' => 'approved']) }}"
                                    method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-success mb-1">
                                        <i class="bi bi-check-circle"></i> Setuju
                                    </button>
                                </form>

                                {{-- Tombol Tolak --}}
                                <form
                                    action="{{ route('admin.customers.refunds.update', ['order' => $refund->id, 'status' => 'rejected']) }}"
                                    method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-danger mb-1">
                                        <i class="bi bi-x-circle"></i> Tolak
                                    </button>
                                </form>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center text-muted py-4">
                            <i class="bi bi-arrow-counterclockwise fs-3 d-block mb-2"></i>
                            Belum ada pengajuan refund.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
