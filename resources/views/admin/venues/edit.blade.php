@extends('admin.layouts.app')

@section('title', 'Edit Venue')

@push('styles')
<!-- Tambahkan Bootstrap Icon & SweetAlert2 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

<style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f5f6fa;
    }

    .edit-card {
        max-width: 700px;
        margin: 40px auto;
        border: none;
        border-radius: 18px;
        background: #fff;
        box-shadow: 0 4px 25px rgba(0,0,0,0.1);
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .edit-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.15);
    }

    .edit-card-header {
        background: linear-gradient(135deg, #4e73df, #224abe);
        padding: 25px;
        color: #fff;
        text-align: center;
    }

    .edit-card-header h4 {
        margin: 0;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    .form-floating input,
    .form-floating textarea {
        border-radius: 12px;
        padding-left: 45px;
        background-color: #f9f9f9;
        transition: all 0.2s ease-in-out;
    }

    .form-floating input:focus,
    .form-floating textarea:focus {
        background-color: #fff;
        box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
    }

    .input-icon {
        position: absolute;
        top: 50%;
        left: 15px;
        transform: translateY(-50%);
        color: #6c757d;
        font-size: 1.2rem;
    }

    .btn-modern {
        border-radius: 30px;
        padding: 10px 25px;
        font-weight: 600;
        transition: all 0.25s ease;
    }

    .btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }

    @media (max-width: 576px) {
        .edit-card {
            margin: 20px;
        }
    }
</style>
@endpush

@section('content')
<div class="edit-card">
    <div class="edit-card-header">
        <h4><i class="bi bi-pencil-square me-2"></i> Edit Data Venue</h4>
    </div>
    <div class="card-body p-4">
        <form action="{{ route('admin.venues.update', $venue->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Nama Venue --}}
            <div class="form-floating mb-4 position-relative">
                <i class="bi bi-building input-icon"></i>
                <input type="text" name="name" id="name" class="form-control" value="{{ $venue->name }}" required placeholder="Nama Venue">
                <label for="name">Nama Venue</label>
            </div>

            {{-- Alamat --}}
            <div class="form-floating mb-4 position-relative">
                <i class="bi bi-geo-alt input-icon"></i>
                <textarea name="address" id="address" class="form-control" style="height: 100px" required placeholder="Alamat Lengkap">{{ $venue->address }}</textarea>
                <label for="address">Alamat Lengkap</label>
            </div>

            {{-- Kota --}}
            <div class="form-floating mb-4 position-relative">
                <i class="bi bi-geo input-icon"></i>
                <input type="text" name="city" id="city" class="form-control" value="{{ $venue->city }}" required placeholder="Kota">
                <label for="city">Kota</label>
            </div>

            {{-- Kapasitas --}}
            <div class="form-floating mb-4 position-relative">
                <i class="bi bi-people input-icon"></i>
                <input type="number" name="capacity" id="capacity" class="form-control" value="{{ $venue->capacity }}" required placeholder="Kapasitas">
                <label for="capacity">Kapasitas (Orang)</label>
            </div>

            {{-- Deskripsi --}}
            <div class="form-floating mb-4 position-relative">
                <i class="bi bi-chat-text input-icon"></i>
                <textarea name="description" id="description" class="form-control" style="height: 100px" placeholder="Deskripsi">{{ $venue->description }}</textarea>
                <label for="description">Deskripsi</label>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('admin.venues.index') }}" class="btn btn-light border btn-modern">
                    <i class="bi bi-arrow-left-circle me-1"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary btn-modern">
                    <i class="bi bi-save me-1"></i> Simpan Perubahan
                </button>
            </div>
        </form>

        <hr class="my-4">

        {{-- Tombol Hapus --}}
        <div class="text-end">
            <button class="btn btn-danger btn-modern" id="deleteBtn">
                <i class="bi bi-trash3 me-1"></i> Hapus Venue
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('deleteBtn').addEventListener('click', function (e) {
        e.preventDefault();
        Swal.fire({
            title: 'Yakin Hapus Data Ini?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            showClass: {
                popup: 'animate__animated animate__zoomIn'
            },
            hideClass: {
                popup: 'animate__animated animate__zoomOut'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect ke route delete
                window.location.href = "{{ route('admin.venues.destroy', $venue->id) }}";
            }
        })
    });
</script>
@endpush
