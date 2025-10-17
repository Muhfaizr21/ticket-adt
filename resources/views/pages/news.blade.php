@extends('layouts.app')

@section('title', 'Berita & Informasi')

@section('content')
<div class="container my-5">
    <h2 class="fw-bold mb-3">📰 Berita & Informasi</h2>
    <p class="text-muted mb-4">Dapatkan update dan informasi terbaru seputar event dan aktivitas.</p>

    <div class="row g-4">
        @foreach ($news as $item)
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card news-card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                @php
                    $images = [];
                    if (!empty($item->images)) {
                        if (is_array($item->images)) {
                            $images = $item->images;
                        } elseif ($item->images instanceof \Illuminate\Support\Collection) {
                            $images = $item->images->all();
                        } else {
                            // JSON stored in db
                            $decoded = json_decode($item->images, true);
                            if (is_array($decoded)) $images = $decoded;
                        }
                    }
                    if (empty($images) && !empty($item->image)) {
                        $images = [$item->image];
                    }
                @endphp

                @if(count($images) === 1)
                <div class="news-image-wrapper single">
                    <img src="{{ asset('storage/'.$images[0]) }}" class="card-img-top" alt="{{ $item->title }}">
                </div>
                @elseif(count($images) > 1)
                <div class="news-image-wrapper gallery">
                    @foreach($images as $img)
                        <img src="{{ asset('storage/'.$img) }}" alt="{{ $item->title }}">
                    @endforeach
                </div>
                @endif

                <div class="card-body d-flex flex-column p-3">
                    <h5 class="card-title fw-semibold mb-2 text-truncate" title="{{ $item->title }}">{{ $item->title }}</h5>
                    <div class="small text-muted mb-2">
                        <i class="bi bi-person"></i> {{ $item->author ?? 'Administrator' }} &nbsp;|&nbsp;
                        <i class="bi bi-calendar"></i>
                        {{ $item->published_at ? \Carbon\Carbon::parse($item->published_at)->format('d M Y') : '-' }}
                    </div>
                    <p class="card-text mb-3 small text-truncate-2">{{ \Illuminate\Support\Str::limit(strip_tags($item->content), 80, '...') }}</p>
                    <div class="mt-auto text-end">
                        <a href="{{ route('news.show', $item->slug) }}" class="btn btn-primary btn-sm px-3 rounded-pill">
                            Baca Selengkapnya <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
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
    .news-card {
        min-height: 320px;
        max-height: 380px;
        display: flex;
        flex-direction: column;
        border-radius: 1rem;
        transition: box-shadow 0.18s, transform 0.18s;
    }
    .news-card:hover {
        box-shadow: 0 10px 30px rgba(0,0,0,0.12);
        transform: translateY(-4px);
    }

    /* single image */
    .news-image-wrapper.single {
        height: 160px;
        overflow: hidden;
        background: #f7f7f7;
    }
    .news-image-wrapper.single img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-top-left-radius: 1rem;
        border-top-right-radius: 1rem;
        display: block;
    }
    /* gallery: grid with gap */
    .news-image-wrapper.gallery {
        display: grid;
        gap: 8px; /* jarak antar gambar */
        padding: 8px;
        background: #f7f7f7;
        border-top-left-radius: 1rem;
        border-top-right-radius: 1rem;
        grid-template-columns: repeat(2, 1fr);
        align-items: center;
        justify-items: center;
    }
    .news-image-wrapper.gallery img {
        width: 100%;
        height: 110px;
        object-fit: cover;
        border-radius: 8px;
        display:block;
    }

    /* lebih banyak gambar: 3 kolom di layar besar */
    @media (min-width: 992px) {
        .news-image-wrapper.gallery {
            grid-template-columns: repeat(3, 1fr);
        }
        .news-image-wrapper.gallery img {
            height: 100px;
        }
    }

    .card-title.text-truncate {
        max-width: 100%;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .text-truncate-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 2.7em;
        max-height: 2.7em;
    }
    @media (max-width: 576px) {
        .news-card {
            min-height: 280px;
            max-height: 340px;
        }
        .news-image-wrapper.single { height: 120px; }
        .news-image-wrapper.gallery img { height: 90px; }
    }
</style>
@endpush
@endsection
