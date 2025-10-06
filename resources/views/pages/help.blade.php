@extends('layouts.app')

@section('title', 'Help Center')
@section('page-title', 'Help Center')

@push('styles')
<link href="{{ asset('css/help.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="help-container">
    <div class="help-header">
        <h1 class="help-title">Help Center</h1>
    </div>
</div>
@endsection