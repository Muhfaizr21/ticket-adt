@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Manajemen Tiket Festival Indonesia')

@push('styles')
<link href="{{ asset('css/admin/dashboard.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="dashboard-header">
    <h1 class="dashboard-title">Dashboard</h1>
</div>

<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-content">
            <div class="stat-icon">
                <i class="bi bi-ticket-perforated"></i>
            </div>
            <div class="stat-details">
                <div class="stat-number">{{ number_format($dashboardData['stats']['total_tickets']) }}</div>
                <div class="stat-title">Total Tiket</div>
                <div class="stat-change positive">
                    <i class="bi bi-arrow-up"></i>
                    18.2%
                </div>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-content">
            <div class="stat-icon">
                <i class="bi bi-currency-dollar"></i>
            </div>
            <div class="stat-details">
                <div class="stat-number">Rp{{ number_format($dashboardData['stats']['total_revenue'], 0, ',', '.') }}</div>
                <div class="stat-title">Total Pendapatan</div>
                <div class="stat-change positive">
                    <i class="bi bi-arrow-up"></i>
                    22.7%
                </div>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-content">
            <div class="stat-icon">
                <i class="bi bi-clock"></i>
            </div>
            <div class="stat-details">
                <div class="stat-number">{{ $dashboardData['stats']['pending_tickets'] }}</div>
                <div class="stat-title">Tiket Pending</div>
                <div class="stat-change negative">
                    <i class="bi bi-arrow-down"></i>
                    3.5%
                </div>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-content">
            <div class="stat-icon">
                <i class="bi bi-people"></i>
            </div>
            <div class="stat-details">
                <div class="stat-number">{{ number_format($dashboardData['stats']['attendees_count']) }}</div>
                <div class="stat-title">Total Peserta</div>
                <div class="stat-change positive">
                    <i class="bi bi-arrow-up"></i>
                    25.3%
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="charts-grid">
    <!-- Revenue Chart -->
    <div class="chart-card">
        <div class="chart-header">
            <h3 class="chart-title">Pendapatan Bulanan (Rupiah)</h3>
            <div class="chart-actions">
                <button class="btn btn-sm">Bulan</button>
                <button class="btn btn-sm">Tahun</button>
            </div>
        </div>
        <div class="chart-container">
            <div class="simple-bars">
                @foreach($dashboardData['monthly_sales']['data'] as $index => $value)
                <div class="bar-container">
                    <div class="bar" style="height: {{ ($value / max($dashboardData['monthly_sales']['data'])) * 180 }}px;"></div>
                    <div class="bar-label">{{ $dashboardData['monthly_sales']['labels'][$index] }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Top Events -->
    <div class="chart-card">
        <div class="chart-header">
            <h3 class="chart-title">Festival Terpopuler</h3>
        </div>
        <div class="mini-chart">
            @foreach($dashboardData['top_events'] as $event)
            <div class="mini-chart-item">
                <div class="mini-chart-info">
                    <div class="mini-chart-name">{{ $event['name'] }}</div>
                    <div class="mini-chart-stats">{{ number_format($event['tickets_sold']) }} tiket</div>
                    <div class="mini-chart-bar">
                        <div class="mini-chart-progress" style="width: {{ ($event['tickets_sold'] / max(array_column($dashboardData['top_events'], 'tickets_sold'))) * 100 }}%"></div>
                    </div>
                </div>
                <div class="mini-chart-value">Rp{{ number_format($event['revenue'], 0, ',', '.') }}</div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Recent Tickets & Quick Actions -->
<div class="row">
    <!-- Recent Tickets -->
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Tiket Terbaru</h3>
                <a href="#" class="btn btn-primary btn-sm">Lihat Semua</a>
            </div>
            <div class="p-0">
                <ul class="activity-list">
                    @foreach($dashboardData['recent_tickets'] as $ticket)
                    <li class="activity-item">
                        <div class="activity-icon">
                            <i class="bi bi-ticket-perforated"></i>
                        </div>
                        <div class="activity-details">
                            <div class="activity-title">{{ $ticket['event'] }}</div>
                            <div class="activity-meta">
                                {{ $ticket['customer'] }} • {{ $ticket['type'] }} • Rp{{ number_format($ticket['price'], 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="activity-badge badge-{{ $ticket['status'] }}">
                            {{ ucfirst($ticket['status']) }}
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection