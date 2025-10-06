<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $festivalData = [
            'title' => 'Pestapora 2024',
            'rating' => 4.7,
            'date' => 'Saturday, August 24, 2024',
            'time' => '14.00 – 23.00 WIB',
            'location' => 'Gelora Bung Karno, Jakarta',
            'description' => 'Pestapora adalah festival musik terbesar di Indonesia yang menghadirkan lineup artis lokal dan internasional terbaik. Tahun ini menampilkan lebih dari 50 artis dari berbagai genre musik seperti pop, rock, indie, EDM, dan R&B. Nikmati pengalaman festival yang tak terlupakan dengan stage yang megah, kuliner khas Indonesia, dan berbagai merchandise eksklusif.',
            'min_age' => 17,
            'ticket_prices' => [
                ['type' => 'Early Bird', 'price' => 'Rp 350.000', 'description' => 'Tiket akses standar - terjual cepat'],
                ['type' => 'Reguler', 'price' => 'Rp 550.000', 'description' => 'Tiket akses standar - available'],
                ['type' => 'VIP', 'price' => 'Rp 850.000', 'description' => 'Area khusus VIP + lounge access'],
                ['type' => 'VVIP', 'price' => 'Rp 1.250.000', 'description' => 'Backstage access + meet & greet'],
            ],
            'similar_events' => [
                [
                    'date' => '15 Sep 2024',
                    'title' => 'We The Fest 2024',
                    'location' => 'GBK Tennis Indoor, Jakarta',
                    'time' => '13:00 WIB',
                    'image' => 'wethefest.jpg',
                    'price' => '450',
                    'description' => 'Festival musik urban terbesar dengan lineup artis lokal dan internasional pilihan'
                ],
                [
                    'date' => '28 Sep 2024',
                    'title' => 'Djakarta Warehouse Project',
                    'location' => 'JIExpo Kemayoran, Jakarta',
                    'time' => '16:00 WIB',
                    'image' => 'warehouse.jpg',
                    'price' => '650',
                    'description' => 'Festival musik elektronik terbesar di Asia Tenggara dengan DJ dunia'
                ],
                [
                    'date' => '12 Oct 2024',
                    'title' => 'Soundrenaline 2024',
                    'location' => 'Pantai Carnaval, Ancol Jakarta',
                    'time' => '12:00 WIB',
                    'image' => 'soundrenaline.jpg',
                    'price' => '400',
                    'description' => 'Festival musik rock dan alternatif dengan atmosfer pantai yang menawan'
                ],
            ]
        ];

        return view('pages.dashboard', compact('festivalData'));
    }
}