<?php

namespace App\Http\Controllers\Admin\Tickets;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class TicketTypeController extends Controller
{
    // 🟢 Tampilkan semua event dengan tipe tiket
    public function index()
    {
        $events = Event::latest()->get();
        return view('admin.tickets.ticket-types.index', compact('events'));
    }

    // 🟢 Form edit VIP & Reguler per event
    public function edit($id)
    {
        $event = Event::findOrFail($id);

        // Default value jika kolom masih null
        $vipQuantity = $event->vip_tickets ?? ceil($event->total_tickets * 0.3);
        $regulerQuantity = $event->reguler_tickets ?? floor($event->total_tickets * 0.7);
        $vipPrice = $event->vip_price ?? $event->price * 1.5;
        $regulerPrice = $event->reguler_price ?? $event->price;

        return view('admin.tickets.ticket-types.edit', compact(
            'event',
            'vipQuantity',
            'regulerQuantity',
            'vipPrice',
            'regulerPrice'
        ));
    }

    // 🟢 Update tipe tiket VIP & Reguler
    public function update(Request $request, $id)
    {
        $request->validate([
            'vip_tickets' => 'required|integer|min:0',
            'vip_price' => 'required|numeric|min:0',
            'reguler_tickets' => 'required|integer|min:0',
            'reguler_price' => 'required|numeric|min:0',
        ]);

        $event = Event::findOrFail($id);

        // Update kolom VIP & Reguler
        $event->vip_tickets = $request->vip_tickets;
        $event->vip_price = $request->vip_price;
        $event->reguler_tickets = $request->reguler_tickets;
        $event->reguler_price = $request->reguler_price;

        // Update total tickets
        $event->total_tickets = $request->vip_tickets + $request->reguler_tickets;

        $event->save();

        return redirect()->route('admin.ticket-types.index')
            ->with('success', 'Tipe tiket berhasil diperbarui!');
    }
    public function updateAll(Request $request)
    {
        $events = Event::all();

        foreach ($events as $event) {
            $event->vip_tickets = $request->input('vip_tickets.' . $event->id) ?? $event->vip_tickets;
            $event->vip_price   = $request->input('vip_price.' . $event->id) ?? $event->vip_price;
            $event->reguler_tickets = $request->input('reguler_tickets.' . $event->id) ?? $event->reguler_tickets;
            $event->reguler_price   = $request->input('reguler_price.' . $event->id) ?? $event->reguler_price;

            // Update total tiket
            $event->total_tickets = ($event->vip_tickets ?? 0) + ($event->reguler_tickets ?? 0);

            $event->save();
        }

        return redirect()->back()->with('success', 'Jumlah dan harga tiket berhasil diperbarui!');
    }
}
