<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VenueController extends Controller
{
    public function index()
    {
        $venues = Venue::where('is_global', true)
            ->orWhereNull('organizer_id')
            ->get();
        return view('admin.venues.index', compact('venues'));
    }

    public function create()
    {
        return view('admin.venues.create');
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
            'is_global' => $request->has('is_global'),
            'organizer_id' => null,
            'seat_numbers' => $validated['seat_numbers'],
        ]);

        return redirect()->route('admin.venues.index')->with('success', 'Global venue created successfully.');
    }

    public function edit(Venue $venue)
    {
        if (!$venue->is_global) {
            abort(403);
        }
        return view('admin.venues.edit', compact('venue'));
    }

    public function update(Request $request, Venue $venue)
    {
        if (!$venue->is_global) {
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

        $validated['is_global'] = $request->has('is_global');

        $venue->update($validated);

        return redirect()->route('admin.venues.index')->with('success', 'Global venue updated successfully.');
    }

    public function destroy(Venue $venue)
    {
        if (!$venue->is_global) {
            abort(403);
        }

        if ($venue->seat_map_image) {
            Storage::disk('public')->delete($venue->seat_map_image);
        }

        $venue->delete();

        return redirect()->route('admin.venues.index')->with('success', 'Global venue deleted successfully.');
    }
}
