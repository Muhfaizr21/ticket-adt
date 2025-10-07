<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Storage;

class AdminEventController extends Controller
{
    /**
     * 🟢 Menampilkan semua event
     */
    public function index()
    {
        $events = Event::latest()->paginate(10);
        return view('admin.events.index', compact('events'));
    }

    /**
     * 🟢 Form tambah event baru
     */
    public function create()
    {
        return view('admin.events.create');
    }

    /**
     * 🟢 Simpan event baru ke database
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'total_tickets' => 'required|integer|min:1',
            'poster' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Upload poster jika ada
        $posterPath = $request->hasFile('poster')
            ? $request->file('poster')->store('posters', 'public')
            : null;

        $vip = ceil($request->total_tickets * 0.3);
        $reguler = floor($request->total_tickets * 0.7);

        // Simpan event
        Event::create([
            'name' => $request->name,
            'description' => $request->description,
            'date' => $request->date,
            'location' => $request->location,
            'price' => $request->price,
            'total_tickets' => $request->total_tickets,
            'available_tickets' => $request->total_tickets,
            'vip_tickets' => null,
            'vip_price' => null,
            'reguler_tickets' => null,
            'reguler_price' => null,
            'poster' => $posterPath,
        ]);


        return redirect()->route('admin.events.index')
            ->with('success', 'Event berhasil dibuat.');
    }

    /**
     * 🟢 Form edit event
     */
    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    /**
     * 🟢 Update data event
     */
    public function update(Request $request, Event $event)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'total_tickets' => 'required|integer|min:1',
            'poster' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Update poster jika ada upload baru
        if ($request->hasFile('poster')) {
            if ($event->poster && Storage::disk('public')->exists($event->poster)) {
                Storage::disk('public')->delete($event->poster);
            }
            $event->poster = $request->file('poster')->store('posters', 'public');
        }

        // Update data dasar event
        $event->name = $request->name;
        $event->description = $request->description;
        $event->date = $request->date;
        $event->location = $request->location;
        $event->price = $request->price;
        $event->total_tickets = $request->total_tickets;

        // Pastikan tiket tersedia tidak melebihi total tiket
        if ($event->available_tickets > $request->total_tickets) {
            $event->available_tickets = $request->total_tickets;
        }

        $event->save();

        return redirect()->route('admin.events.index')
            ->with('success', 'Event berhasil diperbarui.');
    }

    /**
     * 🟢 Hapus event
     */
    public function destroy(Event $event)
    {
        // Hapus poster jika ada
        if ($event->poster && Storage::disk('public')->exists($event->poster)) {
            Storage::disk('public')->delete($event->poster);
        }

        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', 'Event berhasil dihapus.');
    }
}
