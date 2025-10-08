<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {
        // Ambil semua event terbaru dengan pagination
        $events = Event::latest()->paginate(8);

        // Kirim ke view shop/index.blade.php
        return view('shop.index', compact('events'));
    }

    public function show($id)
    {
        $event = Event::findOrFail($id);
        return view('shop.show', compact('event'));
    }
}
