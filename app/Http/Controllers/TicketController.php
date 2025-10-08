<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    /**
     * Menampilkan daftar tiket user yang sudah dibeli
     */
    public function index()
{
    // ambil semua event buat tampilan
    $events = \App\Models\Event::latest()->paginate(8);

    // ambil tiket user kalau perlu dipakai
    $tickets = Ticket::with('event')
        ->where('user_id', Auth::id())
        ->latest()
        ->get();

    return view('shop.index', compact('events', 'tickets'));
}

    /**
     * Menangani pembelian tiket
     */
   public function store(Request $request)
{
    $request->validate([
        'event_id' => 'required|exists:events,id',
        'quantity' => 'required|integer|min:1',
    ]);

    $event = Event::findOrFail($request->event_id);

    if ($request->quantity > $event->available_tickets) {
        return back()->with('error', 'Jumlah tiket melebihi ketersediaan.');
    }

    // Buat tiket sesuai jumlah yang dibeli
    for ($i = 0; $i < $request->quantity; $i++) {
Ticket::create([
    'event_id' => $event->id,
    'user_id'  => Auth::id(),
    'type'     => 'reguler',   // ⚠️ kolom ini tidak ada di tabel
    'price'    => $event->price,
    'status'   => 'pending'
]);
    }

    // Kurangi stok tiket
    $event->decrement('available_tickets', $request->quantity);

    // ✅ Buat Order baru untuk admin panel
    \App\Models\Order::create([
        'user_id'      => Auth::id(),
        'event_id'     => $event->id,
        'quantity'     => $request->quantity,
        'total_price'  => $event->price * $request->quantity,
        'status'       => 'pending',
    ]);

    return redirect()->route('tickets.index')
        ->with('success', 'Tiket berhasil dibeli! Pesananmu sedang diproses.');
}


}
