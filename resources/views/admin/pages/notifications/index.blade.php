@extends('admin.layouts.app')

@section('title', 'Notifikasi Terkini')
@section('page-title', '🔔 Notifikasi Terkini')

@section('content')
<div class="flex justify-between items-center mb-4">
    <h2 class="text-2xl font-semibold">Notifikasi</h2>
    <form action="{{ route('admin.notifications.index') }}" method="POST">
        @csrf
        <button class="btn btn-sm bg-green-600 text-white px-3 py-1 rounded-md hover:bg-green-700">
            Tandai Semua Dibaca
        </button>
    </form>
</div>

<div class="bg-white p-4 rounded-lg shadow-md">
    @forelse ($notifications as $notification)
        <div class="flex justify-between items-center border-b py-3 {{ !$notification->is_read ? 'bg-gray-50' : '' }}">
            <div>
                <h4 class="font-semibold text-lg text-gray-800">
                    {{ $notification->title }}
                    @if(!$notification->is_read)
                        <span class="ml-2 inline-block bg-blue-500 text-white text-xs px-2 py-0.5 rounded-full">Baru</span>
                    @endif
                </h4>
                <p class="text-gray-600 text-sm mt-1">{{ $notification->message }}</p>
                <span class="text-xs text-gray-400">{{ $notification->created_at->diffForHumans() }}</span>
            </div>
            @if(!$notification->is_read)
                <form action="{{ route('admin.notifications.read', $notification->id) }}" method="POST">
                    @csrf
                    <button class="btn btn-sm text-blue-600 hover:text-blue-800">Tandai Dibaca</button>
                </form>
            @endif
        </div>
    @empty
        <p class="text-center text-gray-500 py-4">Tidak ada notifikasi saat ini.</p>
    @endforelse

    <div class="mt-4">
        {{ $notifications->links() }}
    </div>
</div>
@endsection
