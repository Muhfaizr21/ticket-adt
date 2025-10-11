@extends('layouts.app')

@section('title', 'Buat Order Baru')

@section('content')
<div class="container py-5">
    <h2 class="fw-bold mb-4">🛒 Buat Order Baru</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('orders.store') }}" method="POST">
                @csrf
                <input type="hidden" name="event_id" value="{{ $event->id }}">

                {{-- Tipe Tiket --}}
                <div class="mb-3">
                    <label for="ticket_type_id" class="form-label fw-semibold">Tipe Tiket</label>
                    <select name="ticket_type_id" id="ticket_type_id" class="form-select" required>
                        <option value="">-- Pilih Tipe Tiket --</option>
                        @foreach($event->ticketTypes as $ticket)
                            <option value="{{ $ticket->id }}">
                                {{ $ticket->name }} - Rp{{ number_format($ticket->price,0,',','.') }}
                                (Tersisa: {{ $ticket->available_tickets }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Jumlah --}}
                <div class="mb-3">
                    <label for="quantity" class="form-label fw-semibold">Jumlah Tiket</label>
                    <input type="number" name="quantity" id="quantity" class="form-control" min="1" value="1" required>
                </div>

                {{-- Promo --}}
                <div class="mb-3">
                    <label for="promo_code" class="form-label fw-semibold">Kode Promo (Opsional)</label>
                    <input type="text" name="promo_code" id="promo_code" class="form-control" placeholder="Masukkan kode promo jika ada">
                </div>

                {{-- Metode Pembayaran --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Metode Pembayaran</label>
                    <select name="payment_method" id="payment_method" class="form-select" required>
                        <option value="">-- Pilih Metode Pembayaran --</option>
                        @foreach($paymentMethods as $method)
                            <option value="{{ $method->id }}" data-type="{{ $method->type }}">
                                {{ $method->name }} {{ $method->type === 'qris' ? '(QRIS)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Info Bank / QRIS --}}
                <div id="payment-info" class="mb-3" style="display:none;">
                    <div id="bank-info" style="display:none;">
                        <p><strong>No. Rekening:</strong> <span id="account-number"></span></p>
                        <p><strong>Atas Nama:</strong> <span id="account-name"></span></p>
                    </div>
                    <div id="qris-info" style="display:none;">
                        <p><strong>Scan QRIS berikut untuk membayar:</strong></p>
                        <img id="qris-image" src="" alt="QRIS" class="img-fluid" style="max-width:200px;">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Buat Order</button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentMethods = @json($paymentMethods);

    const paymentSelect = document.getElementById('payment_method');
    const paymentInfo = document.getElementById('payment-info');
    const bankInfo = document.getElementById('bank-info');
    const qrisInfo = document.getElementById('qris-info');
    const accountNumber = document.getElementById('account-number');
    const accountName = document.getElementById('account-name');
    const qrisImage = document.getElementById('qris-image');

    paymentSelect.addEventListener('change', function() {
        const selectedId = parseInt(this.value);
        const selected = paymentMethods.find(m => m.id === selectedId);

        if (!selected) {
            paymentInfo.style.display = 'none';
            bankInfo.style.display = 'none';
            qrisInfo.style.display = 'none';
            return;
        }

        paymentInfo.style.display = 'block';

        if(selected.type === 'bank') {
            bankInfo.style.display = 'block';
            qrisInfo.style.display = 'none';
            accountNumber.innerText = selected.account_number || '-';
            accountName.innerText = selected.account_name || '-';
        } else if(selected.type === 'qris') {
            bankInfo.style.display = 'none';
            qrisInfo.style.display = 'block';
            qrisImage.src = selected.qr_code_image ? '/storage/' + selected.qr_code_image : '';
        }
    });
});
</script>
@endsection
