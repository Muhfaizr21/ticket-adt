@extends('layouts.app')

@section('title', 'Buat Order Baru / Update')

@section('content')
<div class="container py-5" style="max-width: 1000px;">
    <h2 class="fw-bold mb-5 text-center" style="color:#03346E;">
        🛒 Buat Order Baru / Update
    </h2>

    {{-- ALERT --}}
    @foreach (['success' => 'success', 'error' => 'danger'] as $key => $type)
        @if(session($key))
            <div class="alert alert-{{ $type }} alert-dismissible fade show shadow-sm mb-4">
                {{ session($key) }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    @endforeach

    {{-- FORM --}}
    <div class="card border-0 shadow-lg p-4 rounded-4">
        <form action="{{ route('orders.store') }}" method="POST">
            @csrf
            <input type="hidden" name="event_id" value="{{ $event->id }}">
            <input type="hidden" name="order_id" value="{{ $order->id ?? '' }}">

            @php
                use Carbon\Carbon;
                $today = Carbon::now();
            @endphp

            {{-- =============================
                CARD: PILIH TIPE TIKET
            ============================== --}}
            <div class="form-section mb-5">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-primary text-white fw-bold fs-5 py-3">
                        🎟️ Pilih Tiket
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            @foreach($event->ticketTypes as $ticket)
                                @php
                                    $promo = $ticket->promotions
                                        ->where('is_active', 1)
                                        ->where('start_date', '<=', $today)
                                        ->where('end_date', '>=', $today)
                                        ->first();

                                    $originalPrice = $ticket->price;
                                    $finalPrice = $promo
                                        ? ($promo->persen_diskon
                                            ? $originalPrice - ($originalPrice * $promo->persen_diskon / 100)
                                            : $originalPrice - $promo->value)
                                        : $originalPrice;
                                @endphp

                                <div class="col-md-6">
                                    <div class="ticket-box card p-3 shadow-sm border-0 cursor-pointer 
                                        {{ isset($order) && $order->ticket_type_id == $ticket->id ? 'active' : '' }}"
                                        data-id="{{ $ticket->id }}">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="fw-semibold mb-0">{{ $ticket->name }}</h6>
                                            @if($promo)
                                                <span class="badge bg-success">{{ $promo->code }}</span>
                                            @endif
                                        </div>
                                        <div>
                                            @if($promo)
                                                <small class="text-muted d-block">
                                                    <del>Rp {{ number_format($originalPrice,0,',','.') }}</del>
                                                </small>
                                            @endif
                                            <span class="text-primary fw-bold fs-6">
                                                Rp {{ number_format($finalPrice,0,',','.') }}
                                            </span>
                                            <div class="text-muted small mt-1">
                                                Tersisa: {{ $ticket->available_tickets }} tiket
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <input type="hidden" name="ticket_type_id" id="ticket_type_id" required>
                    </div>
                </div>
            </div>

            {{-- JUMLAH TIKET --}}
            <div class="form-section mb-5">
                <label for="quantity" class="form-label fw-semibold text-dark mb-2">Jumlah Tiket</label>
                <input type="number" name="quantity" id="quantity"
                       class="form-control form-control-lg shadow-sm py-2"
                       min="1" value="{{ $order->quantity ?? 1 }}" required>
            </div>

            {{-- KODE PROMO --}}
            <div class="form-section mb-5">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-primary text-white fw-bold fs-5 py-3">
                        🎁 Gunakan Kode Promo (Opsional)
                    </div>
                    <div class="card-body p-4">
                        @php
                            $activePromos = ($event->promotions ?? collect())
                                ->where('is_active', 1)
                                ->where('start_date', '<=', $today)
                                ->where('end_date', '>=', $today);
                        @endphp
                        <select name="promo_code" id="promo_code"
                                class="form-select form-select-lg shadow-sm py-2">
                            <option value="">-- Pilih Kode Promo --</option>
                            @foreach($activePromos as $promo)
                                <option value="{{ $promo->code }}" {{ isset($order) && $order->promo_code == $promo->code ? 'selected' : '' }}>
                                    {{ $promo->code }} ({{ $promo->name }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- =============================
                CARD: PILIH METODE PEMBAYARAN
            ============================== --}}
            <div class="form-section mb-5">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-primary text-white fw-bold fs-5 py-3">
                        💳 Pilih Metode Pembayaran
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            @foreach($paymentMethods as $method)
                                <div class="col-md-6">
                                    <div class="payment-box card p-3 shadow-sm border-0 cursor-pointer 
                                        {{ isset($order) && $order->payment && $order->payment->method_id == $method->id ? 'active' : '' }}"
                                        data-id="{{ $method->id }}">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="fw-semibold mb-0">{{ $method->name }}</h6>
                                            @if($method->type === 'qris')
                                                <span class="badge bg-success">QRIS</span>
                                            @endif
                                        </div>
                                        <div class="payment-details" style="display:none;">
                                            @if($method->type === 'bank')
                                                <p class="mb-1"><strong>No. Rekening:</strong> {{ $method->account_number }}</p>
                                                <p class="mb-1"><strong>Atas Nama:</strong> {{ $method->account_name }}</p>
                                                <small class="text-muted">Transfer sesuai nominal order.</small>
                                            @elseif($method->type === 'qris')
                                                <p class="mb-1"><strong>Scan QRIS:</strong></p>
                                                <img src="{{ $method->qr_code_image ? asset('storage/' . $method->qr_code_image) : '' }}"
                                                     alt="QRIS" class="img-fluid mt-2 rounded" style="max-width:150px;">
                                            @elseif($method->type === 'ewallet')
                                                <p class="mb-1"><strong>Nomor / ID:</strong> {{ $method->account_number ?? '-' }}</p>
                                                <small class="text-muted">Gunakan e-wallet Anda untuk membayar.</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <input type="hidden" name="payment_method" id="payment_method">
                    </div>
                </div>
            </div>

            {{-- SUBMIT --}}
            <div class="text-center mt-5">
                <button type="submit"
                        class="btn btn-primary btn-lg w-100 py-3 shadow-sm rounded-3 fw-semibold"
                        style="background: linear-gradient(45deg,#aebac9,#2575FC); border:none;">
                    <i class="bi bi-cart-fill me-2"></i> Buat Order
                </button>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
.form-section {
    padding-top: 10px;
    padding-bottom: 10px;
}
.ticket-box, .payment-box {
    cursor: pointer;
    transition: all 0.3s ease-in-out;
    border: 1px solid transparent;
    border-radius: 10px;
    background-color: #fff;
}
.ticket-box:hover, .payment-box:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.1);
}
.ticket-box.active, .payment-box.active {
    border: 2px solid #03346E;
    background: linear-gradient(135deg,#e9f3ff,#f8fbff);
    box-shadow: 0 8px 18px rgba(0,0,0,0.15);
}
.payment-details {
    border-top: 1px dashed #ccc;
    padding-top: 8px;
    margin-top: 6px;
    font-size: 0.85rem;
}
.cursor-pointer { cursor: pointer; }
.card-header {
    background: linear-gradient(90deg,#aebac9,#0056D2);
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Pilih tiket
    const ticketBoxes = document.querySelectorAll('.ticket-box');
    const ticketInput = document.getElementById('ticket_type_id');
    ticketBoxes.forEach(box => {
        box.addEventListener('click', function() {
            ticketBoxes.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            ticketInput.value = this.dataset.id;
        });
    });

    // Pilih metode pembayaran
    const paymentBoxes = document.querySelectorAll('.payment-box');
    const paymentInput = document.getElementById('payment_method');
    paymentBoxes.forEach(box => {
        box.addEventListener('click', function() {
            paymentBoxes.forEach(b => {
                b.classList.remove('active');
                b.querySelector('.payment-details').style.display = 'none';
            });
            this.classList.add('active');
            this.querySelector('.payment-details').style.display = 'block';
            paymentInput.value = this.dataset.id;
        });
    });
});
</script>
@endpush
@endsection
