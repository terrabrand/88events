<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Guest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuestlistController extends Controller
{
    public function index()
    {
        $organizerId = Auth::id();
        $guests = Guest::where('organizer_id', $organizerId)
            ->withCount('events')
            ->latest()
            ->paginate(15);
        
        $events = Event::where('organizer_id', $organizerId)
            ->where('status', 'published')
            ->where('start_date', '>', now())
            ->get();
            
        return view('organizer.guests.index', compact('guests', 'events'));
    }

    public function addToEvents(Request $request, Guest $guest)
    {
        $request->validate([
            'event_ids' => 'required|array',
            'event_ids.*' => 'exists:events,id'
        ]);

        // Verify ownership and sync
        $events = Event::where('organizer_id', Auth::id())
            ->whereIn('id', $request->event_ids)
            ->get();

        foreach ($events as $event) {
            $event->guests()->syncWithoutDetaching([$guest->id]);
        }

        return back()->with('success', 'Guest invited to selected events.');
    }

    public function eventGuestlist(Event $event)
    {
        $this->authorize('update', $event);
        
        $guests = $event->guests()->paginate(15);
        
        // Fetch previous guests for the "Import" feature
        $previousGuests = Guest::where('organizer_id', Auth::id())
            ->whereDoesntHave('events', function($query) use ($event) {
                $query->where('event_id', $event->id);
            })
            ->get();

        return view('organizer.guests.event_guestlist', compact('event', 'guests', 'previousGuests'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'event_id' => 'nullable|exists:events,id'
        ]);

        $organizerId = Auth::id();
        
        // Check for duplicates in organizer's global list
        $guest = Guest::where('organizer_id', $organizerId)
            ->where(function($query) use ($validated) {
                if ($validated['email']) $query->where('email', $validated['email']);
                if ($validated['phone']) $query->orWhere('phone', $validated['phone']);
            })->first();

        if (!$guest) {
            $guest = Guest::create([
                'organizer_id' => $organizerId,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
            ]);
        }

        if ($request->has('event_id')) {
            $event = Event::find($request->event_id);
            if ($event->organizer_id == $organizerId) {
                $event->guests()->syncWithoutDetaching([$guest->id]);
            }
        }

        return back()->with('success', 'Guest added successfully.');
    }

    public function importFromPastEvents(Request $request, Event $event)
    {
        $this->authorize('update', $event);
        
        $request->validate([
            'guest_ids' => 'required|array',
            'guest_ids.*' => 'exists:guests,id'
        ]);

        $event->guests()->syncWithoutDetaching($request->guest_ids);

        return back()->with('success', 'Guests imported successfully.');
    }

    public function removeFromEvent(Event $event, Guest $guest)
    {
        $this->authorize('update', $event);
        $event->guests()->detach($guest->id);
        
        return back()->with('success', 'Guest removed from event.');
    }

    public function toggleStatus(Event $event, Guest $guest)
    {
        $this->authorize('update', $event);
        
        $currentStatus = $event->guests()->where('guest_id', $guest->id)->first()->pivot->status;
        $newStatus = ($currentStatus === 'checked-in') ? 'invited' : 'checked-in';
        
        $event->guests()->updateExistingPivot($guest->id, ['status' => $newStatus]);
        
        return back()->with('success', "Guest " . ($newStatus === 'checked-in' ? 'checked in' : 'status reset') . " successfully.");
    }

    public function destroy(Guest $guest)
    {
        if ($guest->organizer_id !== Auth::id()) {
            abort(403);
        }
        
        $guest->delete();
        return back()->with('success', 'Guest deleted from global list.');
    }
}
