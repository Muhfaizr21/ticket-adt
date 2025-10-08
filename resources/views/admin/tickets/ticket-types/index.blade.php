@extends('admin.layouts.app')

@section('title', 'Kategori Tiket')

@section('content')
    <div class="page-header mb-4 d-flex justify-content-between align-items-center">
        <h2 class="fw-semibold text-dark">🎟️ Kategori Tiket per Event</h2>
        <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Event
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Daftar Event & Kategori Tiket</h5>

            <table class="table table-hover align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Poster</th>
                        <th>Nama Event</th>
                        <th>Tanggal Event</th>
                        <th>Lokasi</th>
                        <th>Nama Tiket</th>
                        <th>Harga Dasar</th>
                        <th>Harga Setelah Diskon</th>
                        <th>Status Diskon</th>
                        <th>Total Tiket</th>
                        <th>Tersedia</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $eventIndex => $event)
                        @foreach($event->ticketTypes as $ticketIndex => $ticket)
                            <tr>
                                <td>{{ $eventIndex + 1 }}.{{ $ticketIndex + 1 }}</td>

                                {{-- Poster hanya ditampilkan sekali per event --}}
                                @if($ticketIndex === 0)
                                    <td rowspan="{{ $event->ticketTypes->count() }}">
                                        @if($event->poster)
                                            <img src="{{ asset('storage/' . $event->poster) }}" alt="{{ $event->name }}" class="rounded"
                                                width="80" height="60" style="object-fit: cover;">
                                        @else
                                            <span class="text-muted">Tidak ada</span>
                                        @endif
                                    </td>
                                    <td rowspan="{{ $event->ticketTypes->count() }}" class="fw-semibold">{{ $event->name }}</td>
                                    <td rowspan="{{ $event->ticketTypes->count() }}">
                                        {{ $event->date ? \Carbon\Carbon::parse($event->date)->translatedFormat('d F Y') : '-' }}
                                    </td>
                                    <td rowspan="{{ $event->ticketTypes->count() }}">{{ $event->location ?? '-' }}</td>
                                @endif

                                {{-- Nama tiket & harga --}}
                                <td>{{ $ticket->name }}</td>
                                <td>Rp {{ number_format($ticket->price, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($ticket->discounted_price, 0, ',', '.') }}</td>
                                <td>
                                    @if($ticket->discount_status)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    @endif
                                </td>

                                @if($ticketIndex === 0)
                                    <td rowspan="{{ $event->ticketTypes->count() }}">{{ $event->total_tickets ?? '-' }}</td>
                                    <td rowspan="{{ $event->ticketTypes->count() }}">
                                        <span class="badge bg-success">{{ $event->available_tickets ?? '-' }}</span>
                                    </td>
                                    <td rowspan="{{ $event->ticketTypes->count() }}">
                                        <a href="{{ route('admin.ticket-types.edit', $event->id) }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-gear"></i> Kelola
                                        </a>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="12" class="text-center text-muted py-4">
                                <i class="bi bi-calendar-x fs-3 d-block mb-2"></i>
                                Belum ada event yang terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection