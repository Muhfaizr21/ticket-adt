<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    // 🟢 Daftar semua event
    public function index()
    {
        $events = Event::latest()->paginate(10);
        return view('admin.events.index', compact('events'));
    }

    // 🟢 Form tambah event baru
    public function create()
    {
        return view('admin.events.create');
    }

    // 🟢 Simpan event baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'total_tickets' => 'required|integer|min:1',
            'available_tickets' => 'nullable|integer|min:0',
            'poster' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Upload poster jika ada
        if ($request->hasFile('poster')) {
            $validated['poster'] = $request->file('poster')->store('posters', 'public');
        }

        // Pastikan available_tickets ada
        if (empty($validated['available_tickets'])) {
            $validated['available_tickets'] = $validated['total_tickets'];
        }

        // Simpan ke database
        Event::create($validated);

        return redirect()->route('admin.events.index')->with('success', '✅ Event berhasil dibuat.');
    }

    // 🟢 Form edit event
    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    // 🟢 Update data event
    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'total_tickets' => 'required|integer|min:1',
            'available_tickets' => 'nullable|integer|min:0',
            'poster' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Jika poster baru diupload
        if ($request->hasFile('poster')) {
            if ($event->poster && Storage::disk('public')->exists($event->poster)) {
                Storage::disk('public')->delete($event->poster);
            }
            $validated['poster'] = $request->file('poster')->store('posters', 'public');
        } else {
            // Gunakan poster lama jika tidak diubah
            $validated['poster'] = $event->poster;
        }

        // Update data di database
        $event->update($validated);

        return redirect()->route('admin.events.index')->with('success', '✅ Event berhasil diperbarui.');
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
