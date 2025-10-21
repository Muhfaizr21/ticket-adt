@extends('layouts.app')

@section('title', 'Buat Order Baru / Update')

@section('content')
<div class="container py-5" style="padding-left:2cm; padding-right:2cm;">
    <h2 class="fw-bold mb-5 text-center" style="color:#03346E;">🛒 Buat Order Baru / Update</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <form action="{{ route('orders.store') }}" method="POST" class="card shadow-lg p-4 rounded-4 border-0">
                @csrf
                <input type="hidden" name="event_id" value="{{ $event->id }}">
                <input type="hidden" name="order_id" value="{{ $order->id ?? '' }}">

                @php
                    use Carbon\Carbon;
                    $today = Carbon::now();
                @endphp

                {{-- Tipe Tiket --}}
                <div class="mb-4">
                    <label for="ticket_type_id" class="form-label fw-semibold">Tipe Tiket</label>
                    <div class="row g-3">
                        @foreach($event->ticketTypes as $ticket)
                            @php
                                $ticketPromos = $ticket->promotions ?? collect();
                                $promo = $ticketPromos
                                    ->where('is_active', 1)
                                    ->where('start_date', '<=', $today)
                                    ->where('end_date', '>=', $today)
                                    ->first();

                                $originalPrice = $ticket->price;
                                $finalPrice = $originalPrice;

                                if($promo) {
                                    if($promo->persen_diskon) {
                                        $finalPrice -= ($finalPrice * $promo->persen_diskon / 100);
                                    } elseif($promo->value) {
                                        $finalPrice -= $promo->value;
                                    }
                                }
                            @endphp
                            <div class="col-md-6">
                                <div class="ticket-box card p-3 rounded-3 shadow-sm cursor-pointer 
                                    {{ isset($order) && $order->ticket_type_id == $ticket->id ? 'active' : '' }}"
                                    data-id="{{ $ticket->id }}">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="fw-semibold">{{ $ticket->name }}</span>
                                        @if($promo)
                                            <span class="badge bg-success text-white">{{ $promo->code }}</span>
                                        @endif
                                    </div>
                                    <div class="mt-2">
                                        @if($promo)
                                            <span class="text-muted"><del>Rp {{ number_format($originalPrice,0,',','.') }}</del></span>
                                        @endif
                                        <strong class="text-primary fw-bold fs-6">Rp {{ number_format($finalPrice, 0, ',', '.') }}</strong>
                                        <p class="mb-0 text-muted" style="font-size:0.85rem;">Tersisa: {{ $ticket->available_tickets }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <input type="hidden" name="ticket_type_id" id="ticket_type_id" required>
                </div>

                {{-- Jumlah Tiket --}}
                <div class="mb-4">
                    <label for="quantity" class="form-label fw-semibold">Jumlah Tiket</label>
                    <input type="number" name="quantity" id="quantity" class="form-control form-control-lg shadow-sm" min="1"
                        value="{{ $order->quantity ?? 1 }}" required>
                </div>

                {{-- Kode Promo --}}
                <div class="mb-4">
                    <label for="promo_code" class="form-label fw-semibold">Kode Promo (Opsional)</label>
                    @php
                        $eventPromos = $event->promotions ?? collect();
                        $activePromos = $eventPromos
                            ->where('is_active', 1)
                            ->where('start_date', '<=', $today)
                            ->where('end_date', '>=', $today);
                    @endphp
                    <select name="promo_code" id="promo_code" class="form-control form-control-lg shadow-sm">
                        <option value="">-- Pilih Kode Promo --</option>
                        @foreach($activePromos as $promo)
                            <option value="{{ $promo->code }}" {{ (isset($order) && $order->promo_code == $promo->code) ? 'selected' : '' }}>
                                {{ $promo->code }} ({{ $promo->name }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Metode Pembayaran --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">Metode Pembayaran</label>
                    <div class="row g-3">
                        @foreach($paymentMethods as $method)
                            <div class="col-md-6">
                                <div class="payment-box card p-3 rounded-3 shadow-sm cursor-pointer 
                                    {{ isset($order) && $order->payment && $order->payment->method_id == $method->id ? 'active' : '' }}"
                                    data-id="{{ $method->id }}">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="fw-semibold">{{ $method->name }}</span>
                                        @if($method->type === 'qris')
                                            <span class="badge bg-success text-white">QRIS</span>
                                        @endif
                                    </div>
                                    <div class="payment-details" style="display:none;">
                                        @if($method->type === 'bank')
                                            <p class="mb-1"><strong>No. Rekening:</strong> {{ $method->account_number }}</p>
                                            <p class="mb-1"><strong>Atas Nama:</strong> {{ $method->account_name }}</p>
                                            <p class="mb-0 text-muted" style="font-size:0.85rem;">Silakan transfer sesuai nominal order.</p>
                                        @elseif($method->type === 'qris')
                                            <p class="mb-1"><strong>Scan QRIS berikut untuk membayar:</strong></p>
                                            <img src="{{ $method->qr_code_image ? asset('storage/' . $method->qr_code_image) : '' }}" alt="QRIS" class="img-fluid mt-2 rounded" style="max-width:150px;">
                                            <p class="mb-0 text-muted" style="font-size:0.85rem;">Scan dan lakukan pembayaran sesuai nominal order.</p>
                                        @elseif($method->type === 'ewallet')
                                            <p class="mb-1"><strong>Nomor / ID:</strong> {{ $method->account_number ?? '-' }}</p>
                                            <p class="mb-0 text-muted" style="font-size:0.85rem;">Gunakan e-wallet Anda untuk membayar.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <input type="hidden" name="payment_method" id="payment_method">
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100 mt-3 shadow-sm" style="background: linear-gradient(45deg,#03346E,#2575FC); border:none;">
                    <i class="bi bi-cart-fill me-2"></i> Buat Order
                </button>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
/* Animasi & Tampilan Elegan */
.ticket-box, .payment-box {
    cursor:pointer;
    transition: all 0.3s ease-in-out;
    border:1px solid transparent;
}
.ticket-box:hover, .payment-box:hover {
    transform: translateY(-4px);
    box-shadow:0 8px 20px rgba(0,0,0,0.2);
}
.ticket-box.active, .payment-box.active {
    border:2px solid #03346E;
    box-shadow:0 10px 25px rgba(0,0,0,0.25);
    background: linear-gradient(135deg,#e0f2ff,#f0f8ff);
}
.ticket-box .badge, .payment-box .badge {
    font-size:0.75rem;
}
del { color:#999; margin-right:5px; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Pilih tiket
    const tickets = document.querySelectorAll('.ticket-box');
    const ticketInput = document.getElementById('ticket_type_id');
    tickets.forEach(box => {
        box.addEventListener('click', function() {
            tickets.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            ticketInput.value = this.dataset.id;
        });
        @if(isset($order) && $order->ticket_type_id)
            if(this.dataset.id == "{{ $order->ticket_type_id }}") {
                this.classList.add('active');
                ticketInput.value = this.dataset.id;
            }
        @endif
    });

    // Pilih pembayaran
    const boxes = document.querySelectorAll('.payment-box');
    const hiddenInput = document.getElementById('payment_method');
    boxes.forEach(box => {
        box.addEventListener('click', function() {
            boxes.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            hiddenInput.value = this.dataset.id;

            boxes.forEach(b => b.querySelector('.payment-details').style.display = 'none');
            this.querySelector('.payment-details').style.display = 'block';
        });

        @if(isset($order) && $order->payment)
            if(this.dataset.id == "{{ $order->payment->method_id }}") {
                this.classList.add('active');
                this.querySelector('.payment-details').style.display = 'block';
                hiddenInput.value = this.dataset.id;
            }
        @endif
    });
});
</script>
@endpush
@endsection
