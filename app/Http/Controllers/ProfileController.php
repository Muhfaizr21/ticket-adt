<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Display user profile
     */
    public function index()
    {
        // Data dummy untuk profile
        $userData = [
            'user' => [
                'id' => 1,
                'name' => 'Ahmad Rizki',
                'email' => 'ahmad.rizki@example.com',
                'phone' => '+62 812-3456-7890',
                'avatar' => null,
                'joined_date' => '2023-01-15',
                'total_events' => 12,
                'total_tickets' => 24,
                'member_since' => '1 tahun 2 bulan'
            ],
            'recent_events' => [
                [
                    'event_name' => 'DWP 2024 - Djakarta Warehouse Project',
                    'date' => '2024-12-15',
                    'ticket_type' => 'VIP 2 Days',
                    'status' => 'confirmed'
                ],
                [
                    'event_name' => 'We The Fest 2024',
                    'date' => '2024-08-20',
                    'ticket_type' => 'General Admission',
                    'status' => 'confirmed'
                ],
                [
                    'event_name' => 'Java Jazz Festival 2024',
                    'date' => '2024-03-05',
                    'ticket_type' => 'Gold Pass',
                    'status' => 'completed'
                ]
            ]
        ];

        return view('pages.profile', compact('userData'));
    }

    /**
     * Update user profile
     */
    public function update(Request $request)
    {
        // Logic untuk update profile akan ditambahkan nanti
        return back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        // Logic untuk update password akan ditambahkan nanti
        return back()->with('success', 'Password updated successfully!');
    }
}