@extends('admin.layouts.app')

@section('content')
<div class="container py-5">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary">❓ Help & Support</h2>
        <span class="badge bg-primary fs-6 px-3 py-2">Admin Panel</span>
    </div>

    <p class="text-muted mb-5">
        Halaman ini menyediakan panduan, dokumentasi, dan solusi cepat untuk membantu Anda memahami sistem TicketMaster dengan lebih baik.
    </p>

    {{-- Accordion --}}
    <div class="accordion" id="supportAccordion">
        @foreach ($topics as $index => $topic)
            <div class="accordion-item mb-3 shadow-sm rounded">
                <h2 class="accordion-header" id="heading{{ $index }}">
                    <button class="accordion-button {{ $index !== 0 ? 'collapsed' : '' }}"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#collapse{{ $index }}"
                            aria-expanded="{{ $index === 0 ? 'true' : 'false' }}"
                            aria-controls="collapse{{ $index }}">
                        <strong>{{ $topic['title'] }}</strong>
                    </button>
                </h2>
                <div id="collapse{{ $index }}"
                     class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }} animate-collapse"
                     aria-labelledby="heading{{ $index }}"
                     data-bs-parent="#supportAccordion">
                    <div class="accordion-body">
                        <p class="fw-semibold text-secondary">{{ $topic['description'] }}</p>
                        <hr>
                        <p class="mb-0">{{ $topic['content'] }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Contact --}}
    <div class="mt-5 text-center">
        <h5 class="fw-bold">Masih butuh bantuan?</h5>
        <p class="text-muted">Hubungi tim teknis kami melalui email berikut:</p>
        <a href="mailto:support@ticketmaster.local" class="btn btn-primary px-4 py-2">
            📧 support@ticketmaster.local
        </a>
        <div class="mt-3 text-muted small">Kami siap membantu Anda kapan saja 🚀</div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* =============================
       GLOBAL STYLE
    ============================= */
    body {
        background-color: #f8f9fa;
        font-family: 'Poppins', sans-serif;
        color: #212529;
    }

    h2, h5 {
        font-weight: 700;
    }

    p {
        font-size: 15px;
    }

    /* =============================
       HEADER SECTION
    ============================= */
    .badge.bg-primary {
        background-color: #0d6efd !important;
        font-size: 0.95rem;
        letter-spacing: 0.5px;
    }

    /* =============================
       ACCORDION STYLE
    ============================= */
    .accordion-button {
        background-color: #ffffff;
        font-size: 1.05rem;
        padding: 1rem 1.25rem;
        transition: all 0.3s ease;
        border: none;
        box-shadow: none;
    }

    .accordion-button:hover {
        background-color: #f1f5ff;
        color: #0d6efd;
    }

    .accordion-button:not(.collapsed) {
        background-color: #e9f1ff;
        color: #0d6efd;
        box-shadow: none;
    }

    .accordion-item {
        border: none;
        border-radius: 10px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .accordion-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    }

    .accordion-body {
        background-color: #ffffff;
        padding: 1.25rem;
        font-size: 0.95rem;
        line-height: 1.6;
    }

    /* =============================
       ANIMASI COLLAPSE
    ============================= */
    .animate-collapse {
        transition: all 0.35s ease;
    }

    .accordion-collapse.collapsing {
        opacity: 0;
        transform: translateY(-10px);
    }

    .accordion-collapse.collapse.show {
        opacity: 1;
        transform: translateY(0);
    }

    /* =============================
       BUTTON CONTACT
    ============================= */
    .btn.btn-primary {
        background-color: #0d6efd;
        border: none;
        font-weight: 500;
        transition: all 0.3s ease;
        border-radius: 8px;
    }

    .btn.btn-primary:hover {
        background-color: #0b5ed7;
        box-shadow: 0 5px 15px rgba(13,110,253,0.3);
    }

    .text-muted.small {
        font-size: 0.85rem;
    }

    /* =============================
       RESPONSIVE
    ============================= */
    @media (max-width: 768px) {
        .accordion-button {
            font-size: 0.95rem;
        }

        h2 {
            font-size: 1.5rem;
        }
    }
</style>
@endpush
