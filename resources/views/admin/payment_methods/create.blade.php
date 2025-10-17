@extends('admin.layouts.app')

@section('title', 'Tambah Metode Pembayaran')

@push('styles')
<style>
    .form-container {
        max-width: 600px;
        margin: 0 auto;
    }

    .form-floating > label {
        font-size: 0.85rem;
        color: #6c757d;
    }

    .form-floating .form-control,
    .form-floating .form-select {
        border-radius: 10px;
        padding: 1rem 0.75rem 0.25rem 0.75rem;
        font-size: 0.95rem;
    }

    .form-floating .form-control:focus,
    .form-floating .form-select:focus {
        border-color: #198754;
        box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25);
    }

    .card {
        border-radius: 12px;
    }

    .btn {
        border-radius: 8px;
        padding: 8px 18px;
    }

    .bank-field, .qris-field {
        display: none;
    }

    #qr-preview {
        max-width: 200px;
        max-height: 200px;
        border-radius: 12px;
        display: none;
        margin-top: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }
</style>
@endpush

@section('content')
<div class="page-header mb-4">
    <h2 class="fw-semibold text-dark">Tambah Metode Pembayaran</h2>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body form-container">
        @if($errors->any())
            <div class="alert alert-danger mb-4">
                <ul class="mb-0">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.payment_methods.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Tipe -->
            <div class="form-floating mb-3">
                <select name="type" id="type" class="form-select" required>
                    <option value="">-- Pilih Tipe --</option>
                    <option value="bank" {{ old('type') == 'bank' ? 'selected' : '' }}>Bank</option>
                    <option value="qris" {{ old('type') == 'qris' ? 'selected' : '' }}>QRIS</option>
                </select>
                <label for="type">Tipe</label>
            </div>

            <!-- Nama Metode -->
            <div class="form-floating mb-3">
                <input type="text" name="name" id="name" class="form-control" placeholder="Nama Metode" value="{{ old('name') }}" required>
                <label for="name">Nama Metode</label>
            </div>

            <!-- Nomor Rekening -->
            <div class="form-floating mb-3 bank-field">
                <input type="text" name="account_number" id="account_number" class="form-control" placeholder="Nomor Rekening" value="{{ old('account_number') }}">
                <label for="account_number">Nomor Rekening</label>
            </div>

            <!-- Nama Pemilik Rekening -->
            <div class="form-floating mb-3 bank-field">
                <input type="text" name="account_name" id="account_name" class="form-control" placeholder="Nama Pemilik Rekening" value="{{ old('account_name') }}">
                <label for="account_name">Nama Pemilik Rekening</label>
            </div>

            <!-- QR Code -->
            <div class="mb-3 qris-field">
                <label for="qr_code_image" class="form-label fw-semibold">QR Code (Upload)</label>
                <input type="file" name="qr_code_image" id="qr_code_image" class="form-control" accept="image/png,image/jpeg">
                <small class="text-muted d-block mt-1">Format: JPG, JPEG, PNG (maks. 2MB)</small>
                <img id="qr-preview" alt="Preview QR Code">
            </div>

            <!-- Tombol -->
            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('admin.payment_methods.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-success">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Script Toggle & Preview -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const typeSelect = document.getElementById('type');
        const bankFields = document.querySelectorAll('.bank-field');
        const qrisFields = document.querySelectorAll('.qris-field');
        const qrInput = document.getElementById('qr_code_image');
        const qrPreview = document.getElementById('qr-preview');

        function toggleFields() {
            if (typeSelect.value === 'bank') {
                bankFields.forEach(f => f.style.display = 'block');
                qrisFields.forEach(f => f.style.display = 'none');
            } else if (typeSelect.value === 'qris') {
                bankFields.forEach(f => f.style.display = 'none');
                qrisFields.forEach(f => f.style.display = 'block');
            } else {
                bankFields.forEach(f => f.style.display = 'none');
                qrisFields.forEach(f => f.style.display = 'none');
            }
        }

        typeSelect.addEventListener('change', toggleFields);
        toggleFields();

        qrInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (ev) {
                    qrPreview.src = ev.target.result;
                    qrPreview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                qrPreview.style.display = 'none';
            }
        });
    });
</script>
@endsection
