@extends('admin.layouts.app')

@section('content')
    <div class="container mt-4">
        <h3>Tambah Metode Pembayaran</h3>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.payment_methods.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Tipe Metode -->
            <div class="mb-3">
                <label for="type" class="form-label">Tipe</label>
                <select name="type" id="type" class="form-select" required>
                    <option value="">-- Pilih Tipe --</option>
                    <option value="bank" {{ old('type') == 'bank' ? 'selected' : '' }}>Bank</option>
                    <option value="qris" {{ old('type') == 'qris' ? 'selected' : '' }}>QRIS</option>
                </select>
            </div>

            <!-- Nama Metode -->
            <div class="mb-3">
                <label for="name" class="form-label">Nama Metode</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
            </div>

            <!-- Nomor Rekening (hanya untuk bank) -->
            <div class="mb-3 bank-field" style="display:none;">
                <label for="account_number" class="form-label">Nomor Rekening</label>
                <input type="text" name="account_number" id="account_number" class="form-control"
                    value="{{ old('account_number') }}">
            </div>

            <!-- Nama Pemilik Rekening (hanya untuk bank) -->
            <div class="mb-3 bank-field" style="display:none;">
                <label for="account_name" class="form-label">Nama Pemilik Rekening</label>
                <input type="text" name="account_name" id="account_name" class="form-control"
                    value="{{ old('account_name') }}">
            </div>

            <!-- QR Code (hanya untuk QRIS) -->
            <div class="mb-3 qris-field" style="display:none;">
                <label for="qr_code_image" class="form-label">QR Code</label>
                <input type="file" name="qr_code_image" id="qr_code_image" class="form-control">
            </div>

            <!-- Tombol Submit -->
            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="{{ route('admin.payment_methods.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>

    <!-- Script Toggle Field Berdasarkan Tipe -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const typeSelect = document.getElementById('type');
            const bankFields = document.querySelectorAll('.bank-field');
            const qrisFields = document.querySelectorAll('.qris-field');

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
        });
    </script>
@endsection