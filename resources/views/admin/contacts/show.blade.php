@extends('admin.layouts.app')
@section('title','Detail Aduan')

@section('content')
<div class="container py-3">
    <a href="{{ route('admin.contacts.index') }}" class="btn btn-sm btn-light mb-3">← Kembali</a>

    <div class="card">
        <div class="card-body">
            <h4>{{ $contact->subject }}</h4>
            <p><strong>Nama:</strong> {{ $contact->name }} — <strong>Email:</strong> {{ $contact->email }}</p>
            <hr>
            <p style="white-space:pre-wrap;">{{ $contact->message }}</p>
            <hr>
            <small class="text-muted">Dikirim: {{ $contact->created_at->format('d M Y H:i') }}</small>
        </div>
    </div>
</div>
@endsection
