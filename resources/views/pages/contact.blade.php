@extends('layouts.app')

@section('title', 'Contact Us')
@section('page-title', 'Contact Us')

@push('styles')
<link href="{{ asset('css/contact.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="contact-container">
    <div class="contact-header">
        <h1 class="contact-title">Contact Us</h1>
    </div>
</div>
@endsection