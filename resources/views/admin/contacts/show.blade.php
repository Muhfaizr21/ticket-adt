@extends('admin.layouts.app')
@section('title','Detail Aduan')

@push('styles')
<style>
    /* Ultra-modern card */
    .aduan-card {
        border-radius: 1.25rem;
        overflow: hidden;
        box-shadow: 0 15px 40px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .aduan-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 25px 50px rgba(0,0,0,0.15);
    }

    .aduan-header {
        background: linear-gradient(135deg, #4f46e5, #3b82f6);
        color: white;
        padding: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .aduan-header h4 {
        font-weight: 600;
        font-size: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .aduan-body {
        padding: 2rem;
        background: #f9fafb;
    }

    .aduan-info {
        display: flex;
        flex-wrap: wrap;
        gap: 2rem;
        margin-bottom: 1.5rem;
    }

    .aduan-info div {
        flex: 1 1 250px;
        font-weight: 500;
    }

    .aduan-message {
        background: white;
        padding: 1.5rem;
        border-radius: 1rem;
        box-shadow: inset 0 0 10px rgba(0,0,0,0.03);
        white-space: pre-wrap;
        min-height: 150px;
        margin-bottom: 1.5rem;
    }

    .aduan-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.875rem;
        color: #6b7280;
    }

    .btn-ultra {
        border-radius: 0.75rem;
        padding: 0.5rem 1rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .btn-ultra-primary {
        background: #4f46e5;
        color: white;
        border: none;
    }

    .btn-ultra-primary:hover {
        background: #4338ca;
    }

    .btn-ultra-danger {
        background: #ef4444;
        color: white;
        border: none;
    }

    .btn-ultra-danger:hover {
        background: #dc2626;
    }

    @media(max-width:768px){
        .aduan-info {
            flex-direction: column;
        }
    }
</style>
@endpush

@section('content')
<div class="container py-5">

    <a href="{{ route('admin.contacts.index') }}" class="btn btn-light btn-sm mb-4 d-inline-flex align-items-center gap-2">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>

    <div class="aduan-card">
        {{-- Header --}}
        <div class="aduan-header">
            <h4>
                <i class="bi bi-envelope-fill"></i>
                {{ $contact->subject }}
            </h4>
            @if(!$contact->read_at)
                <form action="{{ route('admin.contacts.show', $contact->id) }}" method="POST">
                    @csrf

                </form>
            @else
                <span class="badge bg-success py-2 px-3">Sudah Dibaca</span>
            @endif
        </div>

        {{-- Body --}}
        <div class="aduan-body">
            <div class="aduan-info">
                <div><strong>Nama:</strong> {{ $contact->name }}</div>
                <div><strong>Email:</strong> <a href="mailto:{{ $contact->email }}" class="text-primary">{{ $contact->email }}</a></div>
            </div>

            <div class="aduan-message">
                {{ $contact->message }}
            </div>

            <div class="aduan-footer">
                <div><i class="bi bi-clock me-1"></i> Dikirim: {{ $contact->created_at->format('d M Y H:i') }}</div>
                <div><i class="bi bi-hash me-1"></i>ID: {{ $contact->id }}</div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="p-4 d-flex justify-content-end border-top bg-gray-50">
            <form action="{{ route('admin.contacts.destroy', $contact->id) }}" method="POST" onsubmit="return confirm('Hapus aduan ini?')">
                @csrf @method('DELETE')
                <button class="btn-ultra btn-ultra-danger"><i class="bi bi-trash"></i> Hapus</button>
            </form>
        </div>
    </div>

</div>
@endsection
