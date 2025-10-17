@extends('admin.layouts.app')

@section('title', 'Manage News')

@section('content')
<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">📰 News Management</h1>
        <a href="{{ route('admin.news.create') }}" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-circle me-1"></i> Add News
        </a>
    </div>

    {{-- Table --}}
    <div class="card shadow-sm border-0">
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-primary">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Title</th>
                        <th scope="col">Author</th>
                        <th scope="col">Published</th>
                        <th scope="col" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($news as $index => $item)
                    <tr>
                        <td>{{ $loop->iteration + ($news->currentPage() - 1) * $news->perPage() }}</td>
                        <td>
                            <strong>{{ $item->title }}</strong><br>

                        </td>
                        <td>{{ $item->author ?? 'Admin' }}</td>
                        <td>
                            {{ $item->published_at ? $item->published_at->format('d M Y') : '-' }}
                        </td>
                        <td class="text-center">
                            <a href="{{ route('news.show', $item->slug) }}" target="_blank" class="btn btn-info btn-sm me-1">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('admin.news.edit', $item->id) }}" class="btn btn-warning btn-sm me-1">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.news.destroy', $item->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this news?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            <i class="bi bi-info-circle me-1"></i> No news found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-3">
        {{ $news->links() }}
    </div>
</div>

@push('styles')
<style>
    .table thead th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }
    .table tbody tr:hover {
        background-color: #f5f8fc;
    }
    .btn-sm i {
        font-size: 0.9rem;
    }
</style>
@endpush
@endsection
