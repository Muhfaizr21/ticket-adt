@extends('layouts.app')

@section('title', 'Search Events')
@section('page-title', 'Search Results')

@section('content')
    <div class="container py-5">
        <h2 class="mb-4">Search Results for: <span class="text-primary">{{ $search ?? 'All Events' }}</span></h2>

        @if ($events->count() > 0)
            <div class="row g-4">
                @foreach ($events as $event)
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm">
                            <img src="{{ asset('storage/' . $event->poster) }}" class="card-img-top" alt="{{ $event->name }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $event->name }}</h5>
                                <p class="card-text"><i class="bi bi-geo-alt"></i> {{ $event->location }}</p>
                                <p class="card-text"><i class="bi bi-calendar"></i> {{ $event->date->format('d M Y') }}</p>
                                <a href="{{ route('events.show', $event->id) }}" class="btn btn-primary">View Details</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-warning mt-4">No events found for your search.</div>
        @endif
    </div>
@endsection