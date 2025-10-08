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
     * 🟢 Tampilkan semua event
     */
    public function index()
    {
        // Ambil event terbaru beserta tipe tiket
        $events = Event::with('ticketTypes')->latest()->paginate(10);
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
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'date'          => 'required|date',
            'location'      => 'required|string|max:255',
            'price'         => 'required|numeric|min:0',
            'total_tickets' => 'required|integer|min:1',
            'venue_id'      => 'nullable|exists:venues,id',
            'poster'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Upload poster jika ada
        if ($request->hasFile('poster')) {
            $validated['poster'] = $request->file('poster')->store('posters', 'public');
        }

        // Total tiket tersedia sama dengan total tiket
        $validated['available_tickets'] = $validated['total_tickets'];

        // Simpan event
        $event = Event::create($validated);

        // Tambahkan tipe tiket default (VIP dan Reguler)
        TicketType::create([
            'event_id'    => $event->id,
            'name'        => 'VIP',
            'description' => 'Akses eksklusif untuk area VIP',
        ]);

        TicketType::create([
            'event_id'    => $event->id,
            'name'        => 'Reguler',
            'description' => 'Tiket reguler untuk pengunjung umum',
        ]);

        return redirect()->route('admin.events.index')
            ->with('success', '✅ Event baru berhasil ditambahkan dengan tipe tiket default!');
    }

    /**
     * 🟢 Form edit event
     */
    public function edit($id)
    {
        $event = Event::with('ticketTypes')->findOrFail($id);
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
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'date'          => 'required|date',
            'location'      => 'required|string|max:255',
            'price'         => 'nullable|numeric|min:0',
            'total_tickets' => 'required|integer|min:1',
            'venue_id'      => 'nullable|exists:venues,id',
            'poster'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Update poster jika diunggah ulang
        if ($request->hasFile('poster')) {
            if ($event->poster && Storage::disk('public')->exists($event->poster)) {
                Storage::disk('public')->delete($event->poster);
            }
            $validated['poster'] = $request->file('poster')->store('posters', 'public');
        }

        // Update jumlah tiket yang tersedia agar tidak melebihi total
        $validated['available_tickets'] = min($event->available_tickets, $validated['total_tickets']);

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

        if ($event->poster && Storage::disk('public')->exists($event->poster)) {
            Storage::disk('public')->delete($event->poster);
        }

        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', '🗑️ Event berhasil dihapus.');
    }
}
