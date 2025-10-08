<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Models\Event;
use App\Models\TicketType;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::with(['ticketType', 'ticketType.event'])->latest()->get();
        return view('admin.promotions.index', compact('promotions'));
    }



    public function create()
    {
        $events = Event::latest()->get();
        $ticketTypes = TicketType::latest()->get();
        return view('admin.promotions.create', compact('events', 'ticketTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'nullable|exists:events,id',
            'ticket_type_id' => 'nullable|exists:ticket_types,id',
            'code' => 'required|unique:promotions,code',
            'name' => 'required|string|max:255',
            'type' => 'required|in:percentage,nominal',
            'value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_active' => 'nullable|boolean',
        ]);

        Promotion::create([
            'event_id' => $request->event_id,
            'ticket_type_id' => $request->ticket_type_id,
            'code' => $request->code,
            'name' => $request->name,
            'type' => $request->type,
            'value' => $request->value,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->is_active ?? 1,
        ]);

        return redirect()->route('admin.promotions.index')->with('success', 'Promo berhasil ditambahkan!');
    }

    public function edit(Promotion $promotion)
    {
        $events = Event::latest()->get();
        $ticketTypes = TicketType::latest()->get();
        return view('admin.promotions.edit', compact('promotion', 'events', 'ticketTypes'));
    }

    public function update(Request $request, Promotion $promotion)
    {
        $request->validate([
            'event_id' => 'nullable|exists:events,id',
            'ticket_type_id' => 'nullable|exists:ticket_types,id',
            'name' => 'required|string|max:255',
            'type' => 'required|in:percentage,nominal',
            'value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_active' => 'nullable|boolean',
        ]);

        $promotion->update([
            'event_id' => $request->event_id,
            'ticket_type_id' => $request->ticket_type_id,
            'name' => $request->name,
            'type' => $request->type,
            'value' => $request->value,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->is_active ?? 1,
        ]);

        return redirect()->route('admin.promotions.index')->with('success', 'Promo berhasil diperbarui!');
    }

    public function destroy(Promotion $promotion)
    {
        $promotion->delete();
        return redirect()->route('admin.promotions.index')->with('success', 'Promo berhasil dihapus!');
    }
}
