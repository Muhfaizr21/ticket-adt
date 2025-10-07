@extends('admin.layouts.app')

@section('title', 'Manajemen Promo')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-semibold text-dark">Manajemen Promo</h2>
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
                    <th>Jenis</th>
                    <th>Nilai</th>
                    <th>Berlaku</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($promotions as $promotion)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td class="fw-semibold">{{ $promotion->code }}</td>
                    <td>{{ $promotion->name }}</td>
                    <td>
                        <span class="badge {{ $promotion->type == 'percentage' ? 'bg-info' : 'bg-success' }}">
                            {{ ucfirst($promotion->type) }}
                        </span>
                    </td>
                    <td>
                        {{ $promotion->type == 'percentage' ? $promotion->value.'%' : 'Rp '.number_format($promotion->value,0,',','.') }}
                    </td>
                    <td>{{ \Carbon\Carbon::parse($promotion->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($promotion->end_date)->format('d M Y') }}</td>
                    <td>
                        <span class="badge {{ $promotion->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $promotion->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.promotions.edit', $promotion->id) }}" class="btn btn-sm btn-warning me-1" title="Edit">
                            <i class="bi bi-pencil-square"></i>
                        </a>

                        <form id="delete-form-{{ $promotion->id }}" action="{{ route('admin.promotions.destroy', $promotion->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete({{ $promotion->id }})" title="Hapus">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">
                        <i class="bi bi-ticket-x fs-3 d-block mb-2"></i>
                        Belum ada promo yang ditambahkan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- SweetAlert Notifikasi --}}
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
