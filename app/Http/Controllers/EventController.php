<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use App\Models\Venue;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->hasRole('attendee')) {
            $events = Event::where('status', 'published')
                ->where('start_date', '>=', now())
                ->orderBy('start_date', 'asc')
                ->paginate(12);
        } else {
            $events = Event::where('organizer_id', Auth::id())->latest()->paginate(10);
        }
        
        return view('events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Event::class);
        $venues = Venue::where('is_global', true)
            ->orWhere('organizer_id', Auth::id())
            ->get();
        return view('events.create', compact('venues'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEventRequest $request)
    {
        $this->authorize('create', Event::class);
        $validated = $request->validated();
        
        $validated['organizer_id'] = Auth::id();
        $validated['slug'] = Str::slug($validated['title']) . '-' . Str::random(6);

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('covers', 'public');
            $validated['cover_image_path'] = $path;
        }

        Event::create($validated);

        return redirect()->route('events.index')->with('success', 'Event created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        $event->load('venue');
        $occupiedSeats = $event->tickets()
            ->where('status', '!=', 'cancelled')
            ->whereNotNull('seat_number')
            ->pluck('seat_number')
            ->toArray();

        return view('events.show', compact('event', 'occupiedSeats'));
    }

    public function showPublic(Event $event)
    {
        $event->load(['venue', 'ticketTypes', 'organizer']);
        
        if ($event->status !== 'published') {
            abort(404);
        }

        return view('pages.events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        $this->authorize('update', $event);
        $scanners = User::role('scanner')->get();
        $venues = Venue::where('is_global', true)
            ->orWhere('organizer_id', Auth::id())
            ->get();
        return view('events.edit', compact('event', 'scanners', 'venues'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEventRequest $request, Event $event)
    {
        $this->authorize('update', $event);
        $validated = $request->validated();
        
        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('covers', 'public');
            $validated['cover_image_path'] = $path;
        }

        $event->update($validated);

        if ($request->has('update_scanners')) {
            $event->scanners()->sync($request->input('scanners', []));
        }

        return redirect()->route('events.index')->with('success', 'Event updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $this->authorize('delete', $event);
        $event->delete();
        return redirect()->route('events.index')->with('success', 'Event deleted successfully.');
    }

    public function exportAttendees(Event $event)
    {
        $this->authorize('update', $event);

        $tickets = $event->tickets()->with('user', 'ticketType')->where('status', '!=', 'cancelled')->get();

        $csvFileName = 'attendees-' . $event->slug . '-' . date('Y-m-d') . '.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$csvFileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($tickets) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Name', 'Email', 'Ticket Type', 'Code', 'Status', 'Purchase Date']);

            foreach ($tickets as $ticket) {
                fputcsv($file, [
                    $ticket->user->name,
                    $ticket->user->email,
                    $ticket->ticketType->name,
                    $ticket->ticket_code,
                    $ticket->status,
                    $ticket->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
