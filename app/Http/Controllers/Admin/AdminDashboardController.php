<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    /**
     * Display admin dashboard
     */
    public function index()
    {
        // Data dummy untuk dashboard ticket management - Festival Indonesia
        $dashboardData = [
            'stats' => [
                'total_tickets' => 8560,
                'pending_tickets' => 45,
                'sold_tickets' => 7820,
                'total_revenue' => 12580000,
                'events_count' => 15,
                'attendees_count' => 12450
            ],
            'recent_tickets' => [
                [
                    'id' => 'TKT-2024-001',
                    'event' => 'DWP 2024 - Djakarta Warehouse Project',
                    'customer' => 'Ahmad Rizki',
                    'type' => 'VIP 2 Days',
                    'price' => 3500000,
                    'status' => 'confirmed',
                    'date' => '2024-03-15'
                ],
                [
                    'id' => 'TKT-2024-002',
                    'event' => 'We The Fest 2024',
                    'customer' => 'Sarah Putri',
                    'type' => 'General Admission',
                    'price' => 1850000,
                    'status' => 'pending',
                    'date' => '2024-03-14'
                ],
                [
                    'id' => 'TKT-2024-003',
                    'event' => 'Java Jazz Festival 2024',
                    'customer' => 'Budi Santoso',
                    'type' => 'Gold Pass',
                    'price' => 2750000,
                    'status' => 'confirmed',
                    'date' => '2024-03-13'
                ],
                [
                    'id' => 'TKT-2024-004',
                    'event' => 'Bali Arts Festival',
                    'customer' => 'Maya Sari',
                    'type' => 'Weekend Pass',
                    'price' => 850000,
                    'status' => 'confirmed',
                    'date' => '2024-03-12'
                ],
                [
                    'id' => 'TKT-2024-005',
                    'event' => 'Soundrenaline 2024',
                    'customer' => 'Rizky Pratama',
                    'type' => 'Early Bird',
                    'price' => 650000,
                    'status' => 'cancelled',
                    'date' => '2024-03-11'
                ]
            ],
            'top_events' => [
                ['name' => 'DWP 2024 - Djakarta Warehouse Project', 'tickets_sold' => 12500, 'revenue' => 2875000000],
                ['name' => 'We The Fest 2024', 'tickets_sold' => 8500, 'revenue' => 1572500000],
                ['name' => 'Java Jazz Festival 2024', 'tickets_sold' => 6200, 'revenue' => 1364000000],
                ['name' => 'Soundrenaline 2024', 'tickets_sold' => 4800, 'revenue' => 312000000],
                ['name' => 'Bali Arts Festival', 'tickets_sold' => 3200, 'revenue' => 272000000]
            ],
            'monthly_sales' => [
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                'data' => [450000000, 680000000, 1258000000, 950000000, 1100000000, 1420000000, 1650000000, 1880000000, 1250000000, 980000000, 1150000000, 2100000000]
            ]
        ];

        return view('admin.pages.dashboard', compact('dashboardData'));
    }
}