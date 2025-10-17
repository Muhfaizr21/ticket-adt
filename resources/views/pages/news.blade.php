@extends('layouts.app')

@section('title', 'Berita & Informasi')

@section('content')
<div class="container my-5">
    <h2 class="fw-bold mb-3">📰 Berita & Informasi</h2>
    <p class="text-muted mb-4">Dapatkan update dan informasi terbaru seputar event dan aktivitas.</p>

    <div class="news-grid">
        @foreach ($news as $item)
        <div class="news-card">
            @if($item->image)
            <div class="news-img">
                <img src="{{ asset('storage/'.$item->image) }}" alt="{{ $item->title }}">
            </div>
            @endif

            <div class="news-body">
                <h5 class="news-title">{{ $item->title }}</h5>
                <div class="news-meta">
                    <i class="bi bi-person"></i> {{ $item->author ?? 'Administrator' }}
                    &nbsp;•&nbsp;
                    <i class="bi bi-calendar"></i>
                    {{ $item->published_at ? \Carbon\Carbon::parse($item->published_at)->format('d M Y') : '-' }}
                </div>
                <p class="news-text">
                    {{ \Illuminate\Support\Str::limit(strip_tags($item->content), 80, '...') }}
                </p>
                <a href="{{ route('news.show', $item->slug) }}" class="news-btn">
                    Baca Selengkapnya <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $news->links() }}
    </div>
</div>

@push('styles')
<style>
    body {
        background: #f5f7fa !important;
    }

    /* Grid layout */
    .news-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 24px;
    }

    .news-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.05);
        transition: all 0.25s ease;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    .news-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.08);
    }

    .news-img {
        height: 150px;
        overflow: hidden;
        background: #e9ecef;
    }
    .news-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .news-body {
        padding: 18px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .news-title {
        font-weight: 600;
        font-size: 1.05rem;
        color: #1e293b;
        margin-bottom: 6px;
    }

    .news-meta {
        font-size: 0.85rem;
        color: #6b7280;
        margin-bottom: 10px;
    }

    .news-text {
        font-size: 0.9rem;
        color: #4b5563;
        margin-bottom: 16px;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .news-btn {
        align-self: flex-start;
        font-size: 0.85rem;
        color: #2563eb;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.2s ease;
    }
    .news-btn:hover {
        color: #1d4ed8;
        text-decoration: underline;
    }

    @media (max-width: 576px) {
        .news-img { height: 120px; }
        .news-grid { gap: 16px; }
    }
</style>
@endpush
@endsection
