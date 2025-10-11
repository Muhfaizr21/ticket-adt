<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Order;
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
        $events = Event::latest()->paginate(8);

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

        // Buat tiket
        for ($i = 0; $i < $request->quantity; $i++) {
            Ticket::create([
                'event_id' => $event->id,
                'user_id'  => Auth::id(),
                'type'     => 'reguler',
                'price'    => $event->price,
                'status'   => 'pending'
            ]);
        }

        $event->decrement('available_tickets', $request->quantity);

        // Buat order baru
        Order::create([
            'user_id'     => Auth::id(),
            'event_id'    => $event->id,
            'quantity'    => $request->quantity,
            'total_price' => $event->price * $request->quantity,
            'status'      => 'pending',
        ]);

        return redirect()->route('tickets.index')
            ->with('success', 'Tiket berhasil dibeli! Pesananmu sedang diproses.');
    }

    /**
     * ✅ Verifikasi tiket (dipanggil dari QR Scanner)
     */
    public function verify(Request $request)
    {
        $barcode = $request->barcode_code;

        // Cari tiket berdasarkan kode barcode
        $order = Order::where('barcode_code', $barcode)->first();

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => '❌ Kode tiket tidak ditemukan di sistem.'
            ]);
        }

        // Jika tiket sudah check-in sebelumnya
        if ($order->checked_in_at) {
            return response()->json([
                'status' => 'error',
                'message' => '⚠️ Tiket ini sudah digunakan untuk check-in sebelumnya.'
            ]);
        }

        // Tandai tiket sebagai sudah check-in
        $order->checked_in_at = now();
        $order->save();

        return response()->json([
            'status' => 'success',
            'message' => '✅ Tiket berhasil diverifikasi!',
            'order_id' => $order->id
        ]);
    }
}
