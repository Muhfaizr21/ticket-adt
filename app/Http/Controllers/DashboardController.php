<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil semua event terbaru
        $events = Event::orderBy('date', 'asc')->get();

        // Pemetaan agar sesuai dengan Blade lama
        $festivalData = $events->map(function($event) {
            return [
                'id' => $event->id,
                'title' => $event->name,
                'description' => $event->description,
                'date' => $event->date ? $event->date->format('l, d F Y') : 'TBA',
                'time' => '00:00 – 23:59 WIB',
                'location' => $event->location,
                'poster' => $event->poster,
                'rating' => 4.5, // bisa dikustomisasi / ambil dari rating db jika ada
                'min_age' => 17,
                'ticket_prices' => [
                    [
                        'type' => 'VIP',
                        'price' => $event->vip_price ? 'Rp ' . number_format($event->vip_price, 0, ',', '.') : 'Rp 0',
                        'description' => $event->vip_tickets ? 'Tersedia' : 'Habis',
                    ],
                    [
                        'type' => 'Reguler',
                        'price' => $event->reguler_price ? 'Rp ' . number_format($event->reguler_price, 0, ',', '.') : 'Rp 0',
                        'description' => $event->reguler_tickets ? 'Tersedia' : 'Habis',
                    ],
                ],
                'similar_events' => [], // bisa ditambahkan nanti
            ];
        });

        return view('pages.dashboard', ['festivalData' => $festivalData]);
    }
}
