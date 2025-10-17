@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Metode Pembayaran</h3>
        <a href="{{ route('admin.payment_methods.create') }}" class="btn btn-primary">Tambah Metode</a>
    </div>

    <table class="table table-bordered table-striped align-middle shadow-sm">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Tipe</th>
                <th>Nama</th>
                <th>Nomor Rekening</th>
                <th>Nama Pemilik</th>
                <th>QR Code</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($methods as $method)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ strtoupper($method->type) }}</td>
                <td>{{ $method->name }}</td>
                <td>{{ $method->account_number ?? '-' }}</td>
                <td>{{ $method->account_name ?? '-' }}</td>
                <td>
                    @if($method->qr_code_image)
                        <img src="{{ asset('storage/' . $method->qr_code_image) }}" alt="QR Code" width="60" class="rounded shadow-sm">
                    @else
                        -
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.payment_methods.edit', $method->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('admin.payment_methods.destroy', $method->id) }}" method="POST" class="d-inline delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-sm btn-danger btn-delete">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Belum ada metode pembayaran.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Animate.css (optional untuk animasi yang lebih smooth) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Alert konfirmasi hapus
    const deleteButtons = document.querySelectorAll('.btn-delete');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function () {
            const form = this.closest('form');
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Data ini akan dihapus secara permanen.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // Alert sukses setelah penghapusan
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            },
            timer: 2000,
            showConfirmButton: false
        });
    @endif
});
</script>
@endsection
