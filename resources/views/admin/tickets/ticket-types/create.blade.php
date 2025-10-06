@extends('admin.layouts.app')

@section('title', 'Tambah Ticket Type')

@section('content')
<div class="page-header mb-4">
    <h2 class="fw-semibold text-dark">Tambah Ticket Type</h2>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form action="{{ route('admin.ticket-types.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nama Ticket Type</label>
                <input type="text" name="name" id="name" class="form-control" required value="{{ old('name') }}">
                @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Deskripsi</label>
                <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
                @error('description') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
            <button class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.ticket-types.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
