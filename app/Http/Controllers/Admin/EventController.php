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
    /**
     * 🟢 Tampilkan semua event (dengan venue dan tipe tiket)
     */
    public function index()
    {
        $events = Event::with(['ticketTypes', 'venue'])
            ->latest()
            ->paginate(10);

        return view('admin.events.index', compact('events'));
    }

    /**
     * 🟢 Form tambah event
     */
    public function create()
    {
        $venues = Venue::all();
        return view('admin.events.create', compact('venues'));
    }

    /**
     * 🟢 Simpan event baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'description'       => 'nullable|string',
            'date'              => 'nullable|date',
            'location'          => 'required|string|max:255',
            'available_tickets' => 'nullable|integer|min:0',
            'venue_id'          => 'nullable|exists:venues,id',
            'poster'            => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Upload poster jika ada
        if ($request->hasFile('poster')) {
            $validated['poster'] = $request->file('poster')
                ->store('posters/events', 'public');
        }

        // Simpan data event ke database
        $event = Event::create($validated);

        // Tambahkan tipe tiket default
        $defaultTickets = [
            [
                'event_id'    => $event->id,
                'name'        => 'VIP',
                'description' => 'Akses eksklusif untuk area VIP',
            ],
            [
                'event_id'    => $event->id,
                'name'        => 'Reguler',
                'description' => 'Tiket reguler untuk pengunjung umum',
            ],
        ];

        TicketType::insert($defaultTickets);

        return redirect()->route('admin.events.index')
            ->with('success', '✅ Event baru berhasil ditambahkan dengan tipe tiket default!');
    }

    /**
     * 🟢 Form edit event
     */
    public function edit($id)
    {
        $event = Event::with(['ticketTypes', 'venue'])->findOrFail($id);
        $venues = Venue::all();

        return view('admin.events.edit', compact('event', 'venues'));
    }

    /**
     * 🟢 Update event
     */
    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'description'       => 'nullable|string',
            'date'              => 'nullable|date',
            'location'          => 'required|string|max:255',
            'available_tickets' => 'nullable|integer|min:0',
            'venue_id'          => 'nullable|exists:venues,id',
            'poster'            => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Jika ada file poster baru
        if ($request->hasFile('poster')) {
            // Hapus poster lama jika ada
            if ($event->poster && Storage::disk('public')->exists($event->poster)) {
                Storage::disk('public')->delete($event->poster);
            }

            // Upload poster baru
            $validated['poster'] = $request->file('poster')
                ->store('posters/events', 'public');
        }

        // Update data event
        $event->update($validated);

        return redirect()->route('admin.events.index')
            ->with('success', '✅ Data event berhasil diperbarui!');
    }

    /**
     * 🟢 Hapus event
     */
    public function destroy($id)
    {
        $event = Event::findOrFail($id);

        // Hapus poster jika ada
        if ($event->poster && Storage::disk('public')->exists($event->poster)) {
            Storage::disk('public')->delete($event->poster);
        }

        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', '🗑️ Event berhasil dihapus.');
    }
}
