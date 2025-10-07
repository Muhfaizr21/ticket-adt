<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Venue;
use Illuminate\Http\Request;

class VenueController extends Controller
{
    public function index()
    {
        $venues = Venue::latest()->get();
        return view('admin.venues.index', compact('venues'));
    }

    public function create()
    {
        return view('admin.venues.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'capacity' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        Venue::create($request->all());

        return redirect()->route('admin.venues.index')->with('success', 'Venue berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $venue = Venue::findOrFail($id);
        return view('admin.venues.edit', compact('venue'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'capacity' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $venue = Venue::findOrFail($id);
        $venue->update($request->all());

        return redirect()->route('admin.venues.index')->with('success', 'Venue berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $venue = Venue::findOrFail($id);
        $venue->delete();

        return redirect()->route('admin.venues.index')->with('success', 'Venue berhasil dihapus!');
    }
}
