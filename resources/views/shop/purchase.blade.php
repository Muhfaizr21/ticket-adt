@extends('layouts.app')

@section('title', 'Pembelian Tiket')

@section('content')
<div class="container my-5">
    <h2>Pembelian Tiket: {{ $event->name }}</h2>

    <div class="card p-4 shadow-sm mt-3">
        <p><strong>Tanggal:</strong> {{ $event->date }}</p>
        <p><strong>Lokasi:</strong> {{ $event->location }}</p>
        <p><strong>Harga:</strong> Rp {{ number_format($event->price, 0, ',', '.') }}</p>
        <p><strong>Tiket Tersedia:</strong> {{ $event->available_tickets }}</p>

        <form action="{{ route('tickets.store') }}" method="POST">
            @csrf
            <input type="hidden" name="event_id" value="{{ $event->id }}">
            <div class="mb-3">
                <label for="quantity" class="form-label">Jumlah Tiket</label>
                <input type="number" name="quantity" id="quantity" class="form-control" min="1" max="{{ $event->available_tickets }}" value="1" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Beli Sekarang</button>
        </form>
    </div>
</div>
@endsection
