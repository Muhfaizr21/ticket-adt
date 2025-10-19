@extends('layouts.app')

@section('title', $item->title)
@section('page-title', $item->title)

@section('content')
<div class="news-detail-container py-5">
    <div class="container">
        {{-- 🖼 Hero Image --}}
        @if($item->image)
        <div class="news-hero mb-5">
            <div class="image-wrapper position-relative">
                <img src="{{ asset('storage/' . $item->image) }}"
                     alt="{{ $item->title }}"
                     class="news-hero-img img-fluid w-100">
                <div class="hero-overlay position-absolute top-0 start-0 w-100 h-100"></div>
                <div class="hero-title position-absolute bottom-0 start-0 text-white p-4">
                    <h1 class="fw-bold">{{ $item->title }}</h1>
                    <div class="meta d-flex flex-wrap gap-2 mt-2">
                        <span><i class="bi bi-person-circle me-1"></i> {{ $item->author ?? 'Admin' }}</span>
                        <span>|</span>
                        <span><i class="bi bi-calendar-event me-1"></i> {{ $item->published_at ? $item->published_at->format('d M Y') : '-' }}</span>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- 📝 Konten Berita --}}
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="news-content bg-white p-4 p-md-5 rounded shadow-sm mb-5">
                    {!! nl2br(e($item->content)) !!}
                </div>

                {{-- 🔙 Tombol Kembali --}}
                <div class="d-flex justify-content-start">
                    <a href="{{ route('news') }}" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Berita
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    body {
        background-color: #f4f6f8;
        color: #333;
        font-family: 'Poppins', sans-serif;
    }

    /* Hero Image Section */
    .news-hero {
        max-height: 450px;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        position: relative;
    }

    .news-hero-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.8s ease;
    }

    .news-hero:hover .news-hero-img {
        transform: scale(1.05);
    }

    .hero-overlay {
        background: linear-gradient(180deg, rgba(0,0,0,0) 0%, rgba(0,0,0,0.6) 100%);
    }

    .hero-title h1 {
        font-size: 2.5rem;
        line-height: 1.3;
    }

    .hero-title .meta {
        font-size: 0.95rem;
        opacity: 0.9;
    }

    /* Konten */
    .news-content {
        font-size: 1.1rem;
        line-height: 1.9;
        color: #444;
        white-space: pre-line;
    }

    /* Tombol kembali */
    .btn-outline-secondary {
        border-radius: 30px;
        font-weight: 500;
        transition: all 0.3s;
    }
    .btn-outline-secondary:hover {
        background-color: #6c757d;
        color: #fff;
    }

    /* Responsif */
    @media (max-width: 768px) {
        .hero-title h1 {
            font-size: 1.8rem;
        }
        .news-content {
            font-size: 1rem;
        }
    }
</style>
@endpush
@endsection
