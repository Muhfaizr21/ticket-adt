<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    // 🟢 Tampilkan semua event
   public function index()
{
    $events = Event::with('venue')->latest()->paginate(10);
    return view('admin.events.index', compact('events'));
}

    // 🟢 Form tambah event baru
    public function create()
    {
        $venues = Venue::all();
        return view('admin.events.create', compact('venues'));
    }

    // 🟢 Simpan data baru
   public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'description' => 'required|string',
        'date' => 'required|date',
        'location' => 'required|string|max:255',
        'total_tickets' => 'required|integer|min:1',
        'available_tickets' => 'required|integer|min:1',
        'poster' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
    ]);

    // simpan poster jika ada
    if ($request->hasFile('poster')) {
        $validated['poster'] = $request->file('poster')->store('posters', 'public');
    }

    // buat event baru
    Event::create($validated);

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

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'venue_id' => 'required|exists:venues,id',
            'date' => 'required|date',
            'price' => 'required|numeric',
            'total_tickets' => 'required|numeric|min:1',
            'poster' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'description' => 'nullable|string'
        ]);

        if ($request->hasFile('poster')) {
            if ($event->poster && Storage::disk('public')->exists($event->poster)) {
                Storage::disk('public')->delete($event->poster);
            }
            $validated['poster'] = $request->file('poster')->store('posters', 'public');
        }

        // pastikan tiket tersedia tidak melebihi total
        $validated['available_tickets'] = min($event->available_tickets, $validated['total_tickets']);

        $event->update($validated);

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil diperbarui!');
    }

    // 🟢 Hapus event
    public function destroy($id)
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
