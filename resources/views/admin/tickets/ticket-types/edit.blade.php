@extends('admin.layouts.app')

@section('title', 'Edit Tipe Tiket')

@section('content')
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark mb-0">✏️ Edit Tipe Tiket</h2>
        <a href="{{ route('admin.ticket-types.index') }}" class="btn btn-secondary shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
        </a>
    </div>

    {{-- Alert Sukses --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Alert Error --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <strong><i class="bi bi-exclamation-triangle-fill me-2"></i>Terjadi kesalahan:</strong>
            <ul class="mt-2 mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow border-0">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0 fw-semibold">Form Edit Tipe Tiket</h5>
        </div>

        <div class="card-body">
            <form
                action="{{ route('admin.ticket-types.update', ['event' => $ticketType->event_id, 'ticket' => $ticketType->id]) }}"
                method="POST" class="needs-validation" novalidate>
                @csrf
                @method('PUT')

                <div class="row g-4">
                    {{-- Nama Tipe Tiket --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nama Tipe Tiket</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $ticketType->name) }}"
                            required>
                        <div class="invalid-feedback">Nama tipe tiket wajib diisi.</div>
                    </div>

                    {{-- Harga --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Harga (Rp)</label>
                        <input type="number" name="price" class="form-control" step="0.01" min="0"
                            value="{{ old('price', $ticketType->price) }}" required>
                        <div class="invalid-feedback">Harga wajib diisi.</div>
                    </div>

                    {{-- Deskripsi --}}
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <textarea name="description" rows="3"
                            class="form-control">{{ old('description', $ticketType->description) }}</textarea>
                    </div>

                    {{-- Total Tiket --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Total Tiket</label>
                        <input type="number" name="total_tickets" class="form-control" min="0"
                            value="{{ old('total_tickets', $ticketType->total_tickets) }}" required>
                        <div class="invalid-feedback">Jumlah tiket wajib diisi.</div>
                    </div>

                    {{-- Tiket Tersedia --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tiket Tersedia</label>
                        <input type="number" name="available_tickets" class="form-control" min="0"
                            value="{{ old('available_tickets', $ticketType->available_tickets) }}" required>
                        <div class="invalid-feedback">Jumlah tiket tersedia wajib diisi.</div>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Script Validasi Bootstrap --}}
    <script>
        (() => {
            'use strict'
            const forms = document.querySelectorAll('.needs-validation')
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>
@endsection