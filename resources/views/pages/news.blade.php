@extends('layouts.app')

@section('title', 'News & Updates')
@section('page-title', 'News & Updates')

@push('styles')
<link href="{{ asset('css/news.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="news-container">
    <div class="news-header">
        <h1 class="news-title">News & Updates</h1>
    </div>
</div>
@endsection