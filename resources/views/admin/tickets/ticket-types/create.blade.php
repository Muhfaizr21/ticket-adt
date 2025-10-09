@extends('admin.layouts.app')

@section('title', 'Tambah Tipe Tiket')

@section('content')
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-semibold text-dark mb-0">➕ Tambah Tipe Tiket</h2>
        <a href="{{ route('admin.ticket-types.index') }}" class="btn btn-secondary shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0 fw-semibold">Form Tambah Tipe Tiket untuk Event: {{ $event->name }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.ticket-types.store', ['event' => $event->id]) }}" method="POST"
                class="needs-validation" novalidate>
                @csrf

                <div class="row g-4">
                    {{-- Nama Tipe Tiket --}}
                    <div class="col-md-6">
                        <label for="name" class="form-label fw-semibold">Nama Tipe Tiket</label>
                        <input type="text" name="name" id="name" class="form-control" required value="{{ old('name') }}">
                        <div class="invalid-feedback">Nama tipe tiket wajib diisi.</div>
                        @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    {{-- Harga --}}
                    <div class="col-md-6">
                        <label for="price" class="form-label fw-semibold">Harga (Rp)</label>
                        <input type="number" name="price" id="price" class="form-control" step="0.01" min="0" required
                            value="{{ old('price') }}">
                        <div class="invalid-feedback">Harga wajib diisi.</div>
                        @error('price') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div class="col-md-12">
                        <label for="description" class="form-label fw-semibold">Deskripsi</label>
                        <textarea name="description" id="description" rows="3"
                            class="form-control">{{ old('description') }}</textarea>
                        @error('description') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    {{-- Total Tiket --}}
                    <div class="col-md-6">
                        <label for="total_tickets" class="form-label fw-semibold">Total Tiket</label>
                        <input type="number" name="total_tickets" id="total_tickets" class="form-control" min="1" required
                            value="{{ old('total_tickets') }}">
                        <div class="invalid-feedback">Total tiket wajib diisi.</div>
                        @error('total_tickets') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    {{-- Tiket Tersedia --}}
                    <div class="col-md-6">
                        <label for="available_tickets" class="form-label fw-semibold">Tiket Tersedia</label>
                        <input type="number" name="available_tickets" id="available_tickets" class="form-control" min="0"
                            required value="{{ old('available_tickets') }}">
                        <div class="invalid-feedback">Jumlah tiket tersedia wajib diisi.</div>
                        @error('available_tickets') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-save me-1"></i> Simpan
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