<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Venue; // ⬅️ tambahkan ini
use Illuminate\Http\Request;

class EventController extends Controller
{
    // 🟢 Tampilkan semua event
    public function index()
    {
        $events = Event::latest()->paginate(10); // pagination
        return view('admin.events.index', compact('events'));
    }

    // 🟢 Form tambah event baru
    public function create()
    {
        $venues = Venue::all(); // ambil semua lokasi (venue)
        return view('admin.events.create', compact('venues'));
    }

    // 🟢 Simpan data baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'total_tickets' => 'required|numeric|min:1',
            'poster' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $data = $request->all();
        if ($request->hasFile('poster')) {
            $data['poster'] = $request->file('poster')->store('posters', 'public');
        }
        $data['available_tickets'] = $data['total_tickets'];

        Event::create($data);

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil ditambahkan!');
    }

    // 🟢 Form edit event
    public function edit(Event $event)
    {
        $event = Event::findOrFail($id);
        return view('admin.events.edit', compact('event'));
    }

    // 🟢 Update data event
    public function update(Request $request, Event $event)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'total_tickets' => 'required|numeric|min:1',
            'poster' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $event = Event::findOrFail($id);
        $data = $request->all();

        if ($request->hasFile('poster')) {
            if ($event->poster && Storage::disk('public')->exists($event->poster)) {
                Storage::disk('public')->delete($event->poster);
            }
            $data['poster'] = $request->file('poster')->store('posters', 'public');
        }

        // Pastikan available_tickets tidak melebihi total
        $event->available_tickets = min($event->available_tickets, $data['total_tickets']);
        $event->update($data);

        return redirect()->route('admin.events.edit', $event->id)
            ->with('success', 'Event berhasil diperbarui!');
    }

    // 🟢 Hapus event
    public function destroy(Event $event)
    {
        $event = Event::findOrFail($id);
        if ($event->poster && Storage::disk('public')->exists($event->poster)) {
            Storage::disk('public')->delete($event->poster);
        }
        $event->delete();

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil dihapus!');
    }

    // 🟢 Tampilkan tipe tiket (VIP & Reguler)
    public function ticketTypes()
    {
        $events = Event::latest()->get();
        return view('admin.tickets.ticket-types.index', compact('events'));
    }
}
