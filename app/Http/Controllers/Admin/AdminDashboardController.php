<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // --- Statistik Dasar ---
        $dashboardData['stats'] = [
            'total_orders' => Order::count(),
            'total_revenue' => Order::sum('total_price'),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'attendees_count' => User::whereHas('orders')->count(),
        ];

        // --- Pendapatan Bulanan ---
        $monthlySales = Order::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_price) as total')
            )
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $dashboardData['monthly_sales'] = [
            'labels' => $monthlySales->pluck('month')->map(fn($m) => date('M', mktime(0, 0, 0, $m, 1)))->toArray(),
            'data' => $monthlySales->pluck('total')->toArray(),
        ];

        // --- Event Terpopuler ---
        $topEvents = Event::select('name')
            ->withSum('orders', 'total_price')
            ->withCount('orders')
            ->orderByDesc('orders_count')
            ->limit(5)
            ->get()
            ->map(function ($event) {
                return [
                    'name' => $event->name,
                    'tickets_sold' => $event->orders_count,
                    'revenue' => $event->orders_sum_total_price,
                ];
            });

        $dashboardData['top_events'] = $topEvents;

        // --- Order Terbaru ---
        $dashboardData['recent_orders'] = Order::latest()
            ->take(5)
            ->get()
            ->map(function ($order) {
                return [
                    'event' => optional($order->event)->name ?? '-',
                    'customer' => optional($order->user)->name ?? 'Tamu',
                    'type' => ucfirst($order->type ?? '-'),
                    'price' => $order->total_price,
                    'status' => ucfirst($order->status),
                ];
            });

        return view('admin.pages.dashboard', compact('dashboardData'));
    }
}
