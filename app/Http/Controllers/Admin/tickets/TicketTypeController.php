<?php

namespace App\Http\Controllers\Admin\Tickets;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\TicketType;
use Illuminate\Http\Request;

class TicketTypeController extends Controller
{
    /**
     * 🟢 Tampilkan daftar event beserta tipe tiketnya
     */
    public function index()
    {
        // Ambil semua event beserta ticket type
        $events = Event::with('ticketTypes')->latest()->get();
        return view('admin.tickets.ticket-types.index', compact('events'));
    }

    /**
     * 🟢 Form untuk mengelola tipe tiket per event
     */
    public function edit($event_id)
    {
        $event = Event::with('ticketTypes')->findOrFail($event_id);
        return view('admin.tickets.ticket-types.edit', compact('event'));
    }

    /**
     * 🟢 Simpan tipe tiket baru
     */
    public function store(Request $request, $event_id)
    {
        $event = Event::findOrFail($event_id);

        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'total_tickets' => 'required|integer|min:1',
            'available_tickets' => 'nullable|integer|min:0',
        ]);

        $event->ticketTypes()->create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'total_tickets' => $request->total_tickets,
            'available_tickets' => $request->available_tickets ?? $request->total_tickets,
        ]);

        return redirect()->route('admin.ticket-types.edit', $event_id)
            ->with('success', 'Tipe tiket baru berhasil ditambahkan!');
    }

    /**
     * 🟢 Update tipe tiket yang ada
     */
    public function update(Request $request, $event_id, $ticket_id)
    {
        $ticketType = TicketType::where('event_id', $event_id)->findOrFail($ticket_id);

        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'total_tickets' => 'required|integer|min:1',
            'available_tickets' => 'nullable|integer|min:0',
        ]);

        $ticketType->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'total_tickets' => $request->total_tickets,
            'available_tickets' => $request->available_tickets ?? $request->total_tickets,
        ]);

        return redirect()->route('admin.ticket-types.edit', $event_id)
            ->with('success', 'Tipe tiket berhasil diperbarui!');
    }

    /**
     * 🔴 Hapus tipe tiket
     */
    public function destroy($event_id, $ticket_id)
    {
        $ticketType = TicketType::where('event_id', $event_id)->findOrFail($ticket_id);
        $ticketType->delete();

        return redirect()->route('admin.ticket-types.edit', $event_id)
            ->with('success', 'Tipe tiket berhasil dihapus!');
    }
}
