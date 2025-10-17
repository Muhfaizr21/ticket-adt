@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">✏️ Edit Metode Pembayaran</h5>
        </div>
        <div class="card-body p-4">
            @if($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.payment_methods.update', $method->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Tipe -->
                <div class="mb-3">
                    <label for="type" class="form-label fw-semibold">Tipe</label>
                    <select name="type" id="type" class="form-select" required>
                        <option value="">-- Pilih Tipe --</option>
                        <option value="bank" {{ $method->type=='bank'?'selected':'' }}>Bank</option>
                        <option value="qris" {{ $method->type=='qris'?'selected':'' }}>QRIS</option>
                    </select>
                </div>

                <!-- Nama Metode -->
                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">Nama Metode</label>
                    <input type="text" name="name" id="name" class="form-control shadow-sm"
                        value="{{ old('name', $method->name) }}" required>
                </div>

                <!-- Bank Fields -->
                <div class="bank-field mb-3" style="display:none;">
                    <label for="account_number" class="form-label fw-semibold">Nomor Rekening</label>
                    <input type="text" name="account_number" id="account_number" class="form-control shadow-sm"
                        value="{{ old('account_number', $method->account_number) }}">
                </div>

                <div class="bank-field mb-3" style="display:none;">
                    <label for="account_name" class="form-label fw-semibold">Nama Pemilik Rekening</label>
                    <input type="text" name="account_name" id="account_name" class="form-control shadow-sm"
                        value="{{ old('account_name', $method->account_name) }}">
                </div>

                <!-- QRIS Fields -->
                <div class="qris-field mb-3" style="display:none;">
                    <label for="qr_code_image" class="form-label fw-semibold">QR Code</label>
                    @if($method->qr_code_image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $method->qr_code_image) }}" alt="QR Code"
                                width="100" class="rounded shadow-sm border">
                        </div>
                    @endif
                    <input type="file" name="qr_code_image" id="qr_code_image" class="form-control shadow-sm">
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-success px-4">
                        💾 Perbarui
                    </button>
                    <a href="{{ route('admin.payment_methods.index') }}" class="btn btn-secondary px-4">
                        ↩️ Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Animasi + Script Toggle -->
<script>
document.addEventListener('DOMContentLoaded', function(){
    const typeSelect = document.getElementById('type');
    const bankFields = document.querySelectorAll('.bank-field');
    const qrisFields = document.querySelectorAll('.qris-field');

    function toggleFields(){
        if(typeSelect.value === 'bank'){
            bankFields.forEach(f => {
                f.style.display='block';
                f.classList.add('animate__animated', 'animate__fadeIn');
            });
            qrisFields.forEach(f => f.style.display='none');
        } else if(typeSelect.value === 'qris'){
            qrisFields.forEach(f => {
                f.style.display='block';
                f.classList.add('animate__animated', 'animate__fadeIn');
            });
            bankFields.forEach(f => f.style.display='none');
        } else {
            bankFields.forEach(f => f.style.display='none');
            qrisFields.forEach(f => f.style.display='none');
        }
    }

    typeSelect.addEventListener('change', toggleFields);
    toggleFields();
});
</script>

<!-- Animate.css -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
@endsection
