<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckInController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'ticket_code' => 'required|string',
        ]);

        $ticket = Ticket::where('ticket_code', $request->ticket_code)->first();

        if (!$ticket) {
            return response()->json(['valid' => false, 'message' => 'Ticket not found.'], 404);
        }

        if ($ticket->status === 'used') {
            return response()->json([
                'valid' => false, 
                'message' => 'Ticket already used.',
                'scanned_at' => $ticket->scanned_at,
                'attendee' => $ticket->user->name
            ], 400);
        }

        if ($ticket->status !== 'valid') {
            return response()->json(['valid' => false, 'message' => 'Ticket is invalid or cancelled.'], 400);
        }

        // Validate Event Logic (e.g. is today?)
        // For now, simple check-in

        $ticket->update([
            'status' => 'used',
            'scanned_at' => now(),
            'scanned_by' => Auth::id(), // Ensure API request is authenticated
        ]);

        return response()->json([
            'valid' => true,
            'message' => 'Access Granted',
            'ticket' => [
                'type' => $ticket->ticketType->name,
                'attendee' => $ticket->user->name,
                'event' => $ticket->event->title,
                'seat_number' => $ticket->seat_number
            ]
        ]);
    }
}
