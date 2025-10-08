@extends('admin.layouts.app')

@section('title', 'Tambah Promo')

@section('content')
    <div class="page-header mb-4 d-flex justify-content-between align-items-center">
        <h2 class="fw-semibold text-dark">➕ Tambah Promo</h2>
        <a href="{{ route('admin.promotions.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('admin.promotions.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="event_id" class="form-label">Event</label>
                    <select name="event_id" id="event_id" class="form-select">
                        <option value="">-- Pilih Event (Opsional) --</option>
                        @foreach($events as $event)
                            <option value="{{ $event->id }}" {{ old('event_id') == $event->id ? 'selected' : '' }}>
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
                            <option value="{{ $type->id }}" {{ old('ticket_type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }} (Event: {{ $type->event->name ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="code" class="form-label">Kode Promo</label>
                    <input type="text" name="code" id="code" class="form-control" value="{{ old('code') }}" required>
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">Nama Promo</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                </div>

                <div class="mb-3">
                    <label for="type" class="form-label">Jenis Promo</label>
                    <select name="type" id="type" class="form-select" required>
                        <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>Persentase</option>
                        <option value="nominal" {{ old('type') == 'nominal' ? 'selected' : '' }}>Nominal</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="value" class="form-label">Nilai</label>
                    <input type="number" name="value" id="value" class="form-control" value="{{ old('value') }}" min="0"
                        required>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="start_date" class="form-label">Tanggal Mulai</label>
                        <input type="date" name="start_date" id="start_date" class="form-control"
                            value="{{ old('start_date') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="end_date" class="form-label">Tanggal Berakhir</label>
                        <input type="date" name="end_date" id="end_date" class="form-control" value="{{ old('end_date') }}"
                            required>
                    </div>
                </div>

                <div class="form-check mb-3">
                    <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1"
                        {{ old('is_active', 1) ? 'checked' : '' }}>
                    <label for="is_active" class="form-check-label">Aktifkan Promo</label>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i> Simpan Promo
                </button>
            </form>
        </div>
    </div>
@endsection