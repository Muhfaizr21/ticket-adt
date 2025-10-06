@extends('admin.layouts.app')

@section('title', 'Ticket Types')

@section('content')
<div class="page-header mb-4 d-flex justify-content-between align-items-center">
    <h2 class="fw-semibold text-dark">🏷️ Ticket Types</h2>
    <a href="{{ route('admin.ticket-types.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Ticket Type
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
                    <th>Nama</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ticketTypes as $type)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $type->name }}</td>
                    <td>{{ $type->description }}</td>
                    <td>
                        <a href="{{ route('admin.ticket-types.edit', $type) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.ticket-types.destroy', $type) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted py-4">
                        <i class="bi bi-tags fs-3 d-block mb-2"></i>
                        Belum ada kategori tiket.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
