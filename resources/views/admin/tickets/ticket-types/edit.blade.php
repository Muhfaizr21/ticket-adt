@extends('admin.layouts.app')

@section('title', 'Edit Ticket Type')

@section('content')
<div class="page-header mb-4">
    <h2 class="fw-semibold text-dark">Edit Ticket Type</h2>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form action="{{ route('admin.ticket-types.update', $ticketType) }}" method="POST">
            @csrf @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Nama Ticket Type</label>
                <input type="text" name="name" id="name" class="form-control" required value="{{ old('name', $ticketType->name) }}">
                @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Deskripsi</label>
                <textarea name="description" id="description" class="form-control">{{ old('description', $ticketType->description) }}</textarea>
                @error('description') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
            <button class="btn btn-primary">Update</button>
            <a href="{{ route('admin.ticket-types.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
