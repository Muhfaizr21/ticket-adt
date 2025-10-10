@extends('layouts.app')

@section('title', $item->title)
@section('page-title', $item->title)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            {{-- Gambar Berita --}}
            @if($item->image)
            <div class="mb-4 position-relative overflow-hidden rounded shadow-sm news-detail-img-wrapper">
                <img src="{{ asset('storage/' . $item->image) }}"
                     alt="{{ $item->title }}"
                     class="img-fluid news-detail-img w-100">
                <div class="overlay position-absolute top-0 start-0 w-100 h-100"></div>
            </div>
            @endif

            {{-- Judul & Informasi --}}
            <h1 class="fw-bold mb-2">{{ $item->title }}</h1>
            <div class="text-muted mb-4 d-flex flex-wrap gap-2 align-items-center">
                <div><i class="bi bi-person-circle me-1"></i> {{ $item->author ?? 'Admin' }}</div>
                <div class="mx-1">|</div>
                <div><i class="bi bi-calendar-event me-1"></i> {{ $item->published_at ? $item->published_at->format('d M Y') : '-' }}</div>
            </div>

            {{-- Isi Berita --}}
            <div class="news-content mb-5">
                {!! nl2br(e($item->content)) !!}
            </div>

            {{-- Tombol Kembali --}}
            <a href="{{ route('news') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Berita
            </a>

        </div>
    </div>
</div>

@push('styles')
<style>
    body {
        background: #f5f5f7;
        color: #333;
    }

    /* Gambar Berita */
    .news-detail-img-wrapper {
        max-height: 400px;
        overflow: hidden;
        border-radius: 12px;
    }
    .news-detail-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .news-detail-img-wrapper:hover .news-detail-img {
        transform: scale(1.05);
    }

    /* Judul */
    h1 {
        font-size: 2.2rem;
        color: #111;
    }

    /* Konten */
    .news-content {
        font-size: 1.1rem;
        line-height: 1.9;
        color: #444;
        white-space: pre-line;
    }

    /* Tombol */
    .btn-outline-secondary {
        border-radius: 8px;
        padding: 0.5rem 1.25rem;
        font-weight: 500;
        transition: all 0.3s;
    }
    .btn-outline-secondary:hover {
        background-color: #6c757d;
        color: #fff;
    }
</style>
@endpush
@endsection
