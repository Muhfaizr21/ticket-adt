@extends('admin.layouts.app')

@section('title', 'Tambah Promo')

@section('content')
<div class="page-header mb-4">
    <h2 class="fw-semibold text-dark">Tambah Promo Baru</h2>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form action="{{ route('admin.promotions.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Kode Promo</label>
                <input type="text" name="code" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Nama Promo</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Jenis Promo</label>
                <select name="type" class="form-select" required>
                    <option value="percentage">Persentase (%)</option>
                    <option value="nominal">Nominal (Rp)</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Nilai Promo</label>
                <input type="number" name="value" class="form-control" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tanggal Mulai</label>
                    <input type="date" name="start_date" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tanggal Berakhir</label>
                    <input type="date" name="end_date" class="form-control" required>
                </div>
            </div>

            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="{{ route('admin.promotions.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection
