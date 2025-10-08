@extends('admin.layouts.app')

@section('title', 'Manajemen Promo')

@section('content')
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-semibold text-dark">🎫 Manajemen Promo Tiket</h2>
        <a href="{{ route('admin.promotions.create') }}" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-circle me-1"></i> Tambah Promo
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <table class="table table-hover align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Kode</th>
                        <th>Nama Promo</th>
                        <th>Event</th>
                        <th>Tipe Tiket</th>
                        <th>Harga Asli</th>
                        <th>Harga Setelah Diskon</th>
                        <th>Jenis</th>
                        <th>Nilai</th>
                        <th>Periode</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($promotions as $promotion)
                        @php
                            $ticket = $promotion->ticketType;
                            $originalPrice = $ticket->price ?? 0;
                            $discountedPrice = $promotion->getDiscountedPrice($originalPrice);
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-semibold">{{ $promotion->code }}</td>
                            <td>{{ $promotion->name }}</td>
                            <td>{{ $ticket->event->name ?? '-' }}</td>
                            <td>{{ $ticket->name ?? '-' }}</td>
                            <td>{{ $originalPrice ? 'Rp ' . number_format($originalPrice, 0, ',', '.') : '-' }}</td>
                            <td>{{ $discountedPrice ? 'Rp ' . number_format($discountedPrice, 0, ',', '.') : '-' }}</td>
                            <td>
                                <span class="badge {{ $promotion->type == 'percentage' ? 'bg-info text-dark' : 'bg-success' }}">
                                    {{ ucfirst($promotion->type) }}
                                </span>
                            </td>
                            <td>
                                {{ $promotion->type == 'percentage' ? $promotion->value . '%' : 'Rp ' . number_format($promotion->value, 0, ',', '.') }}
                            </td>
                            <td>{{ \Carbon\Carbon::parse($promotion->start_date)->format('d M Y') }} -
                                {{ \Carbon\Carbon::parse($promotion->end_date)->format('d M Y') }}</td>
                            <td>
                                <span class="badge {{ $promotion->isCurrentlyActive() ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $promotion->isCurrentlyActive() ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.promotions.edit', $promotion->id) }}"
                                    class="btn btn-sm btn-warning me-1">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form id="delete-form-{{ $promotion->id }}"
                                    action="{{ route('admin.promotions.destroy', $promotion->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger"
                                        onclick="confirmDelete({{ $promotion->id }})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="text-center text-muted py-4">
                                <i class="bi bi-ticket-x fs-3 d-block mb-2"></i>
                                Belum ada promo yang ditambahkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Yakin ingin menghapus promo?',
                text: 'Promo yang dihapus tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
@endsection