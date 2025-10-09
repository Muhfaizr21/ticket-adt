@extends('admin.layouts.app')

@section('title', 'Manajemen Promo')

@section('content')
    <div class="page-header mb-4">
        <h2 class="fw-semibold text-dark">🎫 Manajemen Promo per Event & Tipe Tiket</h2>
    </div>

    @foreach ($events as $event)
        <div class="card shadow-sm mb-4 border-0">
            <div class="card-header bg-primary text-white fw-semibold">
                {{ $event->name }}
            </div>
            <div class="card-body">
                <table class="table table-hover align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Tipe Tiket</th>
                            <th>Harga</th>
                            <th>Harga Setelah Diskon</th>
                            <th>Promo Aktif</th>
                            <th>Diskon</th>
                            <th>Periode</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($event->ticketTypes as $ticketType)
                            @php
                                $promotion = $ticketType->promotions->first(); // ambil promo pertama
                                $originalPrice = $ticketType->price;
                                $discountLabel = '-';
                                $periode = '-';
                                $statusBadge = '<span class="badge bg-secondary">Tidak Aktif</span>';
                                $isActive = false;
                                $finalPrice = $originalPrice;

                                if ($promotion) {
                                    // Tentukan label diskon
                                    if ($promotion->persen_diskon) {
                                        $discountLabel = $promotion->persen_diskon . '%';
                                        $finalPrice = $originalPrice - ($originalPrice * $promotion->persen_diskon / 100);
                                    } elseif ($promotion->value) {
                                        $discountLabel = 'Rp ' . number_format($promotion->value, 0, ',', '.');
                                        $finalPrice = $originalPrice - $promotion->value;
                                    }

                                    // Pastikan tidak negatif
                                    if ($finalPrice < 0)
                                        $finalPrice = 0;

                                    // Tentukan periode dan status aktif
                                    $periode = \Carbon\Carbon::parse($promotion->start_date)->format('d M Y') . ' - ' .
                                        \Carbon\Carbon::parse($promotion->end_date)->format('d M Y');

                                    $isActive = $promotion->is_active == 1 && now()->between($promotion->start_date, $promotion->end_date);
                                    $statusBadge = '<span class="badge ' . ($isActive ? 'bg-success' : 'bg-secondary') . '">' .
                                        ($isActive ? 'Aktif' : 'Nonaktif') . '</span>';

                                    // Jika promo tidak aktif, harga setelah diskon = harga asli
                                    if (!$isActive) {
                                        $finalPrice = $originalPrice;
                                    }
                                }
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="fw-semibold">{{ $ticketType->name }}</td>
                                <td>Rp {{ number_format($originalPrice, 0, ',', '.') }}</td>
                                <td class="text-success fw-semibold">Rp {{ number_format($finalPrice, 0, ',', '.') }}</td>
                                <td>{{ $promotion->name ?? '-' }}</td>
                                <td>{!! $discountLabel !!}</td>
                                <td>{{ $periode }}</td>
                                <td>{!! $statusBadge !!}</td>
                                <td>
                                    @if ($promotion)
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
                                    @else
                                        <a href="{{ route('admin.promotions.create', ['event_id' => $event->id, 'ticket_type_id' => $ticketType->id]) }}"
                                            class="btn btn-sm btn-primary">
                                            <i class="bi bi-plus-circle"></i> Tambah Promo
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-muted">Belum ada tipe tiket untuk event ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach

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