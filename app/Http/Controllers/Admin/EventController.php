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
        $events = Event::with('venue')->get(); // tampilkan event + venue-nya
        return view('admin.events.index', compact('events'));
    }

    // 🟢 Form tambah event
    public function create()
    {
        $venues = Venue::all(); // ambil semua lokasi (venue)
        return view('admin.events.create', compact('venues'));
    }

    // 🟢 Simpan data baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'total_tickets' => 'required|integer',
            'venue_id' => 'nullable|exists:venues,id',
        ]);

        Event::create($request->all());
        return redirect()->route('admin.events.index')->with('success', 'Event berhasil ditambahkan!');
    }

    // 🟢 Form edit event
    public function edit($id)
    {
        $event = Event::findOrFail($id);
        $venues = Venue::all();
        return view('admin.events.edit', compact('event', 'venues'));
    }

    // 🟢 Update data event
    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        $event->update($request->all());

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil diperbarui!');
    }

    // 🟢 Hapus event
    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil dihapus!');
    }
}
