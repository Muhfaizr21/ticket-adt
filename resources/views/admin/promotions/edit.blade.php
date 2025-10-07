@extends('admin.layouts.app')

@section('title', 'Edit Promo')

@section('content')
<div class="page-header mb-4">
    <h2 class="fw-semibold text-dark">Edit Promo</h2>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form action="{{ route('admin.promotions.update', $promotion->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Kode Promo</label>
                <input type="text" name="code" class="form-control" value="{{ $promotion->code }}" disabled>
            </div>

            <div class="mb-3">
                <label class="form-label">Nama Promo</label>
                <input type="text" name="name" class="form-control" value="{{ $promotion->name }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Jenis Promo</label>
                <select name="type" class="form-select" required>
                    <option value="percentage" {{ $promotion->type == 'percentage' ? 'selected' : '' }}>Persentase (%)</option>
                    <option value="nominal" {{ $promotion->type == 'nominal' ? 'selected' : '' }}>Nominal (Rp)</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Nilai Promo</label>
                <input type="number" name="value" class="form-control" value="{{ $promotion->value }}" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tanggal Mulai</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $promotion->start_date }}" required>
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

            <button type="submit" class="btn btn-warning">Perbarui</button>
            <a href="{{ route('admin.promotions.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection
