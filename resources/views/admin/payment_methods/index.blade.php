@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Metode Pembayaran</h3>
        <a href="{{ route('admin.payment_methods.create') }}" class="btn btn-primary">Tambah Metode</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
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
                        <img src="{{ asset('storage/' . $method->qr_code_image) }}" alt="QR Code" width="60">
                    @else
                        -
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.payment_methods.edit', $method->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('admin.payment_methods.destroy', $method->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus metode ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Hapus</button>
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
@endsection
