@extends('admin.layouts.app')

@section('title', 'Kelola Jenis Tiket')

@section('content')
    <div class="page-header mb-4 d-flex justify-content-between align-items-center">
        <h2 class="fw-semibold text-dark">✏️ Kelola Jenis Tiket: {{ $event->name }}</h2>
        <a href="{{ route('admin.ticket-types.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Form Tambah Tipe Tiket --}}
    <div class="card shadow-lg border-0 mb-5">
        <div class="card-header bg-primary text-white p-3">
            <h5 class="fw-bold mb-0">➕ Tambah Tipe Tiket Baru</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.ticket-types.store', $event->id) }}" method="POST">
                @csrf
                <div class="row g-4">
                    {{-- Nama Tipe Tiket --}}
                    <div class="col-md-6 col-lg-4">
                        <label for="name" class="form-label fw-semibold">Nama Tipe Tiket <span class="text-danger">*</span></label>
                        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Contoh: VIP, Reguler" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Harga --}}
                    <div class="col-md-6 col-lg-4">
                        <label for="price" class="form-label fw-semibold">Harga (Rp) <span class="text-danger">*</span></label>
                        <input type="number" id="price" name="price" class="form-control @error('price') is-invalid @enderror" min="0" value="{{ old('price') }}" required>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Total Tiket --}}
                    <div class="col-md-6 col-lg-4">
                        <label for="total_tickets" class="form-label fw-semibold">Total Tiket <span class="text-danger">*</span></label>
                        <input type="number" id="total_tickets" name="total_tickets" class="form-control @error('total_tickets') is-invalid @enderror" min="1" value="{{ old('total_tickets') }}" required>
                        @error('total_tickets')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div class="col-12">
                        <label for="description" class="form-label fw-semibold">Deskripsi</label>
                        <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" rows="3" placeholder="Detail keunggulan atau fasilitas yang didapatkan">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Tombol Submit --}}
                    <div class="col-12 text-end pt-3">
                        <button class="btn btn-primary btn-lg px-4 shadow-sm" type="submit">
                            <i class="bi bi-plus-circle me-2"></i> Tambah Tipe Tiket
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    ---
    
    {{-- Daftar Tipe Tiket --}}
    <div class="card shadow-lg border-0">
        <div class="card-header bg-light p-3">
            <h5 class="fw-bold mb-0">Daftar Tipe Tiket yang Ada</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover text-center align-middle mb-0">
                    <thead class="table-primary">
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Deskripsi</th>
                            <th>Harga (Rp)</th>
                            <th>Total Tiket</th>
                            <th>Tersedia</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($event->ticketTypes as $index => $ticket)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><span class="fw-semibold">{{ $ticket->name }}</span></td>
                                <td class="text-start">{{ $ticket->description ? Str::limit($ticket->description, 50) : '-' }}</td>
                                <td><span class="badge bg-success-subtle text-success">Rp {{ number_format($ticket->price, 0, ',', '.') }}</span></td>
                                <td>{{ $ticket->total_tickets }}</td>
                                <td>
                                    @if ($ticket->available_tickets > 0)
                                        <span class="badge bg-info">{{ $ticket->available_tickets }}</span>
                                    @else
                                        <span class="badge bg-danger">Habis</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                    
                                        {{-- Tombol Hapus --}}
                                        <form
                                            action="{{ route('admin.ticket-types.destroy', ['event' => $event->id, 'ticket' => $ticket->id]) }}"
                                            method="POST" onsubmit="return confirm('Yakin ingin menghapus tipe tiket {{ $ticket->name }}? Tindakan ini tidak dapat dibatalkan.')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" type="submit" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            {{-- Modal Edit (Dibiarkan sama, hanya penyesuaian kelas Bootstrap) --}}
                            <div class="modal fade" id="editModal{{ $ticket->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 rounded-3 shadow-lg">
                                        <div class="modal-header bg-warning text-dark">
                                            <h5 class="modal-title">Edit Tipe Tiket: {{ $ticket->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form
                                            action="{{ route('admin.ticket-types.update', ['event' => $event->id, 'ticket' => $ticket->id]) }}"
                                            method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Nama Tipe Tiket <span class="text-danger">*</span></label>
                                                    <input type="text" name="name" class="form-control" value="{{ old('name', $ticket->name) }}"
                                                        required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Harga (Rp) <span class="text-danger">*</span></label>
                                                    <input type="number" name="price" class="form-control"
                                                        value="{{ old('price', $ticket->price) }}" min="0" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Total Tiket <span class="text-danger">*</span></label>
                                                    <input type="number" name="total_tickets" class="form-control"
                                                        value="{{ old('total_tickets', $ticket->total_tickets) }}" min="1" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Tiket Tersedia</label>
                                                    {{-- Perlu diperhatikan: Field ini bisa dihitung (total - terjual). Saya biarkan tetap ada jika ini adalah kebutuhan khusus. --}}
                                                    <input type="number" name="available_tickets" class="form-control"
                                                        value="{{ old('available_tickets', $ticket->available_tickets) }}" min="0">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Deskripsi</label>
                                                    <textarea name="description" class="form-control"
                                                        rows="2">{{ old('description', $ticket->description) }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="7" class="text-muted py-5">
                                    <i class="bi bi-ticket-perforated fs-3 mb-2 d-block text-secondary"></i>
                                    **Belum ada tipe tiket ditambahkan.** Silakan gunakan formulir di atas untuk memulai.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection