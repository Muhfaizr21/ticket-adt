@php
    use Illuminate\Support\Facades\Auth;
@endphp

@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Manajemen Tiket Festival Indonesia')

@push('styles')
<style>
    /* ====== CARD & GRID ====== */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    .stat-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        padding: 1.5rem;
        transition: all 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    }
    .stat-content {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .stat-icon {
        background: linear-gradient(135deg, #007bff, #0056d2);
        color: white;
        font-size: 1.8rem;
        padding: 0.75rem 1rem;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .stat-details {
        flex: 1;
    }
    .stat-number {
        font-size: 1.8rem;
        font-weight: 700;
        color: #222;
    }
    .stat-title {
        font-size: 0.95rem;
        color: #666;
    }
    .stat-change {
        font-size: 0.9rem;
        margin-top: 0.25rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    .stat-change.positive {
        color: #28a745;
    }
    .stat-change.negative {
        color: #dc3545;
    }

    /* ====== CHART ====== */
    .charts-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    .chart-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        padding: 1.5rem;
    }
    .chart-title {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }
    .simple-bars {
        display: flex;
        align-items: flex-end;
        height: 200px;
        gap: 0.75rem;
    }
    .bar-container {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        align-items: center;
    }
    .bar {
        width: 24px;
        background: linear-gradient(135deg, #007bff, #0056d2);
        border-radius: 6px;
        transition: height 0.4s ease;
    }
    .bar-label {
        margin-top: 0.5rem;
        font-size: 0.8rem;
        color: #666;
    }

    /* ====== MINI CHART ====== */
    .mini-chart-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
    }
    .mini-chart-name {
        font-weight: 500;
        font-size: 0.95rem;
    }
    .mini-chart-stats {
        font-size: 0.8rem;
        color: #666;
    }
    .mini-chart-bar {
        background: #eee;
        height: 6px;
        border-radius: 4px;
        overflow: hidden;
        margin-top: 0.25rem;
    }
    .mini-chart-progress {
        background: linear-gradient(135deg, #007bff, #0056d2);
        height: 6px;
    }
    .mini-chart-value {
        font-weight: 600;
        font-size: 0.95rem;
    }

    /* ====== ACTIVITY ====== */
    .card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        margin-top: 1rem;
    }
    .activity-list {
        list-style: none;
        margin: 0;
        padding: 0;
    }
    .activity-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #eee;
    }
    .activity-item:last-child {
        border-bottom: none;
    }
    .activity-icon {
        font-size: 1.5rem;
        color: #007bff;
        margin-right: 1rem;
    }
    .activity-details {
        flex: 1;
    }
    .activity-title {
        font-weight: 600;
    }
    .activity-meta {
        font-size: 0.85rem;
        color: #666;
    }
    .activity-badge {
        padding: 0.4rem 0.75rem;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: capitalize;
    }
    .badge-paid { background: #d4edda; color: #155724; }
    .badge-pending { background: #fff3cd; color: #856404; }
    .badge-cancelled { background: #f8d7da; color: #721c24; }
</style>
@endpush

@section('content')
<div class="dashboard-header">
    <h1 class="dashboard-title fw-bold fs-3 mb-4">Dashboard</h1>
</div>

<!-- ====== Stats Grid ====== -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-content">
            <div class="stat-icon"><i class="bi bi-cart-check"></i></div>
            <div class="stat-details">
                <div class="stat-number count-animate" data-target="{{ $dashboardData['stats']['total_orders'] }}">0</div>
                <div class="stat-title">Total Pesanan</div>
                <div class="stat-change positive">
                    <i class="bi bi-arrow-up"></i>
                    <span class="percent-animate" data-target="18.2">0%</span>
                </div>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-content">
            <div class="stat-icon"><i class="bi bi-currency-dollar"></i></div>
            <div class="stat-details">
                <div class="stat-number count-animate" data-target="{{ $dashboardData['stats']['total_revenue'] }}">0</div>
                <div class="stat-title">Total Pendapatan</div>
                <div class="stat-change positive">
                    <i class="bi bi-arrow-up"></i>
                    <span class="percent-animate" data-target="22.7">0%</span>
                </div>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-content">
            <div class="stat-icon"><i class="bi bi-clock"></i></div>
            <div class="stat-details">
                <div class="stat-number count-animate" data-target="{{ $dashboardData['stats']['pending_orders'] }}">0</div>
                <div class="stat-title">Pesanan Pending</div>
                <div class="stat-change negative">
                    <i class="bi bi-arrow-down"></i>
                    <span class="percent-animate" data-target="3.5">0%</span>
                </div>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-content">
            <div class="stat-icon"><i class="bi bi-people"></i></div>
            <div class="stat-details">
                <div class="stat-number count-animate" data-target="{{ $dashboardData['stats']['attendees_count'] }}">0</div>
                <div class="stat-title">Total Pelanggan</div>
                <div class="stat-change positive">
                    <i class="bi bi-arrow-up"></i>
                    <span class="percent-animate" data-target="25.3">0%</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ====== Charts Section ====== -->
<div class="charts-grid">
    <div class="chart-card">
        <h3 class="chart-title">Pendapatan Bulanan (Rupiah)</h3>
        <div class="simple-bars">
            @foreach($dashboardData['monthly_sales']['data'] as $index => $value)
            <div class="bar-container">
                <div class="bar" style="height: {{ ($value / max($dashboardData['monthly_sales']['data'])) * 180 }}px;"></div>
                <div class="bar-label">{{ $dashboardData['monthly_sales']['labels'][$index] }}</div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="chart-card">
        <h3 class="chart-title">Event Terpopuler</h3>
        @php
            $maxTicketsSold = $dashboardData['top_events']->max('tickets_sold') ?: 1;
        @endphp
        @foreach($dashboardData['top_events'] as $event)
        <div class="mini-chart-item">
            <div>
                <div class="mini-chart-name">{{ $event['name'] }}</div>
                <div class="mini-chart-stats">{{ number_format($event['tickets_sold']) }} pesanan</div>
                <div class="mini-chart-bar">
                    <div class="mini-chart-progress" style="width: {{ ($event['tickets_sold'] / $maxTicketsSold) * 100 }}%"></div>
                </div>
            </div>
            <div class="mini-chart-value">Rp{{ number_format($event['revenue'], 0, ',', '.') }}</div>
        </div>
        @endforeach
    </div>
</div>

<!-- ====== Recent Orders ====== -->
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center p-3">
                <h3 class="card-title mb-0 fs-5">Pesanan Terbaru</h3>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-primary btn-sm">Lihat Semua</a>
            </div>
            <div class="p-0">
                <ul class="activity-list">
                    @foreach($dashboardData['recent_orders'] as $order)
                    <li class="activity-item">
                        <div class="activity-icon"><i class="bi bi-cart"></i></div>
                        <div class="activity-details">
                            <div class="activity-title">{{ $order['event'] }}</div>
                            <div class="activity-meta">
                                {{ $order['customer'] }} • {{ $order['type'] }} • Rp{{ number_format($order['price'], 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="activity-badge badge-{{ strtolower($order['status']) }}">
                            {{ ucfirst($order['status']) }}
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- ====== Script Animasi ====== -->
<script>
    function animateValue(el, start, end, duration, isPercent = false) {
        let startTime = null;
        function step(currentTime) {
            if (!startTime) startTime = currentTime;
            const progress = Math.min((currentTime - startTime) / duration, 1);
            const value = progress * (end - start) + start;
            el.textContent = isPercent ? value.toFixed(1) + '%' : Math.floor(value).toLocaleString('id-ID');
            if (progress < 1) requestAnimationFrame(step);
        }
        requestAnimationFrame(step);
    }

    document.querySelectorAll('.count-animate').forEach(el => {
        const target = parseFloat(el.getAttribute('data-target')) || 0;
        animateValue(el, 0, target, 1500);
    });

    document.querySelectorAll('.percent-animate').forEach(el => {
        const target = parseFloat(el.getAttribute('data-target')) || 0;
        animateValue(el, 0, target, 1200, true);
    });
</script>
@endsection
