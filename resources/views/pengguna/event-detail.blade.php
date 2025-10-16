@extends('layouts.app')

@section('title', $event->name)
@section('page-title', $event->name)

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-6">
            <img src="{{ asset('storage/' . $event->poster) }}" class="img-fluid rounded" alt="{{ $event->name }}">
        </div>
        <div class="col-md-6">
            <h2>{{ $event->name }}</h2>
            <p><i class="bi bi-calendar"></i> {{ $event->date->format('d M Y') }}</p>
            <p><i class="bi bi-geo-alt"></i> {{ $event->location }}</p>
            <p>{{ $event->description }}</p>
            <p><strong>Available Tickets:</strong> {{ $event->available_tickets }}</p>

            <a href="{{ route('pengguna.dashboard') }}" class="btn btn-secondary mt-3">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>
</div>
@endsection
