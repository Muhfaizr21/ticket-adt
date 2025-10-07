<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Storage;

class AdminEventController extends Controller
{
    /**
     * Menampilkan semua event (Halaman Daftar Event)
     */
    public function index()
    {
        $events = Event::latest()->paginate(10); // pagination untuk rapi
        return view('admin.events.index', compact('events'));
    }

    /**
     * Form tambah event
     */
    public function create()
    {
        return view('admin.events.create');
    }

    /**
     * Simpan event baru ke database
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|integer|min:0',
            'total_tickets' => 'required|integer|min:1',
            'poster' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $posterPath = null;
        if ($request->hasFile('poster')) {
            $posterPath = $request->file('poster')->store('posters', 'public');
        }

        Event::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'total_tickets' => $request->total_tickets,
            'available_tickets' => $request->total_tickets,
            'poster' => $posterPath,
        ]);

        return redirect()->route('admin.events.index')
            ->with('success', 'Event berhasil dibuat.');
    }

    /**
     * Form edit event
     */
    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    /**
     * Update data event
     */
    public function update(Request $request, Event $event)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|integer|min:0',
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

        $event->name = $request->name;
        $event->description = $request->description;
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
     * Hapus event
     */
    public function destroy(Event $event)
    {
        if ($event->poster && Storage::disk('public')->exists($event->poster)) {
            Storage::disk('public')->delete($event->poster);
        }

        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', 'Event berhasil dihapus.');
    }

}
