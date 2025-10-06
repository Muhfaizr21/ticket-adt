<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class AdminEventController extends Controller
{
    /**
     * Menampilkan semua event (Halaman Daftar Event)
     */
    public function index()
    {
        $events = Event::latest()->paginate(10); // pakai paginate biar lebih rapi
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
        ]);

        Event::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'total_tickets' => $request->total_tickets,
            'available_tickets' => $request->total_tickets,
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
            'available_tickets' => 'required|integer|min:0',
        ]);

        $event->update($request->only([
            'name', 'description', 'price', 'total_tickets', 'available_tickets'
        ]));

        return redirect()->route('admin.events.index')
            ->with('success', 'Event berhasil diperbarui.');
    }

    /**
     * Hapus event
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', 'Event berhasil dihapus.');
    }
}
