@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4">🎫 Daftar Event</h3>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('admin.events.create') }}" class="btn btn-primary mb-3">+ Tambah Event</a>

    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Nama Event</th>
                <th>Venue</th>
                <th>Tanggal</th>
                <th>Harga</th>
                <th>Tiket Tersedia</th>
                <th>Poster</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($events as $event)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $event->name }}</td>

                    {{-- 🔹 Pastikan tidak error walau venue null --}}
                    <td>{{ optional($event->venue)->name ?? '-' }}</td>

                    <td>{{ $event->date ?? '-' }}</td>
                    <td>Rp{{ number_format($event->price, 0, ',', '.') }}</td>
                    <td>{{ $event->available_tickets }}</td>
                    <td>
                        @if ($event->poster)
                            <img src="{{ asset('storage/'.$event->poster) }}" width="60" class="rounded">
                        @else
                            <span class="text-muted">Tidak ada</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.events.edit', $event->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus event ini?')">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center text-muted">Belum ada event.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $events->links() }}
    </div>
</div>
@endsection
