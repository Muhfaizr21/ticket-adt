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
            'description' => 'nullable|string',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'venue_id' => 'nullable|exists:venues,id',
            'price' => 'required|numeric|min:0',
            'total_tickets' => 'required|integer|min:1',
            'poster' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Upload poster jika ada
        if ($request->hasFile('poster')) {
            $validated['poster'] = $request->file('poster')->store('posters', 'public');
        }

        // Set available_tickets otomatis
        $validated['available_tickets'] = $validated['total_tickets'];

        Event::create($validated);

        return redirect()->route('admin.events.index')->with('success', '✅ Event berhasil dibuat.');
    }

    // 🟢 Form edit event
    public function edit(Event $event)
    {
        $venues = Venue::all();
        return view('admin.events.edit', compact('event', 'venues'));
    }

    // 🟢 Update data event
    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'venue_id' => 'nullable|exists:venues,id',
            'price' => 'required|numeric|min:0',
            'total_tickets' => 'required|integer|min:1',
            'poster' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Hapus poster lama jika ada
        if ($request->hasFile('poster')) {
            if ($event->poster && Storage::disk('public')->exists($event->poster)) {
                Storage::disk('public')->delete($event->poster);
            }
            $validated['poster'] = $request->file('poster')->store('posters', 'public');
        } else {
            $validated['poster'] = $event->poster;
        }

        // Pastikan available_tickets tidak melebihi total_tickets
        $validated['available_tickets'] = min($event->available_tickets, $validated['total_tickets']);

        $event->update($validated);

        return redirect()->route('admin.events.edit', $event->id)
            ->with('success', '✅ Event berhasil diperbarui.');
    }

    // 🟢 Hapus event
    public function destroy(Event $event)
    {
        if ($event->poster && Storage::disk('public')->exists($event->poster)) {
            Storage::disk('public')->delete($event->poster);
        }

        $event->delete();

        return redirect()->route('admin.events.index')->with('success', '🗑️ Event berhasil dihapus.');
    }
}
