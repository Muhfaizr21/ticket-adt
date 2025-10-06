@extends('layouts.app')

@section('title', 'Shop')
@section('page-title', 'Official Merchandise Shop')

@push('styles')
<link href="{{ asset('css/shop.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="shop-container">
    <div class="shop-header">
        <h1 class="shop-title">Shop Tickets</h1>
    </div>
</div>
@endsection