<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use App\Models\SupportMessage;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportTicketController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $tickets = SupportTicket::where('sender_id', $userId)
            ->orWhere('recipient_id', $userId)
            ->with(['sender', 'recipient', 'event'])
            ->latest()
            ->paginate(10);

        return view('support.index', compact('tickets'));
    }

    public function create(Request $request)
    {
        $type = $request->input('type', 'attendee_to_organizer');
        $events = [];
        $organizers = [];

        if ($type === 'attendee_to_organizer') {
            // Get events user has tickets for or just published events
            $events = Event::where('status', 'published')->get();
        }

        return view('support.create', compact('type', 'events'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:attendee_to_organizer,organizer_to_admin',
            'event_id' => 'nullable|exists:events,id',
            'priority' => 'required|in:low,medium,high',
        ]);

        $ticket = new SupportTicket();
        $ticket->sender_id = Auth::id();
        $ticket->subject = $validated['subject'];
        $ticket->type = $validated['type'];
        $ticket->priority = $validated['priority'];
        $ticket->status = 'open';

        if ($validated['type'] === 'attendee_to_organizer') {
            if ($validated['event_id']) {
                $event = Event::find($validated['event_id']);
                $ticket->event_id = $event->id;
                $ticket->recipient_id = $event->organizer_id;
            } else {
                return back()->withErrors(['event_id' => 'Please select an event to contact the organizer.']);
            }
        } else {
            // Organizer to Admin
            $ticket->recipient_id = null; // Admin-facing
        }

        $ticket->save();

        SupportMessage::create([
            'support_ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $validated['message'],
        ]);

        return redirect()->route('support.show', $ticket)->with('success', 'Support ticket created successfully.');
    }

    public function show(SupportTicket $ticket)
    {
        // Check authorization
        if ($ticket->sender_id !== Auth::id() && $ticket->recipient_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
            // Additional check for Organizer to Admin tickets where recipient_id is null
            if ($ticket->type === 'organizer_to_admin' && !Auth::user()->hasRole('admin') && $ticket->sender_id !== Auth::id()) {
                abort(403);
            }
        }

        $ticket->load(['messages.user', 'sender', 'recipient', 'event']);
        return view('support.show', compact('ticket'));
    }

    public function reply(Request $request, SupportTicket $ticket)
    {
        $validated = $request->validate([
            'message' => 'required|string',
        ]);

        SupportMessage::create([
            'support_ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $validated['message'],
        ]);

        $ticket->update(['status' => 'pending']);

        return back()->with('success', 'Reply sent successfully.');
    }
}
