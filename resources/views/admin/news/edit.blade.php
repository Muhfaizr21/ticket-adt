@extends('admin.layouts.app')

@section('title', 'Edit Berita')

@section('content')
<div class="container py-4">
    <h1 class="h3 fw-bold mb-4">✍️ Edit Berita</h1>

    <form action="{{ route('admin.news.update', $news->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Judul -->
        <div class="mb-4">
            <label class="form-label fw-semibold">Judul</label>
            <input type="text" name="title" class="form-control form-control-lg" value="{{ $news->title }}" required>
        </div>

        <!-- Konten -->
        <div class="mb-4">
            <label class="form-label fw-semibold">Konten</label>
            <textarea name="content" class="form-control form-control-lg resize-none" rows="8" placeholder="Tulis konten berita..." required>{{ $news->content }}</textarea>
        </div>

        <!-- Gambar -->
        <div class="mb-4">
            <label class="form-label fw-semibold">Gambar</label>
            <div class="mb-2">
                @if($news->image)
                    <div class="border rounded p-2 d-inline-block">
                        <img src="{{ asset('storage/' . $news->image) }}" alt="Gambar" width="150" class="rounded">
                    </div>
                @else
                    <p class="text-muted">Belum ada gambar.</p>
                @endif
            </div>
            <input type="file" name="image" class="form-control" accept="image/*">
            <small class="text-muted">Kosongkan jika tidak ingin mengganti gambar.</small>
        </div>

        <!-- Buttons -->
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success">
                <i class="bi bi-check-circle me-1"></i> Update
            </button>
            <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </form>
</div>
@endsection
