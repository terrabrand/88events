<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VenueController extends Controller
{
    public function index()
    {
        $myVenues = Venue::where('organizer_id', Auth::id())->get();
        $globalVenues = Venue::where('is_global', true)->get();
        return view('organizer.venues.index', compact('myVenues', 'globalVenues'));
    }

    public function create()
    {
        return view('organizer.venues.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:0',
            'seat_map_image' => 'nullable|image|max:2048',
            'seat_numbers' => 'nullable|string',
        ]);

        if ($request->hasFile('seat_map_image')) {
            $validated['seat_map_image'] = $request->file('seat_map_image')->store('venues', 'public');
        }

        if (!empty($validated['seat_numbers'])) {
            $validated['seat_numbers'] = array_map('trim', explode(',', $validated['seat_numbers']));
        } else {
            $validated['seat_numbers'] = [];
        }

        Venue::create([
            'name' => $validated['name'],
            'address' => $validated['address'],
            'capacity' => $validated['capacity'] ?? 0,
            'seat_map_image' => $validated['seat_map_image'] ?? null,
            'is_global' => false,
            'organizer_id' => Auth::id(),
            'seat_numbers' => $validated['seat_numbers'],
        ]);

        return redirect()->route('organizer.venues.index')->with('success', 'Venue created successfully.');
    }

    public function edit(Venue $venue)
    {
        if ($venue->organizer_id != Auth::id()) {
            abort(403);
        }
        return view('organizer.venues.edit', compact('venue'));
    }

    public function update(Request $request, Venue $venue)
    {
        if ($venue->organizer_id != Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:0',
            'seat_map_image' => 'nullable|image|max:2048',
            'seat_numbers' => 'nullable|string',
        ]);

        if ($request->hasFile('seat_map_image')) {
            if ($venue->seat_map_image) {
                Storage::disk('public')->delete($venue->seat_map_image);
            }
            $validated['seat_map_image'] = $request->file('seat_map_image')->store('venues', 'public');
        }

        if (isset($validated['seat_numbers'])) {
            $validated['seat_numbers'] = array_map('trim', explode(',', $validated['seat_numbers']));
        }

        $venue->update($validated);

        return redirect()->route('organizer.venues.index')->with('success', 'Venue updated successfully.');
    }

    public function destroy(Venue $venue)
    {
        if ($venue->organizer_id != Auth::id()) {
            abort(403);
        }

        if ($venue->seat_map_image) {
            Storage::disk('public')->delete($venue->seat_map_image);
        }

        $venue->delete();

        return redirect()->route('organizer.venues.index')->with('success', 'Venue deleted successfully.');
    }

    public function pull(Venue $venue)
    {
        if (!$venue->is_global) {
            abort(403);
        }

        $newVenue = $venue->replicate();
        $newVenue->is_global = false;
        $newVenue->organizer_id = Auth::id();
        $newVenue->name = $venue->name . ' (Clone)';
        $newVenue->save();

        return redirect()->route('organizer.venues.index')
            ->with('success', 'Venue pulled from global library. You can now edit it.');
    }
}
