<?php

namespace App\Http\Controllers;
use App\Models\Event;

use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        // Ambil semua event aktif
        $events = Event::where('status', 'active')->orderBy('date', 'asc')->get();

        return view('pembeli.events.index', compact('events'));
    }
}
