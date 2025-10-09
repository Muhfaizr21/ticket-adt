@extends('admin.layouts.app')

@section('content')
    <div class="container mt-5">
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-dark mb-1">
                    <i class="bi bi-calendar-event text-primary"></i> Daftar Event
                </h3>
            </div>
            <a href="{{ route('admin.events.create') }}" class="btn btn-gradient shadow-sm">
                <i class="bi bi-plus-circle me-1"></i> Tambah Event
            </a>
        </div>

        {{-- Alert sukses --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Table --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light border-bottom">
                            <tr class="text-center text-secondary">
                                <th>#</th>
                                <th>Event</th>
                                <th>Venue</th>
                                <th>Tanggal</th>
                                <th>Tiket Tersedia</th>
                                <th>Poster</th>
                                <th>Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($events as $event)
                                <tr>
                                    <td class="text-center text-muted">{{ $loop->iteration }}</td>

                                    {{-- Nama & Deskripsi --}}
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $event->name }}</div>
                                        <div class="text-muted small">{{ Str::limit($event->description, 50) }}</div>
                                    </td>

                                    {{-- Venue --}}
                                    <td class="text-center">
                                        {{ optional($event->venue)->name ?? '-' }}
                                    </td>

                                    {{-- Tanggal --}}
                                    <td class="text-center">
                                        {{ $event->date ? \Carbon\Carbon::parse($event->date)->translatedFormat('d F Y') : '-' }}
                                    </td>

                                    {{-- Tiket --}}
                                    <td class="text-center">
                                        <span class="badge {{ $event->available_tickets > 0 ? 'bg-success' : 'bg-danger' }}">
                                            {{ $event->available_tickets }}
                                        </span>
                                    </td>

                                    {{-- Poster --}}
                                    <td class="text-center">
                                        @if ($event->poster)
                                            <img src="{{ asset('storage/' . $event->poster) }}" width="65" height="65"
                                                class="rounded-3 shadow-sm object-fit-cover" alt="Poster {{ $event->name }}">
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>

                                    {{-- Tanggal dibuat --}}
                                    <td class="text-center text-muted small">
                                        {{ $event->created_at?->format('d M Y, H:i') }}
                                    </td>

                                    {{-- Aksi --}}
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.events.edit', $event->id) }}"
                                                class="btn btn-sm btn-outline-primary" title="Edit Event">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>

                                            <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus event ini?')" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger" title="Hapus Event">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <i class="bi bi-emoji-frown text-muted fs-2"></i>
                                        <p class="text-muted mt-2 mb-0">Belum ada event yang terdaftar.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="mt-4 d-flex justify-content-center">
            {{ $events->links() }}
        </div>
    </div>

    {{-- Custom CSS --}}
    @push('styles')
        <style>
            .btn-gradient {
                background: linear-gradient(90deg, #007bff, #6610f2);
                color: #fff;
                border: none;
                transition: all 0.3s ease;
            }

            .btn-gradient:hover {
                background: linear-gradient(90deg, #6610f2, #007bff);
                transform: translateY(-2px);
                color: #fff;
            }

            .bg-gradient {
                background: linear-gradient(90deg, #28a745, #20c997);
            }

            .table-hover tbody tr:hover {
                background-color: #f8f9fa !important;
            }
        </style>
    @endpush
@endsection