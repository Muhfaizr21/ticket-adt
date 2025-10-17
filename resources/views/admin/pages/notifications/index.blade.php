@extends('admin.layouts.app')

@section('title', 'Notifikasi Terkini')
@section('page-title', '🔔 Notifikasi Terkini')

@push('styles')
<style>
    /* ===============================
       ✨ MODERN NOTIFICATION STYLE ✨
       =============================== */

    body {
        background: linear-gradient(135deg, #eef2f3 0%, #d9e2ec 100%);
        font-family: 'Inter', sans-serif;
    }

    /* Wrapper konten */
    .notif-wrapper {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem 1.5rem; /* tambahkan jarak kiri kanan */
    }

    h2 {
        letter-spacing: 0.3px;
    }

    /* Grid notifikasi */
    .notif-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 1.8rem; /* jarak antar card */
        margin-top: 1.5rem;
    }

    /* Card notifikasi */
    .notif-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 1.8rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        min-height: 160px;
    }

    .notif-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 32px rgba(0, 0, 0, 0.12);
    }

    /* Icon melingkar */
    .notif-icon {
        position: absolute;
        top: -20px;
        left: 20px;
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-size: 20px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    }

    .notif-new .notif-icon {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
    }

    .notif-read .notif-icon {
        background: #e2e8f0;
        color: #475569;
    }

    /* Judul dan isi */
    .notif-title {
        font-size: 1.15rem;
        font-weight: 600;
        color: #1e293b;
        margin-top: 1.4rem;
    }

    .notif-text {
        font-size: 0.95rem;
        color: #475569;
        margin-top: 0.4rem;
        line-height: 1.6;
    }

    /* Badge Baru */
    .notif-badge {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        font-size: 0.7rem;
        padding: 0.2rem 0.6rem;
        border-radius: 9999px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Tombol tandai */
    .notif-button {
        border: 1px solid #2563eb;
        color: #2563eb;
        padding: 0.3rem 0.7rem;
        font-size: 0.75rem;
        border-radius: 6px;
        transition: all 0.2s ease;
    }

    .notif-button:hover {
        background: #2563eb;
        color: white;
    }

    /* Footer card */
    .notif-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.8rem;
        color: #94a3b8;
        margin-top: 1rem;
    }

    /* Empty state */
    .empty-state {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(12px);
        border-radius: 20px;
        padding: 3rem;
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        margin-top: 2rem;
    }

    .empty-state i {
        background: linear-gradient(135deg, #94a3b8, #64748b);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Animasi fadeUp */
    .notif-card {
        opacity: 0;
        transform: translateY(15px);
        animation: fadeUp 0.6s ease forwards;
    }

    @keyframes fadeUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endpush

@section('content')
<div class="notif-wrapper">
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-bold text-gray-800 flex items-center gap-2">
            <i class="bi bi-bell-fill text-blue-600"></i> Notifikasi
        </h2>
        @if($notifications->count() > 0)
        <form action="{{ route('admin.notifications.markAll') }}" method="POST">
            @csrf
            <button
                class="flex items-center gap-2 bg-green-600 text-white text-sm px-4 py-2 rounded-lg shadow hover:bg-green-700 transition">
                <i class="bi bi-check2-circle"></i> Tandai Semua Dibaca
            </button>
        </form>
        @endif
    </div>

    @if($notifications->count() > 0)
    <div class="notif-grid">
        @foreach ($notifications as $notification)
            <div class="notif-card {{ !$notification->is_read ? 'notif-new' : 'notif-read' }}">
                <div class="">
                    <i class="bi bi-bell-fill"></i>
                </div>
                <div class="notif-content">
                    <div class="flex justify-between items-start">
                        <h4 class="notif-title">
                            {{ $notification->title }}
                        </h4>
                        @if(!$notification->is_read)
                            <span class="notif-badge">Baru</span>
                        @endif
                    </div>
                    <p class="notif-text">{{ $notification->message }}</p>
                    <div class="notif-footer">
                        <div class="flex items-center gap-1">
                            <i class="bi bi-clock"></i>
                            {{ $notification->created_at->diffForHumans() }}
                        </div>
                        @if(!$notification->is_read)
                        <form action="{{ route('admin.notifications.read', $notification->id) }}" method="POST">
                            @csrf
                            <button class="notif-button">
                                <i class="bi bi-check2"></i> Tandai Dibaca
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $notifications->links() }}
    </div>
    @else
    <div class="empty-state text-center">
        <i class="bi bi-inbox text-5xl mb-4"></i>
        <p class="text-gray-500 text-lg">Tidak ada notifikasi saat ini.</p>
    </div>
    @endif
</div>
@endsection
