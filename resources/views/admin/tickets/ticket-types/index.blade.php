@extends('admin.layouts.app')

@section('title', 'Daftar Tipe Tiket')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4 fw-bold">🎟️ Daftar Tipe Tiket per Event</h2>

        {{-- Alert Sukses --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @foreach ($events as $event)
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $event->name }}</h5>
                    {{-- Tombol tambah per event --}}
                    <a href="{{ route('admin.ticket-types.create', ['event' => $event->id]) }}"
                        class="btn btn-light btn-sm fw-semibold">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Tipe Tiket
                    </a>
                </div>

                <div class="card-body">
                    @if ($event->ticketTypes->isEmpty())
                        <p class="text-muted mb-0">Belum ada tipe tiket untuk event ini.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama Tipe</th>
                                        <th>Deskripsi</th>
                                        <th>Harga</th>
                                        <th>Total Tiket</th>
                                        <th>Tiket Tersedia</th>
                                        <th class="text-center" width="150">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($event->ticketTypes as $ticket)
                                        <tr>
                                            <td>{{ $ticket->name }}</td>
                                            <td>{{ $ticket->description ?: '-' }}</td>
                                            <td>Rp {{ number_format($ticket->price, 0, ',', '.') }}</td>
                                            <td>{{ $ticket->total_tickets }}</td>
                                            <td>{{ $ticket->available_tickets }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.ticket-types.edit', ['event' => $event->id, 'ticket' => $ticket->id]) }}"
                                                    class="btn btn-sm btn-warning me-1">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <form
                                                    action="{{ route('admin.ticket-types.destroy', ['event' => $event->id, 'ticket' => $ticket->id]) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Yakin ingin menghapus tipe tiket ini?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endsection