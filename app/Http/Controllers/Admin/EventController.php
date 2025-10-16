<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Venue;
use App\Models\TicketType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    // Tampilkan semua event
    public function index()
    {
        $events = Event::with(['ticketTypes', 'venue'])
            ->latest()
            ->paginate(10);

        return view('admin.events.index', compact('events'));
    }

    // Form tambah event
    public function create()
    {
        $venues = Venue::all();
        return view('admin.events.create', compact('venues'));
    }

    // Simpan event baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'date'        => 'nullable|date',
            'start_time'  => 'nullable|date_format:H:i',
            'end_time'    => 'nullable|date_format:H:i|after_or_equal:start_time',
            'location'    => 'required|string|max:255',
            'venue_id'    => 'nullable|exists:venues,id',
            'poster'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('poster')) {
            $validated['poster'] = $request->file('poster')->store('posters/events', 'public');
        }

        $event = Event::create($validated);

        // Tipe tiket default
        $defaultTickets = [
            [
                'event_id'    => $event->id,
                'name'        => 'VIP',
                'description' => 'Akses eksklusif untuk area VIP',
                'available_tickets' => 0,
            ],
            [
                'event_id'    => $event->id,
                'name'        => 'Reguler',
                'description' => 'Tiket reguler untuk pengunjung umum',
                'available_tickets' => 0,
            ],
        ];

        TicketType::insert($defaultTickets);

        return redirect()->route('admin.events.index')
            ->with('success', '✅ Event baru berhasil ditambahkan!');
    }

    // Form edit event
    public function edit($id)
    {
        $event = Event::with(['ticketTypes', 'venue'])->findOrFail($id);
        $venues = Venue::all();

        return view('admin.events.edit', compact('event', 'venues'));
    }

    // Update event
    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'date'        => 'nullable|date',
            'start_time'  => 'nullable|date_format:H:i',
            'end_time'    => 'nullable|date_format:H:i|after_or_equal:start_time',
            'location'    => 'required|string|max:255',
            'venue_id'    => 'nullable|exists:venues,id',
            'poster'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('poster')) {
            // Hapus poster lama otomatis lewat model tidak perlu lagi, tapi bisa double safety
            if ($event->poster && Storage::disk('public')->exists($event->poster)) {
                Storage::disk('public')->delete($event->poster);
            }

            $validated['poster'] = $request->file('poster')->store('posters/events', 'public');
        }

        $event->update($validated);

        return redirect()->route('admin.events.index')
            ->with('success', '✅ Data event berhasil diperbarui!');
    }

    // Hapus event
    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete(); // Poster & ticket types otomatis terhapus

        return redirect()->route('admin.events.index')
            ->with('success', '🗑️ Event berhasil dihapus.');
    }
}
