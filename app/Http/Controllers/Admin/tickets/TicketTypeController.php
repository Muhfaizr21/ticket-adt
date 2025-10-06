<?php

namespace App\Http\Controllers\Admin\Tickets;

use App\Http\Controllers\Controller;
use App\Models\TicketType;
use Illuminate\Http\Request;

class TicketTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ticketTypes = TicketType::all();
        return view('admin.tickets.ticket-types.index', compact('ticketTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.tickets.ticket-types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:ticket_types,name',
            'description' => 'nullable|string|max:255',
        ]);

        TicketType::create($request->only('name', 'description'));

        return redirect()->route('admin.ticket-types.index')->with('success', 'Ticket type berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TicketType $ticketType)
    {
        return view('admin.tickets.ticket-types.edit', compact('ticketType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TicketType $ticketType)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:ticket_types,name,' . $ticketType->id,
            'description' => 'nullable|string|max:255',
        ]);

        $ticketType->update($request->only('name', 'description'));

        return redirect()->route('admin.ticket-types.index')->with('success', 'Ticket type berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TicketType $ticketType)
    {
        $ticketType->delete();
        return redirect()->route('admin.ticket-types.index')->with('success', 'Ticket type berhasil dihapus.');
    }
}
