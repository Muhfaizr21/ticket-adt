<?php

namespace App\Http\Controllers\Admin\Tickets;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\TicketType;
use Illuminate\Http\Request;

class TicketTypeController extends Controller
{
    /**
     * 🧾 Tampilkan daftar semua event dan tipe tiket terkait
     */
    public function index()
    {
        $events = Event::with('ticketTypes')->get();
        return view('admin.tickets.ticket-types.index', compact('events'));
    }

    /**
     * ➕ Form tambah tipe tiket untuk event tertentu
     */
    public function create($event)
    {
        $event = Event::findOrFail($event);
        return view('admin.tickets.ticket-types.create', compact('event'));
    }

    /**
     * 💾 Simpan tipe tiket baru untuk event tertentu
     */
    public function store(Request $request, $event)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'total_tickets' => 'required|integer|min:1',
            'available_tickets' => 'required|integer|min:0|max:' . $request->total_tickets,
        ]);

        $eventModel = Event::findOrFail($event);
        $validated['event_id'] = $eventModel->id;

        TicketType::create($validated);

        return redirect()
            ->route('admin.ticket-types.index')
            ->with('success', 'Tipe tiket berhasil ditambahkan untuk event ' . $eventModel->name . '.');
    }

    /**
     * ✏️ Form edit tipe tiket
     */
    public function edit($event, $ticket)
    {
        $eventModel = Event::findOrFail($event);
        $ticketType = TicketType::where('event_id', $eventModel->id)
            ->findOrFail($ticket);

        return view('admin.tickets.ticket-types.edit', compact('eventModel', 'ticketType'));
    }

    /**
     * 🔄 Update data tipe tiket
     */
    public function update(Request $request, $event, $ticket)
    {
        $ticketType = TicketType::where('event_id', $event)->findOrFail($ticket);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'total_tickets' => 'required|integer|min:1',
            'available_tickets' => 'required|integer|min:0|max:' . $request->total_tickets,
        ]);

        $ticketType->update($validated);

        return redirect()
            ->route('admin.ticket-types.index')
            ->with('success', 'Tipe tiket berhasil diperbarui.');
    }

    /**
     * 🗑️ Hapus tipe tiket
     */
    public function destroy($event, $ticket)
    {
        $ticketType = TicketType::where('event_id', $event)->findOrFail($ticket);
        $ticketType->delete();

        return redirect()
            ->route('admin.ticket-types.index')
            ->with('success', 'Tipe tiket berhasil dihapus.');
    }
}
