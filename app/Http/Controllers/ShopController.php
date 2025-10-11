<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    /**
     * Tampilkan daftar semua event beserta ticket types dan promo aktif
     */
    public function index()
    {
        $events = Event::with(['ticketTypes.promotions' => function ($query) {
            $query->where('is_active', 1)
                ->whereDate('start_date', '<=', now())
                ->whereDate('end_date', '>=', now());
        }])
            ->latest()
            ->paginate(8); // pagination 8 per page

        return view('shop.index', compact('events'));
    }

    /**
     * Tampilkan detail satu event beserta ticket types dan promo aktif
     */
    public function show($id)
    {
        $event = Event::with(['ticketTypes.promotions' => function ($query) {
            $query->where('is_active', 1)
                ->whereDate('start_date', '<=', now())
                ->whereDate('end_date', '>=', now());
        }])->findOrFail($id);

        return view('shop.show', compact('event'));
    }
}
