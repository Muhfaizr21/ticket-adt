@extends('admin.layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">❓ Help & Support</h2>
        <span class="badge bg-primary fs-6 px-3 py-2">Admin Panel</span>
    </div>

    <p class="text-muted mb-5">
        Halaman ini menyediakan panduan, dokumentasi, dan solusi cepat untuk membantu Anda memahami sistem TicketMaster dengan lebih baik.
    </p>

    <div class="accordion" id="supportAccordion">
        @foreach ($topics as $index => $topic)
            <div class="accordion-item mb-3 shadow-sm rounded">
                <h2 class="accordion-header" id="heading{{ $index }}">
                    <button class="accordion-button {{ $index !== 0 ? 'collapsed' : '' }}" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}"
                            aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="collapse{{ $index }}">
                        <strong>{{ $topic['title'] }}</strong>
                    </button>
                </h2>
                <div id="collapse{{ $index }}"
                     class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                     aria-labelledby="heading{{ $index }}" data-bs-parent="#supportAccordion">
                    <div class="accordion-body">
                        <p class="fw-semibold text-secondary">{{ $topic['description'] }}</p>
                        <hr>
                        <p class="mb-0">{{ $topic['content'] }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-5 text-center">
        <h5 class="fw-bold">Masih butuh bantuan?</h5>
        <p class="text-muted">Hubungi tim teknis kami melalui email berikut:</p>
        <a href="mailto:support@ticketmaster.local" class="btn btn-outline-primary">
            📧 support@ticketmaster.local
        </a>
    </div>
</div>
@endsection
