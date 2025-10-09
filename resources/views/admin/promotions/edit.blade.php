@extends('admin.layouts.app')

@section('title', 'Edit Promo')

@section('content')
    <div class="page-header mb-4 d-flex justify-content-between align-items-center">
        <h2 class="fw-semibold text-dark">✏️ Edit Promo</h2>
        <a href="{{ route('admin.promotions.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('admin.promotions.update', $promotion->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="event_id" class="form-label">Event</label>
                    <select name="event_id" id="event_id" class="form-select">
                        <option value="">-- Pilih Event (Opsional) --</option>
                        @foreach($events as $event)
                            <option value="{{ $event->id }}" {{ $promotion->event_id == $event->id ? 'selected' : '' }}>
                                {{ $event->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="ticket_type_id" class="form-label">Tipe Tiket</label>
                    <select name="ticket_type_id" id="ticket_type_id" class="form-select">
                        <option value="">-- Pilih Tipe Tiket (Opsional) --</option>
                        @foreach($ticketTypes as $type)
                            <option value="{{ $type->id }}" {{ $promotion->ticket_type_id == $type->id ? 'selected' : '' }}>
                                {{ $type->name }} (Event: {{ $type->event->name ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Kode Promo</label>
                    <input type="text" name="code" class="form-control" value="{{ $promotion->code }}" disabled>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Promo</label>
                    <input type="text" name="name" class="form-control" value="{{ $promotion->name }}" required>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Persentase Diskon (%)</label>
                        <input type="number" name="persen_diskon" class="form-control"
                            value="{{ $promotion->persen_diskon }}" step="0.01" min="0">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nilai Diskon (Rp)</label>
                        <input type="number" name="value" class="form-control" value="{{ $promotion->value }}" step="0.01"
                            min="0">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" name="start_date" class="form-control" value="{{ $promotion->start_date }}"
                            required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tanggal Berakhir</label>
                        <input type="date" name="end_date" class="form-control" value="{{ $promotion->end_date }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="is_active" class="form-select">
                        <option value="1" {{ $promotion->is_active ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ !$promotion->is_active ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-save me-1"></i> Perbarui
                    </button>
                    <a href="{{ route('admin.promotions.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection