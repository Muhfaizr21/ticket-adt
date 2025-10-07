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
        $events = Event::latest()->paginate(10);
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
            'name'           => 'required|string|max:255',
            'description'    => 'nullable|string',
            'date'           => 'required|date',
            'location'       => 'required|string|max:255',
            'price'          => 'required|numeric|min:0',
            'total_tickets'  => 'required|integer|min:1',
            'venue_id'       => 'nullable|exists:venues,id',
            'poster'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Upload poster jika ada
        if ($request->hasFile('poster')) {
            $validated['poster'] = $request->file('poster')->store('posters', 'public');
        }

        $validated['available_tickets'] = $validated['total_tickets'];

        Event::create($validated);

        return redirect()->route('admin.events.index')
            ->with('success', '✅ Event baru berhasil ditambahkan!');
    }

    // 🟢 Form edit event
    public function edit($id)
    {
        $event = Event::findOrFail($id);
        $venues = Venue::all();
        return view('admin.events.edit', compact('event', 'venues'));
    }

    // 🟢 Update event
    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        // Validasi semua kolom
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'description'    => 'nullable|string',
            'date'           => 'required|date',
            'location'       => 'required|string|max:255',
            'price'          => 'nullable|numeric|min:0', // ← tidak wajib lagi
            'total_tickets'  => 'required|integer|min:1',
            'venue_id'       => 'nullable|exists:venues,id',
            'poster'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $validated;

        // Poster
        if ($request->hasFile('poster')) {
            if ($event->poster && Storage::disk('public')->exists($event->poster)) {
                Storage::disk('public')->delete($event->poster);
            }
            $validated['poster'] = $request->file('poster')->store('posters', 'public');
        }

        // Jangan ubah VIP/Reguler tickets & harga
        $data['vip_tickets'] = $event->vip_tickets;
        $data['vip_price'] = $event->vip_price;
        $data['reguler_tickets'] = $event->reguler_tickets;
        $data['reguler_price'] = $event->reguler_price;

        // Pastikan available_tickets tidak melebihi total_tickets
        $data['available_tickets'] = min($event->available_tickets, $data['total_tickets']);

        $event->fill($data)->save();

        return redirect()->route('admin.events.index')
            ->with('success', '✅ Data event berhasil diperbarui!');
    }

    // 🟢 Hapus event
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
