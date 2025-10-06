@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-semibold text-dark">Manajemen Event</h2>
    <a href="{{ route('admin.events.create') }}" class="btn btn-primary shadow-sm">
        <i class="bi bi-plus-circle me-1"></i> Tambah Event
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body table-responsive">
        <table class="table table-hover align-middle text-center">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Poster</th>
                    <th>Nama Event</th>
                    <th>Harga Tiket</th>
                    <th>Total Tiket</th>
                    <th>Tiket Tersedia</th>
                    <th>Dibuat Pada</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($events as $event)
                <tr>
                    <td>{{ $loop->iteration }}</td>

                    {{-- Poster Event --}}
                    <td>
                        @if($event->poster)
                            <img src="{{ asset('storage/' . $event->poster) }}"
                                 alt="Poster {{ $event->name }}"
                                 class="rounded shadow-sm"
                                 style="width: 80px; height: 80px; object-fit: cover;">
                        @else
                            <span class="text-muted fst-italic">Tidak ada</span>
                        @endif
                    </td>

                    <td class="fw-semibold">{{ $event->name }}</td>
                    <td>Rp {{ number_format($event->price, 0, ',', '.') }}</td>
                    <td>{{ $event->total_tickets }}</td>
                    <td>{{ $event->available_tickets }}</td>
                    <td>{{ $event->created_at->format('d M Y') }}</td>

                    {{-- Tombol Aksi --}}
                    <td>
                        <a href="{{ route('admin.events.edit', $event->id) }}"
                           class="btn btn-sm btn-warning me-1" title="Edit Event">
                            <i class="bi bi-pencil-square"></i>
                        </a>

                        <form id="delete-form-{{ $event->id }}"
                              action="{{ route('admin.events.destroy', $event->id) }}"
                              method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-sm btn-danger"
                                    onclick="confirmDelete({{ $event->id }})"
                                    title="Hapus Event">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">
                        <i class="bi bi-calendar-x fs-3 d-block mb-2"></i>
                        Belum ada event yang ditambahkan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- SweetAlert Notifikasi & Konfirmasi --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmDelete(id) {
    Swal.fire({
        title: 'Yakin ingin menghapus?',
        text: 'Event yang dihapus tidak dapat dikembalikan!',
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

@if(session('success'))
Swal.fire({
    icon: 'success',
    title: 'Berhasil!',
    text: '{{ session('success') }}',
    timer: 2000,
    showConfirmButton: false
});
@endif

@if(session('error'))
Swal.fire({
    icon: 'error',
    title: 'Gagal!',
    text: '{{ session('error') }}',
    timer: 2500,
    showConfirmButton: false
});
@endif
</script>
@endsection
