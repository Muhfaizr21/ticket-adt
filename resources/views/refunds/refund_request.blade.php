@extends('layouts.app')

@section('title', 'Request Refund')

@push('styles')
    <style>
        .refund-container {
            max-width: 700px;
            margin: 40px auto;
            background: #fff;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .refund-container h2 {
            font-weight: 600;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            font-weight: 500;
        }

        .btn-submit {
            margin-top: 15px;
        }

        .alert-info {
            font-size: 14px;
        }
    </style>
@endpush

@section('content')
    <div class="refund-container">
        <h2>💸 Refund Pesanan: {{ $order->event->name }}</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Jika refund ditolak --}}
        @if($order->refund_status == 'rejected')
            <div class="alert alert-danger">
                <h5>Refund Ditolak</h5>
                <p><strong>Alasan Admin:</strong> {{ $order->refund_reason_admin ?? 'Tidak ada catatan' }}</p>
                <p>Jika Anda ingin mengajukan refund lagi, silakan kirim request baru.</p>
            </div>

            <form action="{{ route('refunds.store') }}" method="POST">
                @csrf
                <input type="hidden" name="order_id" value="{{ $order->id }}">
                <div class="form-group">
                    <label for="user_reason">Alasan Refund Baru</label>
                    <textarea name="user_reason" id="user_reason" class="form-control" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-warning btn-submit">Kirim Request Refund</button>
            </form>

            {{-- Jika refund disetujui admin --}}
        @elseif($order->refund_status == 'approved')
            <div class="alert alert-success">
                <h5>Refund Disetujui</h5>
                <p><strong>Alasan Refund:</strong> {{ $order->refund_reason ?? '-' }}</p>
                <p>Silakan isi informasi biaya / bank agar admin dapat memproses pengembalian dana.</p>
            </div>

            <form action="{{ route('refunds.store') }}" method="POST">
                @csrf
                <input type="hidden" name="order_id" value="{{ $order->id }}">

                <div class="form-group">
                    <label for="bank_info">Informasi Bank / Metode Pengembalian Dana</label>
                    <input type="text" name="bank_info" id="bank_info" class="form-control"
                        placeholder="Contoh: BCA 12345678 a/n John Doe" required>
                </div>

                <button type="submit" class="btn btn-success btn-submit">Kirim Info Refund</button>
            </form>

            {{-- Jika refund belum diajukan / pending --}}
        @else
            <div class="alert alert-info">
                <p>Silakan isi alasan refund Anda untuk mengajukan request.</p>
            </div>

            <form action="{{ route('refunds.store') }}" method="POST">
                @csrf
                <input type="hidden" name="order_id" value="{{ $order->id }}">

                <div class="form-group">
                    <label for="user_reason">Alasan Refund</label>
                    <textarea name="user_reason" id="user_reason" class="form-control" rows="3" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary btn-submit">Kirim Request Refund</button>
            </form>
        @endif
    </div>
@endsection