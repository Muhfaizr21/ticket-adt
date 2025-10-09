<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Models\Event;
use App\Models\TicketType;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    /**
     * Tampilkan daftar promo per event & tipe tiket
     */
    public function index()
    {
        // Ambil semua event beserta tipe tiket dan promonya
        $events = Event::with(['ticketTypes.promotions'])->latest()->get();

        return view('admin.promotions.index', compact('events'));
    }

    /**
     * Form tambah promo
     */
    public function create(Request $request)
    {
        $events = Event::latest()->get();
        $ticketTypes = TicketType::latest()->get();

        // Jika create dari tabel event, bisa otomatis pilih event & ticket_type
        $selectedEvent = $request->get('event_id');
        $selectedTicketType = $request->get('ticket_type_id');

        return view('admin.promotions.create', compact('events', 'ticketTypes', 'selectedEvent', 'selectedTicketType'));
    }

    /**
     * Simpan promo baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'nullable|exists:events,id',
            'ticket_type_id' => 'nullable|exists:ticket_types,id',
            'code' => 'required|string|max:255|unique:promotions,code',
            'name' => 'required|string|max:255',
            'persen_diskon' => 'nullable|numeric|min:0|max:100',
            'value' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_active' => 'nullable|boolean',
        ]);

        Promotion::create([
            'event_id' => $request->event_id,
            'ticket_type_id' => $request->ticket_type_id,
            'code' => $request->code,
            'name' => $request->name,
            'persen_diskon' => $request->persen_diskon,
            'value' => $request->value,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.promotions.index')->with('success', 'Promo berhasil ditambahkan!');
    }

    /**
     * Form edit promo
     */
    public function edit(Promotion $promotion)
    {
        $events = Event::latest()->get();
        $ticketTypes = TicketType::latest()->get();

        return view('admin.promotions.edit', compact('promotion', 'events', 'ticketTypes'));
    }

    /**
     * Update promo
     */
    public function update(Request $request, Promotion $promotion)
    {
        $request->validate([
            'event_id' => 'nullable|exists:events,id',
            'ticket_type_id' => 'nullable|exists:ticket_types,id',
            'name' => 'required|string|max:255',
            'persen_diskon' => 'nullable|numeric|min:0|max:100',
            'value' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_active' => 'nullable|boolean',
        ]);

        $promotion->update([
            'event_id' => $request->event_id,
            'ticket_type_id' => $request->ticket_type_id,
            'name' => $request->name,
            'persen_diskon' => $request->persen_diskon,
            'value' => $request->value,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->boolean('is_active', false),
        ]);

        return redirect()->route('admin.promotions.index')->with('success', 'Promo berhasil diperbarui!');
    }

    /**
     * Hapus promo
     */
    public function destroy(Promotion $promotion)
    {
        $promotion->delete();
        return redirect()->route('admin.promotions.index')->with('success', 'Promo berhasil dihapus!');
    }
}
